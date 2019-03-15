<?php
namespace App\Controller\Owner;

use Cake\Event\Event;
use Cake\I18n\Date;
use RuntimeException;
use Cake\Core\Configure;
use Cake\Error\Debugger;
use Cake\Filesystem\File;
use Cake\Filesystem\Folder;
use Cake\ORM\TableRegistry;

/**
 * Controls the data flow into shops object and updates the view whenever data changes.
 */
class ShopsController extends AppController
{
    public function beforeFilter(Event $event) {
        // AppController.beforeFilterをコールバック
        parent::beforeFilter($event);
        //　オーナーに関する情報をセット
        if(!is_null($user = $this->Auth->user())){
            $this->set('infoArray', $this->Util->getItem());
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
            $this->set('infoArray', $this->Util->getItem());
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
                        $errors .= $value2."<br/>";
                        //$this->Flash->error($value2);
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

            $shop = $this->Shops->find()->where(['owner_id' => $id])->contain(['Coupons']);
            $credits = $this->MasterCodes->find()->where(['code_group' => 'credit']);
            $creditsHidden = json_encode($this->Util->getCredit($shop,$credits));
            $this->set(compact('shop', 'credits','creditsHidden', 'activeTab', 'ajax'));
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
        $shop = $this->Shops->find()->where(['owner_id' => $id])->contain(['Coupons']);
        $credits = $this->MasterCodes->find()->where(['code_group' => 'credit']);
        $creditsHidden = json_encode($this->Util->getCredit($shop,$credits));
        $this->set(compact('shop', 'credits','creditsHidden', 'activeTab', 'ajax'));
        $this->render();
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
            if (isset($this->request->data["file_delete"])) {
                try {
                    $this->log("file_delete", "debug");
                    $del_file = new File($dir . "/" .$this->request->getData('file_before'));
                    // ファイル削除処理実行
                    if ($del_file->delete()) {
                        $shop->top_image = "";
                        $delFlg = true;
                    } else {
                        throw new RuntimeException('ファイルの削除ができませんでした。');
                    }
                } catch (RuntimeException $e) {
                    $resultMessage = $e->getMessage();
                    $resultflg = false;
                    $isException = true;
                }
            } else {
                // ファイルが入力されたとき
                if ($this->request->getData('top_image_file.name')) {
                    $limitFileSize = 1024 * 1024;
                    try {
                        $shop->top_image = $this->file_upload($this->request->getData('top_image_file'), $dir, $limitFileSize);
                        // ファイル更新の場合は古いファイルは削除
                        if (isset($this->request->data["file_before"])) {
                            // ファイル名が同じ場合は削除を実行しない
                            if ($this->request->getData('file_before') != $shop->top_image) {
                                $del_file = new File($dir . "/" . $this->request->data["file_before"]);
                                if (!$del_file->delete()) {
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
            }
            // 例外があればsaveしない
            if (!$isException) {
                if ($shopTable->save($shop)) {
                    $this->log("save成功", "debug");
                    if ($delFlg == true) {
                        $resultMessage = Configure::read('irm.003');
                    } else {
                        $resultMessage = Configure::read('irm.001');
                    }
                } else {
                    if ($delFlg == true) {
                        $resultMessage = Configure::read('irm.052');
                    } else {
                        $resultMessage = Configure::read('irm.050');
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
     * キャッチコピータブの処理
     *
     * @param [type] $id
     * @return void
     */
    public function editCatch($id = null)
    {
        $delFlg = false; // 削除実行フラグ
        $shopTable = TableRegistry::get('Shops');
        $entity = $shopTable->newEntity($this->request->getData());

        $this->request->session()->write('activeTab', 'catch');

        // 新規登録、編集時はバリデーションチェックするため、requestをエンティティにマッピングする
        if (isset($this->request->data["catch_edit"])) {
            $entity = $couponsTable->newEntity($this->request->getData());
        }
        if ($this->request->is('ajax')) {
            $resultMessage = '';
            $resultflg = true;
            $isException = false;
            $isSave = false;
            if (!$entity->errors()) {
                $shop = $shopTable->find()->where(['owner_id' => $id])->first();

                // キャッチコピー削除のとき
                if (isset($this->request->data["catch_delete"])) {
                    // キャッチコピー削除
                    $shop->catch = "";
                    $delFlg = true;
                } elseif ($this->request->data["catch"]) {
                    // キャッチコピーが入力されたとき
                    // キャッチコピーを入れる
                    $shop->catch = $this->request->getData('catch');
                }
                if ($shopTable->save($shop)) {
                    if ($delFlg == true) {
                        $resultMessage = Configure::read('irm.003');
                    } else {
                        $resultMessage = Configure::read('irm.001');
                    }
                } else {
                    if ($delFlg == true) {
                        $resultMessage = Configure::read('irm.052');
                    } else {
                        $resultMessage = Configure::read('irm.050');
                    }
                    $resultflg = false;
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
        $this->request->session()->write('activeTab', 'catch');
        return $this->redirect($this->referer());
    }

    /**
     * クーポンタブの処理
     *
     * @param [type] $id
     * @return void
     */
    public function editCoupon($id = null)
    {
        $addFlg = false; // 追加実行フラグ
        $couponsTable = TableRegistry::get('Coupons');
        $entity = $couponsTable->newEntity();
        $this->request->session()->write('activeTab', 'coupon');

        // 追加、編集時はバリデーションチェックするため、requestをエンティティにマッピングする
        if (isset($this->request->data["coupon_edit"]) || isset($this->request->data["coupon_add"])) {
            $entity = $couponsTable->newEntity($this->request->getData());
        }
        if ($this->request->is('ajax')) {
            $resultMessage = '';
            $resultflg = true;
            $isException = false;
            $isSave = false;
            if (!$entity->errors()) {

                // クーポン削除の場合
                if (isset($this->request->data["coupon_delete"])) {
                    $coupon = $couponsTable->get($id);
                    if ($couponsTable->delete($coupon)) {
                        $resultMessage = Configure::read('irm.003');
                    } else {
                        $resultMessage = Configure::read('irm.052');
                        $resultflg = false;
                    }
                } elseif (isset($this->request->data["coupon_edit"])) {
                    // クーポン編集の場合
                    $coupon = $couponsTable->find()->where(['id'
                        => $this->request->getData('coupon_edit_id')])->first();

                    // クーポン内容をセット
                    $coupon->status = $this->request->getData('status');
                    $coupon->from_day = $this->request->getData('from_day');
                    $coupon->to_day = $this->request->getData('to_day');
                    $coupon->title = $this->request->getData('title');
                    $coupon->content = $this->request->getData('content');
                } elseif (isset($this->request->data["coupon_add"])) {
                    // クーポン追加の場合
                    $shop = $this->Shops->find()->where(['owner_id'
                     => $this->request->getSession()->read('Auth.Owner.id')])->first();

                    // クーポン内容をセット
                    $coupon = $couponsTable->newEntity();

                    $coupon->shop_id = $shop->id;
                    $coupon->status = $this->request->getData('status');
                    $coupon->from_day = $this->request->getData('from_day');
                    $coupon->to_day = $this->request->getData('to_day');
                    $coupon->title = $this->request->getData('title');
                    $coupon->content = $this->request->getData('content');
                    $addFlg = true;
                } elseif (isset($this->request->data["coupon_switch"])) {
                    // クーポンステータスの場合
                    $coupon = $couponsTable->find()->where(['id' => $id])->first();

                    // クーポンステータスをセット
                    $coupon->status = $this->request->getData('coupon_switch');
                }
                // saveするか判定する
                if (array_key_exists('coupon_add', $this->request->getData()) ||
                   array_key_exists('coupon_edit', $this->request->getData()) ||
                   array_key_exists('coupon_switch', $this->request->getData())) {
                    $isSave = true;
                }
                if ($isSave) {
                    if ($couponsTable->save($coupon)) {
                        if ($addFlg == true) {
                            $resultMessage = Configure::read('irm.001');
                        } else {
                            $resultMessage = Configure::read('irm.002');
                        }
                    } else {
                        if ($addFlg == true) {
                            $resultMessage = Configure::read('irm.050');
                        } else {
                            $resultMessage = Configure::read('irm.051');
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
                        $resultMessage = Configure::read('irm.003');
                    } else {
                        $resultMessage = Configure::read('irm.052');
                        $resultflg = false;
                    }
                } elseif (isset($this->request->data["tenpo_edit"])) {
                    // 店舗情報編集の場合
                    $shop = $shopsTable->find()->where(['id'
                        => $this->request->getData('tenpo_edit_id')])->first();

                    // 店舗情報内容をセット
                    $shop->name = $this->request->getData('name');
                    $shop->tel = $this->request->getData('tel');
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
                            $resultMessage = Configure::read('irm.001');
                        } else {
                            $resultMessage = Configure::read('irm.002');
                        }
                    } else {
                        if ($addFlg == true) {
                            $resultMessage = Configure::read('irm.050');
                        } else {
                            $resultMessage = Configure::read('irm.051');
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
     * Delete method
     *
     * @param string|null $id Shop id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $shop = $this->Shops->get($id);
        if ($this->Shops->delete($shop)) {
            $this->Flash->success(__('The shop has been deleted.'));
        } else {
            $this->Flash->error(__('The shop could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * ファイルアップロードの処理
     *
     * @param [type] $file
     * @param [type] $dir
     * @param [type] $limitFileSize
     * @return void
     */
    public function file_upload($file = null, $dir = null, $limitFileSize = 1024 * 1024)
    {
        try {
            // ファイルを保存するフォルダ $dirの値のチェック
            if ($dir) {
                debug("file_upload", "debug");
                debug($dir, "debug");
                if (!file_exists($dir)) {
                    throw new RuntimeException('指定のディレクトリがありません。');
                }
            } else {
                throw new RuntimeException('ディレクトリの指定がありません。');
            }

            // 未定義、複数ファイル、破損攻撃のいずれかの場合は無効処理
            if (!isset($file['error']) || is_array($file['error'])) {
                throw new RuntimeException('Invalid parameters.');
            }

            // エラーのチェック
            switch ($file['error']) {
                case 0:
                break;
                case UPLOAD_ERR_OK:
                break;
                case UPLOAD_ERR_NO_FILE:
                throw new RuntimeException('No file sent.');
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                throw new RuntimeException('Exceeded filesize limit.');
                default:
                throw new RuntimeException('Unknown errors.');
            }

            // ファイル情報取得
            $fileInfo = new File($file["tmp_name"]);

            // ファイルサイズのチェック
            if ($fileInfo->size() > $limitFileSize) {
                throw new RuntimeException('Exceeded filesize limit.');
            }

            // ファイルタイプのチェックし、拡張子を取得
            if (false === $ext = array_search(
                $fileInfo->mime(),
                ['jpg' => 'image/jpeg',
              'png' => 'image/png',
              'gif' => 'image/gif',],
                true
            )) {
                throw new RuntimeException('Invalid file format.');
            }

            // ファイル名の生成
//            $uploadFile = $file["name"] . "." . $ext;
            $uploadFile = sha1_file($file["tmp_name"]) . "." . $ext;

            // ファイルの移動
            if (!@move_uploaded_file($file["tmp_name"], $dir . "/" . $uploadFile)) {
                throw new RuntimeException('Failed to move uploaded file.');
            }

            // 処理を抜けたら正常終了
//            echo 'File is uploaded successfully.';
        } catch (RuntimeException $e) {
            throw $e;
        }
        return $uploadFile;
    }
}
