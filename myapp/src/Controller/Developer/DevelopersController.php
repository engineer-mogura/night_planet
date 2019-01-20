<?php
namespace App\Controller\Developer;

use Cake\Error\Debugger;
use Cake\Event\Event;

/**
 * Developers Controller
 *
 * @property \App\Model\Table\DevelopersTable $Developers
 *
 * @method \App\Model\Entity\Developer[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DevelopersController extends AppController
{

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        // ユーザーの登録とログアウトを許可します。
        // allow のリストに "login" アクションを追加しないでください。
        // そうすると AuthComponent の正常な機能に問題が発生します。
        $this->Auth->allow(['logout']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        if(isset($this->request->query["targetTable"])){
            $targetTable = $this->request->getQuery("targetTable");
            if ($targetTable == 'Developers') {
                $developers = $this->paginate($this->Developers);
                if (!$developers) {
                    $this->Flash->success(__('データがありません。'));
                }
                $itemName1 = '開発者';
                $itemName2 = $targetTable;
                $this->set(compact('developers','itemName1','itemName2'));

            } else if ($targetTable == 'Users') {
                $users = $this->paginate($this->Users);
                if (!$users) {

                    $this->Flash->success(__('データがありません。'));
                }
                $itemName1 = 'ユーザー';
                $itemName2 = $targetTable;
                $this->set(compact('users','itemName1','itemName2'));

            } else if ($targetTable == 'Owners') {
                $owners = $this->paginate($this->Owners);
                if (!$owners) {

                    $this->Flash->success(__('データがありません。'));
                }
                $itemName1 = 'オーナー';
                $itemName2 = $targetTable;
                $this->set(compact('owners','itemName1','itemName2'));

            }

        } else {
            $developers = $this->paginate($this->Developers);
            $this->set(compact('developers'));
        }

    }

    /**
     * View method
     *
     * @param string|null $id Developer id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        if(isset($this->request->query["targetTable"])){
            $targetTable = $this->request->getQuery("targetTable");
            if ($targetTable == 'Developers') {

                $developer = $this->Developers->get($id, [
                    'contain' => []
                ]);
                $itemName1 = '開発者';
                $itemName2 = $targetTable;
                $this->set(compact('developer','itemName1','itemName2'));

            } else if ($targetTable == 'Users') {

                $user = $this->Users->get($id, [
                    'contain' => []
                ]);
                $itemName1 = 'ユーザー';
                $itemName2 = $targetTable;
                $this->set(compact('user','itemName1','itemName2'));

            } else if ($targetTable == 'Owners') {

                $owner = $this->Owners->get($id, [
                    'contain' => []
                ]);
                $itemName1 = 'オーナー';
                $itemName2 = $targetTable;
                $this->set(compact('owner','itemName1','itemName2'));

            }

        }
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $targetTable = $this->request->getQuery("targetTable");
        if(isset($this->request->query["targetTable"])){
            if ($this->request->is('post')) {

                if ($targetTable == 'Developers') {
                    $developer = $this->Developers->newEntity();
                    $developer = $this->Developers->patchEntity($developer, $this->request->getData());
                    if ($this->Developers->save($developer)) {

                        $this->Flash->success(__('登録しました。'));
                        return $this->redirect(['action' => 'index']);
                    }

                } else if($targetTable == 'Users') {

                    $user = $this->Users->newEntity();
                    $user = $this->Users->patchEntity($user, $this->request->getData());
                    if ($this->Users->save($user)) {

                        $this->Flash->success(__('登録しました。'));
                        return $this->redirect(['action' => 'index']);
                    }

                } else if($targetTable == 'Owners') {

                    $owner = $this->Owners->newEntity();
                    $owner = $this->Owners->patchEntity($owner, $this->request->getData());
                    if ($this->Owners->save($owner)) {

                        $this->Flash->success(__('登録しました。'));
                        return $this->redirect(['action' => 'index']);
                    }

                }

                $this->Flash->error(__('登録できませんでした。やり直してください。'));
            } else {
                if ($targetTable == 'Developers') {

                    $itemName1 = 'developer';
                    $developer = $this->Developers->newEntity();
                    $this->set(compact('developer','itemName1'));
                } else if($targetTable == 'Users') {

                    $itemName1 = 'user';
                    $user = $this->Users->newEntity();
                    $this->set(compact('user','itemName1'));
                } else if($targetTable == 'Owners') {

                    $itemName1 = 'owner';
                    $owner = $this->Owners->newEntity();
                    $this->set(compact('owner','itemName1'));

                }

                $this->set(compact('developer'));
            }
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Developer id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $targetTable = $this->request->getQuery("targetTable");
        if ($targetTable == 'Developers') {

            $developer = $this->Developers->get($id, [
                'contain' => []
            ]);
        } else if($targetTable == 'Users') {
            $user = $this->Users->get($id, [
                'contain' => []
            ]);

        } else if($targetTable == 'Owners') {
            $owner = $this->Owners->get($id, [
                'contain' => []
            ]);
        }
        if(isset($this->request->query["targetTable"])){

            if ($this->request->is(['patch', 'post', 'put'])) {

                if ($targetTable == 'Developers') {

                    $developer = $this->Developers->patchEntity($developer, $this->request->getData());
                    if ($this->Developers->save($developer)) {
                        $this->Flash->success(__('保存しました。'));

                        return $this->redirect(['action' => 'index']);
                    }

                } else if($targetTable == 'Users') {

                    $user = $this->Users->patchEntity($user, $this->request->getData());
                    if ($this->Users->save($user)) {
                        $this->Flash->success(__('保存しました。'));

                        return $this->redirect(['action' => 'index']);
                    }

                } else if($targetTable == 'Owners') {

                    $owner = $this->Owners->patchEntity($owner, $this->request->getData());
                    if ($this->Owners->save($owner)) {
                        $this->Flash->success(__('保存しました。'));

                        return $this->redirect(['action' => 'index']);
                    }

                }
                $this->Flash->error(__('更新できませんでした。やり直してください。'));

            } else {

                if ($targetTable == 'Developers') {
                    $itemName1 = '開発者';
                    $itemName2 = 'developer';
                    $this->set(compact('developer','itemName1','itemName2'));
                } else if ($targetTable == 'Users') {

                    $itemName1 = 'ユーザー';
                    $itemName2 = 'user';
                    $this->set(compact('user','itemName1','itemName2'));
                } else if ($targetTable == 'Owners') {

                    $itemName1 = 'オーナー';
                    $itemName2 = 'owner';
                    $this->set(compact('owner','itemName1','itemName2'));
                }
            }

        }
    }
    /**
     * Delete method
     *
     * @param string|null $id Developer id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        if(isset($this->request->query["targetTable"])){
            $targetTable = $this->request->getQuery("targetTable");
            if ($targetTable == 'Developers') {

                $developer = $this->Developers->get($id);
                if ($this->Developers->delete($developer)) {
                    $this->Flash->success(__('削除しました。'));
                } else {
                    $this->Flash->error(__('削除できませんでした。やり直してください。'));
                }
            } else if($targetTable == 'Users') {

                $user = $this->Users->get($id);
                if ($this->Users->delete($user)) {
                    $this->Flash->success(__('削除しました。'));
                } else {
                    $this->Flash->error(__('削除できませんでした。やり直してください。'));
                }

            } else if($targetTable == 'Owners') {

                $owner = $this->Owners->get($id);
                if ($this->Owners->delete($owner)) {
                    $this->Flash->success(__('削除しました。'));
                } else {
                    $this->Flash->error(__('削除できませんでした。やり直してください。'));
                }

            }
        }
        return $this->redirect(['action' => 'index']);
    }

    public function login()
    {
        if ($this->request->is('post')) {
            $developer = $this->Auth->identify();
            if ($developer) {
              $this->Auth->setUser($developer);
              return $this->redirect($this->Auth->redirectUrl());
          }
          $this->Flash->error('ユーザー名またはパスワードが不正です。');
      }
  }

  public function logout()
  {
    $this->Flash->success('ログアウトしました。');
    return $this->redirect($this->Auth->logout());
}

}