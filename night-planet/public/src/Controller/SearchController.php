<?php
namespace App\Controller;

use \Cake\ORM\Query;
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
        parent::beforeFilter($event);
        // $this->Auth->allow(['signup','verify','logout']);
        parent::beforeRender($event); //親クラスのbeforeRendorを呼ぶ
        $this->viewBuilder()->layout('userDefault');
        // 常に現在エリアを取得
        $is_area = AREA['okinawa']['path'];
        $query = $this->request->getQuery();
        // 検索結果でエリア、ジャンルで文言を決める
        $result = '';
        if (!empty($query['area']) && !empty($query['genre'])) {
            // コントローラでセットされたresultを代入してセパレータを追加
            $result .=  AREA[$query['area']]['label'] . 'の'.
                        GENRE[$query['genre']]['label'].'一覧';
        } elseif (!empty($query['area'])) {
            $result .=  AREA[$query['area']]['label'] . '一覧';
        } elseif (!empty($query['genre'])) {
            $result .=  GENRE[$query['genre']]['label'] . '一覧';
        }
        // SEO対策
        $title = str_replace("_service_name_", LT['000'], TITLE['SEARCH_TITLE']);
        $description = str_replace("_service_name_", LT['000'], META['SEARCH_DESCRIPTION']);

        $this->set(compact("result", "title", "description","is_area"));
    }

    public function index()
    {
        if ($this->request->is('ajax')) {
            $this->confReturnJson(); // json返却用の設定

            $search = $this->getSearch($this->request->getQuery());

            // 検索ページからの場合は、結果のみを返却する
            $this->set(compact('search'));
            // 店舗検索の場合に店舗用カードをレンダリング
            if ($this->request->getQuery('search-choice') == 'shop') {
                $this->render('/Element/shopCard');
            } elseif ($this->request->getQuery('search-choice') == 'cast') {
                // スタッフ検索の場合にスタッフ用カードをレンダリング
                $this->render('/Element/castCard');
            }

            $response = array(
                'html' => $this->response->body(),
                'error' => "",
                'success' => true,
                'message' => ""
            );
            $this->response->body(json_encode($response));
            return;
        }

        // 次の画面
        $next_view = 'search';

        // 検索ページ以外からの場合は新しくレンダリングする
        $search = $this->getSearch($this->request->getQuery());

        // 店舗検索の場合に店舗用カードをレンダリング
        if ($this->request->getQuery('search-choice') == 'shop') {
            // 使用するテンプレートに店舗用カードを使用
            $this->set('useTemplate', 'shop');
        } elseif ($this->request->getQuery('search-choice') == 'cast') {
            // 使用するテンプレートにスタッフ用カードを使用
            $this->set('useTemplate', 'cast');
        }

        // 検索条件を取得し、画面側でselectedする
        $selected = $this->request->getQuery();
        $masterCodesFind = array('area','genre');
        $selectList = $this->Util->getSelectList($masterCodesFind, $this->MasterCodes, false);
        $this->set(compact('search', 'next_view', 'selectList', 'selected'));
        $this->render();
    }

    /**
     * 店舗または、スタッフを検索取得する
     *
     * @param [type] $requestData
     * @param [type] $columns
     * @return void
     */
    public function getSearch($requestData)
    {
        // 店舗検索の場合
        if ($requestData['search-choice'] == 'shop') {
            $query = $this->Shops->find()
               ->where(['shops.status = 1 AND shops.delete_flag = 0'])
               ->contain(['snss'
               , 'shop_likes' => function (Query $q) {
                   return $q
                        ->select(['shop_likes.id','shop_likes.shop_id','shop_likes.user_id'
                            , 'total' => $q->func()->count('shop_likes.shop_id')])
                        ->group('shop_id')
                        ->where(['shop_likes.shop_id']);
               }, 'shop_likes.users' => function (Query $q) {
                    return $q
                        ->select(['is_like' => $q->func()->count('users.id')])
                        ->where(['users.id' => $this->viewVars['userInfo']['id']]);
                    }]);
            foreach ($requestData as $key => $findData) {
                // ラジオボタンの場合コンティニュー
                if ($key == 'search-choice') {
                    continue;
                }
                // リクエストデータが[key_word]かつ値が空じゃない場合
                if (($key == 'key_word') && ($findData !== "")) {
                    $query->orWhere(function ($exp, $q) use ($findData) {
                        $exp->like('name', '%'.$findData.'%');
                        return $exp;
                    });
                } else {
                    if ($findData !== "") {
                        $query->where(['shops.'.$key => $findData]);
                    }
                }
            }
            // 検索結果を配列で取得
            $search = $query->toArray();
            // 画像を設定する
            foreach ($search as $key => $value) {
                $path = DS.PATH_ROOT['IMG'].DS.AREA[$value->area]['path']
                    .DS.GENRE[$value->genre]['path']
                    .DS.$value->dir.DS.PATH_ROOT['TOP_IMAGE'];
                $dir = new Folder(preg_replace('/(\/\/)/', '/', WWW_ROOT.$path), true, 0755);

                $files = array();
                $files = glob($dir->path.DS.'*.*');
                // ファイルが存在したら、画像をセット
                if (count($files) > 0) {
                    foreach ($files as $file) {
                        $value->set('top_image', $path.DS.(basename($file)));
                    }
                } else {
                    // 共通画像をセット
                    $value->set('top_image', PATH_ROOT['SHOP_TOP_IMAGE']);
                }
            }
        } elseif ($requestData['search-choice'] == 'cast') {
            // スタッフ検索の場合
            $query = $this->Casts->find("all")
                ->contain(['shops', 'snss'])
                ->where(['casts.status = 1 AND casts.delete_flag = 0']);

            foreach ($requestData as $key => $findData) {
                // ラジオボタンの場合コンティニュー
                if ($key == 'search-choice') {
                    continue;
                }
                // リクエストデータが[key_word]かつ値が空じゃない場合
                if (($key == 'key_word') && ($findData !== "")) {
                    $query->orWhere(function ($exp, $q) use ($findData) {
                        $exp->like('nickname', '%'.$findData.'%');
                        return $exp;
                    });
                } elseif (($key == 'area') || ($key == 'genre')) {
                    // エリアかジャンルにパラメタがある場合は、店舗で更に絞る
                    if ($findData !== "") {
                        $query
                            ->contain('shops')
                            ->matching('shops', function (Query $q) use ($key, $findData) {
                                return $q->where(['shops.'.$key => $findData,
                                    'shops.status = 1 AND shops.delete_flag = 0']);
                            });
                    }
                }
            }
            // 検索結果を配列で取得
            $search = $query->toArray();
            // 画像を設定する
            foreach ($search as $key => $value) {
                $path = DS.PATH_ROOT['IMG'].DS.AREA[$value->shop->area]['path']
                    .DS.GENRE[$value->shop->genre]['path']
                    .DS.$value->shop->dir.DS.PATH_ROOT['CAST']
                    .DS.$value->dir.DS.PATH_ROOT['PROFILE'];
                $dir = new Folder(preg_replace('/(\/\/)/', '/', WWW_ROOT.$path), true, 0755);

                $files = array();
                $files = glob($dir->path.DS.'*.*');
                // ファイルが存在したら、画像をセット
                if (count($files) > 0) {
                    foreach ($files as $file) {
                        $value->set('icon', $path.DS.(basename($file)));
                    }
                } else {
                    // 共通画像をセット
                    $value->set('icon', PATH_ROOT['NO_IMAGE02']);
                }
            }
        }

        return $search;
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
