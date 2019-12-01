<?php
namespace App\Controller;

use \Cake\ORM\Query;
use Cake\Event\Event;
use Cake\Routing\Router;
use Cake\Filesystem\Folder;
use Cake\ORM\TableRegistry;
use Cake\Mailer\MailerAwareTrait;

class AreaController extends AppController
{
    use MailerAwareTrait;

    public function initialize()
    {
        parent::initialize();
        $this->Users = TableRegistry::get('users');
        $this->Shops = TableRegistry::get('shops');
        $this->Coupons = TableRegistry::get('coupons');
        $this->Casts = TableRegistry::get('casts');
        $this->Diarys = TableRegistry::get('diarys');
        $this->ShopInfoLikes = TableRegistry::get('shop_info_likes');
        $this->DiaryLikes = TableRegistry::get('diary_likes');
        $this->Jobs = TableRegistry::get('jobs');
        $this->ShopInfos = TableRegistry::get("shop_infos");
        $this->Updates = TableRegistry::get("updates");
        $this->MasterCodes = TableRegistry::get("master_codes");
        $this->WorkSchedules = TableRegistry::get("work_schedules");
    }

    public function beforeFilter(Event $event)
    {
        // 常に現在エリアを取得
        $isArea = mb_strtolower($this->request->getparam("controller"));
        // 常にエリア、ジャンルセレクトリストを取得
        $masterCodesFind = array('area','genre');
        $selectList = $this->Util->getSelectList($masterCodesFind, $this->MasterCodes, false);
        $this->set(compact('selectList', 'isArea'));
        parent::beforeFilter($event);
        $this->viewBuilder()->layout('userDefault');
        $query = $this->request->getQuery();
        $url = explode(DS, rtrim($this->request->url, DS));
        $title = ''; // SEO対策
        $description = ''; // SEO対策

        // キャストトップの場合
        if (!empty($query['genre']) && !empty($query['name'])
            && !empty($query['nickname']) && in_array(PATH_ROOT['CAST'], $url)) {
            $search = array('_area_', '_genre_', '_shop_', '_cast_', '_service_name_');
            $replace = array(AREA[$url[0]]['label'], GENRE[$query['genre']]['label']
                , $query['name'], $query['nickname'], LT['000']);
            $title = $this->Util->strReplace($search, $replace, TITLE['CAST_TITLE']);
            $search = array('_cast_', '_service_name_');
            $replace = array($query['nickname'], LT['000']);
            $description = $this->Util->strReplace($search, $replace, META['CAST_DESCRIPTION']);

        // キャストの日記トップの場合
        } elseif (!empty($query['genre']) && !empty($query['name'])
        && !empty($query['nickname']) && in_array(PATH_ROOT['DIARY'], $url)) {
            $search = array('_area_', '_genre_', '_shop_', '_cast_', '_service_name_');
            $replace = array(AREA[$url[0]]['label'], GENRE[$query['genre']]['label']
                , $query['name'], $query['nickname'], LT['000']);
            $title = $this->Util->strReplace($search, $replace, TITLE['DIARY_TITLE']);
            $search = array('_cast_', '_service_name_');
            $replace = array($query['nickname'], LT['000']);
            $description = $this->Util->strReplace($search, $replace, META['DIARY_DESCRIPTION']);

        // キャストのギャラリートップの場合
        } elseif (!empty($query['genre']) && !empty($query['name'])
        && !empty($query['nickname']) && in_array(PATH_ROOT['GALLERY'], $url)) {
            $search = array('_area_', '_genre_', '_shop_', '_cast_', '_service_name_');
            $replace = array(AREA[$url[0]]['label'], GENRE[$query['genre']]['label']
                , $query['name'], $query['nickname'], LT['000']);
            $title = $this->Util->strReplace($search, $replace, TITLE['GALLERY_TITLE']);
            $search = array('_cast_', '_service_name_');
            $replace = array($query['nickname'], LT['000']);
            $description = $this->Util->strReplace($search, $replace, META['GALLERY_DESCRIPTION']);

        // 店舗のお知らせトップの場合
        } elseif (!empty($query['area']) && !empty($query['genre'])
            && !empty($query['name']) && in_array(PATH_ROOT['NOTICE'], $url)) {
            $search = array('_area_', '_genre_', '_shop_', '_service_name_');
            $replace = array(AREA[$url[0]]['label'], GENRE[$query['genre']]['label']
                , $query['name'], LT['000']);
            $title = $this->Util->strReplace($search, $replace, TITLE['NOTICE_TITLE']);
            $search = array('_shop_', '_service_name_');
            $replace = array($query['name'], LT['000']);
            $description = $this->Util->strReplace($search, $replace, META['NOTICE_DESCRIPTION']);

        // 店舗トップページの場合
        } elseif (!empty($query['area']) && !empty($query['genre'])
            && !empty($query['name'])) {
            $search = array('_area_', '_genre_', '_shop_', '_service_name_');
            $replace = array(AREA[$url[0]]['label'], GENRE[$query['genre']]['label']
                , $query['name'], LT['000']);
            $title = $this->Util->strReplace($search, $replace, TITLE['SHOP_TITLE']);
            $search = array('_catch_copy_', '_service_name_');
            $replace = array($this->Shops->get($url[2])->catch, LT['000']);
            $description = $this->Util->strReplace($search, $replace, META['SHOP_DESCRIPTION']);

        // エリアのトップ画面の場合
        } elseif (array_key_exists($url[0], AREA)) {
            $search = array('_area_', '_service_name_');
            $replace = array(AREA[$url[0]]['label'], LT['000']);
            $title = $this->Util->strReplace($search, $replace, TITLE['AREA_TITLE']);
            $description = $this->Util->strReplace($search, $replace, META['AREA_DESCRIPTION']);
        }

        // コントローラ名からエリアタイトルをセット
        $areaTitle = AREA[mb_strtolower($this->request->getparam("controller"))]['label'];
        $areaLink = DS.AREA[mb_strtolower($this->request->getparam("controller"))]['path'];
        $this->set(compact('title', 'description', 'areaTitle', 'areaLink'));
    }

    public function index()
    {
        if ($this->request->is('ajax')) {
            $this->render();
        }

        $query = $this->Shops->find();
        $query = $this->Shops->find('all', array('fields' =>
                    array('area', 'genre', 'count' => $query->func()->count('genre'))));
        $tmpList = $query->where(['area' => AREA[$this->viewVars['isArea']]['path']])
            ->group('genre')->toArray();
        $genreCounts = GENRE; // ジャンルの配列をコピー
        // それぞれのジャンルの初期値カウントに０,エリア名をセットする
        foreach ($genreCounts as $key => $row) {
            $genreCounts[$key] = $row + array('count'=> 0,'area' => AREA[$this->viewVars['isArea']]['path']);
        }
        // DBから取得したジャンルのカウントをセットする
        foreach ($tmpList as $key => $row) {
            $genreCounts[$row['genre']]['area'] = AREA[$this->viewVars['isArea']]['path'];
            $genreCounts[$row['genre']]['count'] = $row['count'];
        }
        $main_adsenses = $this->Util->getAdsense(PROPERTY['TOP_SLIDER_GALLERY_MAX'], 'main', $this->viewVars['isArea']);
        $sub_adsenses = $this->Util->getAdsense(PROPERTY['SUB_SLIDER_GALLERY_MAX'], 'sub', $this->viewVars['isArea']);
        //広告を配列にセット
        $adsenses = array('main_adsenses' => $main_adsenses, 'sub_adsenses' => $sub_adsenses);
        $diarys = $this->Util->getNewDiarys(PROPERTY['NEW_INFO_MAX'], $this->viewVars['isArea'], null);
        $notices = $this->Util->getNewNotices(PROPERTY['NEW_INFO_MAX'], $this->viewVars['isArea']);
        $this->set(compact('genreCounts', 'selectList', 'diarys', 'notices', 'adsenses'));

        $this->render();
    }

    public function genre()
    {
        $area_genre = ['area'=>AREA[$this->viewVars['isArea']]['label']
            ,'genre'=>GENRE[$this->request->getQuery("genre")]['label']];
        $shops = array(); // 店舗情報格納用
        $shops = $this->Shops->find('all')
                    ->where(['area'=>$this->viewVars['isArea'],
                        'genre' => $this->request->getQuery("genre")])->toArray();

        // トップ画像を設定する
        foreach ($shops as $key => $shop) {
            $path = PATH_ROOT['IMG'].DS.AREA[$shop->area]['path']
                .DS.GENRE[$shop->genre]['path']
                .DS.$shop->dir.DS.PATH_ROOT['TOP_IMAGE'];
            $dir = new Folder(preg_replace('/(\/\/)/', '/', WWW_ROOT.$path), true, 0755);

            $files = array();
            $files = glob($dir->path.DS.'*.*');
            // ファイルが存在したら、画像をセット
            if (count($files) > 0) {
                foreach ($files as $file) {
                    $shop->set('top_image', DS.$path.DS.(basename($file)));
                }
            } else {
                // 共通トップ画像をセット
                $shop->set('top_image', PATH_ROOT['SHOP_TOP_IMAGE']);
            }
        }

        $this->set(compact('shops', 'area_genre'));
        $this->render();
    }

    public function shop($id = null)
    {
        $sharer =  Router::reverse($this->request, true);
        $columns = $this->ShopInfos->schema()->columns();

        $shop = $this->Shops->find('all')
            ->where(['shops.id' => $id])
            ->contain(['owners','owners.servece_plans','casts' => function (Query $q) {
                return $q
                        ->where(['casts.status'=>'1']);
            }, 'coupons' => function (Query $q) {
                return $q
                        ->where(['coupons.status'=>'1']);
            },'shop_infos' => function (Query $q) use ($columns) {
                return $q
                        ->select($columns)
                        ->order(['shop_infos.created'=>'DESC'])
                        ->limit(1);
            },'work_schedules' => function (Query $q) {
                $end_date = date("Y-m-d H:i:s");
                $start_date = date("Y-m-d H:i:s", strtotime($end_date . "-24 hour"));
                $range = "'".$start_date."' and '".$end_date."'";
                return $q
                        ->where(["work_schedules.modified BETWEEN".$range]);
            },'jobs','snss'])->first();

        $shopInfo = $this->Util->getShopInfo($shop);
        $query = $this->Updates->find();
        $columns = $this->Updates->schema()->columns();
        // 店舗の更新情報を取得する
        $updateInfo = $this->Updates->find('all', array(
            'conditions' => array('updates.created > NOW() - INTERVAL '.PROPERTY['UPDATE_INFO_DAY_MAX'].' DAY')
        ))
        ->join([
            'table' => 'updates',
            'alias' => 'u',
            'type' => 'LEFT',
            'conditions' => 'u.content = updates.content and u.created > updates.created'
        ])
        ->select($columns)
        ->where(['updates.shop_id'=>$shopInfo['id'],'u.created IS NULL'])
        ->order(['updates.created'=>'DESC'])
        ->toArray();

        $update_icon = array();
        // 画面の店舗メニューにnew-icon画像を付与するための配列をセットする
        foreach ($updateInfo as $key => $value) {
            $isNew = in_array($value->type, SHOP_MENU_NAME);
            if ($isNew) {
                $update_icon[] = $value->type;
            }
        }
        // 今日の日付から1ヶ月前
        $end_date = date('Y-m-d', strtotime("-1 month"));
        // キャストの登録日付をチェックする
        foreach ($shop->casts as $key => $cast) {
            $user_created = $cast->created->format('Y-m-d');
            $end_ts = strtotime($end_date);
            $user_ts = strtotime($user_created);
            // 新しいキャストの場合フラグセット
            if ($user_ts >= $end_ts) {
                $cast->set('new_cast', true);
            }
            // キャストの更新があればフラグをセット
            foreach ($updateInfo as $key => $value) {
                if (!empty($value->cast_id) && $value->cast_id == $cast->id) {
                    $cast->set('update_cast', true);
                }
            }
        }

        // トップ画像を設定する
        $dir = new Folder(preg_replace('/(\/\/)/', '/', WWW_ROOT.$shopInfo['top_image_path']), true, 0755);

        $files = array();
        $files = glob($dir->path.DS.'*.*');
        // ファイルが存在したら、画像をセット
        if (count($files) > 0) {
            foreach ($files as $file) {
                $shop->set('top_image', $shopInfo['top_image_path'].DS.(basename($file)));
            }
        } else {
            // 共通トップ画像をセット
            $shop->set('top_image', PATH_ROOT['SHOP_TOP_IMAGE']);
        }

        // ギャラリーリストを作成
        // ディクレトリ取得
        $dir = new Folder(preg_replace('/(\/\/)/', '/', WWW_ROOT.$shopInfo['image_path']), true, 0755);
        $gallery = array();

        /// 並び替えして出力
        $files = array();
        $files = glob($dir->path.DS.'*.*');
        usort($files, $this->Util->sortByLastmod);
        foreach ($files as $file) {
            $timestamp = date('Y/m/d H:i', filemtime($file));
            array_push($gallery, array(
                "file_path"=>$shopInfo['image_path'].DS.(basename($file))
                ,"date"=>$timestamp));
        }
        $shop->set('gallery', $gallery);

        // お知らせが１つでもある場合
        if (count($shop->shop_infos) > 0) {

            // お知らせのギャラリーリストを作成
            // ディクレトリ取得
            $dir = new Folder(preg_replace('/(\/\/)/', '/', WWW_ROOT.$shopInfo['notice_path'].$shop->shop_infos[0]->dir), true, 0755);
            $gallery = array();
            /// 並び替えして出力
            $files = array();
            $files = glob($dir->path.DS.'*.*');
            usort($files, $this->Util->sortByLastmod);
            foreach ($files as $file) {
                $timestamp = date('Y/m/d H:i', filemtime($file));
                array_push($gallery, array(
                    "file_path"=>$shopInfo['notice_path'].$shop->shop_infos[0]->dir.DS.(basename($file))
                    ,"date"=>$timestamp));
            }
            $shop->shop_infos[0]->set('gallery', $gallery);
        }

        // キャストのアイコンを設定する
        foreach ($shop->casts as $key => $cast) {
            $path = $shopInfo['cast_path'].DS.$cast->dir.DS.PATH_ROOT['PROFILE'];
            $dir = new Folder(preg_replace('/(\/\/)/', '/', WWW_ROOT.$path), true, 0755);
            $files = array();
            $files = glob($dir->path.DS.'*.*');
            // ファイルが存在したら、画像をセット
            if (count($files) > 0) {
                foreach ($files as $file) {
                    $cast->set('icon', $path.DS.(basename($file)));
                }
            } else {
                // 共通トップ画像をセット
                $cast->set('icon', PATH_ROOT['NO_IMAGE02']);
            }
        }
        // 店舗キャストの最新日記を取得する
        $diarys = $this->Util->getNewDiarys(PROPERTY['NEW_INFO_MAX'], null, $id);

        $credits = $this->MasterCodes->find()->where(['code_group' => 'credit']);
        $ig_data = null; // Instagramデータ
        // Instagramアカウントが入力されていればインスタデータを取得する
        if (!empty($shop->snss[0]->instagram)) {

            $insta_user_name = $shop->snss[0]->instagram;
            // インスタのキャッシュパス
            $cache_path = preg_replace(
                '/(\/\/)/',
                '/',
                WWW_ROOT.$shopInfo['cache_path']
            );
            // インスタ情報を取得
            $tmp_ig_data = $this->Util->getInstagram($insta_user_name, null
                , $shopInfo['current_plan'], $cache_path);
            // データ取得に失敗した場合
            if (!$tmp_ig_data) {
                $this->log('【'.AREA[$shop->area]['label']
                    .GENRE[$shop->genre]['label'].$shop->name
                    .'】のインスタグラムのデータ取得に失敗しました。','error');
                $this->Flash->warning('インスタグラムのデータ取得に失敗しました。');
            }
            $ig_data = $tmp_ig_data->business_discovery;
            // インスタユーザーが存在しない場合
            if (!empty($tmp_ig_data->error)) {
                // エラーメッセージをセットする
                $insta_error = $tmp_ig_data->error->error_user_title;
                $this->set(compact('ig_error'));
            }
        }

        $this->set(compact('shop', 'shopInfo', 'update_icon', 'updateInfo'
            , 'diarys', 'sharer', 'credits', 'creditsHidden', 'ig_data'));
        $this->render();
    }

    public function cast($id = null)
    {
        // キャスト情報、最新の日記情報とイイネの総数取得
        $cast = $this->Casts->find("all")->where(['casts.id' => $id])
            ->contain(['shops','shops.owners.servece_plans', 'diarys' => function (Query $q) {
                return $q
                    ->order(['diarys.created'=>'DESC']);
            }
                , 'diarys.diary_likes','Snss'
            ])->first();
        // その他のキャストを取得する
        $other_casts = $this->Casts->find("all")
            ->where(['casts.shop_id' => $this->request->getQuery('shop')
                , 'casts.id is not' => $id
            ])
            ->order(['created'=>'DESC'])
            ->toArray();

        // 本日のキャストの出勤有無を取得する
        $end_date = date("Y-m-d H:i:s");
        $start_date = date("Y-m-d H:i:s", strtotime($end_date . "-24 hour"));
        $range = "'".$start_date."' and '".$end_date."'";

        $isWorkDay = $this->WorkSchedules->find('all')
                ->where(['shop_id'=>$cast->shop_id
                    , "modified BETWEEN".$range
                    , 'FIND_IN_SET(\''. $cast->id .'\', cast_ids)'])
                ->count();

        // キャスト情報取得
        $castInfo = $this->Util->getCastItem($cast, $cast->shop);

        // トップ画像を設定する
        $dir = new Folder(preg_replace('/(\/\/)/', '/', WWW_ROOT.$castInfo['top_image_path']), true, 0755);

        $files = array();
        $files = glob($dir->path.DS.'*.*');
        // ファイルが存在したら、画像をセット
        if (count($files) > 0) {
            foreach ($files as $file) {
                $cast->set('top_image', $castInfo['top_image_path'].DS.(basename($file)));
            }
        } else {
            // 共通トップ画像をセット
            $cast->set('top_image', PATH_ROOT['CAST_TOP_IMAGE']);
        }
        // アイコンを設定する
        $dir = new Folder(preg_replace('/(\/\/)/', '/', WWW_ROOT.$castInfo['profile_path']), true, 0755);

        $files = array();
        $files = glob($dir->path.DS.'*.*');
        // ファイルが存在したら、画像をセット
        if (count($files) > 0) {
            foreach ($files as $file) {
                $cast->set('icon', $castInfo['profile_path'].DS.(basename($file)));
            }
        } else {
            // 共通トップ画像をセット
            $cast->set('icon', PATH_ROOT['NO_IMAGE02']);
        }

        // ギャラリーリストを作成
        // ディクレトリ取得
        $dir = new Folder(preg_replace('/(\/\/)/', '/', WWW_ROOT.$castInfo['image_path']), true, 0755);
        $gallery = array();

        /// 並び替えして出力
        $files = array();
        $files = glob($dir->path.DS.'*.*');
        usort($files, $this->Util->sortByLastmod);
        foreach ($files as $file) {
            $timestamp = date('Y/m/d H:i', filemtime($file));
            array_push($gallery, array(
                "file_path"=>$castInfo['image_path'].DS.(basename($file))
                ,"date"=>$timestamp));
        }
        $cast->set('gallery', $gallery);

        // 日記が１つでもある場合
        if (count($cast->diarys) > 0) {

            // 日記のギャラリーリストを作成
            // ディクレトリ取得
            $dir = new Folder(preg_replace('/(\/\/)/', '/', WWW_ROOT.$castInfo['diary_path'].$cast->diarys[0]->dir), true, 0755);
            $gallery = array();
            /// 並び替えして出力
            $files = array();
            $files = glob($dir->path.DS.'*.*');
            usort($files, $this->Util->sortByLastmod);
            foreach ($files as $file) {
                $timestamp = date('Y/m/d H:i', filemtime($file));
                array_push($gallery, array(
                    "file_path"=>$castInfo['diary_path'].$cast->diarys[0]->dir.DS.(basename($file))
                    ,"date"=>$timestamp));
            }
            $cast->diarys[0]->set('gallery', $gallery);
        }

        $shopInfo = $this->Util->getShopInfo($cast->shop);

        // その他キャストのアイコンを設定する
        foreach ($other_casts as $key => $otherCast) {
            $path = $shopInfo['cast_path'].DS.$otherCast->dir.DS.PATH_ROOT['PROFILE'];
            $dir = new Folder(preg_replace('/(\/\/)/', '/', WWW_ROOT.$path), true, 0755);
            $files = array();
            $files = glob($dir->path.DS.'*.*');
            // ファイルが存在したら、画像をセット
            if (count($files) > 0) {
                foreach ($files as $file) {
                    $otherCast->set('icon', $path.DS.(basename($file)));
                }
            } else {
                // 共通トップ画像をセット
                $otherCast->set('icon', PATH_ROOT['NO_IMAGE02']);
            }
        }

        $ig_data = null; // Instagramデータ
        // Instagramアカウントが入力されていればインスタデータを取得する
        if (!empty($cast->snss[0]->instagram)) {

            $insta_user_name = $cast->snss[0]->instagram;
            // インスタのキャッシュパス
            $cache_path = preg_replace(
                '/(\/\/)/',
                '/',
                WWW_ROOT.$castInfo['cache_path']
            );
            // インスタ情報を取得
            $tmp_ig_data = $this->Util->getInstagram($insta_user_name, null, $cache_path);
            // データ取得に失敗した場合
            if (!$tmp_ig_data) {
                $this->log('【'.AREA[$shop->area]['label']
                    .GENRE[$shop->genre]['label'].$shop->name
                    .'】のインスタグラムのデータ取得に失敗しました。','error');
                $this->Flash->warning('インスタグラムのデータ取得に失敗しました。');
            }
            $ig_data = $tmp_ig_data->business_discovery;
            // インスタユーザーが存在しない場合
            if (!empty($tmp_ig_data->error)) {
                // エラーメッセージをセットする
                $insta_error = $tmp_ig_data->error->error_user_title;
                $this->set(compact('ig_error'));
            }
        }

        $this->set(compact('cast', 'isWorkDay', 'ig_data', 'other_casts', 'shopInfo', 'castInfo'));
        $this->render();
    }

    public function gallery($id = null)
    {
        $cast = $this->Casts->find('all')
            ->where(['casts.id' => $id])
            ->contain(['shops'])
            ->first();

        // キャスト情報取得
        $castInfo = $this->Util->getCastItem($cast, $cast->shop);
        // ギャラリーリストを作成
        // ディクレトリ取得
        $dir = new Folder(preg_replace('/(\/\/)/', '/', WWW_ROOT.$castInfo['cast_path'].DS.PATH_ROOT['IMAGE']), true, 0755);

        $gallery = array();

        // 並び替えして出力
        $files = glob($dir->path.DS.'*.*');
        usort($files, $this->Util->sortByLastmod);
        foreach ($files as $file) {
            $timestamp = date('Y/m/d H:i', filemtime($file));
            array_push($gallery, array(
                "file_path"=>$castInfo['cast_path'].DS.PATH_ROOT['IMAGE'].DS.(basename($file))
                ,"date"=>$timestamp));
        }

        $this->set(compact('cast', 'gallery'));
        $this->render();
    }

    public function diary($id = null)
    {
        $cast = $this->Casts->find('all')->where(['casts.id' => $id])
            ->contain(['shops'])
            ->first();

        $this->set('userInfo', $this->Util->getCastItem($cast, $cast->shop));
        $this->set('shopInfo', $this->Util->getShopInfo($cast->shop));

        // キャストの全ての日記を取得
        $diarys = $this->Util->getDiarys($id, $this->viewVars['userInfo']['diary_path']);

        $this->set(compact('cast', 'diarys'));
        $this->render();
    }

    public function viewDiary()
    {
        // AJAXのアクセス以外は不正とみなす。
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException('AJAX以外でのアクセスがあります。');
        }
        $this->confReturnJson(); // json返却用の設定
        $cast = $this->Casts->find('all')
            ->where(['casts.id' => $this->request->query["id"]])
            ->contain(['shops'])
            ->first();

        $this->set('userInfo', $this->Util->getCastItem($cast, $cast->shop));
        $diary = $this->Util->getDiary($this->request->getQuery('diary_id'), $this->viewVars['userInfo']['diary_path']);
        $this->response->body(json_encode($diary));
        return;
    }
    /**
     * キャストの全ての日記情報を取得する処理
     *
     * @param [type] $id
     * @return array
     */
    public function getDiarys($id = null)
    {
        $columns = array('id','cast_id','title','content','dir');
        // キャスト情報、最新の日記情報とイイネの総数取得
        $diarys = $this->Diarys->find("all")
            ->select($columns)
            ->where(['cast_id' => $id])
            ->contain(['Diary_Likes'])
            ->order(['created'=>'DESC'])->limit(5);
        // 過去の日記をアーカイブ形式で取得する
        // TODO: 年月毎に取得する。月毎の投稿は、アコーディオンを開いたときに年月を条件にsql取得？
        $query = $this->Diarys->find('all')->select($columns);
        $ym = $query->func()->date_format([
            'created' => 'identifier',
            "'%Y年%c月'" => 'literal']);
        $md = $query->func()->date_format([
            'created' => 'identifier',
            "'%c月%e日'" => 'literal']);
        $count = $query->func()->count('*');
        $archive = $query->select([
            'ym_created' => $ym,
            'md_created' => $md])
            ->where(['cast_id' => $id])
            ->contain(['Diary_Likes'])
            ->order(['created' => 'DESC'])->all();
        $archive = $this->Util->groupArray($archive, 'ym_created');
        $archive = array_values($archive);
        return $archive;
    }

    public function notice($id = null)
    {
        $shop = $this->Shops->get($id);
        $shopInfo = $this->Util->getShopInfo($shop);
        $notices = $this->Util->getNotices($shop->id, $shopInfo['notice_path']);

        $this->set(compact('shop', 'notices', 'shopInfo'));
        $this->render();
    }

    public function viewNotice()
    {

        // AJAXのアクセス以外は不正とみなす。
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException('AJAX以外でのアクセスがあります。');
        }
        $this->confReturnJson(); // json返却用の設定
        $shop = $this->Shops->get($this->request->query["id"]);

        $this->set('shopInfo', $this->Util->getShopInfo($shop));
        $notice = $this->Util->getNotice($this->request->getQuery('notice_id'), $this->viewVars['shopInfo']['notice_path']);
        $this->response->body(json_encode($notice));
        return;

        // if ($this->request->is('ajax')) {
        //     $this->confReturnJson(); // json返却用の設定
        //     $query = $this->ShopInfos->find();
        //     $columns = $this->ShopInfos->schema()->columns();
        //     $ymd = $query->func()->date_format([
        //         'created' => 'literal',
        //         "'%Y/%c/%e %H:%i'" => 'literal']);
        //     $columns = $columns + ['ymd_created'=>$ymd];

        //     // キャスト情報、最新の日記情報とイイネの総数取得
        //     $notice = $this->ShopInfos->find("all")
        //         ->select($columns)
        //         ->where(['id' => $this->request->query["id"]])
        //         ->contain(['Shop_info_Likes'])
        //         ->first();
        //     $this->response->body(json_encode($notice));
        //     return;
        // }
    }

    /**
     * ショップテーブルから検索条件による店舗情報を取得する
     *
     * @param array $validate
     * @return void
     */
    public function getShopList($requestData, $columns)
    {
        $query = $this->Shops->find();
        $findArray = array(); // 検索条件セット用
        foreach ($requestData as $key => $findData) {
            // リクエストデータが[key_word]かつ値が空じゃない場合
            if (($key == 'key_word') && ($findData !== "")) {
                foreach ($columns as $key => $value) {
                    $query->orWhere(function ($exp, $q) use ($value, $findData) {
                        $exp->like($value, '%'.$findData.'%');
                        return $exp;
                    });
                }
            } else {
                if ($findData !== "") {
                    //$findArray[] = ['shops.'.$key => $findData];
                    $query->where(['shops.'.$key => $findData]);
                }
            }
        }
        foreach ($query as $key => $value) {
            $value;
        }
        return $query->toArray();
    }

    /**
     * json返却用の設定
     *
     * @param array $validate
     * @return void
     */
    public function confReturnJson()
    {
        $this->viewBuilder()->autoLayout(false);
        $this->autoRender = false;
        $this->response->charset('UTF-8');
        $this->response->type('json');
    }
}
