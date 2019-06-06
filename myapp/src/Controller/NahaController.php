<?php
namespace App\Controller;

use \Cake\ORM\Query;
use Token\Util\Token;
use Cake\Mailer\Email;
use Cake\Error\Debugger;
use Cake\ORM\TableRegistry;
use Cake\Mailer\MailerAwareTrait;

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
        $shop = $this->Shops->find('all')
            ->where(['Shops.id' => $id])
            ->contain(['Owners','Casts', 'Coupons','Jobs']);
        $this->set('infoArray', $this->Util->getItem($shop->first()));
        $credits = $this->MasterCodes->find()->where(['code_group' => 'credit']);
        $creditsHidden = json_encode($this->Util->getCredit($shop->owner,$credits));
        $this->set(compact('shop', 'credits','creditsHidden'));
        $this->render();
    }

    public function cast($id = null)
    {
        $query = $this->Diarys->find();
        $ymd = $query->func()->date_format([
            'created' => 'literal',
            "'%Y年%c月%e日'" => 'literal']);
        $columns = $this->Diarys->schema()->columns();
        $columns = $columns + ['ymdCreated'=>$ymd];
        $cast = $this->Casts->find("all")->where(['Casts.id' => $id])
            ->contain(['Shops', 'Diarys' => function(Query $q) use ($columns) {
                    return $q
                        ->select($columns)
                        ->order(['created'=>'DESC'])->limit(1);
                    }, 'Shops.Coupons', 'Shops.Jobs'
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
        $array = $this->Util->getDiary($id);
        $cast = $array['cast'];
        $dir = $array['dir'];
        $archive = $array['archive'];

        $this->set('infoArray', $this->Util->getItem($this->Shops->get($cast->shop_id)->toArray()));
        $this->set(compact('cast','dir', 'archive', 'ajax'));
        $this->render();
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
