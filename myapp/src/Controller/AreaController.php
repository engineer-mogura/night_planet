<?php
namespace App\Controller;

use Cake\ORM\TableRegistry;
use Cake\Event\Event;
use Cake\Core\Configure;

class AreaController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->Users = TableRegistry::get('Users');
        $this->Shops = TableRegistry::get('Shops');
        $this->Coupons = TableRegistry::get('Coupons');
        $this->Casts = TableRegistry::get('Casts');
        $this->Diarys = TableRegistry::get('Diarys');
        $this->Likes = TableRegistry::get('Likes');
        $this->Jobs = TableRegistry::get('Jobs');
        $this->MasterCodes = TableRegistry::get("master_codes");

        // コントローラ名からエリアタイトルをセット
        $areaTitle = AREA[mb_strtolower($this->request->getparam("controller"))]['label'];
        $this->set(compact('areaTitle'));

    }

    public function beforeFilter(Event $event)
    {
        // 常に現在エリアを取得
        $isArea = mb_strtolower($this->request->getparam("controller"));
        // 常にエリア、ジャンルセレクトリストを取得
        $masterCodesFind = array('area','genre');
        $selectList = $this->Util->getSelectList($masterCodesFind, $this->MasterCodes, false);
        $this->set(compact('selectList','isArea'));
        // parent::beforeFilter($event);
        // $this->Auth->allow(['signup','verify','logout']);
        parent::beforeRender($event); //親クラスのbeforeRendorを呼ぶ
        $this->viewBuilder()->layout('userDefault');
    }
}
