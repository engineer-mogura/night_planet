<?php

namespace App\Controller;

use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Controller\Component;
use Cake\Controller\Controller;
use Cake\Controller\ComponentRegistry;
use App\Controller\Component\OutSideSqlComponent;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    public $components = array('Util', 'OutSideSql');

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Flash');
        $this->Users         = TableRegistry::get('users');
        $this->Casts         = TableRegistry::get('casts');
        $this->Owners        = TableRegistry::get('owners');
        $this->Coupons       = TableRegistry::get('coupons');
        $this->Diarys        = TableRegistry::get('diarys');
        $this->Jobs          = TableRegistry::get('jobs');
        $this->MasterCodes   = TableRegistry::get("master_codes");
        $this->NewPhotosRank = TableRegistry::get('new_photos_rank');
        $this->ShopLikes     = TableRegistry::get('shop_likes');
        $this->CastLikes     = TableRegistry::get('cast_likes');
        $this->ShopInfoLikes = TableRegistry::get('shop_info_likes');
        $this->DiaryLikes    = TableRegistry::get('diary_likes');
        $this->ShopInfos     = TableRegistry::get("shop_infos");
        $this->Reviews       = TableRegistry::get('reviews');
        $this->ShopOptions   = TableRegistry::get("shop_options");
        $this->Shop_infos    = TableRegistry::get('shop_infos');
        $this->Shops         = TableRegistry::get('shops');
        $this->Updates       = TableRegistry::get("updates");
        $this->WorkSchedules = TableRegistry::get("work_schedules");
        $this->Tmps           = TableRegistry::get("tmps");
    }

    public function beforeFilter(Event $event)
    {
      $masterCodesFind = array('area','genre');
      $selectList = $this->Util->getSelectList($masterCodesFind, $this->MasterCodes, false);
      $this->set(compact('selectList'));
      $this->viewBuilder()->layout('userDefault');

      // 認証済クッキーがあればユーザ情報を取得する
      if(!empty($user = (array) json_decode($this->request->getCookie('_auth_info')))) {
        if ($this->Users->exists(['id' => $user['id']])) {
            $user = $this->Users->get($user['id']);
            // ユーザに関する情報をセット
            $this->set('userInfo', $this->Util->getUserInfo($user));
        }
      }
    }
}
