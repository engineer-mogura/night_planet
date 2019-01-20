<?php
namespace App\Controller\Owner;

use Cake\ORM\TableRegistry;
use Cake\Event\Event;

class AppController extends \App\Controller\AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->Owners = TableRegistry::get('Owners');

        $this->loadComponent('Auth', [
            'authenticate' => [
                'Form' => [
                    'userModel' => 'Owners',
                    'fields' => ['username' => 'email','password' => 'password']
                ]
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

    }

    public function isAuthorized($user)
    {
        $action = $this->request->getParam('action');
        // ログイン時に許可するアクション
        if (in_array($action, ['index', 'view', 'edit','add','delete'])) {
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