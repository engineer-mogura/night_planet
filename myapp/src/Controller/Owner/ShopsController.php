<?php
namespace App\Controller\Owner;

use Cake\ORM\TableRegistry;
use Cake\Error\Debugger;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;
use RuntimeException;
use Cake\Core\Configure;

/**
 * Shops Controller
 *
 * @property \App\Model\Table\ShopsTable $Shops
 *
 * @method \App\Model\Entity\Shop[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ShopsController extends AppController
{


    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index($id = null)
    {
        if(isset($this->request->query["targetEdit"])){
            $targetEdit = $this->request->getQuery("targetEdit");
            $shop = $this->Shops->find()->where(['owner_id' => $id]);
            $this->set(compact('shop','targetEdit'));
        }

    }

    /**
     * View method
     *
     * @param string|null $id Shop id.
     * @return \Cake\Http\Response|vpopmail_del_domain(domain)
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
/*    public function view($id = null)
    {
        $shop = $this->Shops->get($id, [
            'contain' => ['Owners']
        ]);

        $this->set('shop', $shop);
    }*/

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $shop = $this->Shops->newEntity();
        if ($this->request->is('post')) {
            $shop = $this->Shops->patchEntity($shop, $this->request->getData());
            $this->log($this->request->data["file"],"debug");
            if ($this->Shops->save($shop)) {
                $this->Flash->success(__('The shop has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The shop could not be saved. Please, try again.'));
        }
        $owners = $this->Shops->Owners->find('list', ['limit' => 200]);
        $this->set(compact('shop', 'owners'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Shop id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function editTopImage($id = null)
    {
        $shopTable = TableRegistry::get('Shops');
        $shop = $shopTable->find()->where(['owner_id' => $id])->first();
        if ($this->request->is(['patch', 'post', 'put'])) {
            $dir = realpath(WWW_ROOT. $this->viewVars['infoArray']['imgPath']);
            if (!$dir) {
                $dir = new Folder(WWW_ROOT. $this->viewVars['infoArray']['imgPath'], true, 0755);
            }
            if(isset($this->request->data["file_delete"])){
                try {
                    $del_file = new File($dir . "/" .$this->request->data["file_before"]);
                    // ファイル削除処理実行
                    if($del_file->delete()) {
                        $shop->top_image = "";
                    } else {
                        $shop->top_image = $this->request->data["file_before"];
                        throw new RuntimeException('ファイルの削除ができませんでした.');
                    }
                } catch (RuntimeException $e){
                    $this->Flash->error(__($e->getMessage()));
                }
            } else {
                // ファイルが入力されたとき
                if($this->request->data["file"]["name"]){
                    $limitFileSize = 1024 * 1024;
                    try {
                        $shop->top_image = $this->file_upload($this->request->data['file'], $dir, $limitFileSize);
                        // ファイル更新の場合は古いファイルは削除
                        if (isset($this->request->data["file_before"])){
                            // ファイル名が同じ場合は削除を実行しない
                            if ($this->request->data["file_before"] != $shop->top_image){
                                $del_file = new File($dir . "/" . $this->request->data["file_before"]);
                                if(!$del_file->delete()) {
                                    $this->log($this->request->data["file_before"],LOG_DEBUG);
                                }
                            }
                        }

                    } catch (RuntimeException $e){
                        // アップロード失敗の時、既登録ファイルがある場合はそれを保持
                        if (isset($this->request->data["file_before"])){
                            $shop->top_image = $this->request->data["file_before"];
                        }
                        $this->Flash->error(__('ファイルのアップロードができませんでした.'));
                        $this->Flash->error(__($e->getMessage()));
                    }
                } else {
                    // ファイルは入力されていないけど登録されているファイルがあるとき
                    if (isset($this->request->data["file_before"])){
                        $shop->top_image = $this->request->data["file_before"];
                    }
                }
            }

            if ($shopTable->save($shop)) {
                $this->Flash->success(__('登録に成功しました。'));
                return $this->redirect($this->referer());
            }
            $this->Flash->error(__('登録に失敗しました。もう一度登録しなおしてください。'));
        }

        $this->set(compact('shop'));
    }

    public function editCatch($id = null)
    {
        $shopTable = TableRegistry::get('Shops');
        $shop = $shopTable->find()->where(['owner_id' => $id])->first();
        $request_info = $this->request->getData();
        $catch = $this->request->getData('catch');
        $this->log("editCatch","debug");
        $this->log($catch,"debug");
        if ($this->request->is(['patch', 'post', 'put'])) {

            // キャッチコピー削除のとき
            if(isset($this->request->data["catch_delete"])){
                // キャッチコピー削除
                $shop->catch = "";
            } else if($this->request->data["catch"]) {
            // キャッチコピーが入力されたとき
                // キャッチコピーを入れる
                $shop->catch = $this->request->data['catch'];
            }
        }

            if ($shopTable->save($shop)) {
                $this->Flash->success(__('登録に成功しました。'));
                return $this->redirect($this->referer());
            }
            $this->Flash->error(__('登録に失敗しました。もう一度登録しなおしてください。'));

        $this->set(compact('shop'));
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

    public function file_upload ($file = null,$dir = null, $limitFileSize = 1024 * 1024){
        try {
            // ファイルを保存するフォルダ $dirの値のチェック
            if ($dir){
                debug("file_upload");
                debug($dir);
                if(!file_exists($dir)){
                    throw new RuntimeException('指定のディレクトリがありません。');
                }
            } else {
                throw new RuntimeException('ディレクトリの指定がありません。');
            }
 
            // 未定義、複数ファイル、破損攻撃のいずれかの場合は無効処理
            if (!isset($file['error']) || is_array($file['error'])){
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
            if (false === $ext = array_search($fileInfo->mime(),
                                              ['jpg' => 'image/jpeg',
                                               'png' => 'image/png',
                                               'gif' => 'image/gif',],
                                              true)){
                throw new RuntimeException('Invalid file format.');
            }
 
            // ファイル名の生成
//            $uploadFile = $file["name"] . "." . $ext;
            $uploadFile = sha1_file($file["tmp_name"]) . "." . $ext;
 
            // ファイルの移動
            if (!@move_uploaded_file($file["tmp_name"], $dir . "/" . $uploadFile)){
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
