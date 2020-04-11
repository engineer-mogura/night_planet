<?php

namespace App\Controller\Component;

use Cake\Log\Log;
use Cake\I18n\Time;
use \Cake\ORM\Query;
use RuntimeException;
use Cake\Mailer\Email;
use Cake\Filesystem\Folder;
use Cake\ORM\TableRegistry;
use Cake\Controller\Component;
use Cake\Mailer\MailerAwareTrait;
use Cake\Controller\ComponentRegistry;
use Cake\Datasource\ConnectionManager;
use App\Controller\ApiGooglesController;
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
        $period='+30';
        // ルートディレクトリ
        $root = dirname(ROOT);
        // 日付
        $date = date('Ymd');
        // バックアップファイルを保存するディレクトリ
        $dirpath = $root . DS . 'np_backup' . DS . $date;
        // mysqldumpパス
        $mysqldump_path = '/usr/bin/mysqldump ';

        // バックアップディクレトリ作成
        exec('mkdir -p '. $root . DS . 'np_backup', $output, $result_code);
        // パーミッション変更
        exec('chmod 700 ' . $root . DS . 'np_backup');

        // バックアップディクレトリ作成
        exec('mkdir -p '. $dirpath, $output, $result_code);
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
            $backup_name = $dirpath . DS . $backupfolder . '_' . $date;
            // ファイル名を定義(※ファイル名で日付がわかるようにしておきます)
            //$filename = $backupfolder . '_' . $date . '.tar ';
            //$filename = $backupfolder . '_' . $date . '.zip';

            // バックアップ実行
            //exec('tar -cvf ' . $dirpath . DS . $filename . $backupfolder, $output, $result_code);
            //exec('zip -r ' . $dirpath . DS . $filename . $root . '/' . $backupfolder.'/img', $output, $result_code);
            // zip圧縮
            //exec('zip -r ' . $dirpath . DS . $filename . ' ' . $root . DS . $backupfolder, $output, $result_code);
            // imgディクレトリディクレトリ作成
            exec('mkdir -p '. $backup_name, $output, $result_code);
            // パーミッション変更
            exec('chmod 700 ' . $backup_name);
            // imgディクレトリコピーバックアップ※隠しファイルも一応コピーする
            exec('cp -ra ' . $root . DS . $backupfolder . DS . '. ' . $backup_name, $output, $result_code);
            // 古いバックアップファイルを削除
            exec('find ' . dirname($dirpath) . '/ -maxdepth 1 -mtime ' . $period . " -exec rm -d {} \;", $output);

        } else {
            $result = false;
        }
        Log::info(__LINE__ . '::' . __METHOD__ . '::' . "アウトプット:".$output . "結果コード:" . $result_code, 'batch_bk');
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
        Log::info(__LINE__ . '::' . __METHOD__ . '::' . "アウトプット:".$output . "結果コード:" . $result, 'batch_bk');
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
                        // メディアURLが取得出来ない場合があるのでその際は、ログ出してスルーする
                        if (empty($value['media_url'])) {
                            Log::warning(__LINE__ . '::' . __METHOD__ . '::'.'【'.AREA[$shop->area]['label']
                                .GENRE[$shop->genre]['label'].$shop->name
                                .'】のインスタグラム【 media_url 】が存在しませんでした。', "batch_snpr");
                            continue;
                        }
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
                    Log::error(__LINE__ . '::' . __METHOD__ . "::レコードの削除に失敗しました。". $e, "batch_snpr");
                    throw new RuntimeException($action_name.'レコードの削除に失敗しました。');
                }
            }
            // レコードを一括登録する
            if (!$this->NewPhotosRank->saveMany($newPhotosRankEntityList)) {
                Log::error(__LINE__ . '::' . __METHOD__ . "::レコードの登録に失敗しました。". $e, "batch_snpr");
                throw new RuntimeException($action_name.'レコードの登録に失敗しました。');
            }

        } catch(RuntimeException $e) {
            Log::error(__LINE__ . '::' . __METHOD__ . "::バッチ処理が失敗しました。". $e, "batch_snpr");
            $result = false; // 異常終了フラグ
        }

        return $result;
    }

    /**
     * 各店舗のアクセスレポートを集計する処理
     *
     * @return array
     */
    public function analyticsReport()
    {

        $is_hosyu = PROPERTY['ANALYTICS_REPORT_HOSYU']; // 日時範囲を指定して一括登録する（運用はFALSE）
        $is_update = PROPERTY['ANALYTICS_YEAR_WEEK_ISUPDATE']; // 年別、週別を更新するかフラグ
        $this->ApiGoogles = new ApiGooglesController();

        // 保守の場合は日付チェックしない
        if (!$is_hosyu) {
            // 現在日付
            $now_date = new Time(date('Y-m-d'));
            // チェック用
            $data_check = $now_date->format('Y-m-d');

            $range_start  = $data_check . ' 0:00:00';
            $range_end    = $data_check . ' 1:00:00';
            $range_target = $data_check . ' ' . Time::now()->i18nFormat('HH:mm:ss');
            // 年別、週別を更新するかチェックする
            $is_update = $this->Util->check_in_range($range_start, $range_end, $range_target);
        }

        // 前日データを取得するか
        if ($is_update || $is_hosyu) {
            // 前日アナリティクスレポート取得
            $reports = $this->ApiGoogles->index($is_hosyu, true);
        } else {
            // 当日アナリティクスレポート取得
            $reports = $this->ApiGoogles->index($is_hosyu, false);
        }

        // 保守用処理なら終了
        if ($is_hosyu) {
            return true;
        }

        $this->AccessYears  = TableRegistry::get('access_years');
        $this->AccessMonths = TableRegistry::get('access_months');
        $this->AccessWeeks  = TableRegistry::get('access_weeks');
        $this->Shops        = TableRegistry::get('shops');

        $result = true; // 正常終了フラグ
        $action_name = "analyticsReport,";

        $entities_year  = array();
        $entities_month = array();
        $entities_week  = array();

        $entity_year  = null;
        $entity_month = null;
        $entity_week  = null;

        for ($reportIndex = 0; $reportIndex < count($reports); $reportIndex++) {
            $report = $reports[ $reportIndex ];
            $header = $report->getColumnHeader();
            $dimensionHeaders = $header->getDimensions();
            $metricHeaders = $header->getMetricHeader()->getMetricHeaderEntries();
            $rows = $report->getData()->getRows();

            // データがない場合はリターン
            if (empty($rows)) {
                return false;
            }

            for ($rowIndex = 0; $rowIndex < count($rows); $rowIndex++) {
                $row = $rows[ $rowIndex ];
                $dimensions = $row->getDimensions();
                $metrics = $row->getMetrics();

                // 曜日を取得する
                for ($dimensionIndex = 0; $dimensionIndex < count($dimensionHeaders); $dimensionIndex++) {
                    if ($dimensionHeaders[$dimensionIndex] == 'ga:dayOfWeek') {
                        // 日曜日の場合は、6をセット
                        if ($dimensions[$dimensionIndex] == 0) {
                            $week = 6;
                        } else {
                            $week = $dimensions[$dimensionIndex];
                        }
                        $week = $this->Util->getWeek($week);
                    }
                }

                for ($i = 0; $i < count($dimensionHeaders) && $i < count($dimensions); $i++) {
                    if ($dimensionHeaders[$i] == 'ga:pagePath') {
                        $url_aplit = explode('/', $dimensions[$i]);
                        $index = count($url_aplit);
                        // 配列の最後が数字の場合
                        if (preg_match("/^[0-9]+$/",$url_aplit[$index - 1])) {

                            // セットするパラメタを取得
                            $values = $metrics[0]->getValues();

                            $shop_id = $url_aplit[$index - 1];
                            $shop = $this->Shops->find()
                                        ->where(['id'=>$shop_id])
                                        ->first();
                            $patch_data = array('shop_id'=>(int) $shop_id,'owner_id'=>$shop->owner_id
                                        ,'name'=>$shop->name,'area'=>$shop->area
                                        ,'genre'=>$shop->genre,'pagePath'=>$dimensions[$i]);

                            // 年別、週別を更新するか
                            if ($is_update) {

                                // 前日の日付を取得する
                                $zen_date   = new Time(date('Y-m-d'));
                                $zen_date   = $zen_date->subDays(1);

                                // 年別アクセスエンティティ
                                $y = $zen_date->year;
                                $entity_year = $this->AccessYears->find()
                                    ->where(['shop_id' => $shop_id, 'y' => $y])
                                    ->first();
                                // 取得出来なかったら新規エンティティ
                                if (empty($entity_year)) {
                                    $patch_year =  $patch_data;
                                    $patch_year['y'] = $y;
                                    $entity_year = $this->AccessYears->newEntity();
                                    $entity_year = $this->AccessYears
                                        ->patchEntity($entity_year, $patch_year,
                                            ['validate' => false]);
                                }

                                // 曜日別アクセスエンティティ
                                $entity_week = $this->AccessWeeks->find()
                                    ->where(['shop_id'=>$shop_id])->first();
                                // 取得出来なかったら新規エンティティ
                                if (empty($entity_week)) {
                                    $entity_week  = $this->AccessWeeks->newEntity();
                                    $entity_week = $this->AccessWeeks
                                        ->patchEntity($entity_week, $patch_data,
                                            ['validate' => false]);
                                }

                                // 年別アクセスエンティティ
                                $entity_year->set($zen_date->month . '_sessions', $this->Util
                                    ->addVal($entity_year->get(
                                        $zen_date->month . '_sessions'), (int) $values[0]));
                                $entity_year->set($zen_date->month . '_pageviews', $this->Util
                                    ->addVal($entity_year->get(
                                        $zen_date->month . '_pageviews'), (int) $values[1]));
                                $entity_year->set($zen_date->month . '_users', $this->Util
                                    ->addVal($entity_year->get(
                                        $zen_date->month . '_users'), (int) $values[2]));

                                // 曜日別アクセスエンティティ
                                $entity_week->set($week['en'] . '_sessions', $this->Util
                                    ->addVal($entity_week->get(
                                        $week['en'] . '_sessions'), (int) $values[0]));
                                $entity_week->set($week['en'] . '_pageviews', $this->Util
                                    ->addVal($entity_week->get(
                                        $week['en'] . '_pageviews'), (int) $values[1]));
                                $entity_week->set($week['en'] . '_users', $this->Util
                                    ->addVal($entity_week->get(
                                        $week['en'] . '_users'), (int) $values[2]));

                                array_push($entities_year,  $entity_year);
                                array_push($entities_week,  $entity_week);

                            }

                            // 月別前日を更新するか
                            if ($is_update) {
                                // 月別アクセスエンティティ
                                $ym  = $zen_date->format('Y-m');
                                $day = $zen_date->day;
                            } else {
                                // 月別アクセスエンティティ
                                $ym  = $now_date->format('Y-m');
                                $day = $now_date->day;
                            }

                            $entity_month = $this->AccessMonths->find()
                                ->where(['shop_id' => $shop_id , 'ym' => $ym])
                                ->first();
                            // 取得出来なかったら新規エンティティ
                            if (empty($entity_month)) {
                                $patch_month =  $patch_data;
                                $patch_month['ym'] = $ym;
                                $entity_month = $this->AccessMonths->newEntity();
                                $entity_month = $this->AccessMonths
                                    ->patchEntity($entity_month, $patch_month,
                                        ['validate' => false]);
                            }

                            // 月別アクセスエンティティ
                            $entity_month->set($day . '_sessions', $this->Util
                                ->addVal($entity_month->get(
                                    $day . '_sessions'), (int) $values[0]));
                            $entity_month->set($day . '_pageviews', $this->Util
                                ->addVal($entity_month->get(
                                    $day . '_pageviews'), (int) $values[1]));
                            $entity_month->set($day . '_users', $this->Util
                                ->addVal($entity_month->get(
                                    $day . '_users'), (int) $values[2]));

                            array_push($entities_month, $entity_month);

                        } else {
                            // 店舗以外のURLの場合
                            break;
                        }

                    }
                }

            }

            try{
                // レコードを一括登録する
                // 年別、週別を更新するか
                if ($is_update) {
                    if (!$this->AccessYears->saveMany($entities_year)) {
                        throw new RuntimeException($action_name.'レコードの登録に失敗しました。');
                    }
                    if (!$this->AccessWeeks->saveMany($entities_week)) {
                        throw new RuntimeException($action_name.'レコードの登録に失敗しました。');
                    }
                }

                if (!$this->AccessMonths->saveMany($entities_month)) {
                    throw new RuntimeException($action_name.'レコードの登録に失敗しました。');
                }

            } catch(RuntimeException $e) {
                Log::error(__LINE__ . '::' . __METHOD__ . "::バッチ処理が失敗しました。". $e, "batch_snpr");
                $result = false; // 異常終了フラグ
            }

        }

        return $result;
    }

        /**
     * 各店舗のアクセスレポートを集計する処理 保守用
     *
     * @return array
     */
    public function analyticsReportHosyu($reports,  $start_date)
    {

        $this->AccessYears  = TableRegistry::get('access_years');
        $this->AccessMonths = TableRegistry::get('access_months');
        $this->AccessWeeks  = TableRegistry::get('access_weeks');
        $this->Shops        = TableRegistry::get('shops');

        $result = true; // 正常終了フラグ
        $action_name = "analyticsReport,";

        $entities_year  = array();
        $entities_month = array();
        $entities_week  = array();

        $entity_year  = null;
        $entity_month = null;
        $entity_week  = null;

        for ($reportIndex = 0; $reportIndex < count($reports); $reportIndex++) {
            $report = $reports[ $reportIndex ];
            $header = $report->getColumnHeader();
            $dimensionHeaders = $header->getDimensions();
            $metricHeaders = $header->getMetricHeader()->getMetricHeaderEntries();
            $rows = $report->getData()->getRows();

            // データがない場合はリターン
            if (empty($rows)) {
                return false;
            }

            for ($rowIndex = 0; $rowIndex < count($rows); $rowIndex++) {
                $row = $rows[ $rowIndex ];
                $dimensions = $row->getDimensions();
                $metrics = $row->getMetrics();
                if (preg_match("/ガールズトーク/", $dimensions[0])) {
                    echo("test");
                }
                // 曜日を取得する
                for ($dimensionIndex = 0; $dimensionIndex < count($dimensionHeaders); $dimensionIndex++) {
                    if ($dimensionHeaders[$dimensionIndex] == 'ga:dayOfWeek') {
                        $week = $dimensions[$dimensionIndex];
                        $week = $this->Util->getWeek($week);
                    }
                }

                for ($i = 0; $i < count($dimensionHeaders) && $i < count($dimensions); $i++) {
                    if ($dimensionHeaders[$i] == 'ga:pagePath') {
                        $url_aplit = explode('/', $dimensions[$i]);
                        $index = count($url_aplit);
                        // 配列の最後が数字の場合
                        if (preg_match("/^[0-9]+$/",$url_aplit[$index - 1])) {

                            // セットするパラメタを取得
                            $values = $metrics[0]->getValues();

                            $shop_id = $url_aplit[$index - 1];
                            $shop = $this->Shops->find()
                                        ->where(['id'=>$shop_id])
                                        ->first();
                            $patch_data = array('shop_id'=>(int) $shop_id,'owner_id'=>$shop->owner_id
                                        ,'name'=>$shop->name,'area'=>$shop->area
                                        ,'genre'=>$shop->genre,'pagePath'=>$dimensions[$i]);

                            // 日付を取得する
                            $now_date   = new Time($start_date);

                            // 年別アクセスエンティティ
                            $y   = $now_date->format('Y');
                            $ym  = $now_date->format('Y-m');
                            $day = $now_date->format('j');

                            // 月別アクセスエンティティ
                            $entity_year = $this->AccessYears->find()
                                ->where(['shop_id' => $shop_id, 'y' => $y])
                                ->first();
                            // 取得出来なかったら新規エンティティ
                            if (empty($entity_year)) {
                                $patch_year =  $patch_data;
                                $patch_year['y'] = $y;
                                $entity_year = $this->AccessYears->newEntity();
                                $entity_year = $this->AccessYears
                                    ->patchEntity($entity_year, $patch_year,
                                        ['validate' => false]);
                            }

                            // 日別アクセスエンティティ
                            $entity_month = $this->AccessMonths->find()
                                ->where(['shop_id' => $shop_id , 'ym' => $ym])
                                ->first();
                            // 取得出来なかったら新規エンティティ
                            if (empty($entity_month)) {
                                $patch_month =  $patch_data;
                                $patch_month['ym'] = $ym;
                                $entity_month = $this->AccessMonths->newEntity();
                                $entity_month = $this->AccessMonths
                                    ->patchEntity($entity_month, $patch_month,
                                        ['validate' => false]);
                            }

                            // 曜日別アクセスエンティティ
                            $entity_week = $this->AccessWeeks->find()
                                ->where(['shop_id'=>$shop_id])->first();
                            // 取得出来なかったら新規エンティティ
                            if (empty($entity_week)) {
                                $entity_week  = $this->AccessWeeks->newEntity();
                                $entity_week = $this->AccessWeeks
                                    ->patchEntity($entity_week, $patch_data,
                                        ['validate' => false]);
                            }

                            // 年別アクセスエンティティ
                            $entity_year->set($now_date->month . '_sessions', $this->Util
                                ->addVal($entity_year->get(
                                    $now_date->month . '_sessions'), (int) $values[0]));
                            $entity_year->set($now_date->month . '_pageviews', $this->Util
                                ->addVal($entity_year->get(
                                    $now_date->month . '_pageviews'), (int) $values[1]));
                            $entity_year->set($now_date->month . '_users', $this->Util
                                ->addVal($entity_year->get(
                                    $now_date->month . '_users'), (int) $values[2]));

                            // 月別アクセスエンティティ
                            $entity_month->set($day . '_sessions', $this->Util
                                ->addVal($entity_month->get(
                                    $day . '_sessions'), (int) $values[0]));
                            $entity_month->set($day . '_pageviews', $this->Util
                                ->addVal($entity_month->get(
                                    $day . '_pageviews'), (int) $values[1]));
                            $entity_month->set($day . '_users', $this->Util
                                ->addVal($entity_month->get(
                                    $day . '_users'), (int) $values[2]));

                            // 曜日別アクセスエンティティ
                            $entity_week->set($week['en'] . '_sessions', $this->Util
                                ->addVal($entity_week->get(
                                    $week['en'] . '_sessions'), (int) $values[0]));
                            $entity_week->set($week['en'] . '_pageviews', $this->Util
                                ->addVal($entity_week->get(
                                    $week['en'] . '_pageviews'), (int) $values[1]));
                            $entity_week->set($week['en'] . '_users', $this->Util
                                ->addVal($entity_week->get(
                                    $week['en'] . '_users'), (int) $values[2]));

                            array_push($entities_month, $entity_month);
                            array_push($entities_week,  $entity_week);
                            array_push($entities_year,  $entity_year);

                        } else {
                            // 店舗以外のURLの場合
                            break;
                        }

                    }
                }

            }

            try{
                // レコードを一括登録する
                if (!$this->AccessYears->saveMany($entities_year)) {
                    throw new RuntimeException($action_name.'レコードの登録に失敗しました。');
                }
                if (!$this->AccessWeeks->saveMany($entities_week)) {
                    throw new RuntimeException($action_name.'レコードの登録に失敗しました。');
                }
                if (!$this->AccessMonths->saveMany($entities_month)) {
                    throw new RuntimeException($action_name.'レコードの登録に失敗しました。');
                }

            } catch(RuntimeException $e) {
                Log::error(__LINE__ . '::' . __METHOD__ . "::バッチ処理が失敗しました。". $e, "batch_snpr");
                $result = false; // 異常終了フラグ
            }

        }

        return $result;
    }

}
