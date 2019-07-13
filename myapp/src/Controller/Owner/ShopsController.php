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
            $this->set('shopInfo', $this->Util->getItem($shop));
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
            ->where(['Shops.id'=> $this->viewVars["shopInfo"]["shop_id"] , 'owner_id' => $user['id']])
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
        $flg = true; // 返却フラグ
        $errors = ""; // 返却メッセージ
        $this->confReturnJson(); // responceがjsonタイプの場合の共通設定

        $tmpDir = new Folder; // バックアップ用
        $del_path = preg_replace('/(^\/)/', '', 
            $this->viewVars['shopInfo']['dir_path']);
        $file = new File(WWW_ROOT.$del_path . DS .$this->request->getData('file_before'));
        // ロールバック用に一時フォルダにバックアップする。
        //$tmpDir = $this->Util->createFileTmpDirectoy(WWW_ROOT.PATH_ROOT['TMP'], $fileClone);

        try {
            // ロールバック用に一時フォルダにバックアップ
            if(!$file->copy(WWW_ROOT.PATH_ROOT['TMP'].DS.$file->name)) {
                throw new RuntimeException('トップ画像のバックアップに失敗しました。');
            }
            // バックアップしたファイルを取得
            $tmpFile = new File (WWW_ROOT.PATH_ROOT['TMP'].DS.$file->name); // バックアップ用
            // トップ画像削除処理実行
            if (!$file->delete()) {
                throw new RuntimeException('トップ画像の削除に失敗しました。');
            }
        } catch (RuntimeException $e) {
            // 変数が存在するかつバックアップファイルがあれば削除する
            if (isset($tmpFile) && $tmpFile->exists()) {
                $tmpFile->delete();// tmpファイル削除
            }

            $message = RESULT_M['DELETE_FAILED'];
            $flg = false;
            $response = array(
                'success' => $flg,
                'message' => $message
            );
            $this->response->body(json_encode($response));
            return;
        }
        $shop = $this->Shops->get($this->request->getData('id'));
        $shop->top_image = "";
        // データべース削除
        if($this->Shops->save($shop)) {
            // 変数が存在するかつバックアップファイルがあれば削除する
            if (isset($tmpFile) && $tmpFile->exists()) {
                $tmpFile->delete();// tmpファイル削除
            }
            $message = RESULT_M['DELETE_SUCCESS'];
            $flg = true;
       } else {
            // 失敗
            // 変数が存在するかつバックアップファイルがファイルを戻す
            if (isset($tmpFile) && $tmpFile->exists()) {
                $tmpFile->copy($file->path);
                $tmpFile->delete();// tmpファイル削除
            }
            $message = RESULT_M['DELETE_FAILED'];
            $flg = false;
            $response = array(
                'success' => $flg,
                'message' => $message
            );
            $this->response->body(json_encode($response));
            return;
        }

        $shop = $this->Shops->find()
            ->where(['id' => $this->viewVars['shopInfo']['shop_id']])->first();
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
        $flg = true; // 返却フラグ
        $errors = ""; // 返却メッセージ
        $this->confReturnJson(); // responceがjsonタイプの場合の共通設定

        $shop = $this->Shops->get($this->request->getData('id'));
        $message = RESULT_M['UPDATE_SUCCESS']; // 返却メッセージ
        //new Folder(WWW_ROOT. $this->viewVars['shopInfo']['dir_path'], true, 0755);
        $dirPath = preg_replace('/(^\/)/', '', 
            $this->viewVars['shopInfo']['dir_path']);
         $dir = new Folder(WWW_ROOT. $this->viewVars['shopInfo']['dir_path'], true, 0755);
        // ファイルが入力されたとき
        if ($this->request->getData('top_image_file.name')) {
            $limitFileSize = 1024 * 1024;
            try {
                $shop->top_image = $this->Util->file_upload($this->request->getData('top_image_file'),
                    ['name'=>$this->request->getData('file_before')], $dir->path, $limitFileSize);
                // ファイル更新の場合は古いファイルは削除
                if (!empty($this->request->data["file_before"])) {
                    // ファイル名が同じ場合は削除を実行しない
                    if ($this->request->getData('file_before') != $shop->top_image) {
                        $file = new File($dir->path . DS . $this->request->data["file_before"]);
                        if (!$file->delete()) {
                            $this->log($this->request->getData('file_before'), LOG_DEBUG);
                        }
                    }
                }
            } catch (RuntimeException $e) {
                // アップロード失敗の時、既登録ファイルがある場合はそれを保持
                if (isset($this->request->data["file_before"])) {
                    $shop->top_image = $this->request->getData('file_before');
                }
                $this->log($e->getMessage(), LOG_DEBUG);
                $message = RESULT_M['UPDATE_FAILED'];
                $flg = false;
                $response = array(
                    'success' => $flg,
                    'message' => $message
                );
                $this->response->body(json_encode($response));
                return;
            }
        } else {
            // ファイルは入力されていないけど登録されているファイルがあるとき
            if (isset($this->request->data["file_before"])) {
                $shop->top_image = $this->request->getData('file_before');
            }
        }
        // ＤＢ登録
        if (!$this->Shops->save($shop)) {
            $message = RESULT_M['UPDATE_FAILED'];
            $flg = false;
            if($this->request->getData('crud_type') == 'insert') {
                $message = RESULT_M['SIGNUP_FAILED'];
            }
            $response = array(
                'error' => $message,
                'success' => $flg,
            );
            $this->response->body(json_encode($response));
            return;
        }
        $shop = $this->Shops->find()
            ->where(['id' => $this->viewVars['shopInfo']['shop_id']])->first();
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

        $flg = true; // 返却フラグ
        $message = RESULT_M['DELETE_SUCCESS']; // 返却メッセージ
        $errors = ""; // 返却メッセージ
        $this->confReturnJson(); // responceがjsonタイプの場合の共通設定

        $shop = $this->Shops->patchEntity($this->Shops
            ->get($this->request->getData('id')), $this->request->getData());

        if (!$this->Shops->save($shop)) {
            $message = RESULT_M['DELETE_FAILED'];
            $flg = false;
        }

        $shop = $this->Shops->find()
            ->where(['id' => $this->viewVars['shopInfo']['shop_id']])->first();
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
        $flg = true; // 返却フラグ
        $errors = ""; // 返却メッセージ
        $this->confReturnJson(); // responceがjsonタイプの場合の共通設定

        $shop = $this->Shops->patchEntity($this->Shops
            ->get($this->request->getData('id')), $this->request->getData());
        $message = RESULT_M['UPDATE_SUCCESS']; // 返却メッセージ
        // バリデーションチェックエラーがあればセットする。
        if ($shop->errors()) {
            $flg = false;
            if (count($shop->errors()) > 0) {
                foreach ($shop->errors() as $key1 => $value1) {
                    foreach ($value1 as $key2 => $value2) {
                        if (is_array($value2)) {
                            foreach ($value2 as $key3 => $value3) {
                                $errors .= $value3."<br/>";
                            }
                        } else {
                            $errors .= $value2."<br/>";
                            //$this->Flash->error($value2);
                        }
                    }
                }
            }
            $response = array(
                'error' => $errors,
                'success' => $flg,
            );
            $this->response->body(json_encode($response));
            return;
        }
        // ＤＢ登録
        if (!$this->Shops->save($shop)) {
            $message = RESULT_M['UPDATE_FAILED'];
            $flg = false;
            if($this->request->getData('crud_type') == 'insert') {
                $message = RESULT_M['SIGNUP_FAILED'];
            }
            $response = array(
                'error' => $message,
                'success' => $flg,
            );
            $this->response->body(json_encode($response));
            return;
        }
        $shop = $this->Shops->find()
            ->where(['id' => $this->viewVars['shopInfo']['shop_id']])->first();
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
        // TODO: 全体的にajaxのPOSTか確認せないかん。
        $flg = true; // 返却フラグ
        $message = ""; // 返却メッセージ
        $this->confReturnJson(); // responceがjsonタイプの場合の共通設定

        $this->request->session()->write('activeTab', 'coupon'); // タブ状態を保持
        $coupon = $this->Coupons->get($this->request->getData('id'));
        // ステータスをセット
        $coupon->status = $this->request->getData('status');
        // メッセージをセット
        $coupon->status == 1 ? 
            $message = RESULT_M['DISPLAY_SUCCESS']: $message = RESULT_M['HIDDEN_SUCCESS'];
        if (!$this->Coupons->save($coupon)) {
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
        $coupon = $this->Coupons->get($this->request->getData('id'));

        $flg = true; // 返却フラグ
        $message = RESULT_M['DELETE_SUCCESS']; // 返却メッセージ
        $errors = ""; // 返却メッセージ
        $this->confReturnJson(); // responceがjsonタイプの場合の共通設定

        if (!$this->Coupons->delete($coupon)) {
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
        $flg = true; // 返却フラグ
        $errors = ""; // 返却メッセージ
        $this->confReturnJson(); // responceがjsonタイプの場合の共通設定

        // 新規登録 店舗IDとステータスもセットする
        if($this->request->getData('crud_type') == 'insert') {
            $coupon = $this->Coupons->newEntity(array_merge(
                ['shop_id' => $this->viewVars['shopInfo']['shop_id'], 'status'=>0]
                    ,$this->request->getData()));
            $message = RESULT_M['SIGNUP_SUCCESS']; // 返却メッセージ
        } else if($this->request->getData('crud_type') == 'update') {
        // 更新
            $coupon = $this->Coupons->patchEntity($this->Coupons
                ->get($this->request->getData('id')), $this->request->getData());
            $message = RESULT_M['UPDATE_SUCCESS']; // 返却メッセージ
        }
        // バリデーションチェックエラーがあればセットする。
        if ($coupon->errors()) {
            $flg = false;
            if (count($coupon->errors()) > 0) {
                foreach ($coupon->errors() as $key1 => $value1) {
                    foreach ($value1 as $key2 => $value2) {
                        if (is_array($value2)) {
                            foreach ($value2 as $key3 => $value3) {
                                $errors .= $value3."<br/>";
                            }
                        } else {
                            $errors .= $value2."<br/>";
                            //$this->Flash->error($value2);
                        }
                    }
                }
            }
            $response = array(
                'error' => $errors,
                'success' => $flg,
            );
            $this->response->body(json_encode($response));
            return;
        }
        // ＤＢ登録
        if (!$this->Coupons->save($coupon)) {
            $message = RESULT_M['UPDATE_FAILED'];
            $flg = false;
            if($this->request->getData('crud_type') == 'insert') {
                $message = RESULT_M['SIGNUP_FAILED'];
            }
            $response = array(
                'error' => $message,
                'success' => $flg,
            );
            $this->response->body(json_encode($response));
            return;
        }
        $shop = $this->Shops->find()
            ->where(['id' => $this->viewVars['shopInfo']['shop_id']])
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
        $flg = true; // 返却フラグ
        $message = ""; // 返却メッセージ
        $this->confReturnJson(); // responceがjsonタイプの場合の共通設定

        $this->request->session()->write('activeTab', 'cast'); // タブ状態を保持
        $cast = $this->Casts->get($this->request->getData('id'));
        // ステータスをセット
        $cast->status = $this->request->getData('status');
        // メッセージをセット
        $cast->status == 1 ? 
            $message = RESULT_M['DISPLAY_SUCCESS']: $message = RESULT_M['HIDDEN_SUCCESS'];
        if (!$this->Casts->save($cast)) {
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

        $flg = true; // 返却フラグ
        $errors = ""; // 返却メッセージ
        $this->confReturnJson(); // responceがjsonタイプの場合の共通設定

        $tmpDir = new Folder; // バックアップ用
        $del_path = preg_replace('/(^\/)/', '', 
            $this->viewVars['shopInfo']['dir_path'].PATH_ROOT['CAST'].DS.$this->request->getData('dir').DS);
        $delFolder = new Folder(WWW_ROOT.$del_path);

        $dirClone = new Folder($delFolder->path, true, 0755);
        // ロールバック用に一時フォルダにバックアップする。
        $tmpDir = $this->Util->createTmpDirectoy(WWW_ROOT.PATH_ROOT['TMP'], $dirClone);

        try {

            // 日記フォルダ削除処理実行
            if (!$delFolder->delete()) {
                throw new RuntimeException('キャストの削除ができませんでした。');
            }
        } catch (RuntimeException $e) {
            if (is_dir($tmpDir->path)) {
                $tmpDir->copy($dirClone->path);
                $tmpDir->delete();// tmpフォルダ削除
            }

            $message = RESULT_M['DELETE_FAILED'];
            $flg = false;
            $response = array(
                'success' => $flg,
                'message' => $message
            );
            $this->response->body(json_encode($response));
            return;
        }
        $cast = $this->Casts->get($this->request->getData('id'));
        // データべース削除
        if($this->Casts->delete($cast)) {
            // 成功: tmpフォルダ削除
            if (is_dir($tmpDir->path)) {
                $tmpDir->delete();// tmpフォルダ削除
            }
            $message = RESULT_M['DELETE_SUCCESS'];
            $flg = true;
       } else {
            // 失敗: ファイルを戻す
            if (is_dir($tmpDir->path)) {
                $tmpDir->copy($dirClone->path);
                $tmpDir->delete();// tmpフォルダ削除
            }
            $message = RESULT_M['DELETE_FAILED'];
            $flg = false;
            $response = array(
                'success' => $flg,
                'message' => $message
            );
            $this->response->body(json_encode($response));
            return;
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
        $flg = true; // 返却フラグ
        $errors = ""; // 返却メッセージ
        $this->confReturnJson(); // responceがjsonタイプの場合の共通設定

        // 新規登録(仮登録) 店舗IDとステータスも論理削除フラグセットする
        if($this->request->getData('crud_type') == 'insert') {
            $cast = $this->Casts->newEntity(array_merge(
                ['shop_id' => $this->viewVars['shopInfo']['shop_id'], 'status' => 0 , 'delete_flag' => 1]
                    ,$this->request->getData()));
            $message = MAIL['AUTH_CONFIRMATION']; // 返却メッセージ
        } else if($this->request->getData('crud_type') == 'update') {
        // 更新
            $cast = $this->Casts->patchEntity($this->Casts
                ->get($this->request->getData('id')), $this->request->getData());
            $message = RESULT_M['UPDATE_SUCCESS']; // 返却メッセージ
        }
        // バリデーションチェックエラーがあればセットし返却する。
        if ($cast->errors()) {
            $flg = false;
            if (count($cast->errors()) > 0) {
                foreach ($cast->errors() as $key1 => $value1) {
                    foreach ($value1 as $key2 => $value2) {
                        if (is_array($value2)) {
                            foreach ($value2 as $key3 => $value3) {
                                $errors .= $value3."<br/>";
                            }
                        } else {
                            $errors .= $value2."<br/>";
                            //$this->Flash->error($value2);
                        }
                    }
                }
            }
            $response = array(
                'error' => $errors,
                'success' => $flg,
            );
            $this->response->body(json_encode($response));
            return;
        }
        // ＤＢ登録
        if (!$this->Casts->save($cast)) {
            $message = RESULT_M['UPDATE_FAILED'];
            $flg = false;
            if($this->request->getData('crud_type') == 'insert') {
                $message = RESULT_M['SIGNUP_FAILED'];
            }
            $response = array(
                'error' => $message,
                'success' => $flg,
            );
            $this->response->body(json_encode($response));
            return;
        }
        // 新規登録(仮登録)できた場合、登録したメールアドレスに認証メールを送る
        if ($this->request->getData('crud_type') == 'insert') {
            $this->getMailer('Cast')->send('castRegistration', [$cast]);
        }
        $shop = $this->Shops->find()
            ->where(['id' => $this->viewVars['shopInfo']['shop_id']])
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
        $flg = true; // 返却フラグ
        $errors = ""; // 返却メッセージ
        $this->confReturnJson(); // responceがjsonタイプの場合の共通設定

        // 更新
        $shop = $this->Shops->patchEntity($this->Shops
            ->get($this->request->getData('id')), $this->request->getData());
        $message = RESULT_M['UPDATE_SUCCESS']; // 返却メッセージ

        // バリデーションチェックエラーがあればセットし返却する。
        if ($shop->errors()) {
            $flg = false;
            if (count($shop->errors()) > 0) {
                foreach ($shop->errors() as $key1 => $value1) {
                    foreach ($value1 as $key2 => $value2) {
                        if (is_array($value2)) {
                            foreach ($value2 as $key3 => $value3) {
                                $errors .= $value3."<br/>";
                            }
                        } else {
                            $errors .= $value2."<br/>";
                            //$this->Flash->error($value2);
                        }
                    }
                }
            }
            $response = array(
                'error' => $errors,
                'success' => $flg,
            );
            $this->response->body(json_encode($response));
            return;
        }
        // ＤＢ登録
        if (!$this->Shops->save($shop)) {
            $message = RESULT_M['UPDATE_FAILED'];
            $flg = false;
            $response = array(
                'error' => $message,
                'success' => $flg,
            );
            $this->response->body(json_encode($response));
            return;
        }
        $shop = $this->Shops->find()
            ->where(['id' => $this->viewVars['shopInfo']['shop_id']])
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
        $flg = true; // 返却フラグ
        $errors = ""; // 返却メッセージ
        $this->confReturnJson(); // responceがjsonタイプの場合の共通設定

        // 更新
        $job = $this->Jobs->patchEntity($this->Jobs
            ->get($this->request->getData('id')), $this->request->getData());
        $message = RESULT_M['UPDATE_SUCCESS']; // 返却メッセージ

        // バリデーションチェックエラーがあればセットし返却する。
        if ($job->errors()) {
            $flg = false;
            if (count($job->errors()) > 0) {
                foreach ($job->errors() as $key1 => $value1) {
                    foreach ($value1 as $key2 => $value2) {
                        if (is_array($value2)) {
                            foreach ($value2 as $key3 => $value3) {
                                $errors .= $value3."<br/>";
                            }
                        } else {
                            $errors .= $value2."<br/>";
                            //$this->Flash->error($value2);
                        }
                    }
                }
            }
            $response = array(
                'error' => $errors,
                'success' => $flg,
            );
            $this->response->body(json_encode($response));
            return;
        }
        // ＤＢ登録
        if (!$this->Jobs->save($job)) {
            $message = RESULT_M['UPDATE_FAILED'];
            $flg = false;
            $response = array(
                'error' => $message,
                'success' => $flg,
            );
            $this->response->body(json_encode($response));
            return;
        }
        $shop = $this->Shops->find()
            ->where(['id' => $this->viewVars['shopInfo']['shop_id']])
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

        $flg = true; // 返却フラグ
        $errors = ""; // 返却メッセージ
        $this->confReturnJson(); // responceがjsonタイプの場合の共通設定
        $message = RESULT_M['UPDATE_SUCCESS']; // 返却メッセージ

        $tmpDir = new Folder; // バックアップ用
        $dirPath = preg_replace('/(^\/)/', '', 
            $this->viewVars['shopInfo']['dir_path'].PATH_ROOT['IMAGE']);
        $dir = new Folder(WWW_ROOT. $dirPath, true, 0755);

        $files = array();
        // ショップ取得
        $shop = $this->Shops->get($this->viewVars['shopInfo']['shop_id']);

        // 既に登録された画像があればデコードし格納、無ければ空の配列を格納する
        ($files_befor = json_decode($this->request->getData("gallery_befor"), true)) > 0 ? : $files_befor = array();
        $fileMax = CAST_CONFIG['FILE_MAX'];
        // カラム「image*」を格納する
        $imageCol = array_values(preg_grep('/^image/', $this->Shops->schema()->columns()));

        // ファイルのバックアップを取得
        $dirClone = new Folder($dir->path, true, 0755);
        // ロールバック用に一時フォルダにバックアップする。
        $tmpDir = $this->Util->createTmpDirectoy(WWW_ROOT.PATH_ROOT['TMP'], $dirClone);

        // 追加画像がある場合
        if (isset($this->request->data["image"])) {
            $files = $this->request->data['image'];
        }
        foreach ($files as $key => $file) {
            // ファイルが入力されたとき
            if (count($file["name"]) > 0) {
                $limitFileSize = 1024 * 1024;
                try {
                    // TODO: 検証用
                    // if($key == 1) {
                    //     throw new RuntimeException('画像の削除に失敗しました。');
                    // }
                    // ファイル名を取得する
                    $convertFile = $this->Util->file_upload($file, $files_befor, $dir->path, $limitFileSize);

                    // ファイル名が同じ場合は処理をスキップする
                    if ($convertFile === false) {
                        $errors = '同じ画像はアップできません。'."\n";
                        continue;
                    }

                } catch (RuntimeException $e) {

                    // アップロード失敗の時、処理を中断する
                    if (is_dir($tmpDir->path)) {
                        // ファイルを戻す前にアップロードされたファイルがある場合があるため、空にしておく
                        $files = $dirClone->find('.*\.*');
                        foreach ($files as $file) {
                            $file = new File($dir->path . DS . $file);
                            $file->delete(); // このファイルを削除します
                        }
                        $tmpDir->copy($dirClone->path);
                        $tmpDir->delete();// tmpフォルダ削除
                    }
                    $this->log($e->getMessage(), LOG_DEBUG);
                    $message = RESULT_M['UPDATE_FAILED'];
                    $flg = false;
                    $response = array(
                        'success' => $flg,
                        'message' => $message
                    );
                    $this->response->body(json_encode($response));
                    return;
                }
            }
            // カラムimage1～image8の空いてる場所に入れる
            for($i = 0; $i < $fileMax; $i++) {
                if(empty($shop->get($imageCol[$i]))) {
                    $shop->set($imageCol[$i], $convertFile);
                    break;
                }
            }

        }
        // ＤＢ登録
        if (!$this->Shops->save($shop)) {
            // 更新失敗の時
            if (is_dir($tmpDir->path)) {
                // ファイルを戻す前にアップロードされたファイルがある場合があるため、空にしておく
                $files = $dirClone->find('.*\.*');
                foreach ($files as $file) {
                    $file = new File($dir->path . DS . $file);
                    $file->delete(); // このファイルを削除します
                }
                $tmpDir->copy($dirClone->path);
                $tmpDir->delete();// tmpフォルダ削除
            }
            $message = RESULT_M['UPDATE_FAILED'];
            $flg = false;

            $response = array(
                'error' => $message,
                'success' => $flg,
            );
            $this->response->body(json_encode($response));
            return;
        }
        // フォルダを削除
        if (is_dir($tmpDir->path)) {
            $tmpDir->delete();
        }
        $shop = $this->Shops->find()
            ->where(['id' => $this->viewVars['shopInfo']['shop_id']])->first();
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

    /**
     * ギャラリー 削除押下処理
     *
     * @return void
     */
    public function deleteGallery()
    {
        $flg = true; // 返却フラグ
        $errors = ""; // 返却メッセージ
        $this->confReturnJson(); // responceがjsonタイプの場合の共通設定

        $tmpDir = new Folder; // バックアップ用
        $del_path = preg_replace('/(^\/)/', '', 
            $this->viewVars['shopInfo']['dir_path'].PATH_ROOT['IMAGE']);
        $file = new File(WWW_ROOT.$del_path . DS .$this->request->getData('name'));
        // ロールバック用に一時フォルダにバックアップする。
        //$tmpDir = $this->Util->createFileTmpDirectoy(WWW_ROOT.PATH_ROOT['TMP'], $fileClone);

        try {
            // ロールバック用に一時フォルダにバックアップ
            if(!$file->copy(WWW_ROOT.PATH_ROOT['TMP'].DS.$file->name)) {
                throw new RuntimeException('画像のバックアップに失敗しました。');
            }
            // バックアップしたファイルを取得
            $tmpFile = new File (WWW_ROOT.PATH_ROOT['TMP'].DS.$file->name); // バックアップ用
            // 画像削除処理実行
            if (!$file->delete()) {
                throw new RuntimeException('画像の削除に失敗しました。');
            }
        } catch (RuntimeException $e) {
            // 変数が存在するかつバックアップファイルがあれば削除する
            if (isset($tmpFile) && $tmpFile->exists()) {
                $tmpFile->delete();// tmpファイル削除
            }

            $message = RESULT_M['DELETE_FAILED'];
            $flg = false;
            $response = array(
                'success' => $flg,
                'message' => $message
            );
            $this->response->body(json_encode($response));
            return;
        }
        $shop = $this->Shops->get($this->viewVars['shopInfo']['shop_id']);
        $shop->set($this->request->getData('key'), "");
        // テーブル更新
        if($this->Shops->save($shop)) {
            // 変数が存在するかつバックアップファイルがあれば削除する
            if (isset($tmpFile) && $tmpFile->exists()) {
                $tmpFile->delete();// tmpファイル削除
            }
            $message = RESULT_M['DELETE_SUCCESS'];
            $flg = true;
       } else {
            // 失敗
            // 変数が存在するかつバックアップファイルがファイルを戻す
            if (isset($tmpFile) && $tmpFile->exists()) {
                $tmpFile->copy($file->path);
                $tmpFile->delete();// tmpファイル削除
            }
            $message = RESULT_M['DELETE_FAILED'];
            $flg = false;
            $response = array(
                'success' => $flg,
                'message' => $message
            );
            $this->response->body(json_encode($response));
            return;
        }

        $shop = $this->Shops->find()
            ->where(['id' => $this->viewVars['shopInfo']['shop_id']])->first();
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
