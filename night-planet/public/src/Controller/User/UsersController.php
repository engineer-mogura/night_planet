<?php
namespace App\Controller\User;

use Cake\Log\Log;
use Cake\I18n\Time;
use Cake\Event\Event;
use RuntimeException;
use Token\Util\Token;
use Cake\Mailer\Email;
use Cake\Filesystem\File;
use Cake\Filesystem\Folder;
use Cake\Collection\Collection;
use Cake\Mailer\MailerAwareTrait;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Datasource\ConnectionManager;

/**
 * Controls the data flow into shops object and updates the view whenever data changes.
 */
class UsersController extends AppController
{
    use MailerAwareTrait;
    public $components = array('Security');

    public function beforeFilter(Event $event)
    {
        // AppController.beforeFilterをコールバック
        parent::beforeFilter($event);

        // ユーザに関する情報をセット
        if (!is_null($user = $this->Auth->user())) {
            if ($this->Users->exists(['id' => $user['id']])) {
                $user = $this->Users->get($user['id']);
                // ユーザに関する情報をセット
                $this->set('userInfo', $this->Util->getUserItem($user, $shop));
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
    public function index()
    {
        $auth = $this->request->session()->read('Auth.User');
        $id = $auth['id']; // ユーザID
        $query = $this->Diarys->find();
        // ユーザの記事といいね数を取得
        $diarys = $query->select(['id',
            'diary_like_num'=> $query->func()->count('diary_likes.diary_id')])
            ->contain('diary_likes')
            ->leftJoinWith('diary_likes')
            ->where(['diarys.user_id'=>$id])
            ->group(['diary_likes.diary_id'])
            ->order(['diary_like_num' => 'desc'])->toArray();
        $likeTotal = array_sum(array_column($diarys, 'diary_like_num'));
        //$like = $query->select(['total_like' => $query->func()->sum('user_id')])
        //$diarydiary_likes = $this->Diarys->find('all')->where(['user_id'=>$id])->contain('diary_likes');
        $user = $this->Users->find('all')
            ->contain(['shops','diarys'])->where(['users.id'=>$id])->first();

        // 非表示または論理削除している場合はログイン画面にリダイレクトする
        if (!$this->checkStatus($user)) {
            return $this->redirect($this->Auth->logout());
        }

        // JSONファイルをDB内容にて、更新する
        // JSONファイルに書き込むカラム情報
        $Columns = array('id','title','start','end'
            ,'time_start', 'time_end','all_day');

        $user_schedule = $this->UserSchedules->find('all', array('fields' => $Columns))
            ->where(['id'=>$userInfo['id'], 'shop_id'=> $userInfo['shop_id']]);
        $user_schedule = json_encode($user_schedule);
        // JSONファイルに書き込む
        $tmp_path = preg_replace('/(\/\/)/', '/',
            WWW_ROOT.$this->viewVars['userInfo']['tmp_path']);

        $masterCodesFind = array('time','event');
        $selectList = $this->Util->getSelectList($masterCodesFind, $this->MasterCodes, true);

        $this->set(compact('user', 'likeTotal', 'selectList', 'user_schedule'));
        $this->render();
    }

    /**
     * カレンダーの処理
     *
     * @param [type] $id
     * @return void
     */
    public function editCalendar()
    {
        // AJAXのアクセス以外は不正とみなす。
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException('AJAX以外でのアクセスがあります。');
        }
        $flg = true; // 返却フラグ
        $errors = ""; // 返却メッセージ
        $this->confReturnJson(); // responceがjsonタイプの場合の共通設定
        $message = ""; // 返却メッセージ
        $auth = $this->request->session()->read('Auth.User');
        $id = $auth['id']; // ユーザID

        try {

            // イベント削除の場合
            if ($this->request->data["crud_type"] == "delete") {
                $message = RESULT_M['DELETE_SUCCESS'];
                $user_schedules = $this->UserSchedules->get($this->request->data["id"]);
                if (!$this->UserSchedules->delete($user_schedules)) {
                    throw new RuntimeException('レコードの削除ができませんでした。');
                    $message = RESULT_M['DELETE_FAILED'];
                }

            } elseif ($this->request->data["crud_type"] == "update") {
                // イベント編集の場合
                $message = RESULT_M['UPDATE_SUCCESS'];
                $user_schedules = $this->UserSchedules->patchEntity(
                    $this->UserSchedules->get($this->request->getData('id')), $this->request->getData());
                if (!$this->UserSchedules->save($user_schedules)) {
                    throw new RuntimeException('レコードの更新ができませんでした。');
                    $message = RESULT_M['UPDATE_FAILED'];
                }

            } elseif ($this->request->data["crud_type"] == "create") {
                // イベント追加の場合
                $message = RESULT_M['SIGNUP_SUCCESS'];
                $user_schedules = $this->UserSchedules->newEntity($this->request->getData());
                if (!$this->UserSchedules->save($user_schedules)) {
                    throw new RuntimeException('レコードの登録ができませんでした。');
                    $message = RESULT_M['SIGNUP_FAILED'];
                }

            }

            // JSONファイルをDB内容にて、更新する
            $schedule_path = preg_replace('/(\/\/)/', '/'
                , WWW_ROOT.$this->viewVars['userInfo']['schedule_path']);

            // 対象ディレクトリパス取得
            $dir = new Folder($schedule_path, true, 0755);

            // 削除対象ディレクトリパス存在チェック
            if (!file_exists($dir->path)) {
                throw new RuntimeException('ディレクトリが存在しません。');
            }

            // ロールバック用のディレクトリサイズチェック
            if ($dir->dirsize() > CAPACITY['MAX_NUM_BYTES_DIR']) {
                throw new RuntimeException('ディレクトリサイズが大きすぎます。');
            }

            // 一時ディレクトリ作成※なければ作成
            $tmpDir = new Folder(WWW_ROOT.$this->viewVars['userInfo']['tmp_path'] . DS . time(), true, 0777);
            // 一時ディレクトリにバックアップ実行
            if (!$dir->copy($tmpDir->path)) {
                throw new RuntimeException('バックアップに失敗しました。');
            }
            // JSONファイルを取得※なければ作成
            $file = new File($dir->path.DS."calendar.json", true);
            // JSONファイルに書き込むカラム情報
            $Columns = array('id','title','start','end'
                ,'time_start', 'time_end','all_day');

            $user_schedule = $this->UserSchedules->find('all', array('fields' => $Columns))
                ->where(['shop_id' => $this->viewVars['userInfo']['shop_id']
                    , 'user_id' => $this->viewVars['userInfo']['id']]);

            // イベント情報全てJSONファイルに上書きする
            $file->write(json_encode($user_schedule));
            $isWrited = true; // 書き込み済フラグを立てる

        } catch (RuntimeException $e) {

            // ファイル書き込み済の場合は復元する
            if ($isWrited) {
                $tmpDir->copy($dir->path);
            }

            $this->log($this->Util->setLog($auth, $e));
            $flg = false;
            $message = RESULT_M['UPDATE_FAILED'];
        }

        //ファイルを閉じる
        if(isset($file)) {
            $file->close();
        }
        // 一時ディレクトリがあれば削除する
        if (isset($tmpDir) && file_exists($tmpDir->path)) {
            $tmpDir->delete();// tmpディレクトリ削除
        }

        $response = array(
            'success' => $flg,
            'message' => $message
        );
        $this->response->body(json_encode($response));
        return;
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
        // 非表示または論理削除している場合はログイン画面にリダイレクトする
        if (!$this->checkStatus($this->Users->get($this->viewVars['userInfo']['id']))) {
            return $this->redirect($this->Auth->logout());
        }
        if ($this->request->is('ajax')) {

            $flg = true; // 返却フラグ
            $errors = ""; // 返却メッセージ
            $this->confReturnJson(); // responceがjsonタイプの場合の共通設定
            $message = RESULT_M['UPDATE_SUCCESS']; // 返却メッセージ

            // アイコン画像変更の場合
            if (isset($this->request->data["action_type"])) {

                $dirPath = preg_replace('/(\/\/)/', '/',
                    WWW_ROOT.$this->viewVars['userInfo']['profile_path']);

                $user = $this->Users->get($this->viewVars['userInfo']['id']);

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

                        // 更新情報を追加する
                        $updates = $this->Updates->newEntity();
                        $updates->set('content', $this->Auth->user('nickname').'さんがプロフィールアイコンを更新しました。');
                        $updates->set('shop_id', $this->Auth->user('shop_id'));
                        $updates->set('user_id', $this->Auth->user('id'));
                        $updates->set('type', SHOP_MENU_NAME['PROFILE']);
                        // レコード更新実行
                        if (!$this->Updates->save($updates)) {
                            throw new RuntimeException('レコードの登録ができませんでした。');
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
                $user = $this->Users->patchEntity($this->Users
                ->get($id), $this->request->getData(), ['validate' => 'profile']);
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
                try {
                    // レコード更新実行
                    if (!$this->Users->save($user)) {
                        throw new RuntimeException('レコードの更新ができませんでした。');
                    }
                    // 更新情報を追加する
                    $updates = $this->Updates->newEntity();
                    $updates->set('content', $this->Auth->user('nickname').'さんがプロフィールを更新しました。');
                    $updates->set('shop_id', $this->Auth->user('shop_id'));
                    $updates->set('user_id', $this->Auth->user('id'));
                    $updates->set('type', SHOP_MENU_NAME['PROFILE']);
                    // レコード更新実行
                    if (!$this->Updates->save($updates)) {
                        throw new RuntimeException('レコードの登録ができませんでした。');
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

            $user = $this->Users->get($id);
            // 作成するセレクトボックスを指定する
            $masCodeFind = array('time','constellation','blood_type','age');
            // セレクトボックスを作成する
            $selectList = $this->Util->getSelectList($masCodeFind, $this->MasterCodes, true);

            $this->set(compact('user','selectList', 'icons'));
            $this->render('/User/Users/profile');
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

        $user = $this->Users->get($id);
        // 作成するセレクトボックスを指定する
        $masCodeFind = array('time','constellation','blood_type','age');
        // セレクトボックスを作成する
        $selectList = $this->Util->getSelectList($masCodeFind, $this->MasterCodes, true);

        $this->set(compact('user', 'selectList', 'icons'));
        $this->render();
    }

    /**
     * トップ画像 画面表示処理
     *
     * @return void
     */
    public function topImage()
    {
        // ディクレトリ取得
        $dir = new Folder(preg_replace('/(\/\/)/', '/'
            , WWW_ROOT.$this->viewVars['userInfo']['top_image_path'])
            , true, 0755);

        $files = glob($dir->path.DS.'*.*');

        // ファイルが存在したら、画像をセット
        if(count($files) > 0) {
            foreach( $files as $file ) {
                $this->set('top_image', $this->viewVars['userInfo']['top_image_path']
                .DS.(basename($file)));
            }
        } else {
            // 共通トップ画像をセット
            $this->set('top_image', PATH_ROOT['CAST_TOP_IMAGE']);
        }

        $this->render();
    }

    /**
     * トップ画像 編集押下処理
     *
     * @return void
     */
    public function saveTopImage()
    {
        // AJAXのアクセス以外は不正とみなす。
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException('AJAX以外でのアクセスがあります。');
        }
        $flg = true; // 返却フラグ
        $isRemoved = false; // ディレクトリ削除フラグ
        $errors = ""; // 返却メッセージ
        $this->confReturnJson(); // responceがjsonタイプの場合の共通設定
        $message = RESULT_M['SIGNUP_SUCCESS']; // 返却メッセージ
        $auth = $this->request->session()->read('Auth.User');
        $dir = preg_replace('/(\/\/)/', '/',
            WWW_ROOT.$this->viewVars['userInfo']['top_image_path']);

        // ディクレトリ取得
        $dir = new Folder($dir, true, 0755);

        // ファイルは１つのみだけど配列で取得する
        $files = glob($dir->path.DS.'*.*');

        // 前のファイル取得
        $fileBefor = new File($files[0], true, 0755);

        $file = $this->request->getData('top_image_file');
        // ファイルが存在する、かつファイル名がblobの画像のとき
        if (!empty($file["name"]) && $file["name"] == 'blob') {
            $limitFileSize = CAPACITY['MAX_NUM_BYTES_FILE'];
            try {
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

                $newImageName = $this->Util->file_upload($this->request->getData('top_image_file'),
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
                // 更新した場合
                if($isRemoved) {
                    // 更新情報を追加する
                    $updates = $this->Updates->newEntity();
                    $updates->set('content', $this->Auth->user('nickname').'さんがトップ画像を変更しました。');
                    $updates->set('shop_id', $this->Auth->user('shop_id'));
                    $updates->set('user_id', $this->Auth->user('id'));
                    $updates->set('type', SHOP_MENU_NAME['CAST_TOP_IMAGE']);
                    // レコード更新実行
                    if (!$this->Updates->save($updates)) {
                        throw new RuntimeException('レコードの登録ができませんでした。');
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

        $gallery = array();

        // ファイルは１つのみだけど配列で取得する
        $files = glob($dir->path.DS.'*.*');

        foreach( $files as $file ) {
            $timestamp = date('Y/m/d H:i', filemtime($file));
            array_push($gallery, array(
                "file_path"=>$this->viewVars['userInfo']['top_image_path'].DS.(basename( $file ))
                ,"date"=>$timestamp));
        }
        $this->set(compact('gallery'));
        $this->render('/User/Users/top_image');
        $response = array(
            'html' => $this->response->body(),
            'error' => $errors,
            'success' => $flg,
            'message' => $message
        );
        $this->response->body(json_encode($response));
        return;
    }

    /**
     * トップ画像 削除押下処理
     *
     * @return void
     */
    public function deleteTopImage()
    {
        // AJAXのアクセス以外は不正とみなす。
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException('AJAX以外でのアクセスがあります。');
        }
        $flg = true; // 返却フラグ
        $errors = ""; // 返却メッセージ
        $this->confReturnJson(); // responceがjsonタイプの場合の共通設定
        $message = RESULT_M['DELETE_SUCCESS']; // 返却メッセージ
        $auth = $this->request->session()->read('Auth.User');

        try {

            // ディクレトリ取得
            $dir = new Folder(preg_replace('/(\/\/)/', '/'
                , WWW_ROOT.$this->viewVars['userInfo']['top_image_path'])
               , true, 0755);

            $files = glob($dir->path.DS.'*.*');
            // 削除対象ファイルを取得
            $file = new File(preg_replace('/(\/\/)/', '/', 
                $files[0]));

            // 日記ファイル削除処理実行
            if (!$file->delete()) {
                throw new RuntimeException('ファイルの削除ができませんでした。');
            }

        } catch (RuntimeException $e) {

            $this->log($this->Util->setLog($auth, $e));
            $flg = false;
        }
        // 例外が発生している場合にメッセージをセットして返却する
        if (!$flg) {
            $message = RESULT_M['DELETE_FAILED'];
            $response = array(
                'success' => $flg,
                'message' => $message
            );
            $this->response->body(json_encode($response));
            return;
        }
        // 空の配列
        $gallery = array();

        $this->set(compact('gallery'));
        $this->render('/User/Users/top_image');
        $response = array(
            'html' => $this->response->body(),
            'error' => $errors,
            'success' => $flg,
            'message' => $message
        );
        $this->response->body(json_encode($response));
        return;
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
     * sns 画面の処理
     *
     * @return void
     */
    public function sns()
    {
        $auth = $this->request->session()->read('Auth.User');
        $id = $auth['id']; // ユーザID

        // 非表示または論理削除している場合はログイン画面にリダイレクトする
        if (!$this->checkStatus($this->Users->get($this->viewVars['userInfo']['id']))) {
            return $this->redirect($this->Auth->logout());
        }

        // AJAXのアクセス以外は不正とみなす。
        if ($this->request->is('ajax')) {
            $flg = true; // 返却フラグ
            $errors = ""; // 返却メッセージ
            $this->confReturnJson(); // responceがjsonタイプの場合の共通設定
            $message = RESULT_M['UPDATE_SUCCESS']; // 返却メッセージ
            $plan = $this->viewVars['userInfo']['current_plan'];

            try {
                // プレミアムSプラン以外 かつ Instagramが入力されていた場合 不正なパターンでエラー
                if ($plan != SERVECE_PLAN['premium_s']['label'] && !empty($this->request->getData('instagram'))) {
                    throw new RuntimeException(RESULT_M['INSTA_ADD_CAST_FAILED'].' 不正アクセスがあります。');
                }
            } catch (RuntimeException $e) {

                // エラーメッセージをセット
                $search = array('_service_plan_');
                $replace = array(SERVECE_PLAN['premium_s']['name']);
                $message = $this->Util->strReplace($search, $replace, RESULT_M['INSTA_ADD_CAST_FAILED']);

                $this->log($this->Util->setLog($auth, $e));
                $response = array('success'=>false,'message'=>$message);
                $this->response->body(json_encode($response));
                return;
            }

            // レコードが存在するか
            // レコードがない場合は、新規で登録を行う。
            if (!$this->Snss->exists(['user_id' =>$this->viewVars['userInfo']['id']])) {
                $sns = $this->Snss->newEntity($this->request->getData());
                $sns->user_id = $this->viewVars['userInfo']['id'];
            } else {
                // snsテーブルからidのみを取得する
                $sns_id = $this->Snss->find()
                    ->select('id')
                    ->where(['user_id' =>$id])->first()->id;

                $sns = $this->Snss->patchEntity($this->Snss
                ->get($sns_id), $this->request->getData());
            }

            // バリデーションチェック
            if ($sns->errors()) {
                // 入力エラーがあれば、メッセージをセットして返す
                $errors = $this->Util->setErrMessage($sns); // エラーメッセージをセット
                $response = array('success'=>false,'message'=>$errors);
                $this->response->body(json_encode($response));
                return;
            }
            try {
                // レコード更新実行
                if (!$this->Snss->save($sns)) {
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

            $user = $this->Users->find()
                ->where(['id' => $this->viewVars['userInfo']['id']])
                ->contain(['Snss'])->first();

            $this->set(compact('user'));
            $this->render('/User/Users/sns');
            $response = array(
                'html' => $this->response->body(),
                'error' => $errors,
                'success' => $flg,
                'message' => $message
            );
            $this->response->body(json_encode($response));
            return;
        }
        $user = $this->Users->find()
            ->where(['id' => $id])
            ->contain(['Snss'])->first();
 
        $this->set(compact('user'));
        $this->render();
    }

    /**
     * ギャラリー 編集押下処理
     *
     * @return void
     */
    public function saveGallery()
    {
        // AJAXのアクセス以外は不正とみなす。
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException('AJAX以外でのアクセスがあります。');
        }
        $flg = true; // 返却フラグ
        $isRemoved = false; // ディレクトリ削除フラグ
        $isDuplicate = false; // 画像重複フラグ
        $errors = ""; // 返却メッセージ
        $this->confReturnJson(); // responceがjsonタイプの場合の共通設定
        $message = RESULT_M['UPDATE_SUCCESS']; // 返却メッセージ
        $auth = $this->request->session()->read('Auth.User');
        $dirPath = preg_replace('/(\/\/)/', '/',
            WWW_ROOT.$this->viewVars['userInfo']['image_path']);
        $files = array();
        // 対象ディレクトリパス取得
        $dir = new Folder($dirPath, true, 0755);

        // 既に登録された画像があればデコードし格納、無ければ空の配列を格納する
        ($files_befor = json_decode($this->request->getData("gallery_befor")
            , true)) > 0 ? : $files_befor = array();

        $fileMax = PROPERTY['FILE_MAX']; // ファイル格納最大数

        try {

            // 削除対象ディレクトリパス存在チェック
            if (!file_exists($dir->path)) {
                throw new RuntimeException('ディレクトリが存在しません。');
            }

            // ディレクトリサイズチェック
            if ($dir->dirsize() > CAPACITY['MAX_NUM_BYTES_DIR']) {
                throw new RuntimeException('ディレクトリサイズが大きすぎます。');
            }

            // 追加画像がある場合
            if (isset($this->request->data["image"])) {
                $files = $this->request->data['image'];
            }

            foreach ($files as $key => $file) {
                // ファイルが存在する、かつファイル名がblobの画像のとき
                if (!empty($file["name"]) && $file["name"] == 'blob') {
                    $limitFileSize = CAPACITY['MAX_NUM_BYTES_FILE'];

                    // ファイル名を取得する
                    $convertFile = $this->Util->file_upload($file, $files_befor, $dir->path, $limitFileSize);

                    // ファイル名が同じ場合は重複フラグをセットする
                    if ($convertFile === false) {
                        $isDuplicate = true;
                    }

                }
            }

            // 更新情報を追加する
            $updates = $this->Updates->newEntity();
            $updates->set('content', $this->Auth->user('nickname').'さんがギャラリーを追加しました。');
            $updates->set('shop_id', $this->Auth->user('shop_id'));
            $updates->set('user_id', $this->Auth->user('id'));
            $updates->set('type', SHOP_MENU_NAME['CAST_GALLERY']);
            // レコード更新実行
            if (!$this->Updates->save($updates)) {
                throw new RuntimeException('レコードの登録ができませんでした。');
            }

        } catch (RuntimeException $e) {
            $this->log($this->Util->setLog($auth, $e));
            $flg = false;
        }

        // 例外が発生している場合にメッセージをセットして返却する
        if (!$flg) {

            $message = RESULT_M['UPDATE_FAILED'];
            $response = array(
                'success' => $flg,
                'message' => $message
            );
            $this->response->body(json_encode($response));
            return;
        }

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
        $this->render('/User/Users/gallery');
        $response = array(
            'html' => $this->response->body(),
            'error' => $errors,
            'success' => $flg,
            'message' => $isDuplicate ? $message . "\n" . RESULT_M['DUPLICATE'] : $message
        );
        $this->response->body(json_encode($response));
        return;
    }

    /**
     * ギャラリー 削除押下処理
     *
     * @return void
     */
    public function deleteGallery()
    {
        // AJAXのアクセス以外は不正とみなす。
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException('AJAX以外でのアクセスがあります。');
        }
        $flg = true; // 返却フラグ
        $errors = ""; // 返却メッセージ
        $this->confReturnJson(); // responceがjsonタイプの場合の共通設定
        $message = RESULT_M['DELETE_SUCCESS']; // 返却メッセージ
        $auth = $this->request->session()->read('Auth.User');

        try {

            // 削除対象ファイルを取得
            $file = new File(preg_replace('/(\/\/)/', '/', 
                WWW_ROOT. $this->request->getData('file_path')));

            // 削除対象ファイルパス存在チェック
            if (!file_exists($file->path)) {
                throw new RuntimeException('ファイルが存在しません。');
            }

            // 日記ファイル削除処理実行
            if (!$file->delete()) {
                throw new RuntimeException('ファイルの削除ができませんでした。');
            }

        } catch (RuntimeException $e) {

            $this->log($this->Util->setLog($auth, $e));
            $flg = false;
        }
        // 例外が発生している場合にメッセージをセットして返却する
        if (!$flg) {
            $message = RESULT_M['DELETE_FAILED'];
            $response = array(
                'success' => $flg,
                'message' => $message
            );
            $this->response->body(json_encode($response));
            return;
        }

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
        $this->render('/User/Users/gallery');
        $response = array(
            'html' => $this->response->body(),
            'error' => $errors,
            'success' => $flg,
            'message' => $message
        );
        $this->response->body(json_encode($response));
        return;
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

    /**
     * 日記 登録処理
     * @return void
     */
    public function saveDiary()
    {
        // AJAXのアクセス以外は不正とみなす。
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException('AJAX以外でのアクセスがあります。');
        }
        $flg = true; // 返却フラグ
        $isDuplicate = false; // 画像重複フラグ
        $errors = ""; // 返却メッセージ
        $this->confReturnJson(); // responceがjsonタイプの場合の共通設定
        $message = RESULT_M['SIGNUP_SUCCESS']; // 返却メッセージ
        $auth = $this->request->session()->read('Auth.User');
        $id = $auth['id']; // ユーザID
        $files = array();

        $fileMax = PROPERTY['FILE_MAX']; // ファイルアップの制限数
        $files_befor = array(); // 新規なので空の配列

        // エンティティにマッピングする
        $diary = $this->Diarys->newEntity($this->request->getData());
        // バリデーションチェック
        if ($diary->errors()) {
            // 入力エラーがあれば、メッセージをセットして返す
            $errors = $this->Util->setErrMessage($diary); // エラーメッセージをセット
            $response = array('success'=>false,'message'=>$errors);
            $this->response->body(json_encode($response));
            return;
        }

        // 日記用のディレクトリを掘る
        $date = new Time();
        $diaryPath =  DS . $date->format('Y')
            . DS . $date->format('m') . DS . $date->format('d')
            . DS . $date->format('Ymd_His');
        $dir = preg_replace('/(\/\/)/', '/', WWW_ROOT . $this->viewVars['userInfo']['diary_path']
             . $diaryPath);
        $dir = new Folder($dir , true, 0755);
        $diary->dir = $diaryPath; // 日記のパスをセット
        try {
            // 追加画像がある場合
            if (isset($this->request->data["image"])) {
                $files = $this->request->data['image'];
            }
            foreach ($files as $key => $file) {
                // ファイルが存在する、かつファイル名がblobの画像のとき
                if (!empty($file["name"]) && $file["name"] == 'blob') {
                    $limitFileSize = CAPACITY['MAX_NUM_BYTES_FILE'];
                    // ファイル名を取得する
                    $convertFile = $this->Util->file_upload($file, $files_befor, $dir->path, $limitFileSize);

                    // ファイル名が同じ場合は処理をスキップする
                    if ($convertFile === false) {
                        $isDuplicate = true;
                        continue;
                    }

                }
            }
            // レコード更新実行
            if (!$this->Diarys->save($diary)) {
                throw new RuntimeException('レコードの登録ができませんでした。');
            }
            // 更新情報を追加する
            $updates = $this->Updates->newEntity();
            $updates->set('content', $this->Auth->user('nickname').'さんが日記を追加しました。');
            $updates->set('shop_id', $this->Auth->user('shop_id'));
            $updates->set('user_id', $this->Auth->user('id'));
            $updates->set('type', SHOP_MENU_NAME['DIARY']);
            // レコード更新実行
            if (!$this->Updates->save($updates)) {
                throw new RuntimeException('レコードの登録ができませんでした。');
            }
        } catch (RuntimeException $e) {
            $this->log($this->Util->setLog($auth, $e));
            $flg = false;
        }

        // 例外が発生している場合にメッセージをセットして返却する
        if (!$flg) {
            // アップロード失敗の時、処理を中断する
            if (file_exists($dir->path)) {
                $dir->delete();// フォルダ削除
            }
            $message = RESULT_M['SIGNUP_FAILED'];
            $response = array(
                'success' => $flg,
                'message' => $message
            );
            $this->response->body(json_encode($response));
            return;
        }

        $diarys = $this->Util->getDiarys($id
            , $this->viewVars['userInfo']['diary_path']);

        $this->set(compact('diarys'));
        $this->render('/User/Users/diary');
        $response = array(
            'html' => $this->response->body(),
            'error' => $errors,
            'success' => $flg,
            'message' => $isDuplicate ? $message . "\n" . RESULT_M['DUPLICATE'] : $message
        );
        $this->response->body(json_encode($response));
        return;
    }

    /**
     * 日記アーカイブ表示画面の処理
     *
     * @return void
     */
    public function viewDiary()
    {
        // AJAXのアクセス以外は不正とみなす。
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException('AJAX以外でのアクセスがあります。');
        }
        $this->confReturnJson(); // json返却用の設定

        $diary = $this->Util->getDiary($this->request->query["id"]
            , $this->viewVars['userInfo']['diary_path']);

        $this->response->body(json_encode($diary));
        return;
    }

    /**
     * 日記アーカイブ更新処理
     *
     * @return void
     */
    public function updateDiary()
    {

        // AJAXのアクセス以外は不正とみなす。
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException('AJAX以外でのアクセスがあります。');
        }
        $flg = true; // 返却フラグ
        $isRemoved = false; // ディレクトリ削除フラグ
        $isDuplicate = false; // 画像重複フラグ
        $errors = ""; // 返却メッセージ
        $this->confReturnJson(); // responceがjsonタイプの場合の共通設定
        $message = RESULT_M['UPDATE_SUCCESS']; // 返却メッセージ
        $auth = $this->request->session()->read('Auth.User');
        $id = $auth['id']; // ユーザID
        $tmpDir = null; // バックアップ用
        $dir = preg_replace('/(\/\/)/', '/',
            WWW_ROOT.$this->request->data["dir_path"]);
        // 対象ディレクトリパス取得
        $dir = new Folder($dir, true, 0755);
        $files = array();
        $fileMax = PROPERTY['FILE_MAX'];

        // エンティティにマッピングする
        $diary = $this->Diarys->patchEntity($this->Diarys
            ->get($this->request->data['diary_id']), $this->request->getData());
        // バリデーションチェック
        if ($diary->errors()) {
            // 入力エラーがあれば、メッセージをセットして返す
            $errors = $this->Util->setErrMessage($diary); // エラーメッセージをセット
            $response = array('result'=>false,'message'=>$errors);
            $this->response->body(json_encode($response));
            return;
        }

        $delFiles = json_decode($this->request->data["del_list"], true);
        // 既に登録された画像があればデコードし格納、無ければ空の配列を格納する
        ($image_befor = json_decode($this->request->data["json_data"], true)) > 0
            ? : $image_befor = array();

        try {

            // 削除対象ディレクトリパス存在チェック
            if (!file_exists($dir->path)) {
                throw new RuntimeException('ディレクトリが存在しません。');
            }

            // ロールバック用のディレクトリサイズチェック
            if ($dir->dirsize() > CAPACITY['MAX_NUM_BYTES_DIR']) {
                throw new RuntimeException('ディレクトリサイズが大きすぎます。');
            }

            // 既に登録された画像がある場合は、ファイルのバックアップを取得
            if (count($image_befor) > 0) {

                // 一時ディレクトリ作成
                $tmpDir = new Folder(WWW_ROOT.$this->viewVars['userInfo']['tmp_path']
                     . DS . time(), true, 0777);
                // 一時ディレクトリにバックアップ実行
                if (!$dir->copy($tmpDir->path)) {
                    throw new RuntimeException('バックアップに失敗しました。');
                }
            }

            // 削除する画像分処理する
            foreach ($delFiles as $key => $file) {
                $delFile = new File(WWW_ROOT . DS .$file['path']);
                // ファイル削除処理実行
                if (!$delFile->delete()) {
                    throw new RuntimeException('画像の削除に失敗しました。');
                }
            }

            // 追加画像がある場合
            if (isset($this->request->data["image"])) {
                $files = $this->request->data['image'];
            }
            // 追加画像分処理する
            foreach ($files as $key => $file) {
                // ファイルが存在する、かつファイル名がblobの画像のとき
                if (!empty($file["name"]) && $file["name"] == 'blob') {
                    $limitFileSize = CAPACITY['MAX_NUM_BYTES_FILE'];
                    // ファイル名を取得する
                    $convertFile = $this->Util->file_upload($file, $image_befor, $dir->path, $limitFileSize);

                    // ファイル名が同じ場合は処理をスキップする
                    if ($convertFile === false) {
                        $isDuplicate = true;
                        continue;
                    }
                }
            }

            // レコード更新実行
            if (!$this->Diarys->save($diary)) {
                throw new RuntimeException('レコードの登録ができませんでした。');
            }

        } catch (RuntimeException $e) {
            $this->log($this->Util->setLog($auth, $e));
            $flg = false;
        }

        // 例外が発生している場合にメッセージをセットして返却する
        if (!$flg) {
            if (file_exists($tmpDir->path)) {
                // ファイルを戻す前にアップロードされたファイルがある場合があるため、空にしておく
                $files = $dir->find('.*\.*');
                foreach ($files as $file) {
                    $file = new File($dir->path . DS . $file);
                    $file->delete(); // このファイルを削除します
                }
                $tmpDir->copy($dir->path);
                $tmpDir->delete();// tmpフォルダ削除
            }
            $message = RESULT_M['UPDATE_FAILED'];
            $flg = false;
            $response = array(
                'success' => $flg,
                'message' => $message
            );
            $this->response->body(json_encode($response));
            return;
        }

        // 一時ディレクトリ削除
        if (file_exists($tmpDir->path)) {
            $tmpDir->delete();// tmpフォルダ削除
        }
        $diarys = $this->Util->getDiarys($id
            , $this->viewVars['userInfo']['diary_path']);

        $this->set(compact('diarys'));
        $this->render('/User/Users/diary');
        $response = array(
            'html' => $this->response->body(),
            'error' => $errors,
            'success' => $flg,
            'message' => $isDuplicate ? $message . "\n" . RESULT_M['DUPLICATE'] : $message
        );
        $this->response->body(json_encode($response));
        return;
    }

    /**
     * 日記アーカイブ削除処理
     *
     * @return void
     */
    public function deleteDiary()
    {
        // AJAXのアクセス以外は不正とみなす。
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException('AJAX以外でのアクセスがあります。');
        }
        $flg = true; // 返却フラグ
        $isRemoved = false; // ディレクトリ削除フラグ
        $errors = ""; // 返却メッセージ
        $this->confReturnJson(); // responceがjsonタイプの場合の共通設定
        $message = RESULT_M['DELETE_SUCCESS']; // 返却メッセージ
        $auth = $this->request->session()->read('Auth.User');
        $id = $auth['id']; // ユーザID
        $tmpDir = null; // バックアップ用

        try {
            $del_path = preg_replace('/(\/\/)/', '/',
                WWW_ROOT.$this->request->getData('dir_path'));
            // 削除対象ディレクトリパス取得
            $dir = new Folder($del_path);
            // 削除対象ディレクトリパス存在チェック
            if (!file_exists($dir->path)) {
                throw new RuntimeException('ディレクトリが存在しません。');
            }

            // ロールバック用のディレクトリサイズチェック
            if ($dir->dirsize() > CAPACITY['MAX_NUM_BYTES_DIR']) {
                throw new RuntimeException('ディレクトリサイズが大きすぎます。');
            }

            // 一時ディレクトリ作成
            $tmpDir = new Folder(
                WWW_ROOT.$this->viewVars['userInfo']['tmp_path'] . DS . time(), true, 0777);
            // 一時ディレクトリにバックアップ実行
            if (!$dir->copy($tmpDir->path)) {
                throw new RuntimeException('バックアップに失敗しました。');
            }

            // 日記ディレクトリ削除処理実行
            if (!$dir->delete()) {
                throw new RuntimeException('ディレクトリの削除ができませんでした。');
            }
            // ディレクトリ削除フラグを立てる
            $isRemoved = true;
            // 削除対象レコード取得
            $diary = $this->Diarys->get($this->request->getData('id'));
            // レコード削除実行
            if (!$this->Diarys->delete($diary)) {
                throw new RuntimeException('レコードの削除ができませんでした。');
            }
        } catch (RuntimeException $e) {
            // ディレクトリを削除していた場合は復元する
            if ($isRemoved) {
                $tmpDir->copy($dir->path);
            }
            // 一時ディレクトリがあれば削除する
            if (isset($tmpDir) && file_exists($tmpDir->path)) {
                $tmpDir->delete();// tmpディレクトリ削除
            }
            $this->log($this->Util->setLog($auth, $e));
            $flg = false;
        }
        // 例外が発生している場合にメッセージをセットして返却する
        if (!$flg) {
            $message = RESULT_M['DELETE_FAILED'];
            $response = array(
                'success' => $flg,
                'message' => $message
            );
            $this->response->body(json_encode($response));
            return;
        }

        // 一時ディレクトリ削除
        if (file_exists($tmpDir->path)) {
            $tmpDir->delete();
        }
        $diarys = $this->Util->getDiarys($id
            , $this->viewVars['userInfo']['diary_path']);

        $this->set(compact('diarys'));
        $this->render('/User/Users/diary');
        $response = array(
            'html' => $this->response->body(),
            'error' => $errors,
            'success' => $flg,
            'message' => $message
        );
        $this->response->body(json_encode($response));
        return;
    }

    public function login()
    {
        // レイアウトを使用しない
        $this->viewBuilder()->autoLayout(false);

        if ($this->request->is('post')) {

            // バリデーションはログイン用を使う。
            $user = $this->Users->newEntity($this->request->getData(), ['validate' => 'userLogin']);

            if (!$user->errors()) {
                $this->log($this->request->getData("remember_me"), "debug");
                $user = $this->Auth->identify();
                if ($user) {
                    $this->Auth->setUser($user);
                    Log::info($this->Util->setAccessLog(
                        $user, $this->request->params['action']), 'access');

                    return $this->redirect($this->Auth->redirectUrl());
                }

                $this->Flash->error(RESULT_M['FRAUD_INPUT_FAILED']);
            } else {
                foreach ($user->errors() as $key1 => $value1) {
                    foreach ($value1 as $key2 => $value2) {
                        $this->Flash->error($value2);
                    }
                }
            }
        } else {
            $user = $this->Users->newEntity();
        }
        $this->set('user', $user);
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

        $masterCodesFind = array('age');
        $selectList = $this->Util->getSelectList($masterCodesFind, $this->MasterCodes, true);
        $this->set(compact('user', 'selectList'));
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
