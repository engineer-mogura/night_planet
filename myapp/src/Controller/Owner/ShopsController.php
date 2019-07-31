<?php
namespace App\Controller\Owner;

use \Cake\ORM\Query;
use Cake\Event\Event;
use RuntimeException;
use Token\Util\Token;
use Cake\Mailer\Email;
use Cake\Core\Configure;
use Cake\Error\Debugger;
use Cake\Filesystem\File;
use Cake\Filesystem\Folder;
use Cake\ORM\TableRegistry;
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
            // shopControllerにアクセスするときは、URLに必ず店舗IDが存在することを想定
            if($this->request->getQuery('id')) {
                $id = $this->request->getQuery('id');
            }

            $shop = $this->Shops->find('all')
                ->where(['id' => $id , 'owner_id' => $user['id']])
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
    public function index($id = null)
    {
        // アクティブタブを空で初期化
        $activeTab = "";

        if (isset($this->request->query["activeTab"])) {
            $activeTab = $this->request->getQuery("activeTab");
        }
        // セッションにアクティブタブがセットされていればセットする
        if ($this->request->session()->check('activeTab')) {
            $activeTab = $this->request->session()->consume('activeTab');
        }
        if(!is_null($user = $this->Auth->user())){
            $shop = $this->Shops->find()
            ->where(['Shops.id'=> $this->viewVars["shopInfo"]["id"] , 'owner_id' => $user['id']])
            ->contain(['Coupons','Jobs','Casts' => function(Query $q) {
                return $q->where(['Casts.delete_flag'=>'0']);
            }])->first();
        }
        $imageCol = array_values(preg_grep('/^image/', $this->Shops->schema()->columns()));
        $imageList = array(); // 画面側でjsonとして使う画像リスト
        // 画像リストを作成する
        foreach ($imageCol as $key => $value) {
            if (!empty($shop[$imageCol[$key]])) {
                array_push($imageList, ['key'=>$imageCol[$key],'name'=>$shop[$imageCol[$key]]]);
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

        $this->set(compact('shop','imageList','masCredit','masData','selectList', 'activeTab', 'ajax'));
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
        $dirPath = preg_replace('/(\/\/)/', '/',
            WWW_ROOT.$this->viewVars['shopInfo']['shop_path']);

        $shop = $this->Shops->get($this->viewVars['shopInfo']['id']);
        // ディクレトリ取得
        $dir = new Folder($dirPath, true, 0755);
        // 前のファイル取得
        $fileBefor = new File($dirPath . DS .$shop->top_image, true, 0755);

        $file = $this->request->getData('top_image_file');
        // ファイルが存在する、かつファイル名がblobの画像のとき
        if (!empty($file["name"]) && $file["name"] == 'blob') {
            $limitFileSize = 1024 * 1024;
            try {
                if(file_exists($fileBefor->path) && !empty($shop->top_image)) {
                    // ロールバック用のファイルサイズチェック
                    if ($fileBefor->size() > CAPACITY['MAX_NUM_BYTES_FILE']) {
                        throw new RuntimeException('ファイルサイズが大きすぎます。');
                    }

                    // 一時ファイル作成
                    if (!$fileBefor->copy(WWW_ROOT.PATH_ROOT['TMP'].DS.$fileBefor->name)) {
                        throw new RuntimeException('バックアップに失敗しました。');
                    }

                    // 一時ファイル取得
                    $tmpFile = new File(WWW_ROOT.PATH_ROOT['TMP'].DS.$fileBefor->name);
                }

                $shop->top_image = $this->Util->file_upload($this->request->getData('top_image_file'),
                    ['name'=> $fileBefor->name ], $dir->path, $limitFileSize);
                // ファイル更新の場合は古いファイルは削除
                if (!empty($fileBefor->name)) {
                    // ファイル名が同じ場合は削除を実行しない
                    if ($fileBefor->name != $shop->top_image) {
                        // ファイル削除処理実行
                        if (!$fileBefor->delete()) {
                            throw new RuntimeException('ファイルの削除ができませんでした。');
                        }
                        // ファイル削除フラグを立てる
                        $isRemoved = true;
                    }
                }

                // レコード更新実行
                if (!$this->Shops->save($shop)) {
                    throw new RuntimeException('レコードの更新ができませんでした。');
                }

            } catch (RuntimeException $e) {
                // ファイルを削除していた場合は復元する
                if ($isRemoved) {
                    $tmpFile->copy($file->path);
                }
                // ファイルがアップロードされていた場合は削除を行う
                if($shop->isDirty('top_image')) {
                    $file = new File($dirPath . DS .$shop->top_image, true, 0755);
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

        $this->request->session()->write('activeTab', 'coupon'); // タブ状態を保持
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
            ->where(['id' => $this->request->getData('shop_id')])
            ->contain(['Coupons'])->first();
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
            ->contain(['Coupons'])->first();
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
     * キャスト スイッチ押下処理
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
     * キャスト 削除押下処理
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
            ->where(['id' => $this->request->getData('shop_id')])
            ->contain(['Casts' => function(Query $q) {
                    return $q->where(['Casts.delete_flag'=>'0']);
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
     * キャスト 編集押下処理
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
            $cast = $this->Casts->newEntity(array_merge(
                ['shop_id' => $this->viewVars['shopInfo']['id'], 'status' => 0 , 'delete_flag' => 1]
                    ,$this->request->getData()));
            $message = MAIL['AUTH_CONFIRMATION']; // 返却メッセージ
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
            // レコード登録、更新実行
            if (!$this->Casts->save($cast)) {
                if($this->request->getData('crud_type') == 'insert') {
                    $message = RESULT_M['SIGNUP_FAILED'];
                    throw new RuntimeException('レコードの登録ができませんでした。');
                }
                $message = RESULT_M['UPDATE_FAILED'];
                throw new RuntimeException('レコードの更新ができませんでした。');
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
            $this->getMailer('Cast')->send('castRegistration', [$cast]);
        }
        $shop = $this->Shops->find()
            ->where(['id' => $this->viewVars['shopInfo']['id']])
            ->contain(['Casts' => function(Query $q) {
                return $q->where(['Casts.delete_flag'=>'0']);
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
            ->contain(['Casts' => function(Query $q) {
                return $q->where(['Casts.delete_flag'=>'0']);
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

        $job = $this->Shops->patchEntity($this->Jobs
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

        // マスタコードの待遇リスト取得
        $masTreatment = $this->MasterCodes->find()->where(['code_group' => 'treatment'])->toArray();
        // 求人情報の待遇リストを作成する
        $jobTreatments = $this->Util->getTreatment(reset($shop->jobs)['treatment'], $masTreatment);
        // クレジット、待遇リストをセット
        $masData = array('treatment'=>json_encode($jobTreatments));

        $this->set(compact('shop','masData','masTreatment'));
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
        // ショップ取得
        $shop = $this->Shops->get($this->viewVars['shopInfo']['id']);

        // 既に登録された画像があればデコードし格納、無ければ空の配列を格納する
        ($files_befor = json_decode($this->request->getData("gallery_befor"), true)) > 0 ? : $files_befor = array();
        $fileMax = CAST_CONFIG['FILE_MAX'];
        // カラム「image*」を格納する
        $imageCol = array_values(preg_grep('/^image/', $this->Shops->schema()->columns()));

        try{

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
            // 追加画像がある場合
            if (isset($this->request->data["image"])) {
                $files = $this->request->data['image'];
            }
            foreach ($files as $key => $file) {
                // ファイルが存在する、かつファイル名がblobの画像のとき
                if (!empty($file["name"]) && $file["name"] == 'blob') {
                    $limitFileSize = 1024 * 1024;

                    // ファイル名を取得する
                    $convertFile = $this->Util->file_upload($file, $files_befor, $dir->path, $limitFileSize);

                    // ファイル名が同じ場合は処理をスキップする
                    if ($convertFile === false) {
                        $isDuplicate = true;
                        continue;
                    }

                    // カラムimage1～image8の空いてる場所に入れる
                    for ($i = 0; $i < $fileMax; $i++) {
                        if (empty($shop->get($imageCol[$i]))) {
                            $shop->set($imageCol[$i], $convertFile);
                            break;
                        }
                    }
                }
            }

            // レコード更新実行
            if (!$this->Shops->save($shop)) {
                throw new RuntimeException('レコードの更新ができませんでした。');
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
            $response = array(
                'success' => $flg,
                'message' => $message
            );
            $this->response->body(json_encode($response));
            return;
        }
        // フォルダを削除
        if (file_exists($tmpDir->path)) {
            $tmpDir->delete();
        }

        $shop = $this->Shops->get($this->viewVars['shopInfo']['id']);
        $imageCol = array_values(preg_grep('/^image/', $this->Shops->schema()->columns()));
        $imageList = array(); // 画面側でjsonとして使う画像リスト
        // 画像リストを作成する
        foreach ($imageCol as $key => $value) {
            if (!empty($shop[$imageCol[$key]])) {
                array_push($imageList, ['key'=>$imageCol[$key],'name'=>$shop[$imageCol[$key]]]);
            }
        }
        $this->set(compact('shop','imageList'));
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
        $isRemoved = false; // ファイル削除フラグ
        $errors = ""; // 返却メッセージ
        $this->confReturnJson(); // responceがjsonタイプの場合の共通設定
        $message = RESULT_M['DELETE_SUCCESS']; // 返却メッセージ
        $auth = $this->request->session()->read('Auth.Owner');
        $id = $auth['id']; // ユーザーID
        $tmpFile = null; // バックアップ用

        try {
            $del_path = preg_replace('/(^\/)/', '', $this->viewVars['shopInfo']['image_path']);
            // 削除対象ファイルを取得
            $file = new File(WWW_ROOT.$del_path . DS .$this->request->getData('name'));

            // 削除対象ファイルパス存在チェック
            if (!file_exists($file->path)) {
                throw new RuntimeException('ファイルが存在しません。');
            }

            // ロールバック用のファイルサイズチェック
            if ($file->size() > CAPACITY['MAX_NUM_BYTES_FILE']) {
                throw new RuntimeException('ファイルサイズが大きすぎます。');
            }

            // 一時ファイル作成
            if (!$file->copy(WWW_ROOT.PATH_ROOT['TMP'].DS.$file->name)) {
                throw new RuntimeException('画像のバックアップに失敗しました。');
            }

            // 一時ファイル取得
            $tmpFile = new File(WWW_ROOT.PATH_ROOT['TMP'].DS.$file->name);

            // 日記ファイル削除処理実行
            if (!$file->delete()) {
                throw new RuntimeException('ファイルの削除ができませんでした。');
            }
            // ファイル削除フラグを立てる
            $isRemoved = true;
            // 更新対象レコード取得
            $shop = $this->Shops->get($this->viewVars['shopInfo']['id']);
            $shop->set($this->request->getData('key'), "");
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

        $shop = $this->Shops->find()
            ->where(['id' => $this->viewVars['shopInfo']['id']])->first();
        $imageCol = array_values(preg_grep('/^image/', $this->Shops->schema()->columns()));
        $imageList = array(); // 画面側でjsonとして使う画像リスト
        // 画像リストを作成する
        foreach ($imageCol as $key => $value) {
            if (!empty($shop[$imageCol[$key]])) {
                array_push($imageList, ['key'=>$imageCol[$key],'name'=>$shop[$imageCol[$key]]]);
            }
        }
        $this->set(compact('shop','imageList'));
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

    // /**
    //  * ギャラリー 削除押下処理
    //  *
    //  * @return void
    //  */
    // public function deleteGallery()
    // {
    //     $flg = true; // 返却フラグ
    //     $errors = ""; // 返却メッセージ
    //     $this->confReturnJson(); // responceがjsonタイプの場合の共通設定

    //     $tmpDir = new Folder; // バックアップ用
    //     $del_path = preg_replace('/(^\/)/', '', 
    //         $this->viewVars['shopInfo']['image_path']);
    //     $file = new File(WWW_ROOT.$del_path . DS .$this->request->getData('name'));
    //     // ロールバック用に一時フォルダにバックアップする。
    //     //$tmpDir = $this->Util->createFileTmpDirectoy(WWW_ROOT.PATH_ROOT['TMP'], $fileClone);

    //     try {
    //         // ロールバック用に一時フォルダにバックアップ
    //         if(!$file->copy(WWW_ROOT.PATH_ROOT['TMP'].DS.$file->name)) {
    //             throw new RuntimeException('バックアップに失敗しました。');
    //         }
    //         // バックアップしたファイルを取得
    //         $tmpFile = new File (WWW_ROOT.PATH_ROOT['TMP'].DS.$file->name); // バックアップ用
    //         // 画像削除処理実行
    //         if (!$file->delete()) {
    //             throw new RuntimeException('画像の削除に失敗しました。');
    //         }
    //     } catch (RuntimeException $e) {
    //         // 変数が存在するかつバックアップファイルがあれば削除する
    //         if (isset($tmpFile) && $tmpFile->exists()) {
    //             $tmpFile->delete();// tmpファイル削除
    //         }
    //         $this->log($e->getMessage());
    //         $message = RESULT_M['DELETE_FAILED'];
    //         $flg = false;
    //         $response = array(
    //             'success' => $flg,
    //             'message' => $message
    //         );
    //         $this->response->body(json_encode($response));
    //         return;
    //     }
    //     $shop = $this->Shops->get($this->viewVars['shopInfo']['id']);
    //     $shop->set($this->request->getData('key'), "");
    //     // テーブル更新
    //     if($this->Shops->save($shop)) {
    //         // 変数が存在するかつバックアップファイルがあれば削除する
    //         if (isset($tmpFile) && $tmpFile->exists()) {
    //             $tmpFile->delete();// tmpファイル削除
    //         }
    //         $message = RESULT_M['DELETE_SUCCESS'];
    //         $flg = true;
    //    } else {
    //         // 失敗
    //         // 変数が存在するかつバックアップファイルがファイルを戻す
    //         if (isset($tmpFile) && $tmpFile->exists()) {
    //             $tmpFile->copy($file->path);
    //             $tmpFile->delete();// tmpファイル削除
    //         }
    //         $message = RESULT_M['DELETE_FAILED'];
    //         $flg = false;
    //         $response = array(
    //             'success' => $flg,
    //             'message' => $message
    //         );
    //         $this->response->body(json_encode($response));
    //         return;
    //     }

    //     $shop = $this->Shops->find()
    //         ->where(['id' => $this->viewVars['shopInfo']['id']])->first();
    //     $imageCol = array_values(preg_grep('/^image/', $this->Shops->schema()->columns()));
    //     $imageList = array(); // 画面側でjsonとして使う画像リスト
    //     // 画像リストを作成する
    //     foreach ($imageCol as $key => $value) {
    //         if (!empty($shop[$imageCol[$key]])) {
    //             array_push($imageList, ['key'=>$imageCol[$key],'name'=>$shop[$imageCol[$key]]]);
    //         }
    //     }
    //     $this->set(compact('shop','imageList'));
    //     $this->render('/Element/shopEdit/gallery');
    //     $response = array(
    //         'html' => $this->response->body(),
    //         'error' => $errors,
    //         'success' => $flg,
    //         'message' => $message
    //     );
    //     $this->response->body(json_encode($response));
    //     return;
    // }

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
