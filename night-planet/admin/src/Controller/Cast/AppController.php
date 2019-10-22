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
        $this->Shops = TableRegistry::get('shops');
        $this->Casts = TableRegistry::get('casts');
        $this->Diarys = TableRegistry::get('diarys');
        $this->DiaryLikes = TableRegistry::get('diary_likes');
        $this->CastSchedules = TableRegistry::get('cast_schedules');
        $this->Snss = TableRegistry::get('snss');
        $this->Updates = TableRegistry::get('updates');
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
        $access = ['index','editCalendar','profile','topImage','saveTopImage'
            ,'gallery','saveGallery','sns','deleteGallery','diary'
            ,'saveDiary','viewDiary','deleteDiary','updateDiary'];
        if (in_array($action, $access)) {
            return true;
        }
        return false;

        // // ログイン時に許可するアクション
        // if (in_array($action, ['index', 'view', 'add', 'delete', 'edit','editCast',
        //     'editCalendar','profile','image','diary','diaryView','diaryUpdate','diaryDelete'])) {
        //     return true;
        // }
        // return false;
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['signup','verify','logout']);
        parent::beforeRender($event); //親クラスのbeforeRendorを呼ぶ
        $this->viewBuilder()->layout('castDefault');
    }

}
