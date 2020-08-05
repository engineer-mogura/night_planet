<?php
namespace App\Controller\User;

use Cake\Log\Log;
use Cake\Event\Event;
use RuntimeException;
use Token\Util\Token;
use Cake\Mailer\Email;
use Cake\Filesystem\File;
use Cake\Filesystem\Folder;
use Cake\Http\Cookie\Cookie;
use Cake\Collection\Collection;
use Cake\Mailer\MailerAwareTrait;
use Cake\Auth\DefaultPasswordHasher;

/**
 * Controls the data flow into shops object and updates the view whenever data changes.
 */
class UsersController extends AppController
{
    use MailerAwareTrait;
    //public $components = array('Security');

    public function beforeFilter(Event $event)
    {
        // AppController.beforeFilterをコールバック
        parent::beforeFilter($event);

        //$this->Security->setConfig('blackHoleCallback', 'blackhole');
        // アクションのセキュリティ無効化 トークンが発行されていないため。
        //CakePHPでは $this->Form->create(); で作成したフォームにtokenが発行される。
        // そのtokenが有効期限以内に渡されているかどうかでセキュリティを担保している。
        //$this->Security->setConfig('unlockedActions', ['login']);
        // ユーザに関する情報をセット
        if (!is_null($user = $this->Auth->user())) {
            if ($this->Users->exists(['id' => $user['id']])) {
                $user = $this->Users->get($user['id']);
                // ユーザに関する情報をセット
                $this->set('userInfo', $this->Util->getUserInfo($user));

                // 常に現在エリアを取得
                $is_area = AREA['okinawa']['path'];
                // SEO対策
                $title = str_replace("_service_name_", LT['000'], TITLE['TOP_TITLE']);
                $description = str_replace("_service_name_", LT['000'], META['TOP_DESCRIPTION']);
                $this->set(compact("title", "description","is_area"));

            } else {
                $session = $this->request->getSession();
                $session->destroy();
                $this->Flash->error('うまくアクセス出来ませんでした。もう一度やり直してみてください。');
            }

        }
    }

    /**
     * ユーザ画面トップの処理
     *
     * @return void
     */
    public function mypage()
    {
        $user = $this->request->session()->read('Auth.User');
        $id = $user['id']; // ユーザID

        // ユーザ認証後の初回のみ自動でモーダルを表示するパラメタをセットする
        if ($this->request->session()->check('first_login')) {

        }
        // $query = $this->Diarys->find();
        // // ユーザの記事といいね数を取得
        // $diarys = $query->select(['id',
        //     'diary_like_num'=> $query->func()->count('diary_likes.diary_id')])
        //     ->contain('diary_likes')
        //     ->leftJoinWith('diary_likes')
        //     ->where(['diarys.user_id'=>$id])
        //     ->group(['diary_likes.diary_id'])
        //     ->order(['diary_like_num' => 'desc'])->toArray();
        // $likeTotal = array_sum(array_column($diarys, 'diary_like_num'));
        //$like = $query->select(['total_like' => $query->func()->sum('user_id')])
        //$diarydiary_likes = $this->Diarys->find('all')->where(['user_id'=>$id])->contain('diary_likes');
        // $user = $this->Users->find('all')
        //     ->contain(['shops','diarys'])->where(['users.id'=>$id])->first();

        // 非表示または論理削除している場合はログイン画面にリダイレクトする
        if (!$this->checkStatus($user)) {
            return $this->redirect($this->Auth->logout());
        }
        $this->set('next_view', 'mypage');
        $this->set(compact('user'));
        $this->render();
    }

    /**
     * プロフィール画面の処理
     *
     * @return void
     */
    public function profile()
    {
        $auth = $this->request->session()->read('Auth.User');
        $id = $auth['id']; // ユーザーID
        $user = $this->Users->get($id);
        $this->set('next_view', 'profile');

        // 非表示または論理削除している場合はログイン画面にリダイレクトする
        if (!$this->checkStatus($user)) {
            return $this->redirect($this->Auth->logout());
        }
        if ($this->request->is('ajax')) {

            $flg = true; // 返却フラグ
            $errors = ""; // 返却メッセージ
            $this->confReturnJson(); // responceがjsonタイプの場合の共通設定
            $message = RESULT_M['UPDATE_SUCCESS']; // 返却メッセージ

            // アイコン画像変更の場合
            if (isset($this->request->data["action_type"])) {

                $b_file = ""; // 前ファイルOBJ
                $b_name = ""; // 前ファイル名
                // ディクレトリ取得
                $dir = new Folder(preg_replace('/(\/\/)/', '/'
                    , WWW_ROOT.$this->viewVars['userInfo']['user_path']), true, 0755);
                // 前のファイル取得
                if (!empty($this->viewVars['userInfo']['icon_path'])) {
                    $b_file = new File(preg_replace('/(\/\/)/', '/'
                        , WWW_ROOT.$this->viewVars['userInfo']['icon_path']));
                    $b_name = $b_file->name;
                }

                $file = $this->request->data['image'];

                // ファイルが存在する、かつファイル名がblobの画像のとき
                if (!empty($file["name"]) && $file["name"] == 'blob') {
                    $limitFileSize = CAPACITY['MAX_NUM_BYTES_FILE'];
                    try {
                        // 前のファイルがある場合
                        if(!empty($b_file)) {
                            // ロールバック用のファイルサイズチェック
                            if ($b_file->size() > CAPACITY['MAX_NUM_BYTES_FILE']) {
                                throw new RuntimeException('ファイルサイズが大きすぎます。');
                            }

                            if (!$b_file->copy(preg_replace('/(\/\/)/', '/',
                                    WWW_ROOT.$this->viewVars['userInfo']['tmp_path']
                                    .DS.$b_file->name))) {
                                throw new RuntimeException('バックアップに失敗しました。');
                            }
                            // 一時ファイル取得
                            $tmpFile = new File(preg_replace('/(\/\/)/', '/',
                                WWW_ROOT.$this->viewVars['userInfo']['tmp_path'].DS.$b_file->name));
                        }

                        $newImageName = $this->Util->file_upload($this->request->getData('image'),
                            ['name'=> $b_name], $dir->path, $limitFileSize);
                        // ファイル更新の場合は古いファイルは削除
                        if (!empty($b_file->name)) {
                            // ファイル名が同じ場合は削除を実行しない
                            if ($b_file->name != $newImageName) {
                                // ファイル削除処理実行
                                if (!$b_file->delete()) {
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
                // ファイル名セット
                $user->file_name = $newImageName;

            } else {

                // バリデーションはプロフィール変更用を使う。
                $user = $this->Users->patchEntity($user
                        , $this->request->getData(), ['validate' => 'profile']);
                // バリデーションチェック
                if ($user->errors()) {
                    $flg = false;
                    // 入力エラーがあれば、メッセージをセットして返す
                    $message = $this->Util->setErrMessage($user); // エラーメッセージをセット
                    $response = array(
                        'success' => $flg,
                        'message' => $message
                    );
                    $this->response->body(json_encode($response));
                    return;
                }

            }
            try {
                // レコード更新実行
                if (!$this->Users->save($user)) {
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
            $user = $this->Users->get($id);
            // ユーザに関する情報をセット
            $this->set('userInfo', $this->Util->getUserInfo($user));
            $this->set(compact('user'));
            $this->render();
            $response = array(
                'html' => $this->response->body(),
                'error' => $errors,
                'success' => $flg,
                'message' => $message
            );
            $this->response->body(json_encode($response));
            return;
        }

        $this->set('next_view', 'profile');
        $user = $this->Users->get($id);
        $this->set(compact('user'));
        $this->render();
    }

    /**
     * ギャラリー 画面表示処理
     *
     * @return void
     */
    public function gallery()
    {

        $gallery = array();

        // ディクレトリ取得
        $dir = new Folder(preg_replace('/(\/\/)/', '/'
            , WWW_ROOT.$this->viewVars['userInfo']['image_path'])
            , true, 0755);

        /// 並び替えして出力
        $files = glob($dir->path.DS.'*.*');
        usort( $files, $this->Util->sortByLastmod );
        foreach( $files as $file ) {
            $timestamp = date('Y/m/d H:i', filemtime($file));
            array_push($gallery, array(
                "file_path"=>$this->viewVars['userInfo']['image_path'].DS.(basename( $file ))
                ,"date"=>$timestamp));
        }

        $this->set(compact('gallery'));
        $this->render();
    }

    /**
     * 日記 画面表示処理
     *
     * @return void
     */
    public function diary()
    {
        $id = $this->request->getSession()->read('Auth.User.id');

        $diarys = $this->Util->getDiarys($id
            , $this->viewVars['userInfo']['diary_path']);

        $this->set(compact('diarys'));
        $this->render();
    }

    public function login()
    {
        // レイアウトを使用しない
        //$this->viewBuilder()->autoLayout(false);

        if ($this->request->is('post')) {
            // エラーフラグ
            $is_error = false;
            // バリデーションはログイン用を使う。
            $user = $this->Users->newEntity($this->request->getData(), ['validate' => 'userLogin']);

            if (!$user->errors()) {

                // 現在リクエスト中のユーザーを識別する
                $user = $this->Auth->identify();
                if ($user) {
                    $this->Auth->setUser($user);
                    Log::info($this->Util->setAccessLog(
                        $user, $this->request->params['action']), 'access');

                } else {
                    // ログイン失敗
                    $is_error = true;
                    $this->Flash->error(RESULT_M['FRAUD_INPUT_FAILED']);
                }

            } else {

                Log::error($this->Util->setAccessLog(
                    $user, $this->request->params['action']).'　失敗', 'access');

                    foreach ($user->errors() as $key1 => $value1) {
                    foreach ($value1 as $key2 => $value2) {
                        $this->Flash->error($value2);
                    }
                }
                $is_error = true;
            }
            // エラーがある場合はログイン画面でエラーを表示する
            if ($is_error) {
                // 認証完了セッションをセット※モーダルを自動表示するためのパラメタ
                $this->request->session()->write('auth_success', 1);
                // エラーをセッションにセット
                $this->request->session()->write('error', $this->request->data());
                $this->set('user', $user);
                return $this->redirect(PUBLIC_DOMAIN);
            }
        } else {
            $user = $this->Users->newEntity();
        }
        // 認証後、Main,Areaコントローラでも認証情報を保持するため、
        // クッキーを作成する
        $values = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'file_name' => $user['file_name']
        ];
        $values = json_encode($values);
        $cookie = [
            'value' => $values,
            'path' => '/',
            'httpOnly' => true,
            'secure' => false,
            'expire' => strtotime('+100 day')
        ];

        $this->response = $this->response->withCookie('_auth_info', $cookie);

        // 認証完了セッションをセット※モーダルを自動表示するためのパラメタ
        $this->request->session()->write('first_login', 1);
        $this->redirect('user/users/mypage');
    }

    public function logout()
    {
        $auth = $this->request->session()->read('Auth.User');

        Log::info($this->Util->setAccessLog(
            $auth, $this->request->params['action']), 'access');

        // レイアウトを使用しない
        $this->viewBuilder()->autoLayout(false);
        $this->Flash->success(COMMON_M['LOGGED_OUT']);
        return $this->redirect($this->Auth->logout());
    }

    /**
     * ユーザ登録時の認証
     *
     * @param [type] $token
     * @return void
     */
    public function verify($token)
    {
        // シンプルレイアウトを使用
        $this->viewBuilder()->layout('simpleDefault');
        try {
            $user = $this->Users->get(Token::getId($token));
        } catch(RuntimeException $e) {
            $this->Flash->error('URLが無効になっています。');
            return $this->render('/common/error');
        }

        // 以下でトークンの有効期限や改ざんを検証することが出来る
        if (!$user->tokenVerify($token)) {
            $this->log($this->Util->setLog($user
                , 'トークンの有効期限が切れたか、改ざんが行われた可能性があります。'));
            // 仮登録してるレコードを削除する
            $this->Users->delete($user);

            $this->Flash->error(RESULT_M['AUTH_FAILED']);
            return $this->render('/common/error');
        }

        // ユーザレイアウトを使用
        $this->viewBuilder()->layout('userDefault');

        // 仮登録時点で仮登録フラグは立っていない想定。
        if ($user->status == 1) {
            // すでに登録しているとみなし、ログイン画面へ
            $this->Flash->success(RESULT_M['REGISTERED_FAILED']);
            // 認証完了セッションをセット※モーダルを自動表示するためのパラメタ
            $this->request->session()->write('auth_success', 1);
            // 認証完了でトップページへ遷移する
            return $this->redirect(PUBLIC_DOMAIN);
        }

        try{
            $user->status = 1;      // 仮登録フラグを下げる
            // ユーザ登録
            if (!$this->Users->save($user)) {

                throw new RuntimeException('レコードの更新に失敗しました。');
            }

            // 認証完了したら、メール送信
            $email = new Email('default');
            $email->setFrom([MAIL['FROM_SUBSCRIPTION'] => MAIL['FROM_NAME']])
                ->setSubject($user->name."様、メールアドレスの認証が完了しました。")
                ->setTo($user->email)
                ->setBcc(MAIL['FROM_INFO_GMAIL'])
                ->setTemplate("auth_success")
                ->setLayout("auth_success_layout")
                ->emailFormat("html")
                ->viewVars(['user' => $user])
                ->send();
            $this->set('user', $user);
            $this->log($email,'debug');

        } catch(RuntimeException $e) {

            $this->log($this->Util->setLog($user, $e));
            // 仮登録してるレコードを削除する
            $this->Users->delete($user);
            $this->Flash->error(RESULT_M['AUTH_FAILED']);
            return $this->render('/common/error');
        }
        // 認証完了セッションをセット※モーダルを自動表示するためのパラメタ
        $this->request->session()->write('auth_success', 1);
        // 認証完了でトップページへ遷移する
        $this->Flash->success(RESULT_M['AUTH_SUCCESS']);
        return $this->redirect(PUBLIC_DOMAIN);

    }

    public function passReset()
    {
        // シンプルレイアウトを使用
        $this->viewBuilder()->layout('simpleDefault');

        if ($this->request->is('post')) {

            // バリデーションはパスワードリセットその１を使う。
            $user = $this->Users->newEntity( $this->request->getData()
                , ['validate' => 'UserPassReset1']);

            if(!$user->errors()) {
                // メールアドレスで取得
                $user = $this->Users->find()
                    ->where(['email' => $user->email])->first();

                // 非表示または論理削除している場合はログイン画面にリダイレクトする
                if (!$this->checkStatus($user)) {
                    return $this->redirect($this->Auth->logout());
                }

                $email = new Email('default');
                $email->setFrom([MAIL['FROM_SUBSCRIPTION'] => MAIL['FROM_NAME']])
                    ->setSubject(MAIL['FROM_NAME_PASS_RESET'])
                    ->setTo($user->email)
                    ->setBcc(MAIL['FROM_INFO_GMAIL'])
                    ->setTemplate("pass_reset_email")
                    ->setLayout("simple_layout")
                    ->emailFormat("html")
                    ->viewVars(['user' => $user])
                    ->send();
                $this->set('user', $user);

                $this->Flash->success('パスワード再設定用メールを送信しました。');
                Log::info("ID：【".$user['id']."】アドレス：【".$user->email
                    ."】パスワード再設定用メールを送信しました。", 'pass_reset');

                return $this->render('/common/pass_reset_send');

            } else {
                // 送信失敗
                foreach ($user->errors() as $key1 => $value1) {
                    foreach ($value1 as $key2 => $value2) {
                        $this->Flash->error($value2);
                        Log::error("ID：【".$user['id']."】アドレス：【".$user->email
                            ."】エラー：【".$value2."】", 'pass_reset');
                    }
                }
            }
        } else {
            $user = $this->Users->newEntity();
        }
        $this->set('user', $user);
        return $this->render('/common/pass_reset_form');
    }

    /**
     * トークンをチェックして不整合が無ければ
     * パスワードの変更をする
     *
     * @param [type] $token
     * @return void
     */
    public function resetVerify($token)
    {

        // シンプルレイアウトを使用
        $this->viewBuilder()->layout('simpleDefault');
        $user = $this->Auth->identify();
        try {
            $user = $this->Users->get(Token::getId($token));
        } catch(RuntimeException $e) {
            $this->Flash->error('URLが無効になっています。');
            return $this->render('/common/pass_reset_form');
        }

        // 以下でトークンの有効期限や改ざんを検証することが出来る
        if (!$user->tokenVerify($token)) {
            Log::info("ID：【".$user->id."】"."アドレス：【".$user->email."】".
                "エラー：【".RESULT_M['PASS_RESET_FAILED']."】アクション：【"
                . $this->request->params['action']. "】", "pass_reset");

            $this->Flash->error(RESULT_M['PASS_RESET_FAILED']);
            return $this->render('/common/pass_reset_form');
        }

        if ($this->request->is('post')) {

            // パスワードリセットフォームの表示フラグ
            $is_reset_form = false;

            // バリデーションはパスワードリセットその２を使う。
            $validate = $this->Users->newEntity( $this->request->getData()
                , ['validate' => 'UserPassReset2']);

           if (!$validate->errors()) {

                // 再設定したバスワードを設定する
                $user->password = $this->request->getData('password');
                // 自動ログインフラグを下げる
                $user->remember_token = 0;

                // 一応ちゃんと変更されたかチェックする
                if (!$user->isDirty('password')) {

                    Log::info("ID：【".$user->id."】"."アドレス：【".$user->email."】".
                    "エラー：【パスワードの変更に失敗しました。】アクション：【"
                        . $this->request->params['action']. "】", "pass_reset");

                    $this->Flash->error('パスワードの変更に失敗しました。');
                    return $this->redirect(['action' => 'login']);
                }

               if ($this->Users->save($user)) {

                    // 変更完了したら、メール送信
                    $email = new Email('default');
                    $email->setFrom([MAIL['FROM_SUBSCRIPTION'] => MAIL['FROM_NAME']])
                        ->setSubject($user->name."様、メールアドレスの変更が完了しました。")
                        ->setTo($user->email)
                        ->setBcc(MAIL['FROM_INFO_GMAIL'])
                        ->setTemplate("pass_reset_success")
                        ->setLayout("simple_layout")
                        ->emailFormat("html")
                        ->viewVars(['user' => $user])
                        ->send();
                    $this->set('user', $user);

                    // 変更完了でログインページへ
                    $this->Flash->success(RESULT_M['PASS_RESET_SUCCESS']);
                    Log::info("ID：【".$user['id']."】アドレス：【".$user->email
                        ."】". RESULT_M['PASS_RESET_SUCCESS'], 'pass_reset');
                    return $this->redirect(['action' => 'login']);
               }

           } else {

                // パスワードリセットフォームの表示フラグ
                $is_reset_form = true;
                $this->set(compact('is_reset_form'));
               // 入力エラーがあれば、メッセージをセットして返す
               $this->Flash->error(__('入力内容に誤りがあります。'));
               return $this->render('/common/pass_reset_form');
            }

        } else {

            // パスワードリセットフォームの表示フラグ
            $is_reset_form = true;
            $this->set(compact('is_reset_form','user'));
            return $this->render('/common/pass_reset_form');
        }
    }

    public function passChange()
    {
        $auth = $this->request->session()->read('Auth.User');
        $id = $auth['id']; // ユーザーID
        $new_pass = "";
        if ($this->request->is('post')) {

            $isValidate = false; // エラー有無
            // バリデーションはパスワードリセットその３を使う。
            $validate = $this->Users->newEntity( $this->request->getData()
                , ['validate' => 'UserPassReset3']);

            if(!$validate->errors()) {

                $hasher = new DefaultPasswordHasher();
                $user = $this->Users->get($this->viewVars['userInfo']['id']);
                $equal_check = $hasher->check($this->request->getData('password')
                    , $user->password);
                // 入力した現在のパスワードとデータベースのパスワードを比較する
                if (!$equal_check) {
                    $this->Flash->error('現在のパスワードが間違っています。');
                    return $this->render();
                }
                // 新しいバスワードを設定する
                $user->password = $this->request->getData('password_new');

                // 一応ちゃんと変更されたかチェックする
                if (!$user->isDirty('password')) {

                    Log::info("ID：【".$user->id."】"."アドレス：【".$user->email."】".
                    "エラー：【パスワードの変更に失敗しました。】アクション：【"
                        . $this->request->params['action']. "】", "pass_reset");

                    $this->Flash->error('パスワードの変更に失敗しました。');
                    return $this->render();
                }

                try {
                    // レコード更新実行
                    if (!$this->Users->save($user)) {
                        throw new RuntimeException('レコードの更新ができませんでした。');
                    }
                    $this->Flash->success('パスワードの変更をしました。');
                    return $this->redirect('/user/users/profile');

                } catch (RuntimeException $e) {
                    $this->log($this->Util->setLog($auth, $e));
                    $this->Flash->error('パスワードの変更に失敗しました。');
                }

            } else {
                $user = $validate;
            }
        } else {
            $user = $this->Users->newEntity();
        }
        $this->set('user', $user);
        return $this->render();
    }

    public function signup()
    {

        $this->viewBuilder()->layout('simpleDefault');
        $this->Security->setConfig('blackHoleCallback', 'blackhole');

        // 登録ボタン押下時
        if ($this->request->is('post')) {
            // バリデーションは新規登録用を使う。
            $user = $this->Users->newEntity($this->request->getData(), ['validate' => 'userRegistration']);

            if (!$user->errors()) {

                $user = $this->Users->patchEntity($user, $this->request->getData());

                if ($this->Users->save($user)) {

                    $email = new Email('default');
                    $email->setFrom([MAIL['FROM_SUBSCRIPTION'] => MAIL['FROM_NAME']])
                        ->setSubject($user->name."様、メールアドレスの認証を完了してください。")
                        ->setTo($user->email)
                        ->setBcc(MAIL['FROM_INFO_GMAIL'])
                        ->setTemplate("auth_send")
                        ->setLayout("simple_layout")
                        ->emailFormat("html")
                        ->viewVars(['user' => $user])
                        ->send();
                    $this->log($email,'debug');
                    $this->Flash->success('ご指定のメールアドレスに認証メールを送りました。認証を完了してください。');
                    return $this->render('send_auth_email');
                }

            }
            // 入力エラーがあれば、メッセージをセットして返す
            $this->Flash->error(__('入力内容に誤りがあります。'));
        }

        $this->set(compact('user'));
        $this->render();
    }

    public function blackhole($type)
    {
        switch ($type) {
          case 'csrf':
            $this->Flash->error(__('不正な送信が行われました'));
            $this->redirect(array('controller' => 'user', 'action' => $this->action));
            break;
          default:
            $this->redirect(array('controller' => 'user', 'action' => 'display'));
            break;
        }
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
