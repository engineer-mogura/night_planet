<?php
namespace App\Controller\Owner;

use Cake\ORM\TableRegistry;
use Cake\Event\Event;
use Cake\Core\Configure;

class AppController extends \App\Controller\AppController
{
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

        // ログインしている場合は、オーナー情報を取得
        if(!is_null($this->Auth->user()))
        {
            $this->getItem();
        }

    }

    public function isAuthorized($user)
    {
        $action = $this->request->getParam('action');
        // ログイン時に許可するアクション
        if (in_array($action, ['index', 'view', 'add', 'delete', 'edit', 'editTopImage', 'editCatch', 'editCoupon'])) {
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
    public function getItem() {
        $ownerArea = $this->request->getSession()->read('Auth.Owner.area');
        $ownerGenre = $this->request->getSession()->read('Auth.Owner.genre');
        $ownerDir = $this->request->getSession()->read('Auth.Owner.dir');
        $areas = array ('miyako','ishigaki','naha','nanjo','tomigusuku',
                        'urasoe','ginowan','okinawashi','uruma','nago');
        $genres = array ('caba','snack','girlsbar','bar');

        $infoArray = array();
        foreach ( $areas as $area ) {
            if ($area == $ownerArea) {
                $infoArray = $infoArray + Configure::read('area.'.$area);
                break;
            }
        }
        foreach ( $genres as $genre ) {
            if ($genre == $ownerGenre) {
                $infoArray = $infoArray + Configure::read('genre.'.$genre);
                break;
            }
        }
        $infoArray = $infoArray + array('dir'=> $ownerDir);
        $path = "img/".$infoArray['area_path']."/".$infoArray['genre_path']."/".$infoArray['dir']."/";
        $infoArray = $infoArray + array("dir_path"=> $path);
        $this->set('infoArray',$infoArray);

    }


}