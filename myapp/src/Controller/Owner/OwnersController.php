<?php
namespace App\Controller\Owner;

use Token\Util\Token;
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
class OwnersController extends AppController
{
    use MailerAwareTrait;

    public function signup()
    {
        $this->loadModel('Owners');
        $owner = $this->Owners->newEntity();
        if ($this->request->is('post')) {

            $owner = $this->Owners->patchEntity($owner, $this->request->getData());

            if ($this->Owners->save($owner)) {
/*                    $this->log('save','debug');
                $this->log($this,'debug');
                $email = new Email('default');
                $email->from(['okiyoru1@gmail.com' => 'My Site'])
                    ->to('8---30@ezweb.ne.jp')
                    ->cc('8---30@ezweb.ne.jp')
                    ->subject('About')
                    ->send('My message');
                $this->log($email,'debug');*/
                $this->getMailer('Owner')->send('registration', [$owner]);
                $this->Flash->success('入力したアドレスにメールを送りました。URLをクリックし、認証を完了してください。今から１０分以内に完了しないと、やり直しになりますのでご注意ください。');
                return $this->redirect(['action' => 'login']);
            }
        } else {
            $this->set(compact('owner'));
        }
        $this->render('/owner/Owners/signup');

    }

    public function login()
    {
        if ($this->request->is('post')) {

            // TODO オートログイン機能は、後回し
            //$this->log($this->request->getData("auto_login"),"debug");
            $this->log($this->request->getData("remember_me"),"debug");
            $owner = $this->Auth->identify();
            if ($owner) {
              $this->Auth->setUser($owner);
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

    public function verify($token)
    {
        $this->loadModel('Owners');
        $owner = $this->Owners->get(Token::getId($token));
        $this->log("verify","debug");
        if (!$owner->tokenVerify($token)) {
            $this->log("tokenVerify","debug");
            $this->Flash->success('認証に失敗しました。もう一度登録しなおしてください。');
            return $this->redirect(['action' => 'signup']);

        }
        if ($owner->status == 0 ) {
            $this->log("status","debug");
            $owner->status = 1;
            // ユーザーステータスを本登録にする。(statusカラムを本登録に更新する)
            $this->Owners->save($owner);
            $this->Flash->success('認証完了しました。ログインしてください。');
            return $this->redirect(['action' => 'login']);
        }
        $this->Flash->success('すでに登録されてます。ログインしてください。');
        return $this->redirect(['action' => 'login']);
    }

    public function index()
    {
        $this->log($this->Owners,"debug");
        $owner = $this->paginate($this->Owners);
        $this->set(compact('owner'));
    }

        public function view($id = null)
    {

        if(isset($this->request->query["targetEdit"])){
            $targetEdit = $this->request->getQuery("targetEdit");
            $shop = $this->Owners->find('all')->contain(['Shops']);

            if ($targetEdit == 'topImage') {
                $this->paginate = [
                    'contain' => ['Shops']
                ];
                $this->log("shop","debug");
                $this->log($shop,"debug");
                $this->set(compact('shop'));
            }
        }
    }


}
