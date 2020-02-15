<?php

namespace App\Controller\Component;

use Cake\Log\Log;
use \Cake\ORM\Query;
use RuntimeException;
use Cake\Mailer\Email;
use Cake\Filesystem\File;
use Cake\Filesystem\Folder;
use Cake\ORM\TableRegistry;
use Cake\Controller\Component;
use Cake\Mailer\MailerAwareTrait;
use Cake\Controller\ComponentRegistry;
use Cake\Datasource\ConnectionManager;
use App\Controller\Component\UtilComponent;

class BatchComponent extends Component
{

    use MailerAwareTrait; // メールクラス
    public function initialize() {
        // Utilコンポーネント
        $this->Util = new UtilComponent(new ComponentRegistry());
    }

    /**
     * mysqldumpを実行する
     * @return mixed
     */
    public function backup()
    {
        $result = true; // 正常終了フラグ
        // コネクションオブジェクト取得
        $con = ConnectionManager::get('default');
        // バックアップファイルを何日分残しておくか
        $period='+7';
        // ルートディレクトリ
        $root = dirname(ROOT);
        // 日付
        $date = date('Ymd');
        // バックアップファイルを保存するディレクトリ
        $dirpath = $root . DS . 'np_backup' . DS . $date;
        // mysqldumpパス
        $mysqldump_path = '/usr/bin/mysqldump ';

        // バックアップディクレトリ作成
        exec('mkdir '. $root . DS . 'np_backup', $output, $result_code);
        // パーミッション変更
        exec('chmod 700 ' . $root . DS . 'np_backup');

        // バックアップディクレトリ作成
        exec('mkdir '. $dirpath, $output, $result_code);
        // パーミッション変更
        exec('chmod 700 ' . $dirpath);

        // コマンド
        $command = sprintf($mysqldump_path . ' -h %s -u %s -p%s %s > %sbackup.sql'
            , $con->config()['host'], $con->config()['username']
            , $con->config()['password'], $con->config()['database']
            , $dirpath . DS . $date);

        // データベースバックアップ
        exec($command, $output, $result_code);

        // 結果コードが0の場合imageディクレトリをバックアップする
        if ($result_code == 0) {

            // バックアップ元フォルダ
            $backupfolder = strstr(IMG_DOMAIN, 'img');
            // ファイル名を定義(※ファイル名で日付がわかるようにしておきます)
            $filename = $backupfolder . '_' . $date . '.tar ';

            // バックアップ実行
            exec('tar -cvf ' . $dirpath . DS . $filename . $backupfolder, $output, $result_code);
            // パーミッション変更
            exec('chmod 700 ' . $dirpath . DS . $filename);
            // 古いバックアップファイルを削除
            exec('find ' . dirname($dirpath) . ' -type d -mtime ' . $period . " -exec rm {} \\;");

        } else {
            $result = false;
        }
        Log::info(__LINE__ . '::' . __METHOD__ . '::' . "アウトプット:".$output . "結果コード:" . $result_code, 'batch_snpr');
        return $result;
    }

    /**
     * ディクレトリバックアップを実行する
     * @return mixed
     */
    public function dirBackup()
    {
        $result = true; // 正常終了フラグ
        // バックアップファイルを何日分残しておくか
        $period='+7';
        // ルートディレクトリ
        $root = dirname(ROOT);
        // 日付
        $date = date('Ymd');
        // バックアップファイルを保存するディレクトリ
        $dirpath = $root . DS . 'np_backup';
        // バックアップ元フォルダ
        $backupfolder= $root . DS . 'img';
        // ファイル名を定義(※ファイル名で日付がわかるようにしておきます)
        $filename = 'images_' . $date . 'tar.gz ';
        // バックアップ実行
        exec('tar -zcvf ' . $dirpath . DS . $filename . $backupfolder, $output, $result_code);
        // パーミッション変更
        exec('chmod 700 ' . $dirpath . DS . $filename);
        // 古いバックアップファイルを削除
        exec('find ' . $dirpath . ' -type f -mtime ' . $period . " -exec rm {} \\;");
        // 結果コードが0以外の場合FALSEを設定する
        if ($result_code != 0) {
            $result = false;
        }
        Log::info(__LINE__ . '::' . __METHOD__ . '::' . "アウトプット:".$output . "結果コード:" . $result, 'batch_snpr');
        return $result;
    }

    /**
     * サービスプラン期間適応外をフリープランに変更する処理
     *
     * @param [type] $id
     * @param [type] $diaryPath
     * @return array
     */
    public function changeServicePlan()
    {

        $result = true; // 正常終了フラグ
        $servece_plans = TableRegistry::get('servece_plans');
        $owners        = TableRegistry::get('owners');
        $plans = $servece_plans->find("all")
                    ->where(['NOW() > to_end', 'to_end !=' => '0000-00-00'])
                    ->contain(['owners'])
                    ->toArray();

        $update_entities = array();

        foreach ($plans as $key => $plan) {
            $update_entity = [];
            $update_entity['id'] = $plan->id;
            $update_entity['course'] = 0;
            $update_entity['previous_plan'] = $plan->current_plan;
            $update_entity['current_plan'] = SERVECE_PLAN['free']['label'];
            $update_entity['from_start'] = '0000-00-00';
            $update_entity['to_end'] = '0000-00-00';
            array_push($update_entities, $update_entity);
        }
        $entities = $servece_plans->patchEntities(
                $servece_plans, $update_entities, ['validate' => false]);
        try {
            // レコード更新実行
            // if (!$servece_plans->saveMany($entities)) {
            //     throw new RuntimeException('レコードの更新ができませんでした。');
            // }
            foreach ($plans as $key => $plan) {
                $email = new Email('default');
                $email->setFrom([MAIL['FROM_INFO'] => MAIL['FROM_NAME']])
                    ->setSubject(MAIL['EXPIRED_SERVICE_PLAN'])
                    ->setTo($plan->owner->email)
                    ->setBcc(MAIL['FROM_INFO'])
                    ->setTemplate("expired_service_plan")
                    ->setLayout("simple_layout")
                    ->emailFormat("html")
                    ->viewVars(['plan' => $plan])
                    ->send();
            }

            // Log::info("ID：【".$owner[0]['id']."】アドレス：【".$owner[0]->email
            //     ."】" . RESULT_M['CHANGE_PLAN_SUCCESS'] . ', pass_reset');

        } catch (RuntimeException $e) {
            $result = false; // 異常終了フラグ
            $this->log($this->Util->setLog($auth, $e));
        }
        return $result;
    }

    /**
     * 新着画像投稿を集計する処理
     *
     * @param [type] $id
     * @param [type] $diaryPath
     * @return array
     */
    public function saveNewPhotosRank()
    {
        $this->NewPhotosRank   = TableRegistry::get('new_photos_rank');
        $this->Snss            = TableRegistry::get('snss');
        $this->Shops           = TableRegistry::get('shops');

        $result = true; // 正常終了フラグ
        $action_name = "saveNewPhotosRank,";
        // webroot/img/配下のinstaキャッシュを取得する
        $dir = WWW_ROOT . PATH_ROOT['IMG'];
        $exp = "*.dat";

        $shops = $this->Shops->find('all')
            ->contain(['casts', 'casts.diarys' => function (Query $q) {
                return $q
                        ->order(['diarys.created'=>'DESC'])
                        ->limit(5);
                }, 'shop_infos' => function (Query $q) {
                    return $q
                        ->order(['shop_infos.created'=>'DESC'])
                        ->limit(5);
                }
        ])->limit("200")->toArray();

        $snss   =  $this->Snss->find("all", ["DISTINCT instagram"])
                    ->where(["instagram IS NOT NULL"])
                    ->contain(['shops', 'shops.owners.servece_plans' => function (Query $q) {
                        return $q
                            ->where(['current_plan is not'=> SERVECE_PLAN['free']['label']]);
                    }])->limit(199)->toArray();

        // 店舗,スタッフ情報をセット
        foreach($shops as $key => $shop) {

            $shop->set('shopInfo', $this->Util->getShopInfo($shop));
            foreach($shop->casts as $key => $cast) {
                $cast->set('castInfo', $this->Util->getCastItem($cast, $shop));
            }
        }

        // 店舗,スタッフ情報をセット
        foreach($snss as $key => $sns) {
            $shop = $sns->shop;
            // ナイプラ自身のインスタの場合
            if ($shop->ig_data['business_discovery']['username'] == API['INSTAGRAM_USER_NAME']) {
                continue;
            }

            $shop->set('shopInfo', $this->Util->getShopInfo($shop));
            foreach($shop->casts as $key => $cast) {
                $cast->set('castInfo', $this->Util->getCastItem($cast, $shop));
            }
        }

        // ナイプラのsnsエンティティ作成
        $sns = $this->Snss->newEntity();
        $sns->set("shop", $this->Shops->newEntity());
        $sns->set("instagram", API['INSTAGRAM_USER_NAME']);
        $sns->shop->set("shopInfo"
            , array('area' => REGION['okinawa']
            , 'genre' => array('label'=>'ナイプラ')));
        array_push($snss, $sns);

        // Instagram情報セット
        foreach ($snss as $key => $sns) {

            $insta_user_name = $sns->instagram;
            $shop = $sns->shop;

            // インスタのキャッシュパス
            $cache_path = preg_replace(
                '/(\/\/)/',
                '/',
                WWW_ROOT.$shop->shopInfo['cache_path']
            );

            // インスタ情報を取得
            $tmp_ig_data = $this->Util->getInstagram($insta_user_name, null, $shop->shopInfo['current_plan'], $cache_path);
            // データ取得に失敗した場合
            if (!$tmp_ig_data) {
                Log::warning(__LINE__ . '::' . __METHOD__ . '::'.'【'.AREA[$shop->area]['label']
                    .GENRE[$shop->genre]['label'].$shop->name
                    .'】のインスタグラムのデータ取得に失敗しました。', "batch_snpr");
            }
            $ig_data = $tmp_ig_data->business_discovery;
            // インスタユーザーが存在しない場合
            if (!empty($tmp_ig_data->error)) {
                // エラーメッセージをセットする
                $insta_error = $tmp_ig_data->error->error_user_title;
                $this->set(compact('ig_error'));
            }
            $cache_file = $this->Util->scanDir($cache_path, $exp);

            $shop->set("ig_data", $ig_data);
            $shop->set("ig_date", date ("Y-m-d H:i:s", filemtime($cache_file[0])));
            $shop->set("ig_path", $cache_file[0]);
            array_push($shops, $shop);

        }

        $sort_lists = array();
        foreach ($shops as $key => $shop) {
            array_push($sort_lists, $shop);
            foreach ($shop->casts as $key => $cast) {
                array_push($sort_lists, $cast);
            }
        }
        $newPhotosRankEntityList = array();
        // エンティティを配列に詰め込む
        foreach ($sort_lists as $key => $sort_list) {

            if ($sort_list->getSource() == 'shops') {

                if ($sort_list->__isset('ig_data')) {
                    $shop = $sort_list;
                    $ig_data = json_decode(json_encode($shop->ig_data), true);

                    // 最大５件までデータ取得
                    foreach ($ig_data['media']['data'] as $key => $value) {
                        if ($key == 5) { break; }
                        $newPhotosRankEntity = $this->NewPhotosRank->newEntity();

                        $newPhotosRankEntity->set('name', $ig_data['username']);
                        $newPhotosRankEntity->set('area', $shop->shopInfo['area']['label']);
                        $newPhotosRankEntity->set('genre', $shop->shopInfo['genre']['label']);
                        $newPhotosRankEntity->set('is_insta', 1);
                        $newPhotosRankEntity->set('media_type', $value['media_type']);
                        $newPhotosRankEntity->set('like_count', $value['like_count']);
                        $newPhotosRankEntity->set('comments_count', $value['comments_count']);
                        $newPhotosRankEntity->set('photo_path', $value['media_url']);
                        // ナイプラ自身のインスタの場合
                        if ($ig_data['username'] == API['INSTAGRAM_USER_NAME']) {
                            $newPhotosRankEntity->set('details', 'https://www.instagram.com/'. $ig_data['username']);
                        } else {
                            $newPhotosRankEntity->set('details', $shop->shopInfo['shop_url']);
                        }
                        $newPhotosRankEntity->set('content', $value['caption']);
                        $newPhotosRankEntity->set('post_date', date("Y-m-d H:i:s", strtotime($value['timestamp'])));

                        array_push($newPhotosRankEntityList, $newPhotosRankEntity);
                    }


                } else if (count($sort_list->shop_infos) > 0) {
                    $shop = $sort_list;
                    foreach ($sort_list->shop_infos as $key => $value) {
                        if ($key == 5) { break; }

                        $photo_path = $shop->shopInfo['notice_path'] . $value['dir'];

                        $dir = new Folder(preg_replace('/(\/\/)/', '/'
                                , WWW_ROOT.$photo_path), true, 0755);
                        $files = glob($dir->path.DS.'*.*');
                        usort($files, $this->Util->sortByLastmod);

                        $newPhotosRankEntity = $this->NewPhotosRank->newEntity();
                        $newPhotosRankEntity->set('name', $shop->name);
                        $newPhotosRankEntity->set('area', $shop->shopInfo['area']['label']);
                        $newPhotosRankEntity->set('genre', $shop->shopInfo['genre']['label']);
                        $newPhotosRankEntity->set('is_insta', 0);
                        $newPhotosRankEntity->set('media_type', 'IMAGE');
                        $newPhotosRankEntity->set('like_count', 0);
                        $newPhotosRankEntity->set('comments_count', 0);
                        $newPhotosRankEntity->set('photo_path', $photo_path.DS.basename($files[0]));
                        $newPhotosRankEntity->set('details', $shop->shopInfo['shop_url']);
                        $newPhotosRankEntity->set('content', $value->content);
                        $newPhotosRankEntity->set('post_date', $value->modified->format("Y-m-d H:i:s"));
                        array_push($newPhotosRankEntityList, $newPhotosRankEntity);
                    }

                }
            } else if ($sort_list->getSource() == 'casts') {
                if (count($sort_list->diarys) > 0) {
                    $cast = $sort_list;
                    foreach ($sort_list->diarys as $key => $value) {
                        if ($key == 5) { break; }

                        $photo_path = $cast->castInfo['diary_path'] . $value['dir'];
                        $dir = new Folder(preg_replace('/(\/\/)/', '/'
                                , WWW_ROOT.$photo_path), true, 0755);
                        $files = glob($dir->path.DS.'*.*');
                        usort($files, $this->Util->sortByLastmod);

                        $newPhotosRankEntity = $this->NewPhotosRank->newEntity();
                        $newPhotosRankEntity->set('name', $cast->nickname);
                        $newPhotosRankEntity->set('area', $cast->castInfo['area']['label']);
                        $newPhotosRankEntity->set('genre', $cast->castInfo['genre']['label']);
                        $newPhotosRankEntity->set('is_insta', 0);
                        $newPhotosRankEntity->set('media_type', 'IMAGE');
                        $newPhotosRankEntity->set('like_count', 0);
                        $newPhotosRankEntity->set('comments_count', 0);
                        $newPhotosRankEntity->set('photo_path', $photo_path.DS.basename($files[0]));
                        $newPhotosRankEntity->set('details', $cast->castInfo['cast_url']);
                        $newPhotosRankEntity->set('content', $value->content);
                        $newPhotosRankEntity->set('post_date', $value->modified->format("Y-m-d H:i:s"));
                        array_push($newPhotosRankEntityList, $newPhotosRankEntity);
                    }

                }
            }

        }
        foreach ($newPhotosRankEntityList as $key => $entity) {
            $updated[$key] = $entity['post_date'];
        }
        //配列のkeyのupdatedでソート
        array_multisort($updated, SORT_DESC, $newPhotosRankEntityList);

        try{
            // レコードが存在した場合は削除する
            if ($this->NewPhotosRank->find('all')->count() > 0) {
                // 新着フォトランキングレコード削除
                if (!$this->NewPhotosRank->deleteAll([""])) {
                    throw new RuntimeException($action_name.'レコードの削除に失敗しました。');
                }
            }
            $entities = $this->NewPhotosRank->newEntities($newPhotosRankEntityList);
            // レコードを一括登録する
            if (!$this->NewPhotosRank->saveMany($newPhotosRankEntityList)) {
                throw new RuntimeException($action_name.'レコードの登録に失敗しました。');
            }

        } catch(RuntimeException $e) {
            Log::error(__LINE__ . '::' . __METHOD__ . "::バッチ処理が失敗しました。". $e, "batch_snpr");
            $result = false; // 異常終了フラグ
        }

        return $result;
    }

}
