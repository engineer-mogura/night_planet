<?php
namespace App\Controller\User;

use Cake\ORM\TableRegistry;
use Cake\Event\Event;

class AppController extends \App\Controller\AppController
{
    public $components = array('Util');

    public function initialize()
    {
        parent::initialize();
        $this->Users = TableRegistry::get("users");
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
                    'userModel' => 'Users',
                    'fields' => ['username' => 'email','password' => 'password']
                ],
               'NodeLink/RememberMe.Cookie' => [
                   'userModel' => 'Users',  // 'Form'認証と同じモデルを指定します
                   'fields' => ['token' => 'remember_token'],  // Remember-Me認証用のトークンを保存するカラムを指定します
               ],
            ],
            'storage' => ['className' => 'Session', 'key' => 'Auth.User'],

            'loginAction' => ['controller' => 'Users','action' => 'login'],
            'unauthorizedRedirect' => ['controller' => 'Main','action' => 'top'],
            'loginRedirect' => ['controller' => 'Users','action' => 'index'],
            'logoutRedirect' => ['controller' => 'Main','action' => 'top'],
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
        $access = ['index','deleteDiary','updateDiary','passChange'];
        if (in_array($action, $access)) {
            return true;
        }
        return false;
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['signup','verify','resetVerify','logout','passReset']);
        $this->Auth->config('authError', "もう一度ログインしてください。");
        parent::beforeRender($event); //親クラスのbeforeRendorを呼ぶ
        $this->viewBuilder()->layout('userDefault');
    }

   /**
     * ユーザのステータス、論理削除フラグチェック
     *
     * @param Array $user
     * @return Boolean $rslt
     */
    public function checkStatus($user)
    {
        $rslt = true;

        if ($user['delete_flag'] == 1) {
            $rslt = false;
            $body .= "そのままご送信ください。【ID】： ".$user->id."、";
            $body .= "【お名前】： ".$user->name."、";
            $body .= "【メールアドレス】： ".$user->email;

            $message = "アカウントが凍結または削除された可能性があります。アカウントを回復希望の方はお問い合わせのリンクを開きそのままご送信ください。";
            $this->log($this->Util->setLog($user, $message));
            $this->Flash->error($message . "<a href='mailto:info@night-planet.com?subject=アカウント回復希望&amp;body=".$body."'>お問い合わせ</a>", ['escape' => false]);
        }

        return $rslt;
    }
}
