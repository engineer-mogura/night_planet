<?php
namespace App\Controller;

use Token\Util\Token;
use Cake\Mailer\MailerAwareTrait;
use Cake\Error\Debugger;
use Cake\Mailer\Email;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;

/**
* Users Controller
*
* @property \App\Model\Table\UsersTable $Users
*
* @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
*/
class MainController extends AppController
{
    use MailerAwareTrait;

    public function initialize()
    {
        parent::initialize();
        $this->Users = TableRegistry::get('Users');
        $this->Shops = TableRegistry::get('Shops');
        $this->Coupons = TableRegistry::get('Coupons');
        $this->Casts = TableRegistry::get('Casts');
        $this->Jobs = TableRegistry::get('Jobs');
        $this->MasterCodes = TableRegistry::get("master_codes");

    }

    public function top()
    {
        $masterCodesFind = array('area','genre');
        $selectList = $this->Util->getSelectList($masterCodesFind, $this->MasterCodes, false);
        $diarys = $this->Util->getNewDiarys(PROPERTY['NEW_INFO_MAX']);
        $this->set(compact('selectList', 'diarys'));
    }

    public function search()
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
        // トップページからの遷移の場合
        if ($referer = (($this->referer()) == "http://okiyoru.local/") ||
            /** ローカル環境スマホ用 */(($this->referer()) == "http://192.168.33.10/")) {
            $columns = array('Shops.name', 'Shops.catch'); // like条件用
            $shops = $this->getShopList($this->request->getQuery(), $columns);
            // 検索条件を取得し、画面側でselectedする
            $selected = $this->request->getQuery();
        }
        $masterCodesFind = array('area','genre');
        $selectList = $this->Util->getSelectList($masterCodesFind, $this->MasterCodes, false);
        $this->set(compact('shops', 'selectList','selected'));
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

    public function beforeFilter(Event $event)
    {
        // parent::beforeFilter($event);
        // $this->Auth->allow(['signup','verify','logout']);
        parent::beforeRender($event); //親クラスのbeforeRendorを呼ぶ
        $this->viewBuilder()->layout('userDefault');
    }

}
