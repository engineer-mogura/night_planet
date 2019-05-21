<?php
namespace App\Controller\User;

use Token\Util\Token;
use Cake\Mailer\MailerAwareTrait;
use Cake\Error\Debugger;
use Cake\Mailer\Email;
use Cake\ORM\TableRegistry;

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

    public function top()
    {
        $masterCodesFind = array('area','genre');
        $selectList = $this->Util->getSelectList($masterCodesFind, $this->MasterCodes, false);
        $this->set(compact('page', 'subpage', 'selectList'));
    }

    public function search()
    {
        if ($this->request->is('ajax')) {

            $columns = array('Shops.name', 'Shops.catch'); // like条件用
            $shops = $this->getShopList($this->request->getQuery(), $columns);
            // 検索ページからの場合は、結果のみを返却する
            $this->confReturnJson(); // json返却用の設定
            $this->response->body(json_encode($shops));
            return;

        }
        $shops = array(); // 店舗情報格納用
        // トップページからの遷移の場合
        if ($referer = (($this->referer()) == "http://okiyoru.local/") ||
            /** スマホデバグ用 */(($this->referer()) == "http://192.168.33.10/")) {
            $columns = array('Shops.name', 'Shops.catch'); // like条件用
            $shops = $this->getShopList($this->request->getQuery(), $columns);
            // 検索条件を取得し、画面側でselectedする
            $conditionSelected = $this->request->getQuery();
        }
        $masterCodesFind = array('area','genre');
        $selectList = $this->Util->getSelectList($masterCodesFind, $this->MasterCodes, false);
        $this->set(compact('shops', 'selectList','conditionSelected'));
        $this->render();
    }

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

                $this->Flash->error(RESULT_M['FRAUD_INPUT_FAILED']);
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

        $this->Flash->success(COMMON_M['LOGGED_OUT']);
        return $this->redirect($this->Auth->logout());
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

    /**
     * ショップテーブルから検索条件による店舗情報を取得する
     *
     * @param array $validate
     * @return void
     */
    public function getShopList($requestData, $columns)
    {
        $query = $this->Shops->find();
        $findArray = array(); // 検索条件セット用
        foreach($requestData as $key => $findData) {
            // リクエストデータが[key_word]かつ値が空じゃない場合
            if (($key == 'key_word') && ($findData !== "")) {
                foreach ($columns as $key => $value) {
                    $query->orWhere(function ($exp, $q) use ($value, $findData) {
                        $exp->like($value, '%'.$findData.'%');
                        return $exp;
                    });
                }
            } else {
                if($findData !== "") {
                    //$findArray[] = ['Shops.'.$key => $findData];
                    $query->where(['Shops.'.$key => $findData]);
                }
            }
        }
        return $query->toArray();
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
