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
use App\Controller\Component\UtilComponent;

class CommonComponent extends Component
{

    use MailerAwareTrait; // メールクラス
    public function initialize() {
        // Utilコンポーネント
        $this->Util = new UtilComponent(new ComponentRegistry());
    }

     /**
     * 指定ディレクトリ以下の指定ファイルを取得する処理
     *
     * @param [type] $dir
     * @param [type] $exp
     * @return array
     */
    function scanDir($dir, $exp) {
        $list = $tmp = array();
        foreach(glob($dir.'*/', GLOB_ONLYDIR) as $child) {
            if ($tmp = self::scanDir($child, $exp)) {
                $list = array_merge($list, $tmp);
            }
        }
        foreach(glob($dir.'{'.$exp.'}', GLOB_BRACE) as $file) {
            $list[] = $file;
        }
        return $list;
    }

    /**
     * 更新日時順で並び替える関数
     * @param mixed
     * @param mixed
     * @return mixed
     */
    public function sortByLastmod($a, $b)
    {
        return filemtime($b) - filemtime($a);
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
        $this->newPhotosRank   = TableRegistry::get('new_photos_rank');
        $this->snss            = TableRegistry::get('snss');
        $this->shops           = TableRegistry::get('shops');

        $result = true; // 正常終了フラグ
        $action_name = "saveNewPhotosRank,";
        // webroot/img/配下のinstaキャッシュを取得する
        $dir = WWW_ROOT . PATH_ROOT['IMG'];
        $exp = "*.dat";
        $data = $this->scanDir($dir, $exp);
        var_dump($data);

        $shops = $this->shops->find('all')
            ->contain(['casts', 'casts.diarys' => function (Query $q) {
                return $q
                        ->order(['diarys.created'=>'DESC'])
                        ->limit(1);
                }, 'shop_infos' => function (Query $q) {
                    return $q
                        ->order(['shop_infos.created'=>'DESC'])
                        ->limit(1);
                }
        ])->limit("200")->toArray();

        foreach ($data as $key => $value) {
            if (filesize($value) > 0) {
                $ig_json = @file_get_contents($value);
                if ($ig_json) {
                    $ig_data = json_decode($ig_json);
                    if (isset($ig_data->error)) {
                        continue;
                    }
                }
                $ig_data = json_decode(json_encode($ig_data), true);
                $sns   =  $this->snss->find("all")->where(["snss.instagram"=>$ig_data['business_discovery']['username']])
                            ->contain(['shops', 'shops.owners.servece_plans' => function (Query $q) {
                                return $q
                                    ->where(['current_plan is not'=> SERVECE_PLAN['free']['label']]);
                            }])->toArray();

                if (!empty($sns)) {
                    $shop = $sns[0]->shop;
                    $shop->set("ig_data", $ig_data);
                    $shop->set("ig_date", date ("Y-m-d H:i:s", filemtime($value)));
                    $shop->set("ig_path", $value);
                    array_push($shops, $shop);
                }

            }

        }

        // 店舗,スタッフ情報をセット
        foreach($shops as $key => $shop) {
            $shop->set('shopInfo', $this->Util->getShopInfo($shop));
            foreach($shop->casts as $key => $cast) {
                $cast->set('castInfo', $this->Util->getCastItem($cast, $shop));
            }
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

            $newPhotosRankEntity = $this->newPhotosRank->newEntity();

            if ($sort_list->getSource() == 'shops') {

                if ($sort_list->__isset('ig_data')) {
                    var_dump($sort_list->shop_infos);
                    $shop = $sort_list;
                    $ig_data = $shop->ig_data['business_discovery'];
                    $newPhotosRankEntity->set('name', $shop->name);
                    $newPhotosRankEntity->set('area', $shop->shopInfo['area']['label']);
                    $newPhotosRankEntity->set('genre', $shop->shopInfo['genre']['label']);
                    $newPhotosRankEntity->set('is_insta', 1);
                    $newPhotosRankEntity->set('media_type', $ig_data['media']['data'][0]['media_type']);
                    $newPhotosRankEntity->set('like_count', $ig_data['media']['data'][0]['like_count']);
                    $newPhotosRankEntity->set('comments_count', $ig_data['media']['data'][0]['comments_count']);
                    $newPhotosRankEntity->set('photo_path', $ig_data['media']['data'][0]['media_url']);
                    $newPhotosRankEntity->set('details', $shop->shopInfo['shop_url']);
                    $newPhotosRankEntity->set('content', $ig_data['media']['data'][0]['caption']);
                    $newPhotosRankEntity->set('post_date', date("Y-m-d H:i:s", strtotime($ig_data['media']['data'][0]['timestamp'])));
                    array_push($newPhotosRankEntityList, $newPhotosRankEntity);

                } else if (count($sort_list->shop_infos) > 0) {
                    //$newPhotosRankEntity->
                    var_dump($sort_list->shop_infos);
                    $shop = $sort_list;
                    $shop_infos = $sort_list->shop_infos[0];

                    $photo_path = $sort_list->shopInfo['notice_path'].$shop_infos['dir'];
                    $dir = new Folder(preg_replace('/(\/\/)/', '/'
                            , WWW_ROOT.$photo_path), true, 0755);
                    $files = glob($dir->path.DS.'*.*');
                    usort($files, $this->Util->sortByLastmod);

                    $newPhotosRankEntity->set('name', $shop->name);
                    $newPhotosRankEntity->set('area', $shop->shopInfo['area']['label']);
                    $newPhotosRankEntity->set('genre', $shop->shopInfo['genre']['label']);
                    $newPhotosRankEntity->set('is_insta', 0);
                    $newPhotosRankEntity->set('media_type', 'IMAGE');
                    $newPhotosRankEntity->set('like_count', 0);
                    $newPhotosRankEntity->set('comments_count', 0);
                    $newPhotosRankEntity->set('photo_path', $photo_path.DS.basename($files[0]));
                    $newPhotosRankEntity->set('details', $shop->shopInfo['shop_url']);
                    $newPhotosRankEntity->set('content', $shop_infos->content);
                    $newPhotosRankEntity->set('post_date', $shop_infos->modified->format("Y-m-d H:i:s"));
                    array_push($newPhotosRankEntityList, $newPhotosRankEntity);
                }
            } else if ($sort_list->getSource() == 'casts') {
                if (count($sort_list->diarys) > 0) {
                    $cast = $sort_list;
                    $diary = $sort_list->diarys[0];
                    var_dump($diary);

                    $photo_path = $sort_list->castInfo['diary_path'].$diary['dir'];
                    $dir = new Folder(preg_replace('/(\/\/)/', '/'
                            , WWW_ROOT.$photo_path), true, 0755);
                    $files = glob($dir->path.DS.'*.*');
                    usort($files, $this->Util->sortByLastmod);

                    $newPhotosRankEntity->set('name', $cast->nickname);
                    $newPhotosRankEntity->set('area', $cast->castInfo['area']['label']);
                    $newPhotosRankEntity->set('genre', $cast->castInfo['genre']['label']);
                    $newPhotosRankEntity->set('is_insta', 0);
                    $newPhotosRankEntity->set('media_type', 'IMAGE');
                    $newPhotosRankEntity->set('like_count', 0);
                    $newPhotosRankEntity->set('comments_count', 0);
                    $newPhotosRankEntity->set('photo_path', $photo_path.DS.basename($files[0]));
                    $newPhotosRankEntity->set('details', $cast->castInfo['cast_url']);
                    $newPhotosRankEntity->set('content', $diary->content);
                    $newPhotosRankEntity->set('post_date', $diary->modified->format("Y-m-d H:i:s"));
                    array_push($newPhotosRankEntityList, $newPhotosRankEntity);

                }
            }

        }
        foreach ($newPhotosRankEntityList as $key => $entity) {
            $updated[$key] = $entity['date'];
        }
        //配列のkeyのupdatedでソート
        array_multisort($updated, SORT_DESC, $newPhotosRankEntityList);

        try{
            // レコードが存在した場合は削除する
            if ($this->newPhotosRank->find('all')->count() > 0) {
                // 新着フォトランキングレコード削除
                if (!$this->newPhotosRank->deleteAll([""])) {
                    throw new RuntimeException($action_name.'レコードの削除に失敗しました。');
                }
            }
            $entities = $this->newPhotosRank->newEntities($newPhotosRankEntityList);
            // レコードを一括登録する
            if (!$this->newPhotosRank->saveMany($newPhotosRankEntityList)) {
                throw new RuntimeException($action_name.'レコードの登録に失敗しました。');
            }

        } catch(RuntimeException $e) {
            $this->log("バッチ処理, ". $e, "error");
            $result = false; // 異常終了フラグ
        }

        return $result;
    }

}
