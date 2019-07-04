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
        // オーナーに関する情報をセット
        if(!is_null($user = $this->Auth->user())){
            $shop = $this->Shops->find('all')->where(['owner_id' => $user['id']])->first();
            $this->set('infoArray', $this->Util->getItem($shop));
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
            $shop = $this->Shops->find('all')->where(['owner_id' => $user['id']])->first();
            $this->set('infoArray', $this->Util->getItem($shop));
        }

        if ($this->request->session()->check('ajax')) {
            $ajax = $this->request->session()->consume('ajax'); // セッションを開放する
            $this->viewBuilder()->autoLayout(false);
            $this->autoRender = false;
            $this->response->charset('UTF-8');
            $this->response->type('json');
            $isException = $this->request->session()->consume('exception');
            $resultflg = $this->request->session()->consume('resultflg');
            $resultMessage = $this->request->session()->consume('resultMessage');
            $valideteErrors = $this->request->session()->consume('errors');

            //$valideteErrors = array('fluts'=> array('apple' => 'リンゴ','banana' => 'バナナ'));
            $errors = "";
            // 各タブでのチェックエラー内容があればセットする。
            if (count($valideteErrors) > 0) {
                foreach ($valideteErrors as $key1 => $value1) {
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
            // 各タブでの例外があればセットする。
            if ($isException) {
                $errors = $resultMessage;
                //$this->Flash->error($valideteErrors);
            }

            // 引数にID(URLパラメータにID)がセットされていない場合は、authから直接セット
            if (!isset($id)) {
                $id = $this->request->getSession()->read('Auth.Owner.id');
            }

            $shop = $this->Shops->find()->where(['owner_id' => $id])->contain(['Coupons','Jobs','Casts' => function(Query $q) {
                return $q
                    ->where(['Casts.delete_flag'=>'0']);
                }])->first();
            $masterCodesFind = array('industry','job_type','treatment','day');
            $credits = $this->MasterCodes->find()->where(['code_group' => 'credit']);
            $creditsHidden = $this->Util->getCredit($shop,$credits);
            $treatments = $this->MasterCodes->find()->where(['code_group' => 'treatment']);
            $treatmentHidden = $this->Util->getTreatment($shop,$treatments);
            $masterCodeHidden = array('credit'=>json_encode($creditsHidden));
            $masterCodeHidden = array_merge($masterCodeHidden, array('treatment'=>json_encode($treatmentHidden)));
            $selectList = $this->Util->getSelectList($masterCodesFind,$this->MasterCodes,true);
    
            $this->set(compact('shop','credits','masterCodeHidden','selectList', 'activeTab', 'ajax'));
            $html = (String)$this->render('/Owner/Shops/index');

            $response = array(
                'html' => $html,
                'error' => $errors,
                'success' => $resultflg,
                'message' => $resultMessage
            );

            $this->response->body(json_encode($response));
            return;
        }
        // 引数にID(URLパラメータにID)がセットされていない場合は、authから直接セット
        if (!isset($id)) {
            $id = $this->request->getSession()->read('Auth.Owner.id');
        }
        $shop = $this->Shops->find()->where(['owner_id' => $id])->contain(['Coupons','Jobs','Casts' => function(Query $q) {
            return $q
                ->where(['Casts.delete_flag'=>'0']);
            }])->first();
        $masterCodesFind = array('industry','job_type','treatment','day');
        $credits = $this->MasterCodes->find()->where(['code_group' => 'credit']);
        $creditsHidden = $this->Util->getCredit($shop,$credits);
        $treatments = $this->MasterCodes->find()->where(['code_group' => 'treatment']);
        $treatmentHidden = $this->Util->getTreatment($shop,$treatments);
        $masterCodeHidden = array('credit'=>json_encode($creditsHidden));
        $masterCodeHidden = array_merge($masterCodeHidden, array('treatment'=>json_encode($treatmentHidden)));
        $selectList = $this->Util->getSelectList($masterCodesFind,$this->MasterCodes,true);

        $this->set(compact('shop','credits','masterCodeHidden','selectList', 'activeTab', 'ajax'));
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
            $this->viewVars['infoArray']['dir_path']);
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
            ->where(['id' => $this->viewVars['infoArray']['shop_id']])->first();
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
        //new Folder(WWW_ROOT. $this->viewVars['infoArray']['dir_path'], true, 0755);
        $dirPath = preg_replace('/(^\/)/', '', 
            $this->viewVars['infoArray']['dir_path']);
         $dir = new Folder(WWW_ROOT. $this->viewVars['infoArray']['dir_path'], true, 0755);
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
            ->where(['id' => $this->viewVars['infoArray']['shop_id']])->first();
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
     * トップ画像タブの処理
     *
     * @param [type] $id
     * @return void
     */
    public function editTopImage($id = null)
    {
        $delFlg = false; // 削除実行フラグ
        $shopTable = TableRegistry::get('Shops');
        $shop = $shopTable->find()->where(['owner_id' => $id])->first();
        $this->request->session()->write('activeTab', 'top-image');

    if ($this->request->is('ajax')) {
            $this->log($this,"debug");
            $resultMessage = '';
            $resultflg = true;
            $isException = false;
            $errors = array(); // バリデーションチェックが無い場合に空を一時的に作成
            $dir = realpath(WWW_ROOT. $this->viewVars['infoArray']['dir_path']);
            if (!$dir) {
                $dir = new Folder(WWW_ROOT. $this->viewVars['infoArray']['dir_path'], true, 0755);
            }
            // ファイルが入力されたとき
            if ($this->request->getData('top_image_file.name')) {
                $limitFileSize = 1024 * 1024;
                try {
                    $shop->top_image = $this->Util->file_upload($this->request->getData('top_image_file'),
                        ['name'=>$this->request->getData('file_before')], $dir, $limitFileSize);
                    // ファイル更新の場合は古いファイルは削除
                    if (isset($this->request->data["file_before"])) {
                        // ファイル名が同じ場合は削除を実行しない
                        if ($this->request->getData('file_before') != $shop->top_image) {
                            $file = new File($dir . DS . $this->request->data["file_before"]);
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
                    $resultMessage = $e->getMessage();
                    $resultflg = false;
                    $isException = true;
                }
            } else {
                // ファイルは入力されていないけど登録されているファイルがあるとき
                if (isset($this->request->data["file_before"])) {
                    $shop->top_image = $this->request->getData('file_before');
                }
            }
            // 例外があればsaveしない
            if (!$isException) {
                if ($shopTable->save($shop)) {
                    $this->log("save成功", "debug");
                    if ($delFlg == true) {
                        $resultMessage = RESULT_M['DELETE_SUCCESS'];
                    } else {
                        $resultMessage = RESULT_M['SIGNUP_SUCCESS'];
                    }
                } else {
                    if ($delFlg == true) {
                        $resultMessage = RESULT_M['DELETE_FAILED'];
                    } else {
                        $resultMessage = RESULT_M['SIGNUP_FAILED'];
                    }
                    $resultflg = false;
                }
            }

            $this->request->session()->write('exception', $isException);
            $this->request->session()->write('resultMessage', $resultMessage);
            $this->request->session()->write('resultflg', $resultflg);
            $this->request->session()->write('errors', $errors);
            $this->request->session()->write('ajax', true);
        }
        return $this->redirect($this->referer());
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
            ->where(['id' => $this->viewVars['infoArray']['shop_id']])->first();
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
            ->where(['id' => $this->viewVars['infoArray']['shop_id']])->first();
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
                ['shop_id' => $this->viewVars['infoArray']['shop_id'], 'status'=>0]
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
            ->where(['id' => $this->viewVars['infoArray']['shop_id']])
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
            $this->viewVars['infoArray']['dir_path'].PATH_ROOT['CAST'].DS.$this->request->getData('dir').DS);
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
                ['shop_id' => $this->viewVars['infoArray']['shop_id'], 'status' => 0 , 'delete_flag' => 1]
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
            ->where(['id' => $this->viewVars['infoArray']['shop_id']])
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
     * 店舗情報タブの処理
     *
     * @param [type] $id
     * @return void
     */
    public function editTenpo($id = null)
    {
        $addFlg = false; // 追加実行フラグ
        $shopsTable = TableRegistry::get('Shops');
        $entity = $shopsTable->newEntity();
        $this->request->session()->write('activeTab', 'tenpo');

        // 追加、編集時はバリデーションチェックするため、requestをエンティティにマッピングする
        if (isset($this->request->data["tenpo_edit"])) {
            $entity = $shopsTable->newEntity($this->request->getData());
        }
        if ($this->request->is('ajax')) {
            $resultMessage = '';
            $resultflg = true;
            $isException = false;
            $isSave = false;
            if (!$entity->errors()) {

                // 店舗情報削除の場合
                if (isset($this->request->data["tenpo_delete"])) {
                    $shop = $shopsTable->get($id);
                    if ($shopsTable->delete($shop)) {
                        $resultMessage = RESULT_M['DELETE_SUCCESS'];
                    } else {
                        $resultMessage = RESULT_M['DELETE_FAILED'];
                        $resultflg = false;
                    }
                } elseif (isset($this->request->data["tenpo_edit"])) {
                    // 店舗情報編集の場合
                    $shop = $shopsTable->find()->where(['id'
                        => $this->request->getData('tenpo_edit_id')])->first();

                    // 店舗情報内容をセット
                    $shop->name = $this->request->getData('name');
                    $shop->tel = $entity['tel'];
                    $shop->staff = $this->request->getData('staff');
                    $shop->bus_from_time = $this->request->getData('bus_from_time');
                    $shop->bus_to_time = $this->request->getData('bus_to_time');
                    $shop->bus_hosoku = $this->request->getData('bus_hosoku');
                    $shop->system = $this->request->getData('system');
                    $shop->credit = $this->request->getData('credit');
                    $shop->pref21 = $this->request->getData('pref21');
                    $shop->addr21 = $this->request->getData('addr21');
                    $shop->strt21 = $this->request->getData('strt21');
                }
                // saveするか判定する
                if (array_key_exists('tenpo_edit', $this->request->getData())) {
                    $isSave = true;
                }
                if ($isSave) {
                    if ($shopsTable->save($shop)) {
                        if ($addFlg == true) {
                            $resultMessage = RESULT_M['SIGNUP_SUCCESS'];
                        } else {
                            $resultMessage = RESULT_M['UPDATE_SUCCESS'];
                        }
                    } else {
                        if ($addFlg == true) {
                            $resultMessage = RESULT_M['SIGNUP_FAILED'];
                        } else {
                            $resultMessage = RESULT_M['UPDATE_FAILED'];
                        }
                        $resultflg = false;
                    }
                }
            } else {
                $resultflg = false;
            }
            $this->request->session()->write('exception', $isException);
            $this->request->session()->write('resultMessage', $resultMessage);
            $this->request->session()->write('resultflg', $resultflg);
            $this->request->session()->write('errors', $entity->errors());
            $this->request->session()->write('ajax', true);
        }
        return $this->redirect($this->referer());
    }

        /**
     * 求人情報タブの処理
     *
     * @param [type] $id
     * @return void
     */
    public function editJob($id = null)
    {
        $addFlg = false; // 追加実行フラグ
        $jobsTable = TableRegistry::get('Jobs');
        $entity = $jobsTable->newEntity();
        $this->request->session()->write('activeTab', 'job');

        // 追加、編集時はバリデーションチェックするため、requestをエンティティにマッピングする
        if (isset($this->request->data["job_edit"])) {
            $entity = $jobsTable->newEntity($this->request->getData());
        }
        if ($this->request->is('ajax')) {
            $resultMessage = '';
            $resultflg = true;
            $isException = false;
            $isSave = false;
            if (!$entity->errors()) {

                // 求人情報削除の場合
                if (isset($this->request->data["job_delete"])) {
                    $job = $jobsTable->get($id);
                    if ($jobsTable->delete($job)) {
                        $resultMessage = RESULT_M['DELETE_SUCCESS'];
                    } else {
                        $resultMessage = RESULT_M['DELETE_FAILED'];
                        $resultflg = false;
                    }
                } elseif (isset($this->request->data["job_edit"])) {
                    // 求人情報編集の場合
                    $job = $jobsTable->get($id);

                    // // 求人情報内容をセット
                    $job->industry = $entity['industry'];
                    $job->job_type = $entity['job_type'];
                    $job->work_from_time = $entity['work_from_time'];
                    $job->work_to_time = $entity['work_to_time'];
                    $job->work_time_hosoku = $entity['work_time_hosoku'];
                    $job->from_age = $entity['from_age'];
                    $job->to_age = $entity['to_age'];
                    $job->qualification_hosoku = $entity['qualification_hosoku'];
                    $job->holiday = $entity['holiday'];
                    $job->holiday_hosoku = $entity['holiday_hosoku'];
                    $job->treatment = $entity['treatment'];
                    $job->pr = $entity['pr'];
                    $job->tel1 = $entity['tel1'];
                    $job->tel2 = $entity['tel2'];
                    // TODO: isUnique()は、他のカラムが空文字でもuniqueチェックを行うため、
                    // 重複エラーになるためスルーする。
                    $job->email = $this->Util->ifnullString($entity['email']);
                    $job->lineid = $entity['lineid'];
                    // 求人情報内容をセット
                    // $job->industry = $this->request->getData('industry');
                    // $job->job_type = $this->request->getData('job_type');
                    // $job->work_from_time = $this->request->getData('work_from_time');
                    // $job->work_to_time = $this->request->getData('work_to_time');
                    // $job->work_time_hosoku = $this->request->getData('work_time_hosoku');
                    // $job->from_age = $this->request->getData('from_age');
                    // $job->to_age = $this->request->getData('to_age');
                    // $job->qualification_hosoku = $this->request->getData('qualification_hosoku');
                    // $job->holiday = $entity['holiday'];
                    // $job->holiday_hosoku = $this->request->getData('holiday_hosoku');
                    // $job->treatment = $this->request->getData('treatment');
                    // $job->pr = $this->request->getData('pr');
                    // $job->tel1 = $this->request->getData('tel1');
                    // $job->tel2 = $this->request->getData('tel2');
                    // $job->email = $this->request->getData('email');
                    // $job->lineid = $this->request->getData('lineid');

                }
                // saveするか判定する
                if (array_key_exists('job_edit', $this->request->getData())) {
                    $isSave = true;
                }
                if ($isSave) {
                    try {
                        if ($jobsTable->saveOrFail($job)) {
                            if ($addFlg == true) {
                                $resultMessage = RESULT_M['SIGNUP_SUCCESS'];
                            } else {
                                $resultMessage = RESULT_M['UPDATE_SUCCESS'];
                            }
                        } else {
                            if ($addFlg == true) {
                                $resultMessage = RESULT_M['SIGNUP_FAILED'];
                            } else {
                                $resultMessage = RESULT_M['UPDATE_FAILED'];
                            }
                            $resultflg = false;
                        }
                    } catch (\Cake\ORM\Exception\PersistenceFailedException $e) {
                        echo $e;
                        //  echo $e->getEntity();
                            return '500(Save Failed)';
                    }
                }
            } else {
                $resultflg = false;
            }
            $this->request->session()->write('exception', $isException);
            $this->request->session()->write('resultMessage', $resultMessage);
            $this->request->session()->write('resultflg', $resultflg);
            $this->request->session()->write('errors', $entity->errors());
            $this->request->session()->write('ajax', true);
        }
        return $this->redirect($this->referer());
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
