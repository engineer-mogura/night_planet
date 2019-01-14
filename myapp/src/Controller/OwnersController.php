<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Util\Token;
use Cake\Mailer\MailerAwareTrait;

/**
 * Owners Controller
 *
 * @property \App\Model\Table\OwnersTable $Owners
 *
 * @method \App\Model\Entity\Owner[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class OwnersController extends AppController
{
    use MailerAwareTrait;
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $owners = $this->paginate($this->Owners);

        $this->set(compact('owners'));
    }

    /**
     * View method
     *
     * @param string|null $id Owner id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $owner = $this->Owners->get($id, [
            'contain' => []
        ]);

        $this->set('owner', $owner);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $owner = $this->Owners->newEntity();
        if ($this->request->is('post')) {
            $owner = $this->Owners->patchEntity($owner, $this->request->getData());
            if ($this->Owners->save($owner)) {
                $this->Flash->success(__('The owner has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The owner could not be saved. Please, try again.'));
        }
        $this->set(compact('owner'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Owner id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $owner = $this->Owners->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $owner = $this->Owners->patchEntity($owner, $this->request->getData());
            if ($this->Owners->save($owner)) {
                $this->Flash->success(__('The owner has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The owner could not be saved. Please, try again.'));
        }
        $this->set(compact('owner'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Owner id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $owner = $this->Owners->get($id);
        if ($this->Owners->delete($owner)) {
            $this->Flash->success(__('The owner has been deleted.'));
        } else {
            $this->Flash->error(__('The owner could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function registration()
    {
        $owner = $this->Owner->newEntity();
        if ($this->request->is('post')) {
            $owner = $this->Owner->patchEntity($owner, $this->request->getData());

            if ($this->Owner->save($owner)) {
                $this->getMailer('Owner')->send('registration', [$owner]);
            }
        }
        $this->set('user', $owner);
    }

    public function verify($token)
    {
        $owner = $this->Owners->get(Token::getId($token));
        if (!$owner->tokenVerify($token)) {
            throw new NotFoundException();
        }

        // ユーザーステータスを本登録にする。(statusカラムを本登録に更新する)
        $this->Owners->activate($owner);

        $this->Flash->success('認証完了しました。ログインしてください。');
        return $this->redirect(['action' => 'login']);
    }

}
