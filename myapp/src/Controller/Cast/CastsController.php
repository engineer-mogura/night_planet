<?php
namespace App\Controller\Cast;

use Cake\Log\Log;
use Cake\I18n\Time;
use Cake\Event\Event;
use RuntimeException;
use Token\Util\Token;
use Cake\Filesystem\File;
use Cake\Filesystem\Folder;
use Cake\Collection\Collection;
use Cake\Datasource\ConnectionManager;

/**
 * Controls the data flow into shops object and updates the view whenever data changes.
 */
class CastsController extends AppController
{
    public function beforeFilter(Event $event)
    {
        // AppController.beforeFilterをコールバック
        parent::beforeFilter($event);
        // キャストに関する情報をセット
        if (!is_null($user = $this->Auth->user())) {
            $cast = $this->Casts->get($user['id']);
            $shop = $this->Shops->get($user['shop_id']);
            $this->set('userInfo', $this->Util->getCastItem($cast, $shop));
        }
    }

    /**
     * キャスト画面トップの処理
     *
     * @return void
     */
    public function index()
    {
        $auth = $this->request->session()->read('Auth.Cast');
        $id = $auth['id']; // キャストID
        $query = $this->Diarys->find();
        // キャストの記事といいね数を取得
        $diarys = $query->select(['id',
            'diary_like_num'=> $query->func()->count('diary_likes.diary_id')])
            ->contain('diary_likes')
            ->leftJoinWith('diary_likes')
            ->where(['diarys.cast_id'=>$id])
            ->group(['diary_likes.diary_id'])
            ->order(['diary_like_num' => 'desc'])->toArray();
        $likeTotal = array_sum(array_column($diarys, 'diary_like_num'));
        //$like = $query->select(['total_like' => $query->func()->sum('cast_id')])
        //$diarydiary_likes = $this->Diarys->find('all')->where(['cast_id'=>$id])->contain('diary_likes');
        $cast = $this->Casts->find('all')
            ->contain(['shops','diarys'])->where(['casts.id'=>$id])->first();

        // JSONファイルをDB内容にて、更新する
        // JSONファイルに書き込むカラム情報
        $Columns = array('id','title','start','end'
            ,'time_start', 'time_end','all_day');

        $cast_schedule = $this->CastSchedules->find('all', array('fields' => $Columns))
            ->where(['id'=>$userInfo['id'], 'shop_id'=> $userInfo['shop_id']]);
        $cast_schedule = json_encode($cast_schedule);
        // JSONファイルに書き込む
        $tmp_path = preg_replace('/(\/\/)/', '/',
            WWW_ROOT.$this->viewVars['userInfo']['tmp_path']);

        $masterCodesFind = array('time','event');
        $selectList = $this->Util->getSelectList($masterCodesFind, $this->MasterCodes, true);

        $this->set(compact('cast', 'likeTotal', 'selectList', 'cast_schedule'));
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
        $id = $auth['id']; // キャストID

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

                        // 更新情報を追加する
                        $updates = $this->Updates->newEntity();
                        $updates->set('content', $this->Auth->user('nickname').'さんがプロフィールアイコンを更新しました。');
                        $updates->set('shop_id', $this->Auth->user('shop_id'));
                        $updates->set('cast_id', $this->Auth->user('id'));
                        $updates->set('type', SHOP_MENU_NAME['WORK_SCHEDULE']);
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
            $this->render('/cast/casts/profile');
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

        $gallery = array();

        // ディクレトリ取得
        $dir = new Folder(preg_replace('/(\/\/)/', '/'
            , WWW_ROOT.$this->viewVars['userInfo']['top_image_path'])
            , true, 0755);

        // ファイルは１つのみだけど配列で取得する
        $files = glob($dir->path.DS.'*.*');
        foreach( $files as $file ) {
            $timestamp = date('Y/m/d H:i', filemtime($file));
            array_push($gallery, array(
                "file_path"=>$this->viewVars['userInfo']['top_image_path'].DS.(basename( $file ))
                ,"date"=>$timestamp));
        }
        $this->set(compact('gallery'));
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
        $this->render('/cast/casts/top_image');
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
        $this->render('/cast/casts/top_image');
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
        $id = $auth['id']; // キャストID

        // AJAXのアクセス以外は不正とみなす。
        if ($this->request->is('ajax')) {
            $flg = true; // 返却フラグ
            $errors = ""; // 返却メッセージ
            $this->confReturnJson(); // responceがjsonタイプの場合の共通設定
            $message = RESULT_M['UPDATE_SUCCESS']; // 返却メッセージ
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
            $this->render('/cast/casts/sns');
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
        $this->render('/cast/casts/gallery');
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
        $this->render('/cast/casts/gallery');
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
        $id = $this->request->getSession()->read('Auth.Cast.id');

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
        $auth = $this->request->session()->read('Auth.Cast');
        $id = $auth['id']; // キャストID
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

        $diarys = $this->Util->getDiarys($id
            , $this->viewVars['userInfo']['diary_path']);

        $this->set(compact('diarys'));
        $this->render('/cast/casts/diary');
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
        $auth = $this->request->session()->read('Auth.Cast');
        $id = $auth['id']; // キャストID
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
        $this->render('/cast/casts/diary');
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
        $id = $auth['id']; // キャストID
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
        $this->render('/cast/casts/diary');
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
                debug("this->request->getData()");
                debug($this->request->getData());
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
     * キャスト登録時の認証
     *
     * @param [type] $token
     * @return void
     */
    public function verify($token)
    {
        $cast = $this->Casts->get(Token::getId($token));

        // 以下でトークンの有効期限や改ざんを検証することが出来る
        if (!$cast->tokenVerify($token)) {
            $this->log($this->Util->setLog($cast
                , 'トークンの有効期限が切れたか、改ざんが行われた可能性があります。'));
            // 仮登録してるレコードを削除する
            $this->Casts->delete($cast);
            $this->Flash->success(RESULT_M['AUTH_FAILED']);
            return $this->redirect(['action' => 'signup']);
        }
        // 仮登録時点で削除フラグは立っている想定。
        if ($cast->delete_flag != 1) {
            // すでに登録しているとみなし、ログイン画面へ
            $this->Flash->success(RESULT_M['REGISTERED_FAILED']);
            return $this->redirect(['action' => 'login']);
        }
        // 店舗情報を取得
        $shopInfo = $this->Util->getShopInfo($this->Shops->get($cast->shop_id));
        // キャスト用のディレクトリを掘る
        $dir = new Folder( preg_replace('/(\/\/)/', '/',
                WWW_ROOT . $shopInfo['cast_path'].DS) , true, 0755);

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

        try{
            // パスが存在しなければディレクトリを掘ってDB登録
            if (realpath($dir->path.$nextDir)) {

                throw new RuntimeException('既にディレクトリが存在します。');
            }
            // キャスト情報セット
            $cast->dir = $nextDir; // 連番ディレクトリをセット
            $cast->delete_flag = 0; // 論理削除フラグを下げる
            // キャスト登録
            if (!$this->Casts->save($cast)) {

                throw new RuntimeException('レコードの更新に失敗しました。');
            }
            // 更新情報を追加する
            $updates = $this->Updates->newEntity();
            $updates->set('content', '新しいキャストを追加しました。');
            $updates->set('shop_id', $this->Auth->user('shop_id'));
            $updates->set('cast_id', $this->Auth->user('id'));
            $updates->set('type', SHOP_MENU_NAME['DIARY']);
            // レコード更新実行
            if (!$this->Updates->save($updates)) {
                throw new RuntimeException('レコードの登録ができませんでした。');
            }

            // ディレクトリを掘る
            $dir = new Folder($dir->path.$nextDir, true, 0755);
            // コミット
            $connection->commit();

        } catch(RuntimeException $e) {
            // ロールバック
            $connection->rollback();
            $this->log($this->Util->setLog($cast, $e));
            // 仮登録してるレコードを削除する
            $this->Casts->delete($cast);
            $this->Flash->error(RESULT_M['AUTH_FAILED']);
            return $this->redirect(['action' => 'login']);
        }
        // 認証完了でログインページへ
        $this->Flash->success(RESULT_M['AUTH_SUCCESS']);
        return $this->redirect(['action' => 'login']);

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
