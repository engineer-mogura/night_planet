<?php
namespace App\Controller;

use \Cake\ORM\Query;
use Cake\Event\Event;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use Cake\Mailer\MailerAwareTrait;

class AreaController extends AppController
{
    use MailerAwareTrait;

    public function initialize()
    {
        parent::initialize();
        $this->Users = TableRegistry::get('Users');
        $this->Shops = TableRegistry::get('Shops');
        $this->Coupons = TableRegistry::get('Coupons');
        $this->Casts = TableRegistry::get('Casts');
        $this->Diarys = TableRegistry::get('Diarys');
        $this->ShopInfoLikes = TableRegistry::get('Shop_info_Likes');
        $this->DiaryLikes = TableRegistry::get('Diary_Likes');
        $this->Jobs = TableRegistry::get('Jobs');
        $this->ShopInfos = TableRegistry::get("shop_infos");
        $this->Updates = TableRegistry::get("updates");
        $this->MasterCodes = TableRegistry::get("master_codes");

    }

    public function beforeFilter(Event $event)
    {
        // 常に現在エリアを取得
        $isArea = mb_strtolower($this->request->getparam("controller"));
        // 常にエリア、ジャンルセレクトリストを取得
        $masterCodesFind = array('area','genre');
        $selectList = $this->Util->getSelectList($masterCodesFind, $this->MasterCodes, false);
        $this->set(compact('selectList','isArea'));
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
                , $query['name'], $query['nickname'], LT['001']);
            $title = $this->Util->strReplace($search, $replace, TITLE['CAST_TITLE']);
            $search = array('_cast_', '_service_name_');
            $replace = array($query['nickname'], LT['001']);
            $description = $this->Util->strReplace($search, $replace, META['CAST_DESCRIPTION']);

        // キャストの日記トップの場合
        } else if (!empty($query['genre']) && !empty($query['name']) 
            && !empty($query['nickname']) && in_array(PATH_ROOT['DIARY'], $url)) {

            $search = array('_area_', '_genre_', '_shop_', '_cast_', '_service_name_');
            $replace = array(AREA[$url[0]]['label'], GENRE[$query['genre']]['label']
                , $query['name'], $query['nickname'], LT['001']);
            $title = $this->Util->strReplace($search, $replace, TITLE['DIARY_TITLE']);
            $search = array('_cast_', '_service_name_');
            $replace = array($query['nickname'], LT['001']);
            $description = $this->Util->strReplace($search, $replace, META['DIARY_DESCRIPTION']);

        // 店舗のお知らせトップの場合
        } else if (!empty($query['area']) && !empty($query['genre']) 
            && !empty($query['name']) && in_array(PATH_ROOT['NOTICE'], $url)) {

            $search = array('_area_', '_genre_', '_shop_', '_service_name_');
            $replace = array(AREA[$url[0]]['label'], GENRE[$query['genre']]['label']
                , $query['name'], LT['001']);
            $title = $this->Util->strReplace($search, $replace, TITLE['NOTICE_TITLE']);
            $search = array('_shop_', '_service_name_');
            $replace = array($query['name'], LT['001']);
            $description = $this->Util->strReplace($search, $replace, META['NOTICE_DESCRIPTION']);

        // 店舗トップページの場合
        } else if (!empty($query['area']) && !empty($query['genre']) 
            && !empty($query['name'])) {

            $search = array('_area_', '_genre_', '_shop_', '_service_name_');
            $replace = array(AREA[$url[0]]['label'], GENRE[$query['genre']]['label']
                , $query['name'], LT['001']);
            $title = $this->Util->strReplace($search, $replace, TITLE['SHOP_TITLE']);
            $search = array('_catch_copy_', '_service_name_');
            $replace = array($this->Shops->get($url[2])->catch, LT['001']);
            $description = $this->Util->strReplace($search, $replace, META['SHOP_DESCRIPTION']);

        // エリアのトップ画面の場合
        } else if(array_key_exists($url[0], AREA)) {

            $search = array('_area_', '_service_name_');
            $replace = array(AREA[$url[0]]['label'], LT['001']);
            $title = $this->Util->strReplace($search, $replace, TITLE['AREA_TITLE']);
            $description = $this->Util->strReplace($search, $replace, META['AREA_DESCRIPTION']);
        }

        // コントローラ名からエリアタイトルをセット
        $areaTitle = AREA[mb_strtolower($this->request->getparam("controller"))]['label'];
        $this->set(compact('title','description','areaTitle'));
    }

    public function index()
    {
        if ($this->request->is('ajax')) {
            $this->render();
        }

        $query = $this->Shops->find();
        $query = $this->Shops->find('all', Array('fields' =>
                    Array('area', 'genre', 'count' => $query->func()->count('genre'))));
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
        $diarys = $this->Util->getNewDiarys(PROPERTY['NEW_INFO_MAX'], $this->viewVars['isArea'], null);
        $notices = $this->Util->getNewNotices(PROPERTY['NEW_INFO_MAX'], $this->viewVars['isArea']);
        $this->set(compact('genreCounts', 'selectList', 'diarys', 'notices'));

        $this->render();
    }

    public function genre()
    {
        if ($this->request->is('ajax')) {

            $columns = array('Shops.name', 'Shops.catch'); // like条件用
            $shops = $this->getShopList($this->request->getQuery(), $columns);
            // 検索ページからの場合は、結果のみを返却する
            $this->confReturnJson(); // json返却用の設定
            $this->response->body(json_encode($shops));
            return;

        }
        $shops = array(); // 店舗情報格納用
        $columns = array('Shops.name', 'Shops.catch'); // like条件用
        $shops = $this->Shops->find('all')
                    ->where(['area'=>$this->viewVars['isArea'],
                        'genre' => $this->request->getQuery("genre")])->toArray();
        $this->set(compact('shops'));
        $this->render();
    }

    public function shop($id = null)
    {
       $sharer =  Router::reverse($this->request, true);

       $query = $this->ShopInfos->find();
       $ymd = $query->func()->date_format([
           'Shop_infos.created' => 'literal',
           "'%Y年%c月%e日'" => 'literal']);
       $columns = $this->ShopInfos->schema()->columns();
       $columns = $columns + ['ymd_created'=>$ymd];

        $shop = $this->Shops->find('all')
            ->where(['Shops.id' => $id])
            ->contain(['Owners','Casts' => function(Query $q) {
                    return $q
                        ->where(['Casts.status'=>'1']);
                }, 'Coupons' => function(Query $q) {
                    return $q
                        ->where(['Coupons.status'=>'1']);
                },'Shop_infos' => function(Query $q) use ($columns) {
                    return $q
                        ->select($columns)
                        ->order(['Shop_infos.created'=>'DESC'])
                        ->limit(1);
                },'Jobs','Snss'])->first();

        $shopInfo = $this->Util->getShopInfo($shop);
        // 店舗の更新情報を取得する
        $updateInfo = $this->Updates->find('all',array(
            'conditions' => array('created > NOW() - INTERVAL '.PROPERTY['UPDATE_INFO_DAY_MAX'].' DAY')
            ))
            ->distinct(['content'])
            ->where(['shop_id'=>$shopInfo['id']])
            ->order(['created'=>'DESC'])
            ->toArray();
            $update_icon = array();
        // 画面の店舗メニューにnew-icon画像を付与するための配列をセットする
        foreach ($updateInfo as $key => $value) {
            $isNew = in_array($value->type, SHOP_MENU_NAME);
            if ($isNew) {
                $update_icon[] = $value->type;
            }
        }
        $imageCol = array_values(preg_grep('/^image/', $this->Shops->schema()->columns()));
        $imageList = array(); // 画面側でJSONとして使う画像リスト
        // 画像リストを作成する
        foreach ($imageCol as $key => $value) {
            if (!empty($shop[$imageCol[$key]])) {
                array_push($imageList, ['key'=>$imageCol[$key],'name'=>$shop[$imageCol[$key]]]);
            }
        }
        // 店舗キャストの最新日記を取得する
        $diarys = $this->Util->getNewDiarys(PROPERTY['NEW_INFO_MAX'], null, $id);

        // お知らせのギャラリーリストを作成
        $imageCol = array_values(preg_grep('/^image/', $this->ShopInfos->schema()->columns()));
        $shopInfos = $shop->shop_infos[0];
        $nImageList = array(); // お知らせの画像が存在するカラムリスト
        if(count($shopInfos) > 0) {
            foreach ($imageCol as $key => $value) {
                if (!empty($shopInfos[$value])) {
                    array_push($nImageList, ['key'=>$imageCol[$key],'name'=>$shopInfos[$imageCol[$key]]]);
                }
            }
        }
        $credits = $this->MasterCodes->find()->where(['code_group' => 'credit']);
        //$creditsHidden = json_encode($this->Util->getCredit($shop->owner,$credits));
        $insta_user_name = $shop->snss[0]->instagram;
        // インスタ情報を取得
        $tmp_insta_data = $this->Util->getInstagram($insta_user_name, null
        , API['INSTAGRAM_BUSINESS_ID'], API['INSTAGRAM_GRAPH_API_ACCESS_TOKEN']);
        $insta_data = $tmp_insta_data['business_discovery'];
        // インスタユーザーが存在しない場合
        if(!empty($tmp_insta_data['error'])) {
            // エラーメッセージをセットする
            $insta_error = $tmp_insta_data['error']['error_user_title'];
            $this->set(compact('insta_error'));
        }
        $this->set(compact('shop','shopInfo','update_icon','updateInfo','diarys','sharer','imageList'
            ,'nImageList', 'credits','creditsHidden','insta_data','imageCol'));
        $this->render();
    }

    public function cast($id = null)
    {
        $query = $this->Diarys->find();
        $ymd = $query->func()->date_format([
            'Diarys.created' => 'literal',
            "'%Y年%c月%e日'" => 'literal']);
        $columns = $this->Diarys->schema()->columns();
        $columns = $columns + ['ymd_created'=>$ymd];
        // $columns = $columns + ['ymd_created'=>$ymd,'like_count'=>$query->func()->count('Likes.diary_id')];
        // キャスト情報、最新の日記情報とイイネの総数取得
        $cast = $this->Casts->find("all")->where(['Casts.id' => $id])
            ->contain(['Shops', 'Diarys' => function(Query $q) use ($columns) {
                return $q
                    ->select($columns)
                    ->order(['Diarys.created'=>'DESC'])->limit(1);
                }, 'Diarys.Diary_Likes', 'Shops.Coupons' => function(Query $q) {
                return $q
                    ->where(['Coupons.status'=>'1']);
                }, 'Shops.Jobs'
            ])->first();
        // キャストのギャラリーリストを作成
        $imageCol = array_values(preg_grep('/^image/', $this->Casts->schema()->columns()));
        $imageList = array(); // 画面側でjsonとして使う画像リスト
        // 画像リストを作成する
        foreach ($imageCol as $key => $value) {
            if (!empty($cast[$imageCol[$key]])) {
                array_push($imageList, ['key'=>$imageCol[$key],'name'=>$cast[$imageCol[$key]]]);
            }
        }
        // 日記のギャラリーリストを作成
        $imageCol = array_values(preg_grep('/^image/', $this->Diarys->schema()->columns()));
        $diary = $cast->diarys[0];
        $dImageList = array(); // 日記の画像が存在するカラムリスト
        if(count($diary) > 0) {
            foreach ($imageCol as $key => $value) {
                if (!empty($diary[$value])) {
                    array_push($dImageList, ['key'=>$imageCol[$key],'name'=>$diary[$imageCol[$key]]]);
                }
            }
        }
        $this->set('shopInfo', $this->Util->getShopInfo($cast->shop));
        $this->set(compact('cast','imageList','dImageList'));
        $this->render();
    }

    public function diary($id = null)
    {
        $cast = $this->Casts->find('all')->where(['id' => $id])->first();
        $this->set('shopInfo', $this->Util->getShopInfo($this->Shops->get($cast->shop_id)->toArray()));
        $imageCol = array_values(preg_grep('/^image/', $this->Diarys->schema()->columns()));

        $diarys = $this->Util->getDiarys($id);
        $this->viewVars['shopInfo']['diary_path'] = $this->viewVars['shopInfo']['cast_path']
            .DS.$cast["dir"].DS.PATH_ROOT['DIARY'];

        $this->set(compact('cast','diarys','imageCol'));
        $this->render();
    }

    public function viewDiary($id = null)
    {

        if ($this->request->is('ajax')) {
            $this->confReturnJson(); // json返却用の設定
            $query = $this->Diarys->find();
            $columns = $this->Diarys->schema()->columns();
            $ymd = $query->func()->date_format([
                'created' => 'literal',
                "'%Y年%c月%e日'" => 'literal']);
            $columns = $columns + ['ymd_created'=>$ymd];
            // $diary = $query->select($columns)
            //     ->where(['id' => $this->request->query["id"]])->first();

            // キャスト情報、最新の日記情報とイイネの総数取得
            $diary = $this->Diarys->find("all")
                ->select($columns)
                ->where(['id' => $this->request->query["id"]])
                ->contain(['Diary_Likes'])->first();
            // $diary = $this->Diarys->get($this->request->query["id"]);
            $this->response->body(json_encode($diary));
            return;
        }

    }

    /**
     * キャストの全ての日記情報を取得する処理
     *
     * @param [type] $id
     * @return array
     */
    public function getDiarys($id = null)
    {

        $columns = array('id','cast_id','title','content','image1','dir');
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
        $shopInfos = $this->ShopInfos->get($id);
        $this->set('shopInfo', $this->Util->getShopInfo($this->Shops->get($shopInfos->shop_id)->toArray()));
        $imageCol = array_values(preg_grep('/^image/', $this->ShopInfos->schema()->columns()));

        $notices = $this->Util->getNotices($shopInfos->shop_id);

        $this->set(compact('notices','imageCol'));
        $this->render();
    }

    public function viewNotice($id = null)
    {

        if ($this->request->is('ajax')) {
            $this->confReturnJson(); // json返却用の設定
            $query = $this->ShopInfos->find();
            $columns = $this->ShopInfos->schema()->columns();
            $ymd = $query->func()->date_format([
                'created' => 'literal',
                "'%Y年%c月%e日'" => 'literal']);
            $columns = $columns + ['ymd_created'=>$ymd];
            // $notice = $query->select($columns)
            //     ->where(['id' => $this->request->query["id"]])->first();

            // キャスト情報、最新の日記情報とイイネの総数取得
            $notice = $this->ShopInfos->find("all")
                ->select($columns)
                ->where(['id' => $this->request->query["id"]])
                ->contain(['Shop_info_Likes'])
                ->first();
            // $notice = $this->ShopInfos->get($this->request->query["id"]);
            $this->response->body(json_encode($notice));
            return;
        }

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
        foreach($requestData as $key => $findData) {
            // リクエストデータが[key_word]かつ値が空じゃない場合
            if (($key == 'key_word') && ($findData !== "")) {
                foreach ($columns as $key => $value) {
                    $query->orWhere(function ($exp, $q) use ($value, $findData) {
                        $exp->like($value, '%'.$findData.'%');
                        return $exp;
                    });
                }
            } else {
                if($findData !== "") {
                    //$findArray[] = ['Shops.'.$key => $findData];
                    $query->where(['Shops.'.$key => $findData]);
                }
            }
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
