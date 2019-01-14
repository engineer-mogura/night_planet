<?php
namespace App\Controller\Developer;

use Cake\ORM\TableRegistry;
use Cake\Event\Event;

class AppController extends \App\Controller\AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->Developers = TableRegistry::get('Developers');
        $this->Users = TableRegistry::get('Users');
        $this->Owners = TableRegistry::get('Owners');

        $this->loadComponent('Auth', [
            'authenticate' => [
                'Form' => [
                    'userModel' => 'Developers',
                    'fields' => ['username' => 'email','password' => 'password']
                ]
            ],
            'storage' => ['className' => 'Session', 'key' => 'Auth.Developer'],

            'loginAction' => ['controller' => 'Developers','action' => 'login'],
            'unauthorizedRedirect' => ['controller' => 'Developers','action' => 'login'],
            'loginRedirect' => ['controller' => 'Developers','action' => 'index'],
            'logoutRedirect' => ['controller' => 'Developers','action' => 'login'],
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
        $this->Auth->allow(['logout']);
    }

}