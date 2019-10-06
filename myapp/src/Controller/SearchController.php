<?php
namespace App\Controller;

use Cake\Event\Event;
use Token\Util\Token;
use Cake\Filesystem\Folder;
use Cake\ORM\TableRegistry;
use Cake\Mailer\MailerAwareTrait;

/**
* Users Controller
*
* @property \App\Model\Table\UsersTable $Users
*
* @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
*/
class SearchController extends AppController
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
        $this->MasterCodes = TableRegistry::get("master_codes");

    }

    public function beforeFilter(Event $event)
    {
        // parent::beforeFilter($event);
        // $this->Auth->allow(['signup','verify','logout']);
        parent::beforeRender($event); //親クラスのbeforeRendorを呼ぶ
        $this->viewBuilder()->layout('userDefault');

        $query = $this->request->getQuery();
        // 検索結果でエリア、ジャンルで文言を決める
        $result = '';
        if (!empty($query['area']) && !empty($query['genre'])) {
            // コントローラでセットされたresultを代入してセパレータを追加
            $result .=  AREA[$query['area']]['label'] . 'の'.
                        GENRE[$query['genre']]['label'].'一覧';
        } else if(!empty($query['area'])) {
            $result .=  AREA[$query['area']]['label'] . '一覧';
        } else if(!empty($query['genre'])) {
            $result .=  GENRE[$query['genre']]['label'] . '一覧';
        }
        // SEO対策
        $title = str_replace("_service_name_", LT['001'], TITLE['SEARCH_TITLE']);
        $description = str_replace("_service_name_", LT['001'], META['SEARCH_DESCRIPTION']);

        $this->set(compact("result", "title", "description"));
    }

    public function index()
    {
        if ($this->request->is('ajax')) {

            $this->confReturnJson(); // json返却用の設定

            $columns = array('shops.name', 'shops.catch'); // like条件用
            $shops = $this->getShopList($this->request->getQuery(), $columns);
            // トップ画像を設定する
            foreach ($shops as $key => $shop) {
                $path = DS.PATH_ROOT['IMG'].DS.AREA[$shop->area]['path']
                    .DS.GENRE[$shop->genre]['path']
                    .DS.$shop->dir.DS.PATH_ROOT['TOP_IMAGE'];
                $dir = new Folder(preg_replace('/(\/\/)/', '/'
                    , WWW_ROOT.$path), true, 0755);

                $files = array();
                $files = glob($dir->path.DS.'*.*');
                // ファイルが存在したら、画像をセット
                if(count($files) > 0) {
                    foreach( $files as $file ) {
                        $shop->set('top_image', $path.DS.(basename($file)));
                    }
                } else {
                    // 共通トップ画像をセット
                    $shop->set('top_image', PATH_ROOT['SHOP_TOP_IMAGE']);
                }
            }

            // 検索ページからの場合は、結果のみを返却する
            $this->set(compact('shops'));
            $this->render('/Element/shopCard');
            $response = array(
                'html' => $this->response->body(),
                'error' => "",
                'success' => true,
                'message' => ""
            );
            $this->response->body(json_encode($response));
            return;
        }
        $shops = array(); // 店舗情報格納用

        $columns = array('shops.name', 'shops.catch'); // like条件用
        $shops = $this->getShopList($this->request->getQuery(), $columns);

        // トップ画像を設定する
        foreach ($shops as $key => $shop) {
            $path = DS.PATH_ROOT['IMG'].DS.AREA[$shop->area]['path']
                .DS.GENRE[$shop->genre]['path']
                .DS.$shop->dir.DS.PATH_ROOT['TOP_IMAGE'];
            $dir = new Folder(preg_replace('/(\/\/)/', '/'
                , WWW_ROOT.$path), true, 0755);

            $files = array();
            $files = glob($dir->path.DS.'*.*');
            // ファイルが存在したら、画像をセット
            if(count($files) > 0) {
                foreach( $files as $file ) {
                    $shop->set('top_image', $path.DS.(basename($file)));
                }
            } else {
                // 共通トップ画像をセット
                $shop->set('top_image', PATH_ROOT['SHOP_TOP_IMAGE']);
            }
        }

        // 検索条件を取得し、画面側でselectedする
        $selected = $this->request->getQuery();
        $masterCodesFind = array('area','genre');
        $selectList = $this->Util->getSelectList($masterCodesFind, $this->MasterCodes, false);
        $this->set(compact('shops', 'selectList','selected'));
        $this->render();
    }

    /**
     * ショップテーブルから検索条件による店舗情報を取得する
     *
     * @param [type] $requestData
     * @param [type] $columns
     * @return void
     */
    public function getShopList($requestData, $columns)
    {
        $query = $this->Shops->find();
        $findArray = array(); // 検索条件セット用
        foreach($requestData as $key => $findData) {
            // リクエストデータが[key_word]かつ値が空じゃない場合
            if (($key == 'key_word') && ($findData !== "")) {
                foreach ($columns as $key => $value) {
                    $query->orWhere(function ($exp, $q) use ($value, $findData) {
                        $exp->like($value, '%'.$findData.'%');
                        return $exp;
                    });
                }
            } else {
                if($findData !== "") {
                    //$findArray[] = ['shops.'.$key => $findData];
                    $query->where(['shops.'.$key => $findData]);
                }
            }
        }
        return $query->toArray();
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
