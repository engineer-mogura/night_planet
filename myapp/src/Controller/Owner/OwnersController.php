<?php
namespace App\Controller\Owner;

use Cake\ORM\TableRegistry;
use Cake\ORM\Query;
use Token\Util\Token;
use Cake\Mailer\MailerAwareTrait;
use Cake\Mailer\Email;
use Cake\Error\Debugger;
use Cake\Core\Configure;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;
use Cake\Datasource\ConnectionManager;

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
        // 登録ボタン押下時
        if ($this->request->is('post')) {
            // バリデーションは新規登録用を使う。
            $owner = $this->Owners->newEntity( $this->request->getData(),['validate' => 'ownerRegistration']);

            if(!$owner->errors()) {

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

                foreach ($owner->errors() as $key1 => $value1) {
                    foreach ($value1 as $key2 => $value2) {
                        $this->Flash->error($value2);
                    }
                }
            }
        }
        // エリア、ジャンルリスト生成
        $params1 = array("area");
        $params2 = array("genre");
        //条件文を作成
        $condition1 = array(
            'conditions' => array('master_codes.delete_flag is' => null,
                                  'master_codes.code_group in' => $params1),
                                  'keyField' => 'code',
                                  'valueField' => 'code_name',
            'order' => array('sort' => 'ASC'));
        $area = $this->MasterCodes->find('list',$condition1);
        //条件文を作成
        $condition2 = array(
            'conditions' => array('master_codes.delete_flag is' => null,
                                  'master_codes.code_group in' => $params2),
                                  'keyField' => 'code',
                                  'valueField' => 'code_name',
            'order' => array('sort' => 'ASC'));
        $genre = $this->MasterCodes->find('list',$condition2);
        $area = $area->toArray();
        $genre = $genre->toArray();

        $this->set(compact('owner','area','genre'));
        $this->render();
    }

    public function login()
    {
        $OwnersTable = TableRegistry::get('Owners');
        if ($this->request->is('post')) {
            // バリデーションはログイン用を使う。
            $owner = $this->Owners->newEntity( $this->request->getData(),['validate' => 'ownerLogin']);

            if(!$owner->errors()) {

                $this->log($this->request->getData("remember_me"),"debug");
                $owner = $this->Auth->identify();
                if ($owner) {

                  $this->Auth->setUser($owner);
                  return $this->redirect($this->Auth->redirectUrl());
                }

                $this->Flash->error(Configure::read('ecm.001'));
            } else {
                debug("this->request->getData()");
                debug($this->request->getData());
                foreach ($owner->errors() as $key1 => $value1) {
                    foreach ($value1 as $key2 => $value2) {
                        $this->Flash->error($value2);
                    }
                }
            }
        } else {
            $owner = $OwnersTable->newEntity();
        }
        $this->set('owner', $owner);
    }

    public function logout()
    {
        $this->Flash->success(Configure::read('cm.002'));
        return $this->redirect($this->Auth->logout());
    }

    public function verify($token)
    {
        $owner = $this->Owners->get(Token::getId($token));
        if (!$owner->tokenVerify($token)) {

            $this->Flash->success(Configure::read('irm.053'));
            return $this->redirect(['action' => 'signup']);
        }
        if ($owner->status == 0 ) {
            // 本登録をもってオーナー用のディレクトリを掘る
            $path = 'img/'.$owner->area.'/'.$owner->genre.'/';
            // TODO scandirは、リストがないと、falseだけじゃなく
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
                $owner->dir = $nextDir;
                $owner->status = 1;
                $shop = $this->Shops->newEntity();
                $shop->owner_id = $owner->id;
                $result = true;
                if(!$this->Owners->save($owner)) {
                    $this->Flash->error('オーナーテーブルの更新に失敗した。');
                    $result = false;
                }
                if(!$this->Shops->save($shop)) {
                    $this->Flash->error('ショップテーブルの登録に失敗した。');
                    $result = false;
                }
                if($result) {
                    // 成功: commit
                    $connection->commit();
                    // commit出来たらディレクトリを掘る
                    $dir = new Folder(WWW_ROOT.$path.$nextDir, true, 0755);
                    // ユーザーステータスを本登録にする。(statusカラムを本登録に更新する)
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

    public function index()
    {
        $shop = $this->Owners->find('all')->where(['Owners.id' => $this->request->getSession()->read('Auth.Owner.id')])->contain(['Shops.Coupons'])->first();

        $this->set(compact('shop'));
        $this->render();
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

                $this->set(compact('shop'));
            }
        }
    }


}
