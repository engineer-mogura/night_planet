<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Util\Token;
use Cake\Mailer\MailerAwareTrait;
use Cake\Error\Debugger;
use Cake\Mailer\Email;
/**
* Owners Controller
*
* @property \App\Model\Table\OwnersTable $Owners
*
* @method \App\Model\Entity\Owner[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
*/
class RegisteController extends AppController
{
    use MailerAwareTrait;

    /**
    * Index method
    *
    * @return \Cake\Http\Response|void
    */
    public function owner()
    {
        $this->registration('owner');
    }

    /**
    * Index method
    *
    * @return \Cake\Http\Response|void
    */
    public function user()
    {
        $this->registration('user');
    }

    /**
    * Add method
    *
    * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
    */
    /*      public function add(){
    $user = $this->Users->newEntity();
    if ($this->request->is('post')) {
    $user = $this->Users->patchEntity($user, $this->request->getData());
    if ($this->Users->save($user)) {
    $this->Flash->success(__('The user has been saved.'));

    return $this->redirect(['action' => 'index']);
    }
    $this->Flash->error(__('The user could not be saved. Please, try again.'));
    }
    $this->set(compact('user'));
    }*/


    public function registration($whichUser)
    {
        if ($whichUser == 'owner') {
            $this->loadModel('Owners');
            $owner = $this->Owners->newEntity();
            if ($this->request->is('post')) {

                $owner = $this->Owners->patchEntity($owner, $this->request->getData());

                if ($this->Owners->save($owner)) {
                    $this->log('save','debug');
                    $this->log($this,'debug');
                    $email = new Email('default');
                    $email->from(['okiyoru1@gmail.com' => 'My Site'])
                        ->to('8---30@ezweb.ne.jp')
                        ->subject('About')
                        ->send('My message');
                    $this->log($email,'debug');
/*                    $this->getMailer('Owner')->send('registration', [$owner]);
*/                }
            }
            $this->set(compact('owner'));
            $this->render('/Registe/Owner/signup');

        } else if ($whichUser == 'user') {

            $this->loadModel('Users');
            $user = $this->Users->newEntity();
            if ($this->request->is('post')) {
                $user = $this->Users->patchEntity($user, $this->request->getData());

                if ($this->Users->save($user)) {
                    $this->getMailer('User')->send('registration', [$user]);
                }
            }
            $this->set(compact('users'));
            $this->render('/Registe/User/signup');

        }

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
