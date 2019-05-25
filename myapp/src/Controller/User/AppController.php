<?php
namespace App\Controller\User;

use Cake\ORM\TableRegistry;
use Cake\Event\Event;
use Cake\Core\Configure;

class AppController extends \App\Controller\AppController
{
    public $components = array('Util');

    public function initialize()
    {
        parent::initialize();
        $this->Users = TableRegistry::get('Users');
        $this->Shops = TableRegistry::get('Shops');
        $this->Coupons = TableRegistry::get('Coupons');
        $this->Casts = TableRegistry::get('Casts');
        $this->Jobs = TableRegistry::get('Jobs');
        $this->MasterCodes = TableRegistry::get("master_codes");
        $this->loadComponent('Auth', [
            // 'authenticate' => [
            //     'Form' => [
            //         'userModel' => 'Users',
            //         'fields' => ['username' => 'email','password' => 'password']
            //     ],
            //    'NodeLink/RememberMe.Cookie' => [
            //        'userModel' => 'Users',  // 'Form'認証と同じモデルを指定します
            //        'fields' => ['token' => 'remember_token'],  // Remember-Me認証用のトークンを保存するカラムを指定します
            //    ],
            // ],
            'storage' => ['className' => 'Session', 'key' => 'Auth.User'],

            'loginAction' => ['controller' => 'Users','action' => 'login'],
            'unauthorizedRedirect' => ['controller' => 'Users','action' => 'login'],
            'loginRedirect' => ['controller' => 'Users','action' => 'index'],
            'logoutRedirect' => ['controller' => 'Users','action' => 'login'],
            // コントローラーで isAuthorized を使用します
            'authorize' => ['Controller'],
                // 未認証の場合、直前のページに戻します
            // 'unauthorizedRedirectedRedirect' => $this->referer()
        ]);

        $query = $this->request->getQuery();
        // 検索結果でタイトルで決める
        $title = '';
        if (!empty($query['area']) && !empty($query['genre'])) {
            // コントローラでセットされたtitleを代入してセパレータを追加
            $title .=  AREA[$query['area']]['label'] . 'のおすすめ'.
                        GENRE[$query['genre']]['label'].'一覧';
        } else if(!empty($query['area'])) {
            $title .=  AREA[$query['area']]['label'] . 'のおすすめ一覧';
        } else if(!empty($query['genre'])) {
            $title .=  GENRE[$query['genre']]['label'] . 'のおすすめ一覧';
        }
        $this->set('title', $title);

    }

    public function isAuthorized($user)
    {
        $action = $this->request->getParam('action');
        // ログイン時に許可するアクション
        if (in_array($action, ['top', 'search', 'index', 'view', 'add', 'delete', 'edit'])) {
            return true;
        }
        return false;
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['top', 'search', 'signup','verify','logout']);
        parent::beforeRender($event); //親クラスのbeforeRendorを呼ぶ
        $this->viewBuilder()->layout('userDefault');
    }

}
