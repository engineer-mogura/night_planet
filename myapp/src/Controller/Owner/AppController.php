<?php
namespace App\Controller\Owner;

use Cake\ORM\TableRegistry;
use Cake\Event\Event;
use Cake\Core\Configure;

class AppController extends \App\Controller\AppController
{
    public $components = array('Util');

    public function initialize()
    {
        parent::initialize();
        $this->Owners = TableRegistry::get('Owners');
        $this->Shops = TableRegistry::get('Shops');
        $this->Coupons = TableRegistry::get('Coupons');
        $this->Casts = TableRegistry::get('Casts');
        $this->Jobs = TableRegistry::get('Jobs');
        $this->Snss = TableRegistry::get('Snss');
        $this->Updates = TableRegistry::get('Updates');
        $this->ShopInfos = TableRegistry::get("shop_infos");
        $this->WorkSchedules = TableRegistry::get("work_schedules");
        $this->MasterCodes = TableRegistry::get("master_codes");
        $this->loadComponent('Auth', [
            'authenticate' => [
                'Form' => [
                    'userModel' => 'Owners',
                    'fields' => ['username' => 'email','password' => 'password']
                ],
               'NodeLink/RememberMe.Cookie' => [
                   'userModel' => 'Owners',  // 'Form'認証と同じモデルを指定します
                   'fields' => ['token' => 'remember_token'],  // Remember-Me認証用のトークンを保存するカラムを指定します
               ],
            ],
            'storage' => ['className' => 'Session', 'key' => 'Auth.Owner'],

            'loginAction' => ['controller' => 'Owners','action' => 'login'],
            'unauthorizedRedirect' => ['controller' => 'Owners','action' => 'login'],
            'loginRedirect' => ['controller' => 'Owners','action' => 'index'],
            'logoutRedirect' => ['controller' => 'Owners','action' => 'login'],
            // コントローラーで isAuthorized を使用します
            'authorize' => ['Controller'],
                // 未認証の場合、直前のページに戻します
            'unauthorizedRedirectedRedirect' => $this->referer()
        ]);
        // TODO: この自動ログインのコメントは削除予定。\node-link\cakephp-remember-meプラグインで対応できてる
        // ユーザー自動ログイン(未ログイン時かつ自動ログインCookie有効な場合)
        // $user = $this->Auth->user(); // ユーザーのログイン情報の取得
        // $auto_login_key = $this->request->getCookie('AUTO_LOGIN'); // 自動ログインCookie取得
        // if (empty($user) && !empty($auto_login_key)) {

        //     // Cookieから取得したkey一致かつ有効期限内(1ヶ月)
        //     $owner = $this->Owners->find()
        //         ->where(['id'=>$usre['id'],'remember_token' => $auto_login_key])->first();

        //     if ($owner) {
        //         $this->Auth->setUser($owner); // オーナーログイン
        //     }
        // }

    }

    public function isAuthorized($user)
    {
        $action = $this->request->getParam('action');
        // ログイン時に許可するアクション
        // if (in_array($action, ['index', 'view', 'add', 'delete', 'edit',
        //                         'editTopImage', 'editCatch', 'editCoupon', 'editCast', 'editTenpo', 'editJob'])) {
        //     return true;
        // }

        // ログイン時に許可するオーナー画面アクション
        $ownerAccess = ['index','shopAdd','profile'];

        // ログイン時に許可する店舗編集画面アクション
        $shopAccess = ['index','saveTopImage','deleteTopImage','saveCatch','deleteCatch',
            'saveCoupon','deleteCoupon','switchCoupon','deleteCoupon','saveCast','switchCast',
            'deleteCast','saveTenpo','saveJob','saveSns','saveGallery','deleteGallery','notice','viewNotice',
            'saveNotice','updateNotice','deleteNotice','workSchedule','saveWorkSchedule'];

        //TODO: 権限によって店舗管理者のみとオーナー兼店舗管理者を分ける？
        // 今は、分けず各アクションは統合する
        $access = array_merge($ownerAccess,$shopAccess);
        if (in_array($action, $access)) {
            return true;
        }
        return false;
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['signup','verify','logout']);
        parent::beforeRender($event); //親クラスのbeforeRendorを呼ぶ
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
