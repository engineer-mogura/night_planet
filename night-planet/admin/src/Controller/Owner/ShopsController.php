<?php
namespace App\Controller\Owner;

use Cake\I18n\Time;
use \Cake\ORM\Query;
use Cake\Event\Event;
use RuntimeException;
use Token\Util\Token;
use Cake\Mailer\Email;
use Cake\Filesystem\File;
use Cake\Filesystem\Folder;
use Cake\Mailer\MailerAwareTrait;

/**
 * Controls the data flow into shops object and updates the view whenever data changes.
 */
class ShopsController extends AppController
{
    use MailerAwareTrait;

    public function beforeFilter(Event $event) {
        // AppController.beforeFilterをコールバック
        parent::beforeFilter($event);
        // 店舗編集用テンプレート
        $this->viewBuilder()->layout('shopDefault');
        // 店舗に関する情報をセット
        if(!is_null($user = $this->Auth->user())){

            // URLに店舗IDが存在する場合、セッションに店舗IDをセットする
            if($this->request->getQuery('shop_id')) {
                $this->request->session()->write('shop_id', $this->request->getQuery('shop_id'));
            }
            // セッションに店舗IDをセットする
            if ($this->request->session()->check('shop_id')) {
                $shopId = $this->request->session()->read('shop_id');
            }

            // オーナーに関する情報をセット
            $owner = $this->Owners->find("all")
                ->where(['owners.id'=>$user['id']])
                ->contain(['servece_plans'])
                ->first();
            $this->set('userInfo', $this->Util->getOwnerInfo($owner));

            // 店舗情報取得
            $shop = $this->Shops->find('all')
                ->where(['id' => $shopId , 'owner_id' => $user['id']])
                ->first();
            $this->set('shopInfo', $this->Util->getShopInfo($shop));
        }
    }

    /**
     * 編集画面の処理
     *
     * @param [type] $id
     * @return void
     */
    public function index()
    {

        // アクティブタブ
        $selected_tab = "";
        // サイドバーメニューのパラメータがあればセッションにセットする
        if (isset($this->request->data["selected_tab"])) {
            $this->request->session()->write('selected_tab', $this->request->getData("selected_tab"));
        }
        // セッションにアクティブタブがセットされていればセットする
        if ($this->request->session()->check('selected_tab')) {
            $selectedTab = $this->request->session()->consume('selected_tab');
        }

        if(!is_null($user = $this->Auth->user())){
            $shop = $this->Shops->find()
                ->where(['shops.id'=> $this->viewVars["shopInfo"]["id"] , 'owner_id' => $user['id']])
                ->contain(['coupons','Jobs','snss','casts' => function(Query $q) {
                return $q->where(['casts.delete_flag'=>'0']);
            }])->first();

            // 現在日付
            $date = Time::now();
            $y = $date->year;
            $ym = $date->year . '-' . $date->month;

            $range_years  = array();
            $range_months = array();
            $access_years = array();
            $access_months = array();
            $access_weeks = array();

            $access_years = $this->AccessYears->find()
                                ->where(['shop_id' => $shop->id, 'owner_id' => $shop->owner_id])
                                ->order(['y' => 'DESC'])->toArray();
            $access_months = $this->AccessMonths->find()
                                ->where(['shop_id' => $shop->id, 'owner_id' => $shop->owner_id])
                                ->order(['ym' => 'DESC'])->toArray();
            foreach ($access_years as $key => $value) {
                array_push($range_years, $value->y);
            }
            foreach ($access_months as $key => $value) {
                array_push($range_months, $value->ym);
            }
            $access_weeks = $this->AccessWeeks->find()
                                ->where(['shop_id' => $shop->id, 'owner_id' => $shop->owner_id])
                                ->toArray();

            $reports = array('access_years' => json_encode($access_years)
                            , 'access_months' => json_encode($access_months)
                            , 'access_weeks' => json_encode($access_weeks)
                            , 'ranges' => [$range_years, $range_months]);
        }

        $this->set(compact('shop', 'reports'/*, 'ranges' */));
        $this->render();
    }
    /**
     * 編集画面遷移の処理
     *
     * @param [type] $id
     * @return void
     */
    public function shopEdit()
    {
        // アクティブタブ
        $selected_tab = "";
        // サイドバーメニューのパラメータがあればセッションにセットする
        if (isset($this->request->query["select_tab"])) {
            $this->request->session()->write('select_tab', $this->request->query["select_tab"]);
        }
        // セッションにアクティブタブがセットされていればセットする
        if ($this->request->session()->check('select_tab')) {
            $select_tab = $this->request->session()->consume('select_tab');
        }

        if(!is_null($user = $this->Auth->user())){
            $shop = $this->Shops->find()
                    ->where(['shops.id'=> $this->viewVars["shopInfo"]["id"] , 'owner_id' => $user['id']])
                    ->contain(['coupons','jobs','snss','casts'])
                ->first();
            $tmps  = $this->Tmps->find()
                ->where(['shop_id' => $this->viewVars["shopInfo"]["id"]])
                ->toArray();

            // 承認待ちユーザー追加
            foreach ($tmps as $key => $tmp) {
                array_unshift($shop->casts, $tmp);
            }
        }

        // ディクレトリ取得
        $dir = new Folder(preg_replace('/(\/\/)/', '/'
            , WWW_ROOT.$this->viewVars['shopInfo']['top_image_path'])
            , true, 0755);

        $files = glob($dir->path.DS.'*.*');

        // ファイルが存在したら、画像をセット
        if(count($files) > 0) {
            foreach( $files as $file ) {
                $shop->set('top_image', $this->viewVars['shopInfo']['top_image_path']
                .DS.(basename($file)));
            }
        } else {
            // 共通トップ画像をセット
            $shop->set('top_image', PATH_ROOT['SHOP_TOP_IMAGE']);
        }

        // 店舗ギャラリーを設定する
        $gallery = array();
        // ディクレトリ取得
        $dir = new Folder(preg_replace('/(\/\/)/', '/'
            , WWW_ROOT.$this->viewVars['shopInfo']['image_path'])
            , true, 0755);

        /// 並び替えして出力
        $files = glob($dir->path.DS.'*.*');
        usort( $files, $this->Util->sortByLastmod );
        foreach( $files as $file ) {
            $timestamp = date('Y/m/d H:i', filemtime($file));
            array_push($gallery, array(
                "file_path"=>$this->viewVars['shopInfo']['image_path'].DS.(basename( $file ))
                ,"date"=>$timestamp));
        }

        // スタッフのアイコンを設定する
        foreach ($shop->casts as $key => $cast) {
            $path = $this->viewVars['shopInfo']['cast_path'].DS.$cast->dir.DS.PATH_ROOT['PROFILE'];
            $dir = new Folder(preg_replace('/(\/\/)/', '/'
                , WWW_ROOT.$path), true, 0755);
            $files = array();
            $files = glob($dir->path.DS.'*.*');
            // ファイルが存在したら、画像をセット
            if(count($files) > 0) {
                foreach( $files as $file ) {
                    $cast->set('icon', $path.DS.(basename($file)));
                }
            } else {
                // 共通トップ画像をセット
                $cast->set('icon', PATH_ROOT['NO_IMAGE02']);
            }
        }

        // 作成するセレクトボックスを指定する
        $masCodeFind = array('industry','job_type','treatment','day');
        // セレクトボックスを作成する
        $selectList = $this->Util->getSelectList($masCodeFind,$this->MasterCodes,true);
        // マスタコードのクレジットリスト取得
        $masCredit = $this->MasterCodes->find()->where(['code_group' => 'credit'])->toArray();
        // 店舗情報のクレジットリストを作成する
        $shopCredits = $this->Util->getCredit($shop->credit, $masCredit);
        // マスタコードの待遇リスト取得
        $masTreatment = $this->MasterCodes->find()->where(['code_group' => 'treatment'])->toArray();
        // 求人情報の待遇リストを作成する
        $jobTreatments = $this->Util->getTreatment(reset($shop->jobs)['treatment'], $masTreatment);
        // クレジット、待遇リストをセット
        $masData = array('credit'=>json_encode($shopCredits),'treatment'=>json_encode($jobTreatments));

        $this->set(compact('shop','gallery','masCredit','masData','selectList','select_tab'));
        $this->render();
    }

    /**
     * トップ画像 削除押下処理
     *
     * @return void
     */
    public function deleteTopImage()
    {
        // AJAXのアクセス以外は不正とみなす。
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException('AJAX以外でのアクセスがあります。');
        }
        $flg = true; // 返却フラグ
        $isRemoved = false; // ディレクトリ削除フラグ
        $errors = ""; // 返却メッセージ
        $this->confReturnJson(); // responceがjsonタイプの場合の共通設定
        $message = RESULT_M['DELETE_SUCCESS']; // 返却メッセージ
        $auth = $this->request->session()->read('Auth.Owner');
        $id = $auth['id']; // ユーザーID
        $dirPath = preg_replace('/(\/\/)/', '/',
            WWW_ROOT.$this->viewVars['shopInfo']['shop_path']);

        $shop = $this->Shops->get($this->viewVars['shopInfo']['id']);
        // 削除ファイル取得
        $file = new File($dirPath . DS .$shop->top_image, true, 0755);

        try {
            // ロールバック用に一時フォルダにバックアップ
            if(!$file->copy(WWW_ROOT.PATH_ROOT['TMP'].DS.$file->name)) {
                throw new RuntimeException('バックアップに失敗しました。');
            }
            // バックアップしたファイルを取得
            $tmpFile = new File (WWW_ROOT.PATH_ROOT['TMP'].DS.$file->name); // バックアップ用
            // トップ画像削除処理実行
            if (!$file->delete()) {
                throw new RuntimeException('画像の削除に失敗しました。');
            }
            $isRemoved = true;
            // トップ画像を空にする
            $shop->top_image = "";
            // レコード更新実行
            if (!$this->Shops->save($shop)) {
                throw new RuntimeException('レコードの更新ができませんでした。');
            }

        } catch (RuntimeException $e) {
            // ファイルを削除していた場合は復元する
            if ($isRemoved) {
                $tmpFile->copy($file->path);
            }
            // 一時ファイルがあれば削除する
            if (isset($tmpFile) && file_exists($tmpFile->path)) {
                $tmpFile->delete();// tmpファイル削除
            }
            $this->log($this->Util->setLog($auth, $e));
            $flg = false;
        }
        // 例外が発生している場合にメッセージをセットして返却する
        if (!$flg) {
            $message = RESULT_M['DELETE_FAILED'];
            $response = array(
                'success' => $flg,
                'message' => $message
            );
            $this->response->body(json_encode($response));
            return;
        }

        // 一時ファイル削除
        if (file_exists($tmpFile->path)) {
            $tmpFile->delete();
        }

        $shop = $this->Shops->get(['id' => $this->viewVars['shopInfo']['id']]);
        $this->set(compact('shop'));
        $this->render('/Element/shopEdit/top-image');
        $response = array(
            'html' => $this->response->body(),
            'error' => $errors,
            'success' => $flg,
            'message' => $message
        );
        $this->response->body(json_encode($response));
        return;
    }

   /**
     * トップ画像 編集押下処理
     *
     * @return void
     */
    public function saveTopImage()
    {
        // AJAXのアクセス以外は不正とみなす。
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException('AJAX以外でのアクセスがあります。');
        }
        $flg = true; // 返却フラグ
        $isRemoved = false; // ディレクトリ削除フラグ
        $errors = ""; // 返却メッセージ
        $this->confReturnJson(); // responceがjsonタイプの場合の共通設定
        $message = RESULT_M['SIGNUP_SUCCESS']; // 返却メッセージ
        $auth = $this->request->session()->read('Auth.Owner');
        $id = $auth['id']; // ユーザーID

        $shop = $this->Shops->get($this->viewVars['shopInfo']['id']);

        // ディクレトリ取得
        $dir = new Folder(preg_replace('/(\/\/)/', '/'
            , WWW_ROOT.$this->viewVars['shopInfo']['top_image_path'])
            , true, 0755);

        // 以前のファイル
        $file = null;

        // 画像を取得
        $files = glob($dir->path.DS.'*.*');
        foreach ($files as $file) {
            $file = $this->viewVars['shopInfo']['image_path']
                .DS.(basename($file));
        }
        // 前のファイル取得
        $fileBefor = new File($dir->path . DS .basename($file), true, 0755);
        // 新しいファイルを取得
        $file = $this->request->getData('top_image_file');
        // ファイルが存在する、かつファイル名がblobの画像のとき
        if (!empty($file["name"]) && $file["name"] == 'blob') {
            $limitFileSize = CAPACITY['MAX_NUM_BYTES_FILE'];
            try {
                if(file_exists($fileBefor->path) && !empty($fileBefor->name)) {
                    // ロールバック用のファイルサイズチェック
                    if ($fileBefor->size() > CAPACITY['MAX_NUM_BYTES_FILE']) {
                        throw new RuntimeException('ファイルサイズが大きすぎます。');
                    }
                    // 一時ディレクトリ取得
                    $tmpDir = new Folder(preg_replace('/(\/\/)/', '/'
                        , WWW_ROOT.$this->viewVars['shopInfo']['tmp_path'])
                        , true, 0755);

                    // 一時ファイル作成
                    if (!$fileBefor->copy($tmpDir->path.DS.$fileBefor->name)) {
                        throw new RuntimeException('バックアップに失敗しました。');
                    }

                    // 一時ファイル取得
                    $tmpFile = new File($tmpDir->path.DS.$fileBefor->name);
                }

                $top_image = $this->Util->file_upload($this->request->getData('top_image_file'),
                    ['name'=> $fileBefor->name ], $dir->path, $limitFileSize);
                // ファイル更新の場合は古いファイルは削除
                if (!empty($fileBefor->name)) {
                    // ファイル名が同じ場合は削除を実行しない
                    if ($fileBefor->name != $top_image) {
                        // ファイル削除処理実行
                        if (!$fileBefor->delete()) {
                            throw new RuntimeException('ファイルの削除ができませんでした。');
                        }
                        // ファイル削除フラグを立てる
                        $isRemoved = true;
                    }
                }

                // 更新情報を追加する
                $updates = $this->Updates->newEntity();
                $updates->set('content','トップ画像を更新しました。');
                $updates->set('shop_id', $this->viewVars['shopInfo']['id']);
                $updates->set('type', SHOP_MENU_NAME['SHOP_TOP_IMAGE']);
                // レコード更新実行
                if (!$this->Updates->save($updates)) {
                    throw new RuntimeException('レコードの登録ができませんでした。');
                }

            } catch (RuntimeException $e) {
                // ファイルを削除していた場合は復元する
                if ($isRemoved) {
                    $tmpFile->copy($file->path);
                }
                // ファイルがアップロードされていた場合は削除を行う
                if(!empty($top_image)) {
                    $file = new File($dir->path . DS .$top_image, true, 0755);
                    // 一時ファイルがあれば削除する
                    if (isset($file) && file_exists($file->path)) {
                        $file->delete();// tmpファイル削除
                    }
                }

                // 一時ファイルがあれば削除する
                if (isset($tmpFile) && file_exists($tmpFile->path)) {
                    $tmpFile->delete();// tmpファイル削除
                }
                $this->log($this->Util->setLog($auth, $e));
                $flg = false;
            }

        }
        // 例外が発生している場合にメッセージをセットして返却する
        if (!$flg) {
            $message = RESULT_M['SIGNUP_FAILED'];
            $response = array(
                'success' => $flg,
                'message' => $message
            );
            $this->response->body(json_encode($response));
            return;
        }

        // 一時ファイル削除
        if (file_exists($tmpFile->path)) {
            $tmpFile->delete();
        }

        $shop = $this->Shops->get(['id' => $this->viewVars['shopInfo']['id']]);
        // 新しい画像をセット
        $shop->set('top_image', $this->viewVars['shopInfo']['top_image_path']
            .DS.$top_image);
        $this->set(compact('shop'));
        $this->render('/Element/shopEdit/top-image');
        $response = array(
            'html' => $this->response->body(),
            'error' => $errors,
            'success' => $flg,
            'message' => $message
        );
        $this->response->body(json_encode($response));
        return;
    }

    /**
     * クーポン 削除押下処理
     *
     * @return void
     */
    public function deleteCatch()
    {
        // AJAXのアクセス以外は不正とみなす。
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException('AJAX以外でのアクセスがあります。');
        }
        $flg = true; // 返却フラグ
        $message = RESULT_M['DELETE_SUCCESS']; // 返却メッセージ
        $errors = ""; // 返却メッセージ
        $this->confReturnJson(); // responceがjsonタイプの場合の共通設定
        $auth = $this->request->session()->read('Auth.Owner');
        $id = $auth['id']; // ユーザーID

        $shop = $this->Shops->patchEntity($this->Shops
            ->get($this->viewVars['shopInfo']['id']), $this->request->getData());

        if (!$this->Shops->save($shop)) {
            $this->log($this->Util->setLog($auth, $e));
            $message = RESULT_M['DELETE_FAILED'];
            $flg = false;
        }

        $shop = $this->Shops->get($this->viewVars['shopInfo']['id']);

        $this->set(compact('shop'));
        $this->render('/Element/shopEdit/catch');
        $response = array(
            'html' => $this->response->body(),
            'error' => $errors,
            'success' => $flg,
            'message' => $message
        );
        $this->response->body(json_encode($response));
        return;
    }

    /**
     * キャッチコピー 編集押下処理
     *
     * @return void
     */
    public function saveCatch()
    {
        // AJAXのアクセス以外は不正とみなす。
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException('AJAX以外でのアクセスがあります。');
        }
        $flg = true; // 返却フラグ
        $errors = ""; // 返却メッセージ
        $this->confReturnJson(); // responceがjsonタイプの場合の共通設定
        $message = RESULT_M['UPDATE_SUCCESS']; // 返却メッセージ
        $auth = $this->request->session()->read('Auth.Owner');
        $id = $auth['id']; // ユーザーID

        $shop = $this->Shops->patchEntity($this->Shops
            ->get($this->viewVars['shopInfo']['id']), $this->request->getData());

        // バリデーションチェック
        if ($shop->errors()) {
            // 入力エラーがあれば、メッセージをセットして返す
            $errors = $this->Util->setErrMessage($shop); // エラーメッセージをセット
            $response = array('success'=>false,'message'=>$errors);
            $this->response->body(json_encode($response));
            return;
        }
        try {
            // レコード更新実行
            if (!$this->Shops->save($shop)) {
                throw new RuntimeException('レコードの更新ができませんでした。');
            }
        } catch (RuntimeException $e) {
            $this->log($this->Util->setLog($auth, $e));
            $flg = false;
            $message = RESULT_M['UPDATE_FAILED'];
            $response = array(
                'success' => $flg,
                'message' => $message
            );
            $this->response->body(json_encode($response));
            return;
        }

        $shop = $this->Shops->get($this->viewVars['shopInfo']['id']);
        $this->set(compact('shop'));
        $this->render('/Element/shopEdit/catch');
        $response = array(
            'html' => $this->response->body(),
            'error' => $errors,
            'success' => $flg,
            'message' => $message
        );
        $this->response->body(json_encode($response));
        return;
    }

    /**
     * クーポン スイッチ押下処理
     *
     * @return void
     */
    public function switchCoupon()
    {
        // AJAXのアクセス以外は不正とみなす。
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException('AJAX以外でのアクセスがあります。');
        }
        $flg = true; // 返却フラグ
        $message = ""; // 返却メッセージ
        $this->confReturnJson(); // responceがjsonタイプの場合の共通設定
        $auth = $this->request->session()->read('Auth.Owner');
        $id = $auth['id']; // ユーザーID

        $coupon = $this->Coupons->get($this->request->getData('id'));
        // ステータスをセット
        $coupon->status = $this->request->getData('status');
        // メッセージをセット
        $coupon->status == 1 ? 
            $message = RESULT_M['DISPLAY_SUCCESS']: $message = RESULT_M['HIDDEN_SUCCESS'];
        try {
            // レコード更新実行
            if (!$this->Coupons->save($coupon)) {
                throw new RuntimeException('レコードの更新ができませんでした。');
            }
        } catch(RuntimeException $e) {
            $this->log($this->Util->setLog($auth, $e));
            $message = RESULT_M['CHANGE_FAILED'];
            $flg = false;
        }

        $response = array(
            'success' => $flg,
            'message' => $message
        );
        $this->response->body(json_encode($response));
    }

    /**
     * クーポン 削除押下処理
     *
     * @param [type] $id
     * @return void
     */
    public function deleteCoupon()
    {
        // AJAXのアクセス以外は不正とみなす。
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException('AJAX以外でのアクセスがあります。');
        }
        $flg = true; // 返却フラグ
        $message = RESULT_M['DELETE_SUCCESS']; // 返却メッセージ
        $errors = ""; // 返却メッセージ
        $this->confReturnJson(); // responceがjsonタイプの場合の共通設定
        $auth = $this->request->session()->read('Auth.Owner');
        $id = $auth['id']; // ユーザーID

        $coupon = $this->Coupons->get($this->request->getData('id'));

        if (!$this->Coupons->delete($coupon)) {
            $this->log($this->Util->setLog($auth, $e));
            $message = RESULT_M['DELETE_FAILED'];
            $flg = false;
        }

        $shop = $this->Shops->find()
            ->where(['id' => $this->viewVars['shopInfo']['id']])
            ->contain(['coupons'])->first();
        $this->set(compact('shop'));
        $this->render('/Element/shopEdit/coupon');
        $response = array(
            'html' => $this->response->body(),
            'error' => $errors,
            'success' => $flg,
            'message' => $message
        );
        $this->response->body(json_encode($response));
        return;
    }

    /**
     * クーポン 編集押下処理
     *
     * @param [type] $id
     * @return void
     */
    public function saveCoupon()
    {
        // AJAXのアクセス以外は不正とみなす。
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException('AJAX以外でのアクセスがあります。');
        }
        $flg = true; // 返却フラグ
        $errors = ""; // 返却メッセージ
        $this->confReturnJson(); // responceがjsonタイプの場合の共通設定
        $auth = $this->request->session()->read('Auth.Owner');
        $id = $auth['id']; // ユーザーID

        // 新規登録 店舗IDとステータスもセットする
        if($this->request->getData('crud_type') == 'insert') {
            $coupon = $this->Coupons->newEntity(array_merge(
                ['shop_id' => $this->viewVars['shopInfo']['id'], 'status'=>0]
                    ,$this->request->getData()));
            $message = RESULT_M['SIGNUP_SUCCESS']; // 返却メッセージ
        } else if($this->request->getData('crud_type') == 'update') {
        // 更新
            $coupon = $this->Coupons->patchEntity($this->Coupons
                ->get($this->request->getData('id')), $this->request->getData());
            $message = RESULT_M['UPDATE_SUCCESS']; // 返却メッセージ
        }

        // バリデーションチェック
        if ($coupon->errors()) {
            // 入力エラーがあれば、メッセージをセットして返す
            $errors = $this->Util->setErrMessage($coupon); // エラーメッセージをセット
            $response = array('success'=>false,'message'=>$errors);
            $this->response->body(json_encode($response));
            return;
        }
        try {
            // レコード登録、更新実行
            if (!$this->Coupons->save($coupon)) {
                if($this->request->getData('crud_type') == 'insert') {
                    throw new RuntimeException('レコードの登録ができませんでした。');
                } else {
                    throw new RuntimeException('レコードの更新ができませんでした。');
                }
            }
            // 更新情報を追加する
            $updates = $this->Updates->newEntity();
            $updates->set('content','クーポン情報を更新しました。');
            $updates->set('shop_id', $this->viewVars['shopInfo']['id']);
            $updates->set('type', SHOP_MENU_NAME['COUPON']);
            // レコード更新実行
            if (!$this->Updates->save($updates)) {
                throw new RuntimeException('レコードの登録ができませんでした。');
            }
        } catch (RuntimeException $e) {
            $this->log($this->Util->setLog($auth, $e));
            $flg = false;
            $message = RESULT_M['UPDATE_FAILED'];
            if($this->request->getData('crud_type') == 'insert') {
                $message = RESULT_M['SIGNUP_FAILED'];
            }
            $response = array(
                'success' => $flg,
                'message' => $message
            );
            $this->response->body(json_encode($response));
            return;
        }

        $shop = $this->Shops->find()
            ->where(['id' => $this->viewVars['shopInfo']['id']])
            ->contain(['coupons'])->first();
        $this->set(compact('shop'));
        $this->render('/Element/shopEdit/coupon');
        $response = array(
            'html' => $this->response->body(),
            'error' => $errors,
            'success' => $flg,
            'message' => $message
        );
        $this->response->body(json_encode($response));
        return;
    }

    /**
     * スタッフ スイッチ押下処理
     *
     * @return void
     */
    public function switchCast()
    {
        // AJAXのアクセス以外は不正とみなす。
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException('AJAX以外でのアクセスがあります。');
        }
        $flg = true; // 返却フラグ
        $message = RESULT_M['DELETE_SUCCESS']; // 返却メッセージ
        $errors = ""; // 返却メッセージ
        $this->confReturnJson(); // responceがjsonタイプの場合の共通設定
        $auth = $this->request->session()->read('Auth.Owner');
        $id = $auth['id']; // ユーザーID

        $cast = $this->Casts->get($this->request->getData('id'));
        // ステータスをセット
        $cast->status = $this->request->getData('status');
        // メッセージをセット
        $cast->status == 1 ? 
            $message = RESULT_M['DISPLAY_SUCCESS']: $message = RESULT_M['HIDDEN_SUCCESS'];
        try {
            // レコード更新実行
            if (!$this->Casts->save($cast)) {
                throw new RuntimeException('レコードの更新ができませんでした。');
            }
        } catch(RuntimeException $e) {
            $this->log($this->Util->setLog($auth, $e));
            $message = RESULT_M['CHANGE_FAILED'];
            $flg = false;
        }

        $response = array(
            'success' => $flg,
            'message' => $message
        );
        $this->response->body(json_encode($response));
    }
    /**
     * スタッフ 削除押下処理
     *
     * @return void
     */
    public function deleteCast()
    {
        // AJAXのアクセス以外は不正とみなす。
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException('AJAX以外でのアクセスがあります。');
        }
        $flg = true; // 返却フラグ
        $isRemoved = false; // ディレクトリ削除フラグ
        $errors = ""; // 返却メッセージ
        $this->confReturnJson(); // responceがjsonタイプの場合の共通設定
        $message = RESULT_M['UPDATE_SUCCESS']; // 返却メッセージ
        $auth = $this->request->session()->read('Auth.Owner');
        $id = $auth['id']; // ユーザーID
        $tmpDir = null; // バックアップ用

        try {
            $del_path = preg_replace('/(\/\/)/', '/',
                WWW_ROOT.$this->viewVars['shopInfo']['cast_path'].DS.$this->request->getData('dir'));
            // 削除対象ディレクトリパス取得
            $dir = new Folder($del_path);
            // 削除対象ディレクトリパス存在チェック
            if (!file_exists($dir->path)) {
                throw new RuntimeException('ディレクトリが存在しません。');
            }

            // ロールバック用のディレクトリサイズチェック
            if ($dir->dirsize() > CAPACITY['MAX_NUM_BYTES_DIR']) {
                throw new RuntimeException('ディレクトリサイズが大きすぎます。');
            }

            // 一時ディレクトリ作成
            $tmpDir = new Folder(WWW_ROOT.PATH_ROOT['TMP'] . DS . time(), true, 0777);
            // 一時ディレクトリにバックアップ実行
            if (!$dir->copy($tmpDir->path)) {
                throw new RuntimeException('バックアップに失敗しました。');
            }
            // TODO: 何故かcopy処理でパスが変更されるので再セットする
            $dir->path = $del_path;
            // 日記ディレクトリ削除処理実行
            if (!$dir->delete()) {
                throw new RuntimeException('ディレクトリの削除ができませんでした。');
            }
            // ディレクトリ削除フラグを立てる
            $isRemoved = true;
            // 削除対象レコード取得
            $cast = $this->Casts->get($this->request->getData('id'));
            // レコード削除実行
            if (!$this->Casts->delete($cast)) {
                throw new RuntimeException('レコードの削除ができませんでした。');
            }
        } catch (RuntimeException $e) {
            // ディレクトリを削除していた場合は復元する
            if ($isRemoved) {
                $tmpDir->copy($dir->path);
            }
            // 一時ディレクトリがあれば削除する
            if (isset($tmpDir) && file_exists($tmpDir->path)) {
                $tmpDir->delete();// tmpディレクトリ削除
            }
            $this->log($this->Util->setLog($auth, $e));
            $flg = false;
        }
        // 例外が発生している場合にメッセージをセットして返却する
        if (!$flg) {
            $message = RESULT_M['DELETE_FAILED'];
            $response = array(
                'success' => $flg,
                'message' => $message
            );
            $this->response->body(json_encode($response));
            return;
        }

        // 一時ディレクトリ削除
        if (file_exists($tmpDir->path)) {
            $tmpDir->delete();
        }

        $shop = $this->Shops->find()
            ->where(['id' => $this->viewVars['shopInfo']['id']])
            ->contain(['casts' => function(Query $q) {
                    return $q->where(['casts.delete_flag'=>'0']);
                }])->first();
        $this->set(compact('shop'));
        $this->render('/Element/shopEdit/cast');
        $response = array(
            'html' => $this->response->body(),
            'error' => $errors,
            'success' => $flg,
            'message' => $message
        );
        $this->response->body(json_encode($response));
        return;
    }

    /**
     * スタッフ 編集押下処理
     *
     * @return void
     */
    public function saveCast()
    {
        // AJAXのアクセス以外は不正とみなす。
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException('AJAX以外でのアクセスがあります。');
        }
        $flg = true; // 返却フラグ
        $errors = ""; // 返却メッセージ
        $this->confReturnJson(); // responceがjsonタイプの場合の共通設定

        // 新規登録(仮登録) 店舗IDとステータスも論理削除フラグセットする
        if($this->request->getData('crud_type') == 'insert') {
            $cast = $this->Tmps->newEntity(array_merge(
                ['shop_id' => $this->viewVars['shopInfo']['id'], 'status' => 0 , 'delete_flag' => 1]
                    , $this->request->getData())
                    , ['validate' => 'castRegistration']);

            $message = MAIL['CAST_AUTH_CONFIRMATION']; // 返却メッセージ
        } else if($this->request->getData('crud_type') == 'update') {
            // 更新
            $cast = $this->Casts->patchEntity($this->Casts
                ->get($this->request->getData('id')), $this->request->getData());
            $message = RESULT_M['UPDATE_SUCCESS']; // 返却メッセージ
        }
        // バリデーションチェック
        if ($cast->errors()) {
            $flg = false;
            // 入力エラーがあれば、メッセージをセットして返す
            $message = $this->Util->setErrMessage($cast); // エラーメッセージをセット
            $response = array(
                'success' => $flg,
                'message' => $message
            );
            $this->response->body(json_encode($response));
            return;
        }
        try {

            if($this->request->getData('crud_type') == 'insert') {
                // レコード一時登録実行
                if (!$this->Tmps->save($cast)) {
                    $message = RESULT_M['SIGNUP_FAILED'];
                    throw new RuntimeException('レコードの一時登録ができませんでした。');
                }

            } else {
                // レコード更新実行
                if (!$this->Casts->save($cast)) {
                    $message = RESULT_M['SIGNUP_FAILED'];
                    throw new RuntimeException('レコードの更新ができませんでした。');
                }

            }
        } catch(RuntimeException $e) {
            $this->log($this->Util->setLog($auth, $e));
            $flg = false;
            $response = array(
                'success' => $flg,
                'message' => $message,
            );
            $this->response->body(json_encode($response));
            return;
        }

        // 新規登録(仮登録)できた場合、登録したメールアドレスに認証メールを送る
        if ($this->request->getData('crud_type') == 'insert') {

            $email = new Email('default');
            $email->setFrom([MAIL['FROM_SUBSCRIPTION'] => MAIL['FROM_NAME']])
                ->setSubject($cast->name."様、【".$this->viewVars['shopInfo']['name']."】様よりスタッフ登録のご案内があります。")
                ->setTo($cast->email)
                ->setBcc(MAIL['FROM_INFO_GMAIL'])
                ->setTemplate("auth_send")
                ->setLayout("simple_layout")
                ->emailFormat("html")
                ->viewVars(['cast' => $cast
                    ,'shop_name' => $this->viewVars['shopInfo']['name']])
                ->send();
            $this->log($email,'debug');
            $this->Flash->success($message);
        }
        $shop = $this->Shops->find()
                ->where(['id' => $this->viewVars['shopInfo']['id']])
                ->contain(['casts'])
            ->first();
        $shop = $this->Shops->find()
                ->where(['shops.id'=> $this->viewVars["shopInfo"]["id"]])
                ->contain(['coupons','jobs','snss','casts'])
            ->first();
        $tmps  = $this->Tmps->find()
            ->where(['shop_id' => $this->viewVars["shopInfo"]["id"]])
            ->toArray();
        // 承認待ちユーザー追加
        foreach ($tmps as $key => $tmp) {
            array_unshift($shop->casts, $tmp);
        }
        // スタッフのアイコンを設定する
        foreach ($shop->casts as $key => $cast) {
            $path = $this->viewVars['shopInfo']['cast_path'].DS.$cast->dir.DS.PATH_ROOT['PROFILE'];
            if (file_exists($path)) {
                $dir = new Folder(preg_replace('/(\/\/)/', '/'
                    , WWW_ROOT.$path), true, 0755);
            }

            $files = array();
            $files = glob($dir->path.DS.'*.*');
            // ファイルが存在したら、画像をセット
            if(count($files) > 0) {
                foreach( $files as $file ) {
                    $cast->set('icon', $path.DS.(basename($file)));
                }
            } else {
                // 共通トップ画像をセット
                $cast->set('icon', PATH_ROOT['NO_IMAGE02']);
            }
        }

        $this->set(compact('shop'));
        $this->render('/Element/shopEdit/cast');
        $response = array(
            'html' => $this->response->body(),
            'error' => $errors,
            'success' => $flg,
            'message' => $message
        );
        $this->response->body(json_encode($response));
        return;
    }

    /**
     * 店舗情報 編集押下処理
     *
     * @return void
     */
    public function saveTenpo()
    {
        // AJAXのアクセス以外は不正とみなす。
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException('AJAX以外でのアクセスがあります。');
        }
        $flg = true; // 返却フラグ
        $errors = ""; // 返却メッセージ
        $this->confReturnJson(); // responceがjsonタイプの場合の共通設定
        $message = RESULT_M['UPDATE_SUCCESS']; // 返却メッセージ
        $auth = $this->request->session()->read('Auth.Owner');
        $id = $auth['id']; // ユーザーID

        $shop = $this->Shops->patchEntity($this->Shops
            ->get($this->viewVars['shopInfo']['id']), $this->request->getData());

        // バリデーションチェック
        if ($shop->errors()) {
            // 入力エラーがあれば、メッセージをセットして返す
            $errors = $this->Util->setErrMessage($shop); // エラーメッセージをセット
            $response = array('success'=>false,'message'=>$errors);
            $this->response->body(json_encode($response));
            return;
        }
        try {
            // レコード更新実行
            if (!$this->Shops->save($shop)) {
                throw new RuntimeException('レコードの更新ができませんでした。');
            }
            // 更新情報を追加する
            $updates = $this->Updates->newEntity();
            $updates->set('content','店舗情報を更新しました。');
            $updates->set('shop_id', $this->viewVars['shopInfo']['id']);
            $updates->set('type', SHOP_MENU_NAME['SYSTEM']);
            // レコード更新実行
            if (!$this->Updates->save($updates)) {
                throw new RuntimeException('レコードの登録ができませんでした。');
            }

        } catch (RuntimeException $e) {
            $this->log($this->Util->setLog($auth, $e));
            $flg = false;
            $message = RESULT_M['UPDATE_FAILED'];
            $response = array(
                'success' => $flg,
                'message' => $message
            );
            $this->response->body(json_encode($response));
            return;
        }

        $shop = $this->Shops->find()
            ->where(['id' => $this->viewVars['shopInfo']['id']])
            ->contain(['casts' => function(Query $q) {
                return $q->where(['casts.delete_flag'=>'0']);
            }])->first();

        // マスタコードのクレジットリスト取得
        $masCredit = $this->MasterCodes->find()->where(['code_group' => 'credit'])->toArray();
        // 店舗のクレジットリストを作成する
        $shopCredits = $this->Util->getCredit($shop->credit, $masCredit);
        // クレジットリストをセット
        $masData = array('credit'=>json_encode($shopCredits));
        $this->set(compact('shop','masData','masCredit'));
        $this->render('/Element/shopEdit/tenpo');
        $response = array(
            'html' => $this->response->body(),
            'error' => $errors,
            'success' => $flg,
            'message' => $message
        );
        $this->response->body(json_encode($response));
        return;
    }

    /**
     * 求人情報 編集押下処理
     *
     * @return void
     */
    public function saveJob()
    {
        // AJAXのアクセス以外は不正とみなす。
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException('AJAX以外でのアクセスがあります。');
        }
        $flg = true; // 返却フラグ
        $errors = ""; // 返却メッセージ
        $this->confReturnJson(); // responceがjsonタイプの場合の共通設定
        $message = RESULT_M['UPDATE_SUCCESS']; // 返却メッセージ
        $auth = $this->request->session()->read('Auth.Owner');
        $id = $auth['id']; // ユーザーID

        $job = $this->Jobs->patchEntity($this->Jobs
            ->get($this->request->getData('id')), $this->request->getData());

        // バリデーションチェック
        if ($job->errors()) {
            // 入力エラーがあれば、メッセージをセットして返す
            $errors = $this->Util->setErrMessage($job); // エラーメッセージをセット
            $response = array('success'=>false,'message'=>$errors);
            $this->response->body(json_encode($response));
            return;
        }
        try {
            // レコード更新実行
            if (!$this->Jobs->save($job)) {
                throw new RuntimeException('レコードの更新ができませんでした。');
            }
        } catch (RuntimeException $e) {
            $this->log($this->Util->setLog($auth, $e));
            $flg = false;
            $message = RESULT_M['UPDATE_FAILED'];
            $response = array(
                'success' => $flg,
                'message' => $message
            );
            $this->response->body(json_encode($response));
            return;
        }

        $shop = $this->Shops->find()
            ->where(['id' => $this->viewVars['shopInfo']['id']])
            ->contain(['Jobs'])->first();

        // 作成するセレクトボックスを指定する
        $masCodeFind = array('industry','job_type','treatment','day');
        // セレクトボックスを作成する
        $selectList = $this->Util->getSelectList($masCodeFind,$this->MasterCodes,true);
        // マスタコードの待遇リスト取得
        $masTreatment = $this->MasterCodes->find()->where(['code_group' => 'treatment'])->toArray();
        // 求人情報の待遇リストを作成する
        $jobTreatments = $this->Util->getTreatment(reset($shop->jobs)['treatment'], $masTreatment);
        // 待遇リストをセット
        $masData = array('treatment'=>json_encode($jobTreatments));

        $this->set(compact('shop','masData','selectList'));
        $this->render('/Element/shopEdit/job');
        $response = array(
            'html' => $this->response->body(),
            'error' => $errors,
            'success' => $flg,
            'message' => $message
        );
        $this->response->body(json_encode($response));
        return;
    }

    /**
     * sns 編集押下処理
     *
     * @return void
     */
    public function saveSns()
    {
        // AJAXのアクセス以外は不正とみなす。
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException('AJAX以外でのアクセスがあります。');
        }
        $flg = true; // 返却フラグ
        $errors = ""; // 返却メッセージ
        $this->confReturnJson(); // responceがjsonタイプの場合の共通設定
        $message = RESULT_M['UPDATE_SUCCESS']; // 返却メッセージ
        $auth = $this->request->session()->read('Auth.Owner');
        $id = $auth['id']; // ユーザーID
        $plan = $this->viewVars['userInfo']['current_plan'];

        try {
            // フリープラン かつ Instagramが入力されていた場合 不正なパターンでエラー
            if ($plan == SERVECE_PLAN['free']['label'] && !empty($this->request->getData('instagram'))) {
                throw new RuntimeException(RESULT_M['INSTA_ADD_FAILED'].' 不正アクセスがあります。');
            }
        } catch (RuntimeException $e) {
            $this->log($this->Util->setLog($auth, $e));
            // エラーメッセージをセット
            $response = array('success'=>false,'message'=>RESULT_M['INSTA_ADD_FAILED']);
            $this->response->body(json_encode($response));
            return;
        }

        // レコードが存在するか
        // レコードがない場合は、新規で登録を行う。
        if(!$this->Snss->exists(['shop_id' =>$this->viewVars['shopInfo']['id']])) {
            $sns = $this->Snss->newEntity($this->request->getData());
            $sns->shop_id = $this->viewVars['shopInfo']['id'];
        } else {
            $sns = $this->Snss->patchEntity($this->Snss
            ->get($this->request->getData('id')), $this->request->getData());
        }

        // バリデーションチェック
        if ($sns->errors()) {
            // 入力エラーがあれば、メッセージをセットして返す
            $errors = $this->Util->setErrMessage($sns); // エラーメッセージをセット
            $response = array('success'=>false,'message'=>$errors);
            $this->response->body(json_encode($response));
            return;
        }
        try {
            // レコード更新実行
            if (!$this->Snss->save($sns)) {
                throw new RuntimeException('レコードの更新ができませんでした。');
            }
        } catch (RuntimeException $e) {
            $this->log($this->Util->setLog($auth, $e));
            $flg = false;
            $message = RESULT_M['UPDATE_FAILED'];
            $response = array(
                'success' => $flg,
                'message' => $message
            );
            $this->response->body(json_encode($response));
            return;
        }

        $shop = $this->Shops->find()
            ->where(['id' => $this->viewVars['shopInfo']['id']])
            ->contain(['Snss'])->first();

        $this->set(compact('shop'));
        $this->render('/Element/shopEdit/sns');
        $response = array(
            'html' => $this->response->body(),
            'error' => $errors,
            'success' => $flg,
            'message' => $message
        );
        $this->response->body(json_encode($response));
        return;
    }

    /**
     * ギャラリー 編集押下処理
     *
     * @return void
     */
    public function saveGallery()
    {
        // AJAXのアクセス以外は不正とみなす。
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException('AJAX以外でのアクセスがあります。');
        }
        $flg = true; // 返却フラグ
        $isRemoved = false; // ディレクトリ削除フラグ
        $errors = ""; // 返却メッセージ
        $this->confReturnJson(); // responceがjsonタイプの場合の共通設定
        $message = RESULT_M['UPDATE_SUCCESS']; // 返却メッセージ
        $auth = $this->request->session()->read('Auth.Owner');
        $id = $auth['id']; // ユーザーID
        $tmpDir = null; // バックアップ用設定
        $dirPath = preg_replace('/(\/\/)/', '/',
            WWW_ROOT.$this->viewVars['shopInfo']['image_path']);
        $files = array();
        // 対象ディレクトリパス取得
        $dir = new Folder($dirPath, true, 0755);

        // 既に登録された画像があればデコードし格納、無ければ空の配列を格納する
        ($files_befor = json_decode($this->request->getData("gallery_befor")
            , true)) > 0 ? : $files_befor = array();

        $fileMax = PROPERTY['FILE_MAX']; // ファイル格納最大数

        try{

            // 削除対象ディレクトリパス存在チェック
            if (!file_exists($dir->path)) {
                throw new RuntimeException('ディレクトリが存在しません。');
            }

            // ディレクトリサイズチェック
            if ($dir->dirsize() > CAPACITY['MAX_NUM_BYTES_DIR']) {
                throw new RuntimeException('ディレクトリサイズが大きすぎます。');
            }

            // 追加画像がある場合
            if (isset($this->request->data["image"])) {
                $files = $this->request->data['image'];
            }

            foreach ($files as $key => $file) {
                // ファイルが存在する、かつファイル名がblobの画像のとき
                if (!empty($file["name"]) && $file["name"] == 'blob') {
                    $limitFileSize = CAPACITY['MAX_NUM_BYTES_FILE'];

                    // ファイル名を取得する
                    $convertFile = $this->Util->file_upload($file, $files_befor, $dir->path, $limitFileSize);

                    // ファイル名が同じ場合は重複フラグをセットする
                    if ($convertFile === false) {
                        $isDuplicate = true;
                    }

                }
            }

            // 更新情報を追加する
            $updates = $this->Updates->newEntity();
            $updates->set('content','店内ギャラリーを更新しました。');
            $updates->set('shop_id', $this->viewVars['shopInfo']['id']);
            $updates->set('type', SHOP_MENU_NAME['SHOP_GALLERY']);
            // レコード更新実行
            if (!$this->Updates->save($updates)) {
                throw new RuntimeException('レコードの登録ができませんでした。');
            }

        } catch (RuntimeException $e) {
            $this->log($this->Util->setLog($auth, $e));
            $flg = false;
        }

        // 例外が発生している場合にメッセージをセットして返却する
        if (!$flg) {

            $message = RESULT_M['UPDATE_FAILED'];
            $response = array(
                'success' => $flg,
                'message' => $message
            );
            $this->response->body(json_encode($response));
            return;
        }

        $gallery = array();

        // ディクレトリ取得
        $dir = new Folder(preg_replace('/(\/\/)/', '/'
            , WWW_ROOT.$this->viewVars['shopInfo']['image_path'])
            , true, 0755);

        /// 並び替えして出力
        $files = glob($dir->path.DS.'*.*');
        usort( $files, $this->Util->sortByLastmod );
        foreach( $files as $file ) {
            $timestamp = date('Y/m/d H:i', filemtime($file));
            array_push($gallery, array(
                "file_path"=>$this->viewVars['shopInfo']['image_path'].DS.(basename( $file ))
                ,"date"=>$timestamp));
        }

        $this->set(compact('gallery'));
        $this->render('/Element/shopEdit/gallery');
        $response = array(
            'html' => $this->response->body(),
            'error' => $errors,
            'success' => $flg,
            'message' => $isDuplicate ? $message . "\n" . RESULT_M['DUPLICATE'] : $message
        );
        $this->response->body(json_encode($response));
        return;
    }

    /**
     * ギャラリー 削除押下処理
     *
     * @return void
     */
    public function deleteGallery()
    {

        // AJAXのアクセス以外は不正とみなす。
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException('AJAX以外でのアクセスがあります。');
        }

        $flg = true; // 返却フラグ
        $errors = ""; // 返却メッセージ
        $this->confReturnJson(); // responceがjsonタイプの場合の共通設定
        $message = RESULT_M['DELETE_SUCCESS']; // 返却メッセージ
        $auth = $this->request->session()->read('Auth.Owner');

        try {

            // 削除対象ファイルを取得
            $file = new File(preg_replace('/(\/\/)/', '/', 
                WWW_ROOT. $this->request->getData('file_path')));

            // 削除対象ファイルパス存在チェック
            if (!file_exists($file->path)) {
                throw new RuntimeException('ファイルが存在しません。');
            }

            // 日記ファイル削除処理実行
            if (!$file->delete()) {
                throw new RuntimeException('ファイルの削除ができませんでした。');
            }

        } catch (RuntimeException $e) {

            $this->log($this->Util->setLog($auth, $e));
            $flg = false;
        }
        // 例外が発生している場合にメッセージをセットして返却する
        if (!$flg) {
            $message = RESULT_M['DELETE_FAILED'];
            $response = array(
                'success' => $flg,
                'message' => $message
            );
            $this->response->body(json_encode($response));
            return;
        }

        $gallery = array();

        // ディクレトリ取得
        $dir = new Folder(preg_replace('/(\/\/)/', '/'
            , WWW_ROOT.$this->viewVars['shopInfo']['image_path'])
            , true, 0755);

        /// 並び替えして出力
        $files = glob($dir->path.DS.'*.*');
        usort( $files, $this->Util->sortByLastmod );
        foreach( $files as $file ) {
            $timestamp = date('Y/m/d H:i', filemtime($file));
            array_push($gallery, array(
                "file_path"=>$this->viewVars['shopInfo']['image_path'].DS.(basename( $file ))
                ,"date"=>$timestamp));
        }

        $this->set(compact('gallery'));
        $this->render('/Element/shopEdit/gallery');
        $response = array(
            'html' => $this->response->body(),
            'error' => $errors,
            'success' => $flg,
            'message' => $message
        );
        $this->response->body(json_encode($response));
        return;
    }

    /**
     * お知らせ 画面表示処理
     *
     * @return void
     */
    public function notice()
    {
        $allNotice = $this->getAllNotice($this->viewVars['shopInfo']['id']
            , $this->viewVars['shopInfo']['notice_path'], null);
        $top_notice = $allNotice[0];
        $arcive_notice = $allNotice[1];
        $this->set(compact('top_notice', 'arcive_notice'));
        return $this->render();
    }

    /**
     * お知らせ 登録処理
     * @return void
     */
    public function saveNotice()
    {
        // AJAXのアクセス以外は不正とみなす。
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException('AJAX以外でのアクセスがあります。');
        }
        $flg = true; // 返却フラグ
        $isDuplicate = false; // 画像重複フラグ
        $errors = ""; // 返却メッセージ
        $this->confReturnJson(); // responceがjsonタイプの場合の共通設定
        $message = RESULT_M['SIGNUP_SUCCESS']; // 返却メッセージ
        $auth = $this->request->session()->read('Auth.Owner');
        $id = $auth['id']; // ログインユーザID
        $files = array();

        $fileMax = PROPERTY['FILE_MAX']; // ファイルアップの制限数
        $files_befor = array(); // 新規なので空の配列

        // エンティティにマッピングする
        $notice = $this->ShopInfos->newEntity($this->request->getData());
        // バリデーションチェック
        if ($notice->errors()) {
            // 入力エラーがあれば、メッセージをセットして返す
            $errors = $this->Util->setErrMessage($notice); // エラーメッセージをセット
            $response = array('success'=>false,'message'=>$errors);
            $this->response->body(json_encode($response));
            return;
        }

        // お知らせ用のディレクトリを掘る
        $date = new Time();
        $noticePath =  DS . $date->format('Y')
            . DS . $date->format('m') . DS . $date->format('d')
            . DS . $date->format('Ymd_His');
        $dir = preg_replace('/(\/\/)/', '/', WWW_ROOT . $this->viewVars['shopInfo']['notice_path']
             . $noticePath);
        $dir = new Folder($dir , true, 0755);
        $notice->dir = $noticePath; // お知らせのパスをセット
        try {
            // 追加画像がある場合
            if (isset($this->request->data["image"])) {
                $files = $this->request->data['image'];
            }
            foreach ($files as $key => $file) {
                // ファイルが存在する、かつファイル名がblobの画像のとき
                if (!empty($file["name"]) && $file["name"] == 'blob') {
                    $limitFileSize = CAPACITY['MAX_NUM_BYTES_FILE'];
                    // ファイル名を取得する
                    $convertFile = $this->Util->file_upload($file, $files_befor, $dir->path, $limitFileSize);

                    // ファイル名が同じ場合は処理をスキップする
                    if ($convertFile === false) {
                        $isDuplicate = true;
                        continue;
                    }

                }
            }
            // レコード更新実行
            if (!$this->ShopInfos->save($notice)) {
                throw new RuntimeException('レコードの登録ができませんでした。');
            }
            // 更新情報を追加する
            $updates = $this->Updates->newEntity();
            $updates->set('content','店舗からのお知らせを追加しました。');
            $updates->set('shop_id', $this->viewVars['shopInfo']['id']);
            $updates->set('type', SHOP_MENU_NAME['EVENT']);
            // レコード更新実行
            if (!$this->Updates->save($updates)) {
                throw new RuntimeException('レコードの登録ができませんでした。');
            }

        } catch (RuntimeException $e) {
            $this->log($this->Util->setLog($auth, $e));
            $flg = false;
        }

        // 例外が発生している場合にメッセージをセットして返却する
        if (!$flg) {
            // アップロード失敗の時、処理を中断する
            if (file_exists($dir->path)) {
                $dir->delete();// フォルダ削除
            }
            $message = RESULT_M['SIGNUP_FAILED'];
            $response = array(
                'success' => $flg,
                'message' => $message
            );
            $this->response->body(json_encode($response));
            return;
        }
        // お知らせ取得
        $allNotice = $this->getAllNotice($this->viewVars['shopInfo']['id']
            , $this->viewVars['shopInfo']['notice_path'], null);
        $top_notice = $allNotice[0];
        $arcive_notice = $allNotice[1];

        $this->set(compact('top_notice', 'arcive_notice'));
        $this->render('/Owner/Shops/notice');
        $response = array(
            'html' => $this->response->body(),
            'error' => $errors,
            'success' => $flg,
            'message' => $isDuplicate ? $message . "\n" . RESULT_M['DUPLICATE'] : $message
        );
        $this->response->body(json_encode($response));
        return;
    }

    /**
     * お知らせアーカイブ表示画面の処理
     *
     * @return void
     */
    public function viewNotice()
    {
        // AJAXのアクセス以外は不正とみなす。
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException('AJAX以外でのアクセスがあります。');
        }
        $this->confReturnJson(); // json返却用の設定

        $notice = $this->Util->getNotice($this->request->query["id"]
            , $this->viewVars['shopInfo']['notice_path']);

        $this->response->body(json_encode($notice));
        return;
    }

    /**
     * お知らせアーカイブ更新処理
     *
     * @return void
     */
    public function updateNotice()
    {

        // AJAXのアクセス以外は不正とみなす。
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException('AJAX以外でのアクセスがあります。');
        }
        $flg = true; // 返却フラグ
        $isRemoved = false; // ディレクトリ削除フラグ
        $isDuplicate = false; // 画像重複フラグ
        $errors = ""; // 返却メッセージ
        $this->confReturnJson(); // responceがjsonタイプの場合の共通設定
        $message = RESULT_M['UPDATE_SUCCESS']; // 返却メッセージ
        $auth = $this->request->session()->read('Auth.Owner');
        $id = $auth['id']; // ログインユーザID
        $tmpDir = null; // バックアップ用
        $dir = preg_replace('/(\/\/)/', '/',
            WWW_ROOT.$this->request->data["dir_path"]);
        // 対象ディレクトリパス取得
        $dir = new Folder($dir, true, 0755);
        $files = array();
        $fileMax = PROPERTY['FILE_MAX'];

        // エンティティにマッピングする
        $notice = $this->ShopInfos->patchEntity($this->ShopInfos
            ->get($this->request->data['notice_id']), $this->request->getData());
        // バリデーションチェック
        if ($notice->errors()) {
            // 入力エラーがあれば、メッセージをセットして返す
            $errors = $this->Util->setErrMessage($notice); // エラーメッセージをセット
            $response = array('result'=>false,'errors'=>$errors);
            $this->response->body(json_encode($response));
            return;
        }

        $delFiles = json_decode($this->request->data["del_list"], true);
        // 既に登録された画像があればデコードし格納、無ければ空の配列を格納する
        ($image_befor = json_decode($this->request->data["json_data"], true)) > 0
            ? : $image_befor = array();

        try {

            // 削除対象ディレクトリパス存在チェック
            if (!file_exists($dir->path)) {
                throw new RuntimeException('ディレクトリが存在しません。');
            }

            // ロールバック用のディレクトリサイズチェック
            if ($dir->dirsize() > CAPACITY['MAX_NUM_BYTES_DIR']) {
                throw new RuntimeException('ディレクトリサイズが大きすぎます。');
            }

            // 既に登録された画像がある場合は、ファイルのバックアップを取得
            if (count($image_befor) > 0) {
                // 一時ディレクトリ作成
                $tmpDir = new Folder(WWW_ROOT.$this->viewVars['shopInfo']['tmp_path']
                    . DS . time(), true, 0777);
                // 一時ディレクトリにバックアップ実行
                if (!$dir->copy($tmpDir->path)) {
                    throw new RuntimeException('バックアップに失敗しました。');
                }
            }

            // 削除する画像分処理する
            foreach ($delFiles as $key => $file) {
                $delFile = new File(WWW_ROOT . DS .$file['path']);
                // ファイル削除処理実行
                if (!$delFile->delete()) {
                    throw new RuntimeException('画像の削除に失敗しました。');
                }
            }
            // 追加画像がある場合
            if (isset($this->request->data["image"])) {
                $files = $this->request->data['image'];
            }
            // 追加画像分処理する
            foreach ($files as $key => $file) {
                // ファイルが存在する、かつファイル名がblobの画像のとき
                if (!empty($file["name"]) && $file["name"] == 'blob') {
                    $limitFileSize = CAPACITY['MAX_NUM_BYTES_FILE'];
                    // ファイル名を取得する
                    $convertFile = $this->Util->file_upload($file, $image_befor, $dir->path, $limitFileSize);

                    // ファイル名が同じ場合は処理をスキップする
                    if ($convertFile === false) {
                        $isDuplicate = true;
                        continue;
                    }
                }
            }

            // レコード更新実行
            if (!$this->ShopInfos->save($notice)) {
                throw new RuntimeException('レコードの登録ができませんでした。');
            }

        } catch (RuntimeException $e) {
            $this->log($this->Util->setLog($auth, $e));
            $flg = false;
        }

        // 例外が発生している場合にメッセージをセットして返却する
        if (!$flg) {
            if (file_exists($tmpDir->path)) {
                // ファイルを戻す前にアップロードされたファイルがある場合があるため、空にしておく
                $files = $dir->find('.*\.*');
                foreach ($files as $file) {
                    $file = new File($dir->path . DS . $file);
                    $file->delete(); // このファイルを削除します
                }
                $tmpDir->copy($dir->path);
                $tmpDir->delete();// tmpフォルダ削除
            }
            $message = RESULT_M['UPDATE_FAILED'];
            $flg = false;
            $response = array(
                'success' => $flg,
                'message' => $message
            );
            $this->response->body(json_encode($response));
            return;
        }

        // 一時ディレクトリ削除
        if (file_exists($tmpDir->path)) {
            $tmpDir->delete();// tmpフォルダ削除
        }

        // お知らせ取得
        $allNotice = $this->getAllNotice($this->viewVars['shopInfo']['id']
            , $this->viewVars['shopInfo']['notice_path'], null);
        $top_notice = $allNotice[0];
        $arcive_notice = $allNotice[1];

        $this->set(compact('top_notice', 'arcive_notice'));
        $this->render('/Owner/Shops/notice');
        $response = array(
            'html' => $this->response->body(),
            'error' => $errors,
            'success' => $flg,
            'message' => $isDuplicate ? $message . "\n" . RESULT_M['DUPLICATE'] : $message
        );
        $this->response->body(json_encode($response));
        return;
    }

    /**
     * お知らせアーカイブ削除処理
     *
     * @return void
     */
    public function deleteNotice()
    {
        // AJAXのアクセス以外は不正とみなす。
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException('AJAX以外でのアクセスがあります。');
        }
        $flg = true; // 返却フラグ
        $isRemoved = false; // ディレクトリ削除フラグ
        $errors = ""; // 返却メッセージ
        $this->confReturnJson(); // responceがjsonタイプの場合の共通設定
        $message = RESULT_M['DELETE_SUCCESS']; // 返却メッセージ
        $auth = $this->request->session()->read('Auth.Owner');
        $id = $auth['id']; // ログインユーザID
        $tmpDir = null; // バックアップ用

        try {
            $del_path = preg_replace('/(\/\/)/', '/',
                WWW_ROOT.$this->request->getData('dir_path'));
            // 削除対象ディレクトリパス取得
            $dir = new Folder($del_path);
            // 削除対象ディレクトリパス存在チェック
            if (!file_exists($dir->path)) {
                throw new RuntimeException('ディレクトリが存在しません。');
            }

            // ロールバック用のディレクトリサイズチェック
            if ($dir->dirsize() > CAPACITY['MAX_NUM_BYTES_DIR']) {
                throw new RuntimeException('ディレクトリサイズが大きすぎます。');
            }
            // 一時ディレクトリ作成
            $tmpDir = new Folder(WWW_ROOT.$this->viewVars['shopInfo']['tmp_path']
                . DS . time(), true, 0777);
            // 一時ディレクトリにバックアップ実行
            if (!$dir->copy($tmpDir->path)) {
                throw new RuntimeException('バックアップに失敗しました。');
            }
            // お知らせディレクトリ削除処理実行
            if (!$dir->delete()) {
                throw new RuntimeException('ディレクトリの削除ができませんでした。');
            }
            // ディレクトリ削除フラグを立てる
            $isRemoved = true;
            // 削除対象レコード取得
            $notice = $this->ShopInfos->get($this->request->getData('id'));
            // レコード削除実行
            if (!$this->ShopInfos->delete($notice)) {
                throw new RuntimeException('レコードの削除ができませんでした。');
            }
        } catch (RuntimeException $e) {
            // ディレクトリを削除していた場合は復元する
            if ($isRemoved) {
                $tmpDir->copy($dir->path);
            }
            // 一時ディレクトリがあれば削除する
            if (isset($tmpDir) && file_exists($tmpDir->path)) {
                $tmpDir->delete();// tmpディレクトリ削除
            }
            $this->log($this->Util->setLog($auth, $e));
            $flg = false;
        }
        // 例外が発生している場合にメッセージをセットして返却する
        if (!$flg) {
            $message = RESULT_M['DELETE_FAILED'];
            $response = array(
                'success' => $flg,
                'message' => $message
            );
            $this->response->body(json_encode($response));
            return;
        }

        // 一時ディレクトリ削除
        if (file_exists($tmpDir->path)) {
            $tmpDir->delete();
        }
        // お知らせ取得
        $allNotice = $this->getAllNotice($this->viewVars['shopInfo']['id']
            , $this->viewVars['shopInfo']['notice_path'], null);
        $top_notice = $allNotice[0];
        $arcive_notice = $allNotice[1];

        $this->set(compact('top_notice', 'arcive_notice'));
        $this->render('/Owner/Shops/notice');
        $response = array(
            'html' => $this->response->body(),
            'error' => $errors,
            'success' => $flg,
            'message' => $message
        );
        $this->response->body(json_encode($response));
        return;
    }

    /**
     * 出勤管理 画面表示処理
     *
     * @return void
     */
    public function workSchedule()
    {
        $start_date = new Time(date('Y-m-d 00:00:00')); // システム日時を取得
        $start_date->day(1); // システム月の月初を取得
        $end_date = new Time(date('Y-m-d 00:00:00')); // システム日時を取得
        $last_month = $end_date->modify('last day of next month'); // 翌日の月末を取得
        $end_date = new Time($last_month->format('Y-m-d') .' 23:59:59'); // 翌日の月末の日付変わる直前を取得

        // 店舗に所属するスタッフの
        // 当月の月初から翌日の月末の日付変わる直前までのスタッフのスケジュールを取得する
        $casts = $this->Casts->find('all')
            ->where(['shop_id' => $this->viewVars['shopInfo']['id']
                    , 'casts.status' => 1 , 'casts.delete_flag' => 0])
            ->contain(['shops'
                , 'cast_schedules' => function (Query $q) use ($start_date, $end_date)  {
                    return $q
                    ->where(['cast_schedules.start >='=> $start_date
                            , 'cast_schedules.start <='=> $end_date])
                    ->order(['cast_schedules.start'=>'ASC']);
            }])->order(['casts.created'=>'DESC'])->toArray();

        $workSchedule = $this->WorkSchedules->find('all')
            ->where(['work_schedules.shop_id' => $this->viewVars['shopInfo']['id']])
            ->first();

        // スタッフ配列リスト
        // $castList = array();
        $tempList = array();

        $dateList = $this->Util->getPeriodDate();
        $workPlanList = array();
        // 未入力値で初期化する
        for ($i=0; $i < count($dateList); $i++) {
            $workPlanList[] = 'ー';
        }

        // スタッフ情報を配列にセット
        foreach ($casts as $key1 => $cast) {

            $tempList = array('castInfo'=>$this->Util->getCastItem($cast, $cast->shop));
            //$tempList = array_merge($tempList, array('cast'=>$cast));
            $cloneList = $workPlanList;

            // 予定期間２ヵ月分をループする
            foreach ($cast->cast_schedules as $key2 => $schedule) {
                $sDate = $schedule->start->format('m-d'); // 比較用にフォーマット
                // 予定期間２ヵ月分をループする
                foreach ($dateList as $key3 => $date) {
                    $array = explode(' ', $date); // 比較用に配列化
                    // 日付が一致した場合
                    if(str_replace('/','-', $array[0]) == $sDate) {
                        // 仕事なら予定リストに〇をセット
                        if($schedule->title == '仕事') {
                            $cloneList[$key3] = '〇';
                        } else if($schedule->title == '休み') {
                            // 休みなら予定リストに✕をセット
                            $cloneList[$key3] = '✕';
                        }

                        break;
                    }
                }
            }

            $tempList = array_merge($tempList, array('work_plan'=>$cloneList));
            $cast->set('schedule_info', $tempList);
        }

        $this->set(compact('casts', 'dateList', 'workSchedule'));
        $this->render();
    }

    /**
     * 出勤スケジュール 登録処理
     * @return void
     */
    public function saveWorkSchedule()
    {
        // AJAXのアクセス以外は不正とみなす。
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException('AJAX以外でのアクセスがあります。');
        }
        $flg = true; // 返却フラグ
        $errors = ""; // 返却メッセージ
        $this->confReturnJson(); // responceがjsonタイプの場合の共通設定
        $message = RESULT_M['SIGNUP_SUCCESS']; // 返却メッセージ
        $auth = $this->request->session()->read('Auth.Owner');
        $id = $auth['id']; // ログインユーザID

        // レコードが存在するか
        // レコードがない場合は、新規で登録を行う。
        if (!$this->WorkSchedules->exists(['shop_id' =>$this->viewVars['shopInfo']['id']])) {

            $Work_schedule = $this->WorkSchedules->newEntity($this->request->getData());
            $Work_schedule->shop_id = $this->viewVars['shopInfo']['id'];
        } else {
            // スケジュールテーブルからidのみを取得する
            $old_Work_schedule = $this->WorkSchedules->find('all')
                ->where(['shop_id' => $this->viewVars['shopInfo']['id']])
                ->first();
            $Work_schedule = $this->WorkSchedules
                ->patchEntity($old_Work_schedule, $this->request->getData());

            // 選択済みのメンバーで再登録した場合、レコードの変更無しなるため、変更フラグを立てる
            if (count($Work_schedule->getDirty()) == 0) {
                $Work_schedule->setDirty('cast_ids', true);
            }
        }

        try {

            // レコード更新実行
            if (!$this->WorkSchedules->save($Work_schedule)) {
                throw new RuntimeException('レコードの登録ができませんでした。');
            }
            // 更新情報を追加する
            $updates = $this->Updates->newEntity();
            $updates->set('content','本日のメンバーを更新しました。');
            $updates->set('shop_id', $this->viewVars['shopInfo']['id']);
            $updates->set('type', SHOP_MENU_NAME['WORK_SCHEDULE']);
            // レコード更新実行
            if (!$this->Updates->save($updates)) {
                throw new RuntimeException('レコードの登録ができませんでした。');
            }

        } catch (RuntimeException $e) {
            $this->log($this->Util->setLog($auth, $e));
            $flg = false;
        }

        // 例外が発生している場合にメッセージをセットして返却する
        if (!$flg) {

            $message = RESULT_M['SIGNUP_FAILED'];
            $response = array(
                'success' => $flg,
                'message' => $message
            );
            $this->response->body(json_encode($response));
            return;
        }

        $start_date = new Time(date('Y-m-d 00:00:00'));
        $start_date->day(1);
        $end_date = new Time(date('Y-m-d 00:00:00'));
        $last_month = $end_date->modify('last day of next month');
        $end_date = new Time($last_month->format('Y-m-d') .' 23:59:59');

        // 店舗に所属する全てのスタッフを取得する
        $casts = $this->Casts->find('all')
            ->where(['shop_id' => $this->viewVars['shopInfo']['id']
                    , 'casts.status' => 1 , 'casts.delete_flag' => 0])
            ->contain(['shops'
                , 'cast_schedules' => function (Query $q) use ($start_date, $end_date)  {
                    return $q
                    ->where(['cast_schedules.start >='=> $start_date
                            , 'cast_schedules.start <='=> $end_date])
                    ->order(['cast_schedules.start'=>'ASC']);
            }])->order(['casts.created'=>'DESC']);

        $workSchedule = $this->WorkSchedules->find('all')
            ->where(['shop_id', $this->viewVars['shopInfo']['id']])
            ->first();

        // スタッフ配列リスト
        // $castList = array();
        $tempList = array();

        $dateList = $this->Util->getPeriodDate();
        $workPlanList = array();
        // 未入力値で初期化する
        for ($i=0; $i < count($dateList); $i++) {
            $workPlanList[] = 'ー';
        }

        // スタッフ情報を配列にセット
        foreach ($casts as $key1 => $cast) {

            $tempList = array('castInfo'=>$this->Util->getCastItem($cast, $cast->shop));
            //$tempList = array_merge($tempList, array('cast'=>$cast));
            $cloneList = $workPlanList;

            // 予定期間２ヵ月分をループする
            foreach ($cast->cast_schedules as $key2 => $schedule) {
                $sDate = $schedule->start->format('m-d'); // 比較用にフォーマット
                // 予定期間２ヵ月分をループする
                foreach ($dateList as $key3 => $date) {
                    $array = explode(' ', $date); // 比較用に配列化
                    // 日付が一致した場合
                    if(str_replace('/','-', $array[0]) == $sDate) {
                        // 仕事なら予定リストに〇をセット
                        if($schedule->title == '仕事') {
                            $cloneList[$key3] = '〇';
                        } else if($schedule->title == '休み') {
                            // 休みなら予定リストに✕をセット
                            $cloneList[$key3] = '✕';
                        }

                        break;
                    }
                }
            }

            $tempList = array_merge($tempList, array('work_plan'=>$cloneList));
            $cast->set('schedule_info', $tempList);
        }

        $this->set(compact('casts', 'dateList', 'workSchedule'));

        $this->render('/Owner/Shops/work_schedule');
        $response = array(
            'html' => $this->response->body(),
            'error' => $errors,
            'success' => $flg,
            'message' => $message
        );
        $this->response->body(json_encode($response));
        return;
    }

    /**
     * 設定 画面処理
     *
     * @return void
     */
    public function option()
    {
        $option = $this->ShopOptions->get($this->viewVars['shopInfo']['id']);
        // マスタコード取得
        $masCodeFind = array('option_menu_color');
        // セレクトボックスを作成する
        $mast_data = $this->Util->getSelectList($masCodeFind,$this->MasterCodes,false);

        // 登録ボタン押下時
        if ($this->request->is('ajax')) {

            $flg = true; // 返却フラグ
            $errors = ""; // 返却メッセージ
            $this->confReturnJson(); // responceがjsonタイプの場合の共通設定
            $message = RESULT_M['SIGNUP_SUCCESS']; // 返却メッセージ
            $auth = $this->request->session()->read('Auth.Owner');
            $id = $auth['id']; // ログインユーザID

            // パラメタセット
            $option->set(['menu_color'=>$this->request->getData('menu_color')[0]]);

            try{
                if(!$option->errors()) {
                    if (!$this->ShopOptions->save($option)) {
                        throw new RuntimeException('レコードの更新ができませんでした。');
                    }
                } else {

                    foreach ($option->errors() as $key1 => $value1) {
                        foreach ($value1 as $key2 => $value2) {
                            $this->Flash->error($value2);
                        }
                    }
                }
            } catch (RuntimeException $e) {
                $this->log($this->Util->setLog($auth, $e));
                $flg = false;
            }

                // 例外が発生している場合にメッセージをセットして返却する
                if (!$flg) {

                    $message = RESULT_M['SIGNUP_FAILED'];
                    $response = array(
                        'success' => $flg,
                        'message' => $message
                    );
                    $this->response->body(json_encode($response));
                    return;
                }

            $this->set(compact('option','mast_data'));

            $this->render('/Owner/Shops/option');
            $response = array(
                'html' => $this->response->body(),
                'error' => $errors,
                'success' => $flg,
                'message' => $message
            );
            $this->response->body(json_encode($response));
            return;

        }

        $this->set(compact('option','mast_data'));
        $this->render();
    }

    /**
     * 全てのお知らせを取得する処理
     *
     * @return void
     */
    public function getAllNotice($shop_id, $notice_path, $user_id = null)
    {
        $notice = $this->Util->getNotices($this->viewVars['shopInfo']['id']
            , $this->viewVars['shopInfo']['notice_path'], null);
        $top_notice = array();
        $arcive_notice = array();
        $count = 0;
        foreach ($notice as $key1 => $rows) :
            foreach ($rows as $key2 => $row) :
                if ($count == 5) :
                    break;
                endif;
                array_push($top_notice, $row);
                unset($notice[$key1][$key2]);
                $count = $count + 1;
            endforeach;
        endforeach;
        foreach ($notice as $key => $rows) :
            if (count($rows) == 0) :
                unset($notice[$key]);
            endif;
        endforeach;
        foreach ($notice as $key1 => $rows) :
            $tmp_array = array();
            foreach ($rows as $key2 => $row) :
                array_push($tmp_array, $row);
            endforeach;
            array_push($arcive_notice, array_values($tmp_array));
        endforeach;

        return array($top_notice, $arcive_notice);
    }

    /**
     * お知らせアーカイブ表示画面の処理
     *
     * @return void
     */
    public function viewCalendar()
    {
        // AJAXのアクセス以外は不正とみなす。
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException('AJAX以外でのアクセスがあります。');
        }
        $this->confReturnJson(); // json返却用の設定
        $notice = $this->Util->getNotice($this->request->query["id"]);
        $this->response->body(json_encode($notice));
        return;
    }


}
