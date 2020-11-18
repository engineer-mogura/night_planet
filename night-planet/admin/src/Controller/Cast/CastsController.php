<?php
namespace App\Controller\Cast;

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
class CastsController extends AppController
{
    use MailerAwareTrait;

    public function beforeFilter(Event $event)
    {
        // AppController.beforeFilterをコールバック
        parent::beforeFilter($event);
        // スタッフに関する情報をセット
        if (!is_null($user = $this->Auth->user())) {
            if ($this->Casts->exists(['id' => $user['id']])) {
                $cast = $this->Casts->get($user['id']);
                // オーナーに関する情報をセット
                $shop = $this->Shops->find("all")
                    ->where(['shops.id'=>$user['shop_id']])
                    ->contain(['owners.servece_plans'])
                    ->first();
                $this->set('userInfo', $this->Util->getCastItem($cast, $shop));
            } else {
                $session = $this->request->getSession();
                $session->destroy();
                $this->Flash->error('うまくアクセス出来ませんでした。もう一度やり直してみてください。');
            }

        }
    }

    /**
     * スタッフ画面トップの処理
     *
     * @return void
     */
    public function index()
    {
        $auth = $this->request->session()->read('Auth.Cast');
        $id = $auth['id']; // スタッフID

        $cast = $this->Casts->find('all')
            ->contain(['shops', 'cast_likes' => function ($q) use ($id){
                return $q
                    ->select(['cast_likes.cast_id'
                        , 'total' => $q->func()->count('cast_likes.cast_id')])
                    ->group('cast_likes.cast_id')
                    ->where(['cast_likes.cast_id' => $id]);
            }, 'diarys' => function ($q) use ($id){
                return $q
                    ->select(['diarys.id', 'diarys.cast_id'
                        , 'total' => $q->func()->count('diarys.id')])
                    ->group('diarys.id')
                    ->where(['diarys.id' => $id]);
            }])
            ->where(['casts.id' => $id])
            ->first();

        // 非表示または論理削除している場合はログイン画面にリダイレクトする
        if (!$this->checkStatus($cast)) {
            return $this->redirect($this->Auth->logout());
        }

        // JSONファイルをDB内容にて、更新する
        // JSONファイルに書き込むカラム情報
        $cast_schedule = $this->CastSchedules
            ->find('all')->select($this->CastSchedules)
            ->where(['id' => $userInfo['id'], 'shop_id'=> $userInfo['shop_id']]);
        $cast_schedule = json_encode($cast_schedule);

        $masterCodesFind = array('time','event');
        $selectList = $this->Util->getSelectList($masterCodesFind, $this->MasterCodes, true);

        $this->set(compact('cast', 'selectList', 'cast_schedule'));
        $this->render();
    }

    /**
     * スタッフ画面トップの処理
     *
     * @return void
     */
    public function favo($page)
    {
        $auth = $this->request->session()->read('Auth.Cast');
        $id = $auth['id']; // スタッフID
        if ($page == 'favo') {
            $cast = $this->Casts->find('all')
                ->contain(['cast_likes' => function ($q) use ($id){
                    return $q
                        ->select(['cast_likes.cast_id'
                            , 'total' => $q->func()->count('cast_likes.cast_id')])
                        ->group('cast_likes.cast_id')
                        ->where(['cast_likes.cast_id' => $id]);
                }]);
        } else if ($page == 'likes') {
            $cast = $this->Casts->find('all')
                ->contain(['cast_likes' => function ($q) use ($id){
                    return $q
                        ->select(['cast_likes.cast_id'
                            , 'total' => $q->func()->count('cast_likes.cast_id')])
                        ->group('cast_likes.cast_id')
                        ->where(['cast_likes.cast_id' => $id]);
                }]);
        } else if ($page == 'diary') {
            $cast = $this->Casts->find('all')
                ->contain(['shops', 'cast_likes' => function ($q) use ($id){
                    return $q
                        ->select(['cast_likes.cast_id'
                            , 'total' => $q->func()->count('cast_likes.cast_id')])
                        ->group('cast_likes.cast_id')
                        ->where(['cast_likes.cast_id' => $id]);
                }]);
        }

        // 非表示または論理削除している場合はログイン画面にリダイレクトする
        if (!$this->checkStatus($cast)) {
            return $this->redirect($this->Auth->logout());
        }

        // JSONファイルをDB内容にて、更新する
        // JSONファイルに書き込むカラム情報
        $cast_schedule = $this->CastSchedules
            ->find('all')->select($this->CastSchedules)
            ->where(['id' => $userInfo['id'], 'shop_id'=> $userInfo['shop_id']]);
        $cast_schedule = json_encode($cast_schedule);

        $masterCodesFind = array('time','event');
        $selectList = $this->Util->getSelectList($masterCodesFind, $this->MasterCodes, true);

        $this->set(compact('cast', 'selectList', 'cast_schedule'));
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
        $auth = $this->request->session()->read('Auth.Cast');
        $id = $auth['id']; // スタッフID

        try {

            // イベント削除の場合
            if ($this->request->data["crud_type"] == "delete") {
                $message = RESULT_M['DELETE_SUCCESS'];
                $cast_schedules = $this->CastSchedules->get($this->request->data["id"]);
                if (!$this->CastSchedules->delete($cast_schedules)) {
                    throw new RuntimeException('レコードの削除ができませんでした。');
                    $message = RESULT_M['DELETE_FAILED'];
                }

            } elseif ($this->request->data["crud_type"] == "update") {
                // イベント編集の場合
                $message = RESULT_M['UPDATE_SUCCESS'];
                $cast_schedules = $this->CastSchedules->patchEntity(
                    $this->CastSchedules->get($this->request->getData('id')), $this->request->getData());
                if (!$this->CastSchedules->save($cast_schedules)) {
                    throw new RuntimeException('レコードの更新ができませんでした。');
                    $message = RESULT_M['UPDATE_FAILED'];
                }

            } elseif ($this->request->data["crud_type"] == "create") {
                // イベント追加の場合
                $message = RESULT_M['SIGNUP_SUCCESS'];
                $cast_schedules = $this->CastSchedules->newEntity($this->request->getData());
                if (!$this->CastSchedules->save($cast_schedules)) {
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

            $cast_schedule = $this->CastSchedules->find('all', array('fields' => $Columns))
                ->where(['shop_id' => $this->viewVars['userInfo']['shop_id']
                    , 'cast_id' => $this->viewVars['userInfo']['id']]);

            // イベント情報全てJSONファイルに上書きする
            $file->write(json_encode($cast_schedule));
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
        $auth = $this->request->session()->read('Auth.Cast');
        $id = $auth['id']; // ユーザーID
        // 非表示または論理削除している場合はログイン画面にリダイレクトする
        if (!$this->checkStatus($this->Casts->get($this->viewVars['userInfo']['id']))) {
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

                $cast = $this->Casts->get($this->viewVars['userInfo']['id']);

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
                        $updates->set('cast_id', $this->Auth->user('id'));
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
                $cast = $this->Casts->patchEntity($this->Casts
                ->get($id), $this->request->getData(), ['validate' => 'profile']);
                // バリデーションチェック
                if ($cast->errors()) {
                    $flg = false;
                    // 入力エラーがあれば、メッセージをセットして返す
                $message = $this->Util->setErrMessage($cast); // エラーメッセージをセット
                $response = array(
                    'success' => $flg,
                    'message' => $message
                );
                    $this->response->body(json_encode($response));
                    return;
                }
                try {
                    // レコード更新実行
                    if (!$this->Casts->save($cast)) {
                        throw new RuntimeException('レコードの更新ができませんでした。');
                    }
                    // 更新情報を追加する
                    $updates = $this->Updates->newEntity();
                    $updates->set('content', $this->Auth->user('nickname').'さんがプロフィールを更新しました。');
                    $updates->set('shop_id', $this->Auth->user('shop_id'));
                    $updates->set('cast_id', $this->Auth->user('id'));
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

            $cast = $this->Casts->get($id);
            // 作成するセレクトボックスを指定する
            $masCodeFind = array('time','constellation','blood_type','age');
            // セレクトボックスを作成する
            $selectList = $this->Util->getSelectList($masCodeFind, $this->MasterCodes, true);

            $this->set(compact('cast','selectList', 'icons'));
            $this->render('/Cast/Casts/profile');
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

        $cast = $this->Casts->get($id);
        // 作成するセレクトボックスを指定する
        $masCodeFind = array('time','constellation','blood_type','age');
        // セレクトボックスを作成する
        $selectList = $this->Util->getSelectList($masCodeFind, $this->MasterCodes, true);

        $this->set(compact('cast', 'selectList', 'icons'));
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
        $auth = $this->request->session()->read('Auth.Cast');
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
                    $updates->set('cast_id', $this->Auth->user('id'));
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
        $this->render('/Cast/Casts/top_image');
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
        $auth = $this->request->session()->read('Auth.Cast');

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
        $this->render('/Cast/Casts/top_image');
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
        $auth = $this->request->session()->read('Auth.Cast');
        $id = $auth['id']; // スタッフID

        // 非表示または論理削除している場合はログイン画面にリダイレクトする
        if (!$this->checkStatus($this->Casts->get($this->viewVars['userInfo']['id']))) {
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
            if (!$this->Snss->exists(['cast_id' =>$this->viewVars['userInfo']['id']])) {
                $sns = $this->Snss->newEntity($this->request->getData());
                $sns->cast_id = $this->viewVars['userInfo']['id'];
            } else {
                // snsテーブルからidのみを取得する
                $sns_id = $this->Snss->find()
                    ->select('id')
                    ->where(['cast_id' =>$id])->first()->id;

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

            $cast = $this->Casts->find()
                ->where(['id' => $this->viewVars['userInfo']['id']])
                ->contain(['Snss'])->first();

            $this->set(compact('cast'));
            $this->render('/Cast/Casts/sns');
            $response = array(
                'html' => $this->response->body(),
                'error' => $errors,
                'success' => $flg,
                'message' => $message
            );
            $this->response->body(json_encode($response));
            return;
        }
        $cast = $this->Casts->find()
            ->where(['id' => $id])
            ->contain(['Snss'])->first();
 
        $this->set(compact('cast'));
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
        $auth = $this->request->session()->read('Auth.Cast');
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
            $updates->set('cast_id', $this->Auth->user('id'));
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
        $this->render('/Cast/Casts/gallery');
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
        $auth = $this->request->session()->read('Auth.Cast');

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
        $this->render('/Cast/Casts/gallery');
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
        $allDiary = $this->getAllDiary($this->viewVars['userInfo']['id']
            , $this->viewVars['userInfo']['diary_path'], null);
        $top_diary = $allDiary[0];
        $arcive_diary = $allDiary[1];
        $this->set(compact('top_diary', 'arcive_diary'));
        return $this->render();
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
        $auth = $this->request->session()->read('Auth.Cast');
        $id = $auth['id']; // スタッフID
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
            $updates->set('cast_id', $this->Auth->user('id'));
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
        // 日記取得
        $allDiary = $this->getAllDiary($this->viewVars['userInfo']['id']
            , $this->viewVars['userInfo']['diary_path'], null);
        $top_diary = $allDiary[0];
        $arcive_diary = $allDiary[1];

        $this->set(compact('top_diary', 'arcive_diary'));
        $this->render('/Cast/Casts/diary');
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
            , $this->viewVars['userInfo']['diary_path'], null);

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
        $auth = $this->request->session()->read('Auth.Cast');
        $id = $auth['id']; // スタッフID
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
        // 日記取得
        $allDiary = $this->getAllDiary($this->viewVars['userInfo']['id']
            , $this->viewVars['userInfo']['diary_path'], null);
        $top_diary = $allDiary[0];
        $arcive_diary = $allDiary[1];

        $this->set(compact('top_diary', 'arcive_diary'));
        $this->render('/Cast/Casts/diary');
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
        $auth = $this->request->session()->read('Auth.Cast');
        $id = $auth['id']; // スタッフID
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
        // 日記取得
        $allDiary = $this->getAllDiary($this->viewVars['userInfo']['id']
            , $this->viewVars['userInfo']['diary_path'], null);
        $top_diary = $allDiary[0];
        $arcive_diary = $allDiary[1];

        $this->set(compact('top_diary', 'arcive_diary'));
        $this->render('/Cast/Casts/diary');
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
            $cast = $this->Casts->newEntity($this->request->getData(), ['validate' => 'castLogin']);

            if (!$cast->errors()) {
                $this->log($this->request->getData("remember_me"), "debug");
                $cast = $this->Auth->identify();
                if ($cast) {
                    $this->Auth->setUser($cast);
                    Log::info($this->Util->setAccessLog(
                        $cast, $this->request->params['action']), 'access');

                    return $this->redirect($this->Auth->redirectUrl());
                }

                $this->Flash->error(RESULT_M['FRAUD_INPUT_FAILED']);
            } else {
                foreach ($cast->errors() as $key1 => $value1) {
                    foreach ($value1 as $key2 => $value2) {
                        $this->Flash->error($value2);
                    }
                }
            }
        } else {
            $cast = $this->Casts->newEntity();
        }
        $this->set('cast', $cast);
    }

    public function logout()
    {
        $auth = $this->request->session()->read('Auth.Cast');

        Log::info($this->Util->setAccessLog(
            $auth, $this->request->params['action']), 'access');

        // レイアウトを使用しない
        $this->viewBuilder()->autoLayout(false);
        $this->Flash->success(COMMON_M['LOGGED_OUT']);
        return $this->redirect($this->Auth->logout());
    }

    /**
     * スタッフ登録時の認証
     *
     * @param [type] $token
     * @return void
     */
    public function verify($token)
    {
        // シンプルレイアウトを使用
        $this->viewBuilder()->layout('simpleDefault');
        try {
            $tmp = $this->Tmps->get(Token::getId($token));
        } catch(RuntimeException $e) {
            $this->Flash->error('URLが無効になっています。');
            return $this->render('/common/error');
        }

        // 以下でトークンの有効期限や改ざんを検証することが出来る
        if (!$tmp->tokenVerify($token)) {
            $this->log($this->Util->setLog($tmp
                , 'トークンの有効期限が切れたか、改ざんが行われた可能性があります。'));
            // 仮登録してるレコードを削除する
            $this->Tmps->delete($tmp);

            $this->Flash->error(RESULT_M['AUTH_FAILED']);
            return $this->render('/common/error');
        }

        // スタッフレイアウトを使用
        $this->viewBuilder()->layout('castDefault');

        // 仮登録時点で削除フラグは立っている想定。
        if ($tmp->delete_flag != 1) {
            // すでに登録しているとみなし、ログイン画面へ
            $this->Flash->success(RESULT_M['REGISTERED_FAILED']);
            return $this->redirect(['action' => 'login']);
        }
        // 店舗情報を取得
        $shopInfo = $this->Util->getShopInfo($this->Shops->get($tmp->shop_id));
        // スタッフ用のディレクトリを掘る
        $dir = new Folder( preg_replace('/(\/\/)/', '/',
                WWW_ROOT . $shopInfo['cast_path'].DS) , true, 0755);

        // ディレクトリ存在フラグ
        $exists = true;

        while ($exists) {
            $newDir = $this->Util->makeRandStr(15);
            if (!file_exists($dir->path . $newDir)) {
                $exists = false;
            }
        }

        // コネクションオブジェクト取得
        $connection = ConnectionManager::get('default');
        // トランザクション処理開始
        $connection->begin();

        try{

            // スタッフ情報セット
            $tmp->dir = $newDir; // 連番ディレクトリをセット
            $tmp->delete_flag = 0; // 論理削除フラグを下げる
            $data = ['shop_id'=>$tmp->shop_id,'role'=>$tmp->role
                ,'name'=>$tmp->name,'email'=>$tmp->email
                ,'password'=>$tmp->password,'age'=>$tmp->age
                ,'dir'=>$tmp->dir,'status'=>$tmp->status
                ,'delete_flag'=>$tmp->delete_flag];

            // 新規エンティティ
            $newCast = $this->Casts->newEntity();
            $cast = $this->Casts->patchEntity($newCast, $data);

            // スタッフ登録
            if (!$this->Casts->save($cast)) {

                throw new RuntimeException('レコードの更新に失敗しました。');
            }
            // 更新情報を追加する
            $updates = $this->Updates->newEntity();
            $updates->set('content', '新しいスタッフを追加しました。');
            $updates->set('shop_id', $this->Auth->user('shop_id'));
            $updates->set('cast_id', $this->Auth->user('id'));
            $updates->set('type', SHOP_MENU_NAME['DIARY']);
            // レコード更新実行
            if (!$this->Updates->save($updates)) {
                throw new RuntimeException('レコードの登録ができませんでした。');
            }

            // ディレクトリを掘る
            $dir = new Folder($dir->path.$newDir, true, 0755);
            $paths[] = $dir->path . DS . PATH_ROOT['TOP_IMAGE'];
            $paths[] = $dir->path . DS . PATH_ROOT['IMAGE'];
            $paths[] = $dir->path . DS . PATH_ROOT['PROFILE'];
            $paths[] = $dir->path . DS . PATH_ROOT['DIARY'];
            $paths[] = $dir->path . DS . PATH_ROOT['SCHEDULE'];
            $paths[] = $dir->path . DS . PATH_ROOT['TMP'];
            // その他ディレクトリ作成
            if (!$this->Util->createDir($paths)) {
                throw new RuntimeException('ディレクトリの作成に失敗しました。');
            }
            // コミット
            $connection->commit();

            // 認証完了したら、メール送信
            $email = new Email('default');
            $email->setFrom([MAIL['FROM_SUBSCRIPTION'] => MAIL['FROM_NAME']])
                ->setSubject($cast->name."様、メールアドレスの認証が完了しました。")
                ->setTo($cast->email)
                ->setBcc(MAIL['FROM_INFO_GMAIL'])
                ->setTemplate("auth_success")
                ->setLayout("auth_success_layout")
                ->emailFormat("html")
                ->viewVars(['cast' => $cast])
                ->send();
            $this->set('cast', $cast);
            $this->log($email,'debug');
            // 一時テーブル削除
            $this->Tmps->delete($tmp);

        } catch(RuntimeException $e) {
            // ロールバック
            $connection->rollback();
            $this->log($this->Util->setLog($cast, $e));
            // 仮登録してるレコードを削除する
            $this->Tmps->delete($tmp);
            $this->Flash->error(RESULT_M['AUTH_FAILED']);
            return $this->redirect(['action' => 'login']);
        }
        // 認証完了でログインページへ
        $this->Flash->success(RESULT_M['AUTH_SUCCESS']);
        return $this->redirect(['action' => 'login']);

    }

    public function passReset()
    {
        // シンプルレイアウトを使用
        $this->viewBuilder()->layout('simpleDefault');

        if ($this->request->is('post')) {

            // バリデーションはパスワードリセットその１を使う。
            $cast = $this->Casts->newEntity( $this->request->getData()
                , ['validate' => 'CastPassReset1']);

            if(!$cast->errors()) {
                // メールアドレスで取得
                $cast = $this->Casts->find()
                    ->where(['email' => $cast->email])->first();

                // 非表示または論理削除している場合はログイン画面にリダイレクトする
                if (!$this->checkStatus($cast)) {
                    return $this->redirect($this->Auth->logout());
                }

                $email = new Email('default');
                $email->setFrom([MAIL['FROM_SUBSCRIPTION'] => MAIL['FROM_NAME']])
                    ->setSubject(MAIL['FROM_NAME_PASS_RESET'])
                    ->setTo($cast->email)
                    ->setBcc(MAIL['FROM_INFO_GMAIL'])
                    ->setTemplate("pass_reset_email")
                    ->setLayout("simple_layout")
                    ->emailFormat("html")
                    ->viewVars(['cast' => $cast])
                    ->send();
                $this->set('cast', $cast);

                $this->Flash->success('パスワード再設定用メールを送信しました。');
                Log::info("ID：【".$cast['id']."】アドレス：【".$cast->email
                    ."】パスワード再設定用メールを送信しました。", 'pass_reset');

                return $this->render('/common/pass_reset_send');

            } else {
                // 送信失敗
                foreach ($cast->errors() as $key1 => $value1) {
                    foreach ($value1 as $key2 => $value2) {
                        $this->Flash->error($value2);
                        Log::error("ID：【".$cast['id']."】アドレス：【".$cast->email
                            ."】エラー：【".$value2."】", 'pass_reset');
                    }
                }
            }
        } else {
            $cast = $this->Casts->newEntity();
        }
        $this->set('cast', $cast);
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
        $cast = $this->Auth->identify();
        try {
            $cast = $this->Casts->get(Token::getId($token));
        } catch(RuntimeException $e) {
            $this->Flash->error('URLが無効になっています。');
            return $this->render('/common/pass_reset_form');
        }

        // 以下でトークンの有効期限や改ざんを検証することが出来る
        if (!$cast->tokenVerify($token)) {
            Log::info("ID：【".$cast->id."】"."アドレス：【".$cast->email."】".
                "エラー：【".RESULT_M['PASS_RESET_FAILED']."】アクション：【"
                . $this->request->params['action']. "】", "pass_reset");

            $this->Flash->error(RESULT_M['PASS_RESET_FAILED']);
            return $this->render('/common/pass_reset_form');
        }

        if ($this->request->is('post')) {

            // パスワードリセットフォームの表示フラグ
            $is_reset_form = false;

            // バリデーションはパスワードリセットその２を使う。
            $validate = $this->Casts->newEntity( $this->request->getData()
                , ['validate' => 'CastPassReset2']);

           if (!$validate->errors()) {

                // 再設定したバスワードを設定する
                $cast->password = $this->request->getData('password');
                // 自動ログインフラグを下げる
                $cast->remember_token = 0;

                // 一応ちゃんと変更されたかチェックする
                if (!$cast->isDirty('password')) {

                    Log::info("ID：【".$cast->id."】"."アドレス：【".$cast->email."】".
                    "エラー：【パスワードの変更に失敗しました。】アクション：【"
                        . $this->request->params['action']. "】", "pass_reset");

                    $this->Flash->error('パスワードの変更に失敗しました。');
                    return $this->redirect(['action' => 'login']);
                }

               if ($this->Casts->save($cast)) {

                    // 変更完了したら、メール送信
                    $email = new Email('default');
                    $email->setFrom([MAIL['FROM_SUBSCRIPTION'] => MAIL['FROM_NAME']])
                        ->setSubject($cast->name."様、メールアドレスの変更が完了しました。")
                        ->setTo($cast->email)
                        ->setBcc(MAIL['FROM_INFO_GMAIL'])
                        ->setTemplate("pass_reset_success")
                        ->setLayout("simple_layout")
                        ->emailFormat("html")
                        ->viewVars(['cast' => $cast])
                        ->send();
                    $this->set('cast', $cast);

                    // 変更完了でログインページへ
                    $this->Flash->success(RESULT_M['PASS_RESET_SUCCESS']);
                    Log::info("ID：【".$cast['id']."】アドレス：【".$cast->email
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
            $this->set(compact('is_reset_form','cast'));
            return $this->render('/common/pass_reset_form');
        }
    }

    public function passChange()
    {
        $auth = $this->request->session()->read('Auth.Cast');
        $id = $auth['id']; // ユーザーID
        $new_pass = "";
        if ($this->request->is('post')) {

            $isValidate = false; // エラー有無
            // バリデーションはパスワードリセットその３を使う。
            $validate = $this->Casts->newEntity( $this->request->getData()
                , ['validate' => 'CastPassReset3']);

            if(!$validate->errors()) {

                $hasher = new DefaultPasswordHasher();
                $cast = $this->Casts->get($this->viewVars['userInfo']['id']);
                $equal_check = $hasher->check($this->request->getData('password')
                    , $cast->password);
                // 入力した現在のパスワードとデータベースのパスワードを比較する
                if (!$equal_check) {
                    $this->Flash->error('現在のパスワードが間違っています。');
                    return $this->render();
                }
                // 新しいバスワードを設定する
                $cast->password = $this->request->getData('password_new');

                // 一応ちゃんと変更されたかチェックする
                if (!$cast->isDirty('password')) {

                    Log::info("ID：【".$cast->id."】"."アドレス：【".$cast->email."】".
                    "エラー：【パスワードの変更に失敗しました。】アクション：【"
                        . $this->request->params['action']. "】", "pass_reset");

                    $this->Flash->error('パスワードの変更に失敗しました。');
                    return $this->render();
                }

                try {
                    // レコード更新実行
                    if (!$this->Casts->save($cast)) {
                        throw new RuntimeException('レコードの更新ができませんでした。');
                    }
                    $this->Flash->success('パスワードの変更をしました。');
                    return $this->redirect('/cast/casts/profile');

                } catch (RuntimeException $e) {
                    $this->log($this->Util->setLog($auth, $e));
                    $this->Flash->error('パスワードの変更に失敗しました。');
                }

            } else {
                $cast = $validate;
            }
        } else {
            $cast = $this->Casts->newEntity();
        }
        $this->set('cast', $cast);
        return $this->render();
    }

    /**
     * 全ての日記を取得する処理
     *
     * @return void
     */
    public function getAllDiary($id, $diary_path, $user_id = null)
    {
        $diary = $this->Util->getDiarys($id
            , $this->viewVars['userInfo']['diary_path'], null);
        $top_diary = array();
        $arcive_diary = array();
        $count = 0;
        foreach ($diary as $key1 => $rows) :
            foreach ($rows as $key2 => $row) :
                if ($count == 5) :
                    break;
                endif;
                array_push($top_diary, $row);
                unset($diary[$key1][$key2]);
                $count = $count + 1;
            endforeach;
        endforeach;
        foreach ($diary as $key => $rows) :
            if (count($rows) == 0) :
                unset($diary[$key]);
            endif;
        endforeach;
        foreach ($diary as $key1 => $rows) :
            $tmp_array = array();
            foreach ($rows as $key2 => $row) :
                array_push($tmp_array, $row);
            endforeach;
            array_push($arcive_diary, array_values($tmp_array));
        endforeach;

        return array($top_diary, $arcive_diary);
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
