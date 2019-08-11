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
        $this->Likes = TableRegistry::get('Likes');
        $this->Jobs = TableRegistry::get('Jobs');
        $this->MasterCodes = TableRegistry::get("master_codes");

        // コントローラ名からエリアタイトルをセット
        $areaTitle = AREA[mb_strtolower($this->request->getparam("controller"))]['label'];
        $this->set(compact('areaTitle'));

    }

    // public function beforeFilter(Event $event)
    // {
    //     // AppController.beforeFilterをコールバック
    //     parent::beforeFilter($event);
    //     // キャストに関する情報をセット
    //     if (!is_null($user = $this->Auth->user())) {
    //         $cast = $this->Users->get($user['id']);
    //         $this->set('userInfo', $this->Util->getCastItem($cast));
    //     }
    // }
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

        $this->set(compact('genreCounts'));
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
        $shop = $this->Shops->find('all')
            ->where(['Shops.id' => $id])
            ->contain(['Owners','Casts' => function(Query $q) {
                return $q
                    ->where(['Casts.status'=>'1']);
                }, 'Coupons' => function(Query $q) {
                return $q
                    ->where(['Coupons.status'=>'1']);
                },'Jobs'])->first();
        $shopInfo = $this->Util->getShopInfo($shop);
        $imageCol = array_values(preg_grep('/^image/', $this->Shops->schema()->columns()));
        $imageList = array(); // 画面側でJSONとして使う画像リスト
        // 画像リストを作成する
        foreach ($imageCol as $key => $value) {
            if (!empty($shop[$imageCol[$key]])) {
                array_push($imageList, ['key'=>$imageCol[$key],'name'=>$shop[$imageCol[$key]]]);
            }
        }
        $credits = $this->MasterCodes->find()->where(['code_group' => 'credit']);
        //$creditsHidden = json_encode($this->Util->getCredit($shop->owner,$credits));
        $this->set(compact('shop','shopInfo','sharer','imageList','credits','creditsHidden'));
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
                }, 'Diarys.Likes', 'Shops.Coupons' => function(Query $q) {
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
                ->contain(['Likes'])->first();
            // $diary = $this->Diarys->get($this->request->query["id"]);
            $this->response->body(json_encode($diary));
            return;
        }
        $cast = $this->Casts->find('all')->where(['id' => $id])->first();
        $this->set('shopInfo', $this->Util->getShopInfo($this->Shops->get($cast->shop_id)->toArray()));

        // $diarys1 = $this->getDiarys($id);
        $diarys = $this->Util->getDiarys($id);
        $dir = $this->viewVars['shopInfo']['cast_path'].DS.$cast["dir"].DS.PATH_ROOT['DIARY'];

        $this->set(compact('cast','dir', 'diarys', 'ajax'));
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
                ->contain(['Likes'])->first();
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
            ->contain(['Likes'])
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
            ->contain(['Likes'])
            ->order(['created' => 'DESC'])->all();
        $archive = $this->Util->groupArray($archive, 'ym_created');
        $archive = array_values($archive);
        return $archive;
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

    /**
     * Actionが実行されるよりも前の共通処理
     *
     * @param Event $event
     * @return void
     */
    public function beforeFilter(Event $event)
    {
        // 常に現在エリアを取得
        $isArea = mb_strtolower($this->request->getparam("controller"));
        // 常にエリア、ジャンルセレクトリストを取得
        $masterCodesFind = array('area','genre');
        $selectList = $this->Util->getSelectList($masterCodesFind, $this->MasterCodes, false);
        $this->set(compact('selectList','isArea'));
        // parent::beforeFilter($event);
        // $this->Auth->allow(['signup','verify','logout']);
        parent::beforeRender($event); //親クラスのbeforeRendorを呼ぶ
        $this->viewBuilder()->layout('userDefault');
    }

}
