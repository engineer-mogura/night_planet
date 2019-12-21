<?php
namespace App\Controller;

use Token\Util\Token;
use Cake\Mailer\MailerAwareTrait;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;

/**
* Users Controller
*
* @property \App\Model\Table\UsersTable $Users
*
* @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
*/
class MainController extends AppController
{
    use MailerAwareTrait;

    public function initialize()
    {
        parent::initialize();
        $this->Users = TableRegistry::get('users');
        $this->Shops = TableRegistry::get('shops');
        $this->Coupons = TableRegistry::get('coupons');
        $this->Casts = TableRegistry::get('casts');
        $this->Jobs = TableRegistry::get('jobs');
        $this->Shop_infos = TableRegistry::get('shop_infos');
        $this->Diarys = TableRegistry::get('diarys');
        $this->MasterCodes = TableRegistry::get("master_codes");
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->viewBuilder()->layout('userDefault');

        // SEO対策
        $title = str_replace("_service_name_", LT['000'], TITLE['TOP_TITLE']);
        $description = str_replace("_service_name_", LT['000'], META['TOP_DESCRIPTION']);
        $this->set(compact("title", "description"));
    }

    public function top()
    {
        //$this->redirect("http://localhost:8080/api-googles");
        $masterCodesFind = array('area','genre');
        $selectList = $this->Util->getSelectList($masterCodesFind, $this->MasterCodes, false);

        $shops_query = $this->Shops->find();
        $casts_query = $this->Casts->find();
        $shops = $shops_query->select(['count'=> $shops_query->func()->count('area'),'area'])
                    ->group('area')->toArray();
        $shops_cnt = 0;
        $area = array();
        // 全体店舗数、エリア毎の店舗数をセットする
        foreach (AREA as $key1 => $value) {
            // エリア店舗を０で初期化
            $value['count'] = 0;
            array_push($area, $value);
            foreach ($shops as $key2 => $shop) {
                if ($value['path']  == $shop->area) {
                    // 全体店舗数をセット
                    $shops_cnt += $shop->count;
                    // エリア店舗をセット
                    $area[array_key_last($area)]['count'] = $shop->count;
                    break;
                }
            }
        }
        // 全体スタッフ数を取得
        $casts_cnt = $casts_query->count();
        $all_cnt = ['shops' => $shops_cnt, 'casts' => $casts_cnt];

        $diarys = $this->Util->getNewDiarys(PROPERTY['NEW_INFO_MAX'], null, null);
        $notices = $this->Util->getNewNotices(PROPERTY['NEW_INFO_MAX']);
        $main_adsenses = $this->Util->getAdsense(PROPERTY['TOP_SLIDER_GALLERY_MAX'], 'main', null);
        $sub_adsenses = $this->Util->getAdsense(PROPERTY['SUB_SLIDER_GALLERY_MAX'], 'sub', null);
        //広告を配列にセット
        $adsenses = array('main_adsenses' => $main_adsenses, 'sub_adsenses' => $sub_adsenses);
        $insta_data = $this->Util->getInstagram(null, API['INSTAGRAM_USER_NAME'], API['INSTAGRAM_BUSINESS_ID'], API['INSTAGRAM_GRAPH_API_ACCESS_TOKEN']);
        $this->set(compact('area', 'all_cnt', 'selectList', 'diarys', 'notices', 'insta_data','adsenses'));
    }

    /**
     * json返却用の設定
     *
     * @param array $validate
     * @return void
     */
    public function confReturnJson()
    {
        $this->viewBuilder()->autoLayout(false);
        $this->autoRender = false;
        $this->response->charset('UTF-8');
        $this->response->type('json');
    }
}
