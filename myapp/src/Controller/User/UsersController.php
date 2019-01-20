<?php
namespace App\Controller\User;

use Token\Util\Token;
use Cake\Mailer\MailerAwareTrait;
use Cake\Error\Debugger;
use Cake\Mailer\Email;

/**
* Users Controller
*
* @property \App\Model\Table\UsersTable $Users
*
* @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
*/
class UsersController extends AppController
{
    use MailerAwareTrait;

    public function signup()
    {
        $this->loadModel('Users');
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {

            $user = $this->Users->patchEntity($user, $this->request->getData());

            if ($this->Users->save($user)) {
/*                    $this->log('save','debug');
                $this->log($this,'debug');
                $email = new Email('default');
                $email->from(['okiyoru1@gmail.com' => 'My Site'])
                    ->to('8---30@ezweb.ne.jp')
                    ->cc('8---30@ezweb.ne.jp')
                    ->subject('About')
                    ->send('My message');
                $this->log($email,'debug');*/
                $this->getMailer('User')->send('registration', [$user]);
            }
        } else {
            $this->set(compact('user'));
        }
        $this->render('/Registe/User/signup');

    }

    public function verify($token)
    {
        $this->loadModel('Users');
        $user = $this->Users->get(Token::getId($token));
        $this->log($user,"debug");
        if (!$user->tokenVerify($token)) {
            $this->Flash->success('認証に失敗しました。もう一度登録しなおしてください。');
            return $this->redirect(['action' => 'user']);

        }
        if ($user->status == 0 ) {
            $user->status = 1;
            // ユーザーステータスを本登録にする。(statusカラムを本登録に更新する)
            $this->Users->save($user);
            $this->Flash->success('認証完了しました。ログインしてください。');
            return $this->redirect(['action' => 'user']);
        }
        $this->Flash->success('すでに登録されてます。ログインしてください。');
        return $this->redirect(['action' => 'user']);
    }

}
