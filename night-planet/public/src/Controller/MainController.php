<?php
namespace App\Controller;

use Cake\Event\Event;
use Token\Util\Token;
use Cake\Mailer\MailerAwareTrait;

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
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        // ユーザ認証後の初回のみ自動でモーダルを表示するパラメタをセットする
        if ($this->request->session()->check('auth_success')) {
            $is_login_modal_show = $this->request->session()->consume('auth_success');
            if ($this->request->session()->check('error')) {
                $errors = $this->request->session()->consume('error');
                foreach ($errors as $key => $error) {
                    $this->request->data($key, $error);
                }
            }
        }

        // 常に現在エリアを取得
        $is_area = AREA['okinawa']['path'];
        // SEO対策
        $title = str_replace("_service_name_", LT['000'], TITLE['TOP_TITLE']);
        $description = str_replace("_service_name_", LT['000'], META['TOP_DESCRIPTION']);
        $this->set(compact("title", "description","is_area","is_login_modal_show"));
    }

    public function top()
    {
        //$this->redirect("http://localhost:8080/api-googles/oauth2-callback");

        $shops_query = $this->Shops->find();
        $casts_query = $this->Casts->find();
        $shops = $shops_query->select(['count'=> $shops_query->func()->count('area'),'area'])
                    ->where(['status = 1 AND delete_flag = 0'])
                    ->group('area')->toArray();

        // 全体店舗数
        $shops_cnt = 0;
        // 画面表示するランキング数【１カラム：３】,【２カラム：７】,【３カラム：１０】,【４カラム：１３】
        $limit      = PROPERTY['RANKING_SHOW_MAX'];
        // 範囲日数※最大で直近３０日前までとすること
        $range      = PROPERTY['RANKING_SPAN_MAX'];

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
        $regin = [];
        foreach (REGION as $key => $value1) {
            $region_cnt = 0;
            foreach ($area as $key => $value2) {
                if ($value2['region'] == $value1['path']) {
                    $region_cnt += $value2['count'];
                }
            }
            $region[$value1['path']] = $region_cnt;
        }
        // 全体スタッフ数を取得
        $casts_cnt = $casts_query->find('all')
            ->contain(['shops'])
            ->where(['shops.status = 1 AND shops.delete_flag = 0'])
            ->count();

        $all_cnt = ['shops' => $shops_cnt, 'casts' => $casts_cnt];
        $new_photos = $this->NewPhotosRank->find("all")
                        ->order(['id'=>'ASC'])
                        ->toArray();

        $shop_ranking = $this->Util->getRanking($range, $limit, null);
        $diarys = $this->Util->getNewDiarys(PROPERTY['NEW_INFO_MAX'], null, null, $this->viewVars['userInfo']['id']);
        $notices = $this->Util->getNewNotices(PROPERTY['NEW_INFO_MAX'], null , $this->viewVars['userInfo']['id']);
        $main_adsenses = $this->Util->getAdsense(PROPERTY['TOP_SLIDER_GALLERY_MAX'], 'main', null);
        $sub_adsenses = $this->Util->getAdsense(PROPERTY['SUB_SLIDER_GALLERY_MAX'], 'sub', null);
        //広告を配列にセット
        $adsenses = array('main_adsenses' => $main_adsenses, 'sub_adsenses' => $sub_adsenses);
        $this->set(compact('area', 'region', 'all_cnt', 'diarys'
            , 'notices', 'new_photos', 'ig_data','is_naipura', 'adsenses', 'shop_ranking'));
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
