<?php
namespace App\Controller\Owner;

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
use Cake\Datasource\ConnectionManager;

/**
 * Controls the data flow into shops object and updates the view whenever data changes.
 */
class CastsController extends CastsAppController
{
    public function beforeFilter(Event $event) {
        // AppController.beforeFilterをコールバック
        parent::beforeFilter($event);
        // キャストに関する情報をセット
        if(!is_null($user = $this->Auth->user())){
            $shop = $this->Shops->get($user['shop_id']);
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
                $id = $this->request->getSession()->read('Auth.Cast.id');
            }

            $cast = $this->Casts->find()->where(['id' => $id]);
            $masterCodesFind = array('time');
            $selectList = $this->Util->getSelectList($masterCodesFind,$this->MasterCodes,true);
    
            $this->set(compact('cast', 'activeTab','selectList', 'ajax'));
                $html = (String)$this->render('/Owner/Casts/index');

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
            $id = $this->request->getSession()->read('Auth.Cast.id');
        }
        $cast = $this->Casts->find()->where(['id' => $id]);
        $masterCodesFind = array('time','event');
        $selectList = $this->Util->getSelectList($masterCodesFind,$this->MasterCodes,true);

        $this->set(compact('cast', 'activeTab','selectList', 'ajax'));
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
                        $shop->top_image = $this->Util->file_upload($this->request->getData('top_image_file'), $dir, $limitFileSize);
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
     * カレンダーの処理
     *
     * @param [type] $id
     * @return void
     */
    public function editCalendar($id = null)
    {
        $addFlg = false; // 追加実行フラグ

        if ($this->request->is('ajax')) {
            $resultMessage = '';
            $resultflg = true;
            $isException = false;
            $isSave = false;
            $errors = array(); // バリデーションチェックが無い場合に空を一時的に作成


            // イベント削除の場合
            if ($this->request->data["crud_type"] == "delete") {
                $event = $this->Events->get($this->request->data["id"]);
                if ($this->Events->delete($event)) {
                    $resultMessage = Configure::read('irm.003');
                } else {
                    $resultMessage = Configure::read('irm.052');
                    $resultflg = false;
                }
            } elseif ($this->request->data["crud_type"] == "update") {
                // イベント編集の場合
                $event = $this->Events->find()->where(['id'
                => $this->request->getData('id')])->first();
                $event = $this->Events->patchEntity($event, $this->request->getData());

            } elseif ($this->request->data["crud_type"] == "create") {
                // イベント追加の場合
                $event = $this->Events->newEntity($this->request->getData());
                $addFlg = true;

            }
            // saveするか判定する
            if ($this->request->data["crud_type"] == "create" ||
                $this->request->data["crud_type"] == "update") {
                $isSave = true;
            }
            if ($isSave) {
                if ($this->Events->save($event)) {
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
            $cast = $this->Casts->get($id);
            $Columns = array(
                'Events.id','Events.title','Events.start','Events.end','Events.time_start',
                'Events.time_end','Events.all_day',
            );
            $event = $this->Events->find('all', array('fields' => $Columns))->where(['cast_id' => $id]);
            // jsonファイルに書き込む
            $path = WWW_ROOT. $this->viewVars['infoArray']['dir_path']."cast/".$cast["dir"];
            $dir = realpath($path);
            try {
                $dir = new Folder($dir."/");
                $file = new File($dir->path."calendar.json",true);
                $file->write(json_encode($event));

            } catch (RuntimeException $e) {
                $resultMessage = $e->getMessage();
                $resultflg = false;
                $isException = true;
            } finally {
                $file->close();
            }

            // $this->request->session()->write('exception', $isException);
            // $this->request->session()->write('resultMessage', $resultMessage);
            // $this->request->session()->write('resultflg', $resultflg);
            // $this->request->session()->write('errors', $errors);
            // $this->request->session()->write('ajax', true);
        }
        $this->viewBuilder()->autoLayout(false);
        $this->autoRender = false;
        $this->response->charset('UTF-8');
        $this->response->type('json');
        $this->response->body(json_encode($event));
        return;
    }

    public function login()
    {
        $CastsTable = TableRegistry::get('Casts');

        if ($this->request->is('post')) {

            // バリデーションはログイン用を使う。
            $cast = $this->Casts->newEntity( $this->request->getData(),['validate' => 'castLogin']);

            if(!$cast->errors()) {

                $this->log($this->request->getData("remember_me"),"debug");
                $cast = $this->Auth->identify();
                if ($cast) {
                  $this->Auth->setUser($cast);

                  return $this->redirect($this->Auth->redirectUrl());
                }

                $this->Flash->error(Configure::read('ecm.001'));
            } else {
                debug("this->request->getData()");
                debug($this->request->getData());
                foreach ($cast->errors() as $key1 => $value1) {
                    foreach ($value1 as $key2 => $value2) {
                        $this->Flash->error($value2);
                    }
                }
            }
        } else {
            $cast = $CastsTable->newEntity();
        }
        $this->set('cast', $cast);
    }

    public function logout()
    {
        // TODO: この自動ログインのコメントは削除予定。\node-link\cakephp-remember-meプラグインで対応できてる
        // // ※ $userにユーザー情報取得済み前提
        // // ユーザー自動ログイン管理テーブルからレコード削除
        // $entity = $this->Casts->get(['id' => $this->Auth->user('id')]);
        // $entity->remember_token = "";
        // if ($this->Casts->save($entity)) {
        //     // Cookie削除
        //     $this->response = $this->response->withExpiredCookie('AUTO_LOGIN');
        // }

        $this->Flash->success(Configure::read('cm.002'));
        return $this->redirect($this->Auth->logout());
    }


    public function verify($token)
    {
        $cast = $this->Casts->get(Token::getId($token));
        if (!$cast->tokenVerify($token)) {

            $this->Flash->success(Configure::read('irm.053'));
            return $this->redirect(['action' => 'signup']);
        }
        if ($cast->status == 0 ) {
            $shop = $this->Shops->get($cast->shop_id);
            // 本登録をもってキャスト用のディレクトリを掘る
            $path = 'img/'.$shop->area.'/'.$shop->genre.'/'.$shop->dir.'/cast/';
            // TODO: scandirは、リストがないと、falseだけじゃなく
            // warningも吐く。後で対応を考える。
            $dirList = scandir(WWW_ROOT. $path);

            // 指定フォルダ配下にあればラストの連番に+1していく
            if ($dirList) {
                $i = count(scandir(WWW_ROOT. $path));
                $lastDir = $dirList[$i - 1];
                $nextDir = sprintf("%05d",$lastDir + 1);

            } else {
            // 指定フォルダが空なら00001連番から振る
                $nextDir = sprintf("%05d", 1);

            }
            // パスが存在しなければディレクトリを掘ってDB登録
            if (!realpath(WWW_ROOT.$path.$nextDir)) {
                $connection = ConnectionManager::get('default');
                // トランザクション処理開始
                $connection->begin();
                $cast->dir = $nextDir;
                $cast->status = 1;
                $result = true;
                if(!$this->Casts->save($cast)) {
                    $this->Flash->error('キャストテーブルの更新に失敗した。');
                    $result = false;
                }
                if($result) {
                    // 成功: commit
                    $connection->commit();
                    // commit出来たらディレクトリを掘る
                    $dir = new Folder(WWW_ROOT.$path.$nextDir, true, 0755);
                    // ステータスを本登録にする。(statusカラムを本登録に更新する)
                    $this->Flash->success(Configure::read('irm.054'));
                    return $this->redirect(['action' => 'login']);
                } else {
                    // 失敗: rollback
                    $connection->rollback();
                    $this->Flash->error(Configure::read('irm.053'));
                    return $this->redirect(['action' => 'signup']);
                }
            } else {
                $this->Flash->error('既にディレクトリが存在します。');
                $this->Flash->error(Configure::read('irm.053'));
                return $this->redirect(['action' => 'signup']);
            }

        }
        $this->Flash->success(Configure::read('irm.055'));
        return $this->redirect(['action' => 'login']);
    }


}
