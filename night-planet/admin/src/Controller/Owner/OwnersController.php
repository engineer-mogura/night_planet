<?php
namespace App\Controller\Owner;

use Cake\Log\Log;
use Cake\Event\Event;
use Token\Util\Token;
use Cake\Mailer\Email;
use Cake\Filesystem\File;
use Cake\Filesystem\Folder;
use Cake\Mailer\MailerAwareTrait;
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

    public function beforeFilter(Event $event) {
        // AppController.beforeFilterをコールバック
        parent::beforeFilter($event);
        // オーナー用テンプレート
        $this->viewBuilder()->layout('ownerDefault');
        // オーナーに関する情報をセット
        if(!is_null($user = $this->Auth->user())){
            $owner = $this->Owners->get($user['id']);
            $this->set('userInfo', $this->Util->getOwnerInfo($owner));
        }
    }

    public function signup()
    {
        // レイアウトを使用しない
        $this->viewBuilder()->autoLayout(false);

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
                    $this->getMailer('Owner')->send('ownerRegistration', [$owner]);
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
        // レイアウトを使用しない
        $this->viewBuilder()->autoLayout(false);

        if ($this->request->is('post')) {

            // バリデーションはログイン用を使う。
            $owner = $this->Owners->newEntity( $this->request->getData(),['validate' => 'ownerLogin']);

            if(!$owner->errors()) {

                $this->log($this->request->getData("remember_me"),"debug");
                // 現在リクエスト中のユーザーを識別する
                $owner = $this->Auth->identify();
                if ($owner) {
                    // セッションにユーザー情報を保存する
                    $this->Auth->setUser($owner);
                    Log::info($this->Util->setAccessLog(
                        $owner, $this->request->params['action']), 'access');

                // TODO: 本来ログイン後は、元々のURLに飛ばしたい所だけど、固定でオーナーのトップ画面にする。
                // AuthComponent.loginRedirectでURLの固定が難しい。
                // 何かいい方法があれば...。
                //   $this->request->session()->delete('Auth.redirect');
                //   return $this->redirect($this->Auth->redirectUrl());
                    return $this->redirect(['action' => 'index']);
                }
                // ログイン失敗
                $this->Flash->error(RESULT_M['FRAUD_INPUT_FAILED']);
            } else {
                debug($this->request->getData());
                foreach ($owner->errors() as $key1 => $value1) {
                    foreach ($value1 as $key2 => $value2) {
                        $this->Flash->error($value2);
                    }
                }
            }
        } else {
            $owner = $this->Owners->newEntity();
        }
        $this->set('owner', $owner);
    }

    public function logout()
    {
        $auth = $this->request->session()->read('Auth.Owner');

        Log::info($this->Util->setAccessLog(
            $auth, $this->request->params['action']), 'access');

        // レイアウトを使用しない
        $this->viewBuilder()->autoLayout(false);
        $this->Flash->success(COMMON_M['LOGGED_OUT']);
        return $this->redirect($this->Auth->logout());
    }

    /**
     * トークンをチェックして不整合が無ければ
     * ディレクトリを掘り、オーナー、店舗、求人情報を登録する
     *
     * @param [type] $token
     * @return void
     */
    public function verify($token)
    {
        $owner = $this->Owners->get(Token::getId($token));
        // 以下でトークンの有効期限や改ざんを検証することが出来る
        if (!$owner->tokenVerify($token)) {
            $this->log($this->Util->setLog($owner
                , 'トークンの有効期限が切れたか、改ざんが行われた可能性があります。'));
            // 仮登録してるレコードを削除する
            $this->Owners->delete($owner);
            $this->Flash->success(RESULT_M['AUTH_FAILED']);
            return $this->redirect(['action' => 'signup']);
        }
        // 仮登録時点で仮登録フラグは立っていない想定。
        if ($owner->status != 0) {
            // すでに登録しているとみなし、ログイン画面へ
            $this->Flash->success(RESULT_M['REGISTERED_FAILED']);
            return $this->redirect(['action' => 'login']);
        }
        // 本登録をもってオーナー用のディレクトリを掘る
        $dir = new Folder( preg_replace('/(\/\/)/', '/',
            WWW_ROOT . PATH_ROOT['IMG'] . DS . PATH_ROOT['OWNER'] . DS) , true, 0755);

        // TODO: scandirは、リストがないと、falseだけじゃなく
        // warningも吐く。後で対応を考える。
        // 指定フォルダ配下にあればラストの連番に+1していく
        if (file_exists($dir->path)) {
            $dirArray = scandir($dir->path);
            for($i = 0; $i <= count($dirArray); $i++) {
                if(strpos($dirArray[$i],'.') !== false) {
                    unset($dirArray[$i]);
                }
            }
            $nextDir = sprintf("%05d",count($dirArray) + 1);

        } else {
        // 指定フォルダが空なら00001連番から振る
            $nextDir = sprintf("%05d", 1);

        }
        // コネクションオブジェクト取得
        $connection = ConnectionManager::get('default');
        // トランザクション処理開始
        $connection->begin();

        try{
            // パスが存在しなければディレクトリを掘ってDB登録
            if (realpath($dir->path.$nextDir)) {

                throw new RuntimeException('既にディレクトリが存在します。');
            }

            // オーナー情報セット
            $owner->dir = $nextDir; // 連番ディレクトリをセット
            $owner->status = 1; // 仮登録フラグを下げる
            // オーナー本登録
            if (!$this->Owners->save($owner)) {

                throw new RuntimeException('レコードの更新に失敗しました。');
            }

            // // 店舗情報セット
            // $shop = $this->Shops->newEntity();
            // $shop->owner_id = $owner->id;
            // $shop->area = $owner->area;
            // $shop->genre = $owner->genre;
            // $shop->dir = $owner->dir;
            // // 店舗登録
            // if(!$this->Shops->save($shop)) {

            //     throw new RuntimeException('レコードの登録に失敗しました。');
            // }

            // ディレクトリを掘る
            $dir = new Folder($dir->path.$nextDir, true, 0755);
            // コミット
            $connection->commit();

        } catch(RuntimeException $e) {
            // ロールバック
            $connection->rollback();
            $this->log($this->Util->setLog($owner, $e));
            // 仮登録してるレコードを削除する
            $this->Owners->delete($owner);
            $this->Flash->error(RESULT_M['AUTH_FAILED']);
            return $this->redirect('/entry/siginup');
        }
        // try {
        //     // ショップ情報を取得
        //     $shop = $this->Shops->find()
        //         ->where(['owner_id' => $owner->id])->first();
        //     // 求人情報セット
        //     $job = $this->Jobs->newEntity();
        //     $job->shop_id = $shop->id;
        //     // 求人登録
        //     if(!$this->Jobs->save($job)) {

        //         throw new RuntimeException('レコードの登録に失敗しました。');
        //     }
        // } catch(RuntimeException $e) {

        //     $this->log($this->Util->setLog($owner, $e));
        // }
        // 認証完了でログインページへ
        $this->Flash->success(RESULT_M['AUTH_SUCCESS']);
        return $this->redirect(['action' => 'login']);
    }

    public function index()
    {
        $shops = $this->Shops->newEntity();
        // 認証されてる場合
        if(!is_null($user = $this->Auth->user())){
            // オーナーに所属する全ての店舗を取得する
            $shops = $this->Shops->find('all')->where(['owner_id' => $user['id']]);
            // 店舗トップ画像を設定する
            foreach ($shops as $key => $shop) {
                $path = PATH_ROOT['IMG'].DS.$shop->area.DS.$shop->genre
                    .DS.$shop->dir.DS.PATH_ROOT['TOP_IMAGE'];
                $dir = new Folder(preg_replace('/(\/\/)/', '/'
                    , WWW_ROOT.$path), true, 0755);
                $files = array();
                $files = glob($dir->path.DS.'*.*');
                // ファイルが存在したら、画像をセット
                if(count($files) > 0) {
                    foreach( $files as $file ) {
                        $shop->set('top_image', DS.$path.DS.(basename($file)));
                    }
                } else {
                    // 共通トップ画像をセット
                    $shop->set('top_image', PATH_ROOT['NO_IMAGE02']);
                }
            }
        }
        //$val = $this->Analytics->getAnalytics();
        $this->set('shops', $shops);
        $this->render();
    }

    public function shopAdd()
    {

        // 登録ボタン押下時
        if ($this->request->is('post')) {
            // バリデーションは新規登録用を使う。
            $shop = $this->Shops->newEntity($this->request->getData());

            if (!$shop->errors()) {
                $shop = $this->Shops->patchEntity($shop, $this->request->getData());

                // 店舗用のディレクトリを掘る
                $dir = new Folder(preg_replace(
                    '/(\/\/)/',
                    '/',
                    WWW_ROOT . PATH_ROOT['IMG'] . DS . 
                    $this->request->getData('area') . DS . 
                    $this->request->getData('genre') . DS
                ), true, 0755);

                // TODO: scandirは、リストがないと、falseだけじゃなく
                // warningも吐く。後で対応を考える。
                // 指定フォルダ配下にあればラストの連番に+1していく
                if (file_exists($dir->path)) {
                    $dirArray = scandir($dir->path);
                    for ($i = 0; $i <= count($dirArray); $i++) {
                        if (strpos($dirArray[$i], '.') !== false) {
                            unset($dirArray[$i]);
                        }
                    }
                    $nextDir = sprintf("%05d", count($dirArray) + 1);
                } else {
                    // 指定フォルダが空なら00001連番から振る
                    $nextDir = sprintf("%05d", 1);
                }
                // コネクションオブジェクト取得
                $connection = ConnectionManager::get('default');
                // トランザクション処理開始
                $connection->begin();

                try {
                    // パスが存在しなければディレクトリを掘ってDB登録
                    if (realpath($dir->path.$nextDir)) {
                        throw new RuntimeException('既にディレクトリが存在します。');
                    }

                    // 店舗情報セット
                    $shop = $this->Shops->newEntity();
                    $shop->owner_id = $this->viewVars['userInfo']['id'];
                    $shop->area = $this->request->getData('area');
                    $shop->genre = $this->request->getData('genre');
                    $shop->dir = $nextDir;
                    // 店舗登録
                    if (!$this->Shops->save($shop)) {
                        throw new RuntimeException('レコードの登録に失敗しました。');
                    }

                    // ディレクトリを掘る
                    $dir = new Folder($dir->path.$nextDir, true, 0755);
                    // コミット
                    $connection->commit();
                } catch (RuntimeException $e) {
                    // ロールバック
                    $connection->rollback();
                    $this->log($this->Util->setLog($shop, $e));
                    // 仮登録してるレコードを削除する
                    $this->Owners->delete($shop);
                    $this->Flash->error(RESULT_M['SIGNUP_FAILED']);
                    return $this->redirect('/Owner/Owners/shop_add');
                }
                try {

                    // 求人情報セット
                    $job = $this->Jobs->newEntity();
                    $job->shop_id = $shop->id;
                    // 求人登録
                    if (!$this->Jobs->save($job)) {
                        throw new RuntimeException('レコードの登録に失敗しました。');
                    }
                } catch (RuntimeException $e) {
                    $this->log($this->Util->setLog($shop, $e));
                }
                // オーナートップページへ
                $this->Flash->success(RESULT_M['SIGNUP_SUCCESS']);
                return $this->redirect(['action' => 'index']);
            } else {
                foreach ($shop->errors() as $key1 => $value1) {
                    foreach ($value1 as $key2 => $value2) {
                        $this->Flash->error($value2);
                    }
                }
            }
        }
        $masterCodesFind = array('area','genre');
        $selectList = $this->Util->getSelectList($masterCodesFind, $this->MasterCodes, false);
        $this->set(compact('shop','selectList'));
        $this->render();
    }

    /**
     * プロフィール画面の処理
     *
     * @return void
     */
    public function profile()
    {
        $auth = $this->request->session()->read('Auth.Owner');
        $id = $auth['id']; // ユーザーID

        if ($this->request->is('ajax')) {

            $flg = true; // 返却フラグ
            $errors = ""; // 返却メッセージ
            $this->confReturnJson(); // responceがjsonタイプの場合の共通設定
            $message = RESULT_M['UPDATE_SUCCESS']; // 返却メッセージ

            // アイコン画像変更の場合
            if (isset($this->request->data["action_type"])) {

                $dirPath = preg_replace('/(\/\/)/', '/',
                    WWW_ROOT.$this->viewVars['userInfo']['profile_path']);

                $owner = $this->Owners->get($this->viewVars['userInfo']['id']);

                // ディクレトリ取得
                $dir = new Folder($dirPath, true, 0755);

                // ファイルは１つのみだけど配列で取得する
                $files = glob($dir->path.DS.'*.*');

                // 前のファイル取得
                $fileBefor = new File($files[0], true, 0755);

                $file = $this->request->data['image'];

                // ファイルが存在する、かつファイル名がblobの画像のとき
                if (!empty($file["name"]) && $file["name"] == 'blob') {
                    $limitFileSize = CAPACITY['MAX_NUM_BYTES_FILE'];
                    try {
                        // 前のファイルがある場合
                        if(file_exists($fileBefor->path) && count($files) > 0) {
                            // ロールバック用のファイルサイズチェック
                            if ($fileBefor->size() > CAPACITY['MAX_NUM_BYTES_FILE']) {
                                throw new RuntimeException('ファイルサイズが大きすぎます。');
                            }
                            // 一時ファイル作成
                            if (!$fileBefor->copy(preg_replace('/(\/\/)/', '/',
                                WWW_ROOT.$this->viewVars['userInfo']['tmp_path'].DS.$fileBefor->name))) {
                                throw new RuntimeException('バックアップに失敗しました。');
                            }
                            // 一時ファイル取得
                            $tmpFile = new File(preg_replace('/(\/\/)/', '/',
                                WWW_ROOT.$this->viewVars['userInfo']['tmp_path'].DS.$fileBefor->name));
                        }

                        $newImageName = $this->Util->file_upload($this->request->getData('image'),
                            ['name'=> $fileBefor->name ], $dir->path, $limitFileSize);

                        // ファイル更新の場合は古いファイルは削除
                        if (!empty($fileBefor->name)) {
                            // ファイル名が同じ場合は削除を実行しない
                            if ($fileBefor->name != $newImageName) {
                                // ファイル削除処理実行
                                if (!$fileBefor->delete()) {
                                    throw new RuntimeException('ファイルの削除ができませんでした。');
                                }
                                // ファイル削除フラグを立てる
                                $isRemoved = true;
                            }
                        }

                    } catch (RuntimeException $e) {
                        // ファイルを削除していた場合は復元する
                        if ($isRemoved) {
                            $tmpFile->copy($file->path);
                        }

                        // 一時ファイルがあれば削除する
                        if (isset($tmpFile) && file_exists($tmpFile->path)) {
                            $tmpFile->delete();// tmpファイル削除
                        }
                        $this->log($this->Util->setLog($auth, $e));
                        $flg = false;
                    }

                }
                // 例外が発生している場合にメッセージをセットして返却する
                    if (!$flg) {
                    $message = RESULT_M['SIGNUP_FAILED'];
                    $response = array(
                        'success' => $flg,
                        'message' => $message
                    );
                    $this->response->body(json_encode($response));
                    return;
                }

                // 一時ファイル削除
                if (file_exists($tmpFile->path)) {
                    $tmpFile->delete();
                }

            } else {
                // バリデーションはプロフィール変更用を使う。
                $owner = $this->Owners->patchEntity($this->Owners
                    ->get($id), $this->request->getData(), ['validate' => 'ownerRegistration']);
                // バリデーションチェック
                if ($owner->errors()) {
                    $flg = false;
                    // 入力エラーがあれば、メッセージをセットして返す
                    $message = $this->Util->setErrMessage($owner); // エラーメッセージをセット
                    $response = array(
                        'success' => $flg,
                        'message' => $message
                    );
                    $this->response->body(json_encode($response));
                    return;
                }
                try {
                    // レコード更新実行
                    if (!$this->Owners->save($owner)) {
                        throw new RuntimeException('レコードの更新ができませんでした。');
                    }
                } catch (RuntimeException $e) {
                    $this->log($this->Util->setLog($auth, $e));
                    $flg = false;
                    $message = RESULT_M['UPDATE_FAILED'];
                    $response = array(
                        'success' => $flg,
                        'message' => $message
                    );
                    $this->response->body(json_encode($response));
                    return;
                }
            }

            $icons = array();
            $dirPath = preg_replace('/(\/\/)/', '/',
                WWW_ROOT.$this->viewVars['userInfo']['profile_path']);

            // ディクレトリ取得
            $dir = new Folder($dirPath, true, 0755);

            // ファイルは１つのみだけど配列で取得する
            $files = glob($dir->path.DS.'*.*');

            foreach( $files as $file ) {
                $timestamp = date('Y/m/d H:i', filemtime($file));
                array_push($icons, array(
                    "file_path"=>$this->viewVars['userInfo']['profile_path'].DS.(basename( $file ))
                    ,"date"=>$timestamp));
            }

            $owner = $this->Owners->get($id);
            // 作成するセレクトボックスを指定する
            $masCodeFind = array('age');
            // セレクトボックスを作成する
            $selectList = $this->Util->getSelectList($masCodeFind, $this->MasterCodes, true);

            $this->set(compact('owner', 'selectList', 'icons'));
            $this->render('/Owner/Owners/profile');
            $response = array(
                'html' => $this->response->body(),
                'error' => $errors,
                'success' => $flg,
                'message' => $message
            );
            $this->response->body(json_encode($response));
            return;
        }

        $icons = array();
        $dirPath = preg_replace('/(\/\/)/', '/',
            WWW_ROOT.$this->viewVars['userInfo']['profile_path']);

        // ディクレトリ取得
        $dir = new Folder($dirPath, true, 0755);

        // ファイルは１つのみだけど配列で取得する
        $files = glob($dir->path.DS.'*.*');

        foreach( $files as $file ) {
            $timestamp = date('Y/m/d H:i', filemtime($file));
            array_push($icons, array(
                "file_path"=>$this->viewVars['userInfo']['profile_path'].DS.(basename( $file ))
                ,"date"=>$timestamp));
        }

        $owner = $this->Owners->get($id);
        // 作成するセレクトボックスを指定する
        $masCodeFind = array('age');
        // セレクトボックスを作成する
        $selectList = $this->Util->getSelectList($masCodeFind, $this->MasterCodes, true);

        $this->set(compact('owner', 'selectList', 'icons'));
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
