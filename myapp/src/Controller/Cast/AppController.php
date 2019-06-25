<?php
namespace App\Controller\Cast;

use Cake\ORM\TableRegistry;
use Cake\Event\Event;
use Cake\Core\Configure;

class AppController extends \App\Controller\AppController
{
    public $components = array('Util');

    public function initialize()
    {
        parent::initialize();
        $this->Shops = TableRegistry::get('Shops');
        $this->Casts = TableRegistry::get('Casts');
        $this->Diarys = TableRegistry::get('Diarys');
        $this->Likes = TableRegistry::get('Likes');
        $this->Events = TableRegistry::get('Events');
        $this->MasterCodes = TableRegistry::get("master_codes");
        $this->loadComponent('Auth', [
            'authenticate' => [
                'Form' => [
                    'userModel' => 'Casts',
                    'fields' => ['username' => 'email','password' => 'password']
                ],
               'NodeLink/RememberMe.Cookie' => [
                   'userModel' => 'Casts',  // 'Form'認証と同じモデルを指定します
                   'fields' => ['token' => 'remember_token'],  // Remember-Me認証用のトークンを保存するカラムを指定します
               ],
            ],
            'storage' => ['className' => 'Session', 'key' => 'Auth.Cast'],

            'loginAction' => ['controller' => 'Casts','action' => 'login'],
            'unauthorizedRedirect' => ['controller' => 'Casts','action' => 'login'],
            'loginRedirect' => ['controller' => 'Casts','action' => 'index'],
            'logoutRedirect' => ['controller' => 'Casts','action' => 'login'],
            // コントローラーで isAuthorized を使用します
            'authorize' => ['Controller'],
                // 未認証の場合、直前のページに戻します
            'unauthorizedRedirectedRedirect' => $this->referer()
        ]);

    }

    public function isAuthorized($user)
    {
        $action = $this->request->getParam('action');
        // ログイン時に許可するアクション
        if (in_array($action, ['index', 'view', 'add', 'delete', 'edit','editCast',
            'editCalendar','profile','image','diary','diaryView','diaryUpdate','diaryDelete'])) {
            return true;
        }
        return false;
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['signup','verify','logout']);
        parent::beforeRender($event); //親クラスのbeforeRendorを呼ぶ
        $this->viewBuilder()->layout('castDefault');
    }

}
