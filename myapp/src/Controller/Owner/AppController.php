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
        if (in_array($action, ['index', 'view', 'add', 'delete', 'edit',
                                'editTopImage', 'editCatch', 'editCoupon', 'editTenpo'])) {
            return true;
        }
        return false;
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['signup','verify','logout']);
        parent::beforeRender($event); //親クラスのbeforeRendorを呼ぶ
        $this->viewBuilder()->layout('ownerDefault');
    }

}
