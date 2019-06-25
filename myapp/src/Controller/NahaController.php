<?php
namespace App\Controller;

use \Cake\ORM\Query;
use Token\Util\Token;
use Cake\Mailer\Email;
use Cake\Error\Debugger;
use Cake\ORM\TableRegistry;
use Cake\Mailer\MailerAwareTrait;
use Cake\Routing\Router;
/**
* Users Controller
*
* @property \App\Model\Table\UsersTable $Users
*
* @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
*/
class NahaController extends \App\Controller\AreaController
{
    use MailerAwareTrait;

    public function index()
    {
        if ($this->request->is('ajax')) {
            $this->render();
        }
        $isAreaScreen = mb_strtolower($this->request->getparam("controller"));
        $masterCodesFind = array('area','genre');
        $selectList = $this->Util->getSelectList($masterCodesFind, $this->MasterCodes, false);
        $query = $this->Shops->find();
        $query = $this->Shops->find('all', Array('fields' =>
                    Array('area', 'genre', 'count' => $query->func()->count('genre'))));
        $tmpList = $query->where(['area' => AREA[$isAreaScreen]['path']])
            ->group('genre')->toArray();
        $genreCounts = GENRE; // ジャンルの配列をコピー
        // それぞれのジャンルの初期値カウントに０,エリア名をセットする
        foreach ($genreCounts as $key => $row) {
            $genreCounts[$key] = $row + array('count'=> 0,'area' => AREA[$isAreaScreen]['path']);
        }
        // DBから取得したジャンルのカウントをセットする
        foreach ($tmpList as $key => $row) {
            $genreCounts[$row['genre']]['area'] = AREA[$isAreaScreen]['path'];
            $genreCounts[$row['genre']]['count'] = $row['count'];
        }

        $this->set(compact('isAreaScreen', 'genreCounts', 'selectList'));
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
        $isAreaScreen = mb_strtolower($this->request->getparam("controller"));

        $shops = array(); // 店舗情報格納用
        $columns = array('Shops.name', 'Shops.catch'); // like条件用
        $shops = $this->Shops->find('all')
                    ->where(['area'=>mb_strtolower($this->request->getparam("controller")),
                        'genre' => $this->request->getQuery("genre")])->toArray();
        // 検索条件を取得し、画面側でselectedする
        $conditionSelected = $this->request->getQuery();
        $masterCodesFind = array('area','genre');
        $selectList = $this->Util->getSelectList($masterCodesFind, $this->MasterCodes, false);
        $this->set(compact('isAreaScreen', 'shops', 'selectList','conditionSelected'));
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
                },'Jobs']);
        $this->set('infoArray', $this->Util->getItem($shop->first()));
        $credits = $this->MasterCodes->find()->where(['code_group' => 'credit']);
        //$creditsHidden = json_encode($this->Util->getCredit($shop->owner,$credits));
        $this->set(compact('shop','sharer', 'credits','creditsHidden'));
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
            ]);

        $imageCol = array_values(preg_grep('/^image/', $this->Casts->schema()->columns()));
        $diary = $cast->first()->diarys[0];
        $dImgCol = array(); // 日記の画像が存在するカラムリスト
        if(count($diary) > 0) {
            foreach ($imageCol as $key => $value) {
                if (!empty($diary[$value])) {
                    array_push($dImgCol, $value);
                }
            }
        }
        $this->set('infoArray', $this->Util->getItem($cast->first()->shop));
        $this->set(compact('cast','dImgCol' ,'imageCol'));
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
        $this->set('infoArray', $this->Util->getItem($this->Shops->get($cast->shop_id)->toArray()));

        // $diarys1 = $this->getDiarys($id);
        $diarys = $this->Util->getDiarys($id);
        $dir = $this->viewVars['infoArray']['dir_path'].PATH_ROOT['CAST'].DS.$cast["dir"].DS.PATH_ROOT['DIARY'].DS;

        $this->set(compact('cast','dir', 'diarys', 'ajax'));
        $this->render();
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

}
