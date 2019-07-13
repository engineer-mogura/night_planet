<?php
namespace App\Controller\Cast;

use Cake\I18n\Time;
use Cake\Event\Event;
use RuntimeException;
use Token\Util\Token;
use Cake\Mailer\Email;
use Cake\Core\Configure;
use Cake\Error\Debugger;
use Cake\Filesystem\File;
use Cake\Filesystem\Folder;
use Cake\ORM\TableRegistry;
use Cake\Collection\Collection;
use Cake\Mailer\MailerAwareTrait;
use Cake\Datasource\ConnectionManager;

/**
 * Controls the data flow into shops object and updates the view whenever data changes.
 */
class CastsController extends AppController
{
    public function beforeFilter(Event $event) {
        // AppController.beforeFilterをコールバック
        parent::beforeFilter($event);
        // キャストに関する情報をセット
        if(!is_null($user = $this->Auth->user())){
            $shop = $this->Shops->get($user['shop_id']);
            $this->set('shopInfo', $this->Util->getItem($shop));
        }
    }

    /**
     * 編集画面の処理
     *
     * @param [type] $id
     * @return void
     */
    public function index($id = null)
    {
        // アクティブタブを空で初期化
        $activeTab = "";

        if (isset($this->request->query["activeTab"])) {
            $activeTab = $this->request->getQuery("activeTab");
        }
        // セッションにアクティブタブがセットされていればセットする
        if ($this->request->session()->check('activeTab')) {
            $activeTab = $this->request->session()->consume('activeTab');
        }

        if ($this->request->session()->check('ajax')) {
            $ajax = $this->request->session()->consume('ajax'); // セッションを開放する
            $this->viewBuilder()->autoLayout(false);
            $this->autoRender = false;
            $this->response->charset('UTF-8');
            $this->response->type('json');
            $isException = $this->request->session()->consume('exception');
            $resultflg = $this->request->session()->consume('resultflg');
            $resultMessage = $this->request->session()->consume('resultMessage');
            $valideteErrors = $this->request->session()->consume('errors');

            //$valideteErrors = array('fluts'=> array('apple' => 'リンゴ','banana' => 'バナナ'));
            $errors = "";
            // 各タブでのチェックエラー内容があればセットする。
            if (count($valideteErrors) > 0) {
                foreach ($valideteErrors as $key1 => $value1) {
                    foreach ($value1 as $key2 => $value2) {
                        if (is_array($value2)) {
                            foreach ($value2 as $key3 => $value3) {
                                $errors .= $value3."<br/>";
                            }

                        } else {
                            $errors .= $value2."<br/>";
                            //$this->Flash->error($value2);
                        }
                    }
                }
            }
            // 各タブでの例外があればセットする。
            if ($isException) {
                $errors = $resultMessage;
                //$this->Flash->error($valideteErrors);
            }

            // 引数にID(URLパラメータにID)がセットされていない場合は、authから直接セット
            if (!isset($id)) {
                $id = $this->request->getSession()->read('Auth.Cast.id');
            }

            $cast = $this->Casts->find()->where(['id' => $id]);
            $masterCodesFind = array('time');
            $selectList = $this->Util->getSelectList($masterCodesFind,$this->MasterCodes,true);

            $this->set(compact('cast', 'activeTab','selectList', 'ajax'));
                $html = (String)$this->render('/Cast/Casts/index');

            $response = array(
                'html' => $html,
                'error' => $errors,
                'success' => $resultflg,
                'message' => $resultMessage
            );

            $this->response->body(json_encode($response));
            return;
        }
        // 引数にID(URLパラメータにID)がセットされていない場合は、authから直接セット
        if (!isset($id)) {
            $id = $this->request->getSession()->read('Auth.Cast.id');
        }
        $query = $this->Diarys->find();
        //count = $diarys->find('all')->contain(['diary_id'=>""])->where(['cast_id'=>$id]);
         // キャストの記事といいね数を取得
         $diarys = $query->select(['id',
            'diary_like_num'=> $query->func()->count('Likes.diary_id')])
        ->contain('Likes')
        ->leftJoinWith('Likes')
        ->where(['Diarys.cast_id'=>$id])
        ->group(['Likes.diary_id'])
        ->order(['diary_like_num' => 'desc'])->toArray();
        $likeTotal = array_sum(array_column($diarys, 'diary_like_num'));
        //$like = $query->select(['total_like' => $query->func()->sum('cast_id')])
        //$diaryLikes = $this->Diarys->find('all')->where(['cast_id'=>$id])->contain('Likes');
        $cast = $this->Casts->find('all')->contain(['Shops','Diarys'])->where(['Casts.id'=>$id])->first();
        $masterCodesFind = array('time','event');
        $selectList = $this->Util->getSelectList($masterCodesFind,$this->MasterCodes,true);

        $this->set(compact('cast','likeTotal', 'activeTab','selectList', 'ajax'));
        $this->render();
    }

    /**
     * カレンダーの処理
     *
     * @param [type] $id
     * @return void
     */
    public function editCalendar($id = null)
    {
        $addFlg = false; // 追加実行フラグ

        if ($this->request->is('ajax')) {
            $resultMessage = '';
            $resultflg = true;
            $isException = false;
            $isSave = false;
            $errors = array(); // バリデーションチェックが無い場合に空を一時的に作成


            // イベント削除の場合
            if ($this->request->data["crud_type"] == "delete") {
                $event = $this->Events->get($this->request->data["id"]);
                if ($this->Events->delete($event)) {
                    $resultMessage = RESULT_M['DELETE_SUCCESS'];
                } else {
                    $resultMessage = RESULT_M['DELETE_FAILED'];
                    $resultflg = false;
                }
            } elseif ($this->request->data["crud_type"] == "update") {
                // イベント編集の場合
                $event = $this->Events->find()->where(['id'
                => $this->request->getData('id')])->first();
                $event = $this->Events->patchEntity($event, $this->request->getData());

            } elseif ($this->request->data["crud_type"] == "create") {
                // イベント追加の場合
                $event = $this->Events->newEntity($this->request->getData());
                $addFlg = true;

            }
            // saveするか判定する
            if ($this->request->data["crud_type"] == "create" ||
                $this->request->data["crud_type"] == "update") {
                $isSave = true;
            }
            if ($isSave) {
                if ($this->Events->save($event)) {
                    if ($addFlg == true) {
                        $resultMessage = RESULT_M['SIGNUP_SUCCESS'];;
                    } else {
                        $resultMessage = RESULT_M['UPDATE_SUCCESS'];
                    }
                } else {
                    if ($addFlg == true) {
                        $resultMessage = RESULT_M['SIGNUP_FAILED'];
                    } else {
                        $resultMessage = RESULT_M['UPDATE_FAILED'];
                    }
                    $resultflg = false;
                }
            }
            $cast = $this->Casts->get($id);
            $Columns = array(
                'Events.id','Events.title','Events.start','Events.end','Events.time_start',
                'Events.time_end','Events.all_day',
            );
            $event = $this->Events->find('all', array('fields' => $Columns))->where(['cast_id' => $id]);
            // jsonファイルに書き込む
            $path = WWW_ROOT. $this->viewVars['shopInfo']['dir_path']."cast/".$cast["dir"];
            $dir = realpath($path);
            try {
                $dir = new Folder($dir.DS);
                $file = new File($dir->path."calendar.json",true);
                $file->write(json_encode($event));

            } catch (RuntimeException $e) {
                $resultMessage = $e->getMessage();
                $resultflg = false;
                $isException = true;
            } finally {
                $file->close();
            }

        }
        $this->viewBuilder()->autoLayout(false);
        $this->autoRender = false;
        $this->response->charset('UTF-8');
        $this->response->type('json');
        $this->response->body(json_encode($event));
        return;
    }

    /**
     * 編集画面の処理
     *
     * @param [type] $id
     * @return void
     */
    public function profile($id = null)
    {

            $this->request->session()->write('activeTab', 'cast');
            // アクティブタブを空で初期化
            $activeTab = "";

            if (isset($this->request->query["activeTab"])) {
                $activeTab = $this->request->getQuery("activeTab");
            }
                    // 引数にID(URLパラメータにID)がセットされていない場合は、authから直接セット
            if (!isset($id)) {
                $id = $this->request->getSession()->read('Auth.Cast.id');
            }
            if ($this->request->is('ajax')) {
                $resultMessage = '';
                $isSave = false;
                $errors = '';

                // 追加、編集時はバリデーションチェックするため、requestをエンティティにマッピングする
                if (isset($this->request->data["profile_edit"])) {
                    $entity = $this->Casts->newEntity($this->request->getData());
                }

                if (!$entity->errors()) {

                    if (isset($this->request->data["profile_edit"])) {
                        // キャスト編集の場合
                        $cast = $this->Casts->get($id);
                        $cast = $this->Events->patchEntity($cast, $this->request->getData());

                    }
                    // saveするか判定する
                    if (array_key_exists('profile_edit', $this->request->getData())){
                        $isSave = true;
                    }
                    if ($isSave) {
                        if ($this->Casts->save($cast)) {
                            $resultMessage = RESULT_M['SIGNUP_SUCCESS'];
                        } else {
                            $errors = RESULT_M['SIGNUP_FAILED'];
                        }
                    }
                } else {
                    foreach ($entity->errors() as $key1 => $value1) {
                        foreach ($value1 as $key2 => $value2) {
                            if (is_array($value2)) {
                                foreach ($value2 as $key3 => $value3) {
                                    $errors .= $value3."<br/>";
                                }
                            } else {
                                $errors .= $value2."<br/>";
                                //$this->Flash->error($value2);
                            }
                        }
                    }
                }

                $this->viewBuilder()->autoLayout(false);
                $this->autoRender = false;
                if (strlen($errors) > 0 && strlen($resultMessage) > 0) {
                    $this->Flash->success($errors .= "\n".$resultMessage);
                } else if(strlen($errors) > 0) {
                    $this->Flash->error($errors);
                } else if(strlen($resultMessage) > 0) {
                    $this->Flash->success($resultMessage);
                }
                $cast = $this->Casts->find()->where(['id' => $id]);
                $masterCodesFind = array('time','constellation','blood_type','age');
                $selectList = $this->Util->getSelectList($masterCodesFind,$this->MasterCodes,true);

                $this->set(compact('cast', 'activeTab','selectList', 'ajax'));
                $this->render('/Cast/Casts/profile');
                $this->response->body();
                return;
            }

        $cast = $this->Casts->find()->where(['id' => $id]);
        $masterCodesFind = array('time','constellation','blood_type','age');
        $selectList = $this->Util->getSelectList($masterCodesFind,$this->MasterCodes,true);

        $this->set(compact('cast', 'activeTab','selectList', 'ajax'));
        $this->render();
    }

    public function image($id = null)
    {

        $delFlg = false; // 削除実行フラグ
        $this->request->session()->write('activeTab', 'cast');
        // アクティブタブを空で初期化
        $activeTab = "";
        if (isset($this->request->query["activeTab"])) {
            $activeTab = $this->request->getQuery("activeTab");
        }
        // 引数にID(URLパラメータにID)がセットされていない場合は、authから直接セット
        if (!isset($id)) {
            $id = $this->request->getSession()->read('Auth.Cast.id');
        }
        if ($this->request->is('ajax')) {
            $errors = '';

            // 追加、編集時はバリデーションチェックするため、requestをエンティティにマッピングする
            $entity = $this->Casts->newEntity($this->request->getData(),['validate' => false]);

            if (!$entity->errors()) {

                $tmpDir = new Folder; // バックアップ用
                $files = array();
                // キャスト取得
                $cast = $this->Casts->get($id);
                // キャスト用画像のディレクトリパスを取得
                $dirPath = WWW_ROOT. $this->viewVars['shopInfo']['dir_path'].PATH_ROOT['CAST'].DS.$cast->dir. DS .PATH_ROOT['IMAGE'].DS;
                // キャスト用画像のディレクトリが無い場合は作成する
                $dir = new Folder($dirPath, true, 0755);
                // 既に登録された画像があればデコードし格納、無ければ空の配列を格納する
                ($files_befor = json_decode($this->request->data["image_json"], true)) > 0 ? : $diary_befor = array();
                $fileMax = CAST_CONFIG['FILE_MAX'];
                // カラム「image*」を格納する
                $imageCol = array_values(preg_grep('/^image/', $this->Diarys->schema()->columns()));

                // ファイルのバックアップを取得
                $dirClone = new Folder($dir->path, true, 0755);
                // ロールバック用に一時フォルダにバックアップする。
                $tmpDir = $this->Util->createTmpDirectoy(WWW_ROOT.PATH_ROOT['TMP'], $dirClone);

                if ($this->request->data["crud_type"] == 'update') {
                    // 追加画像がある場合
                    if (isset($this->request->data["image"])) {
                        $files = $this->request->data['image'];
                    }
                    foreach ($files as $key => $file) {
                        // ファイルが入力されたとき
                        if (count($file["name"]) > 0) {
                            $limitFileSize = 1024 * 1024;
                            try {
                                // TODO: 検証用
                                // if($key == 1) {
                                //     throw new RuntimeException('画像の削除に失敗しました。');
                                // }
                                // ファイル名を取得する
                                $convertFile = $this->Util->file_upload($file, $files_befor, $dir->path, $limitFileSize);

                                // ファイル名が同じ場合は処理をスキップする
                                if ($convertFile === false) {
                                    $errors = '同じ画像はアップできません。'."\n";
                                    continue;
                                }

                            } catch (RuntimeException $e) {

                                // アップロード失敗の時、処理を中断する
                                if (is_dir($tmpDir->path)) {
                                    // ファイルを戻す前にアップロードされたファイルがある場合があるため、空にしておく
                                    $files = $dirClone->find('.*\.*');
                                    foreach ($files as $file) {
                                        $file = new File($dir->path . DS . $file);
                                        $file->delete(); // このファイルを削除します
                                    }
                                    $tmpDir->copy($dirClone->path);
                                    $tmpDir->delete();// tmpフォルダ削除
                                }
                                $this->confReturnJson(); // json返却用の設定
                                $errors = $e->getMessage(); // TODO:　運用でログに出す？
                                $errors = RESULT_M['UPDATE_FAILED'];
                                $response = array('result'=>false,'errors'=>$errors);
                                $this->response->getBody()->write(json_encode($response));
                                return;
                            }
                        }
                        // カラムimage1～image8の空いてる場所に入れる
                        for($i = 0; $i < $fileMax; $i++) {
                            if(empty($cast->get($imageCol[$i]))) {
                                $cast->set($imageCol[$i], $convertFile);
                                break;
                            }
                        }

                    }

                } else if ($this->request->data["crud_type"] == 'delete') {
                    $delFlg = true;
                    try {
                        // TODO: 検証用
                        // if($key == 1) {
                        //     throw new RuntimeException('画像の削除に失敗しました。');
                        // }
                        $delFile = new File($dir->path . DS .$this->request->getData('file_name'));
                        // ファイル削除処理実行
                        if ($delFile->delete()) {
                            $cast->set($this->request->getData('col_name'), "");
                        } else {
                            throw new RuntimeException('画像の削除に失敗しました。');
                        }
                    } catch (RuntimeException $e) {
                        // アップロード失敗の時、処理を中断する
                        if (is_dir($tmpDir->path)) {
                            // ファイルを戻す前にアップロードされたファイルがある場合があるため、空にしておく
                            $files = $dirClone->find('.*\.*');
                            foreach ($files as $file) {
                                $file = new File($dir->path . DS . $file);
                                $file->delete(); // このファイルを削除します
                            }
                            $tmpDir->copy($dirClone->path);
                            $tmpDir->delete();// tmpフォルダ削除
                        }
                        $this->confReturnJson(); // json返却用の設定
                        $errors = $e->getMessage(); // TODO:　運用でログに出す？
                        $errors = RESULT_M['DELETE_FAILED'];
                        $response = array('result'=>false,'errors'=>$errors);
                        $this->response->getBody()->write(json_encode($response));
                        return;

                    }
                    $moveImageList = array(); // 移動対象リスト
                    // 削除したカラムの空きを詰める。
                    for ($i = 0; $i < $fileMax; $i++) {
                        if (!empty($cast->get($imageCol[$i]))) {
                            array_push($moveImageList, $cast->get($imageCol[$i]));
                            $cast->set($imageCol[$i], '');
                        }
                    }
                    // カラムimage1から詰めてセットする
                    for ($i = 0; $i < count($moveImageList); $i++) {
                        if (empty($cast->get($imageCol[$i]))) {
                            $cast->set($imageCol[$i], $moveImageList[$i]);
                        }
                    }

                }

                // データべース登録
                if($this->Casts->save($cast)) {
                    // 成功: フォルダを削除
                    if (is_dir($tmpDir->path)) {
                        $tmpDir->delete();// tmpフォルダ削除
                    }
                    if ($delFlg == true) {
                        $this->Flash->success(RESULT_M['DELETE_SUCCESS']);
                    } else {
                        $this->Flash->success(RESULT_M['SIGNUP_SUCCESS']);
                    }
                } else {
                    if ($delFlg == true) {
                        $errors = RESULT_M['DELETE_FAILED'];
                    } else {
                        $errors = RESULT_M['SIGNUP_FAILED'];
                    }

                    if (is_dir($tmpDir->path)) {
                        // ファイルを戻す前にアップロードされたファイルがある場合があるため、空にしておく
                        $files = $dirClone->find('.*\.*');
                        foreach ($files as $file) {
                            $file = new File($dir->path . DS . $file);
                            $file->delete(); // このファイルを削除します
                        }
                        $tmpDir->copy($dirClone->path);
                        $tmpDir->delete();// tmpフォルダ削除
                    }

                    $this->confReturnJson(); // json返却用の設定
                    $response = array('result'=>false,'errors'=>$errors);
                    $this->response->getBody()->write(json_encode($response));
                    return;
                }

            } else {
                // 入力エラーがあれば、メッセージをセットして返す
                $errors = $this->Util->setErrMessage($entity); // エラーメッセージをセット
                $this->confReturnJson(); // json返却用の設定
                $response = array('result'=>false,'errors'=>$errors);
                $this->response->getBody()->write(json_encode($response));
                return;
            }

            $this->viewBuilder()->autoLayout(false);
            $this->autoRender = false;
            $array = array('id','name','image1','image2','image3','image4',
                            'image5','image6','image7','image8','dir');
            $cast = $this->Casts->find('all')->select($array)->where(['id' => $id])->first()->toArray();
            $imageCol = array_values(preg_grep('/^image/', $this->Casts->schema()->columns()));
            $imageList = array(); // 画面側でjsonとして使う画像リスト
            // 画像リストを作成する
            for ($i = 0; $i < count($imageCol); $i++) {
                if (!empty($cast[$imageCol[$i]])) {
                    array_push($imageList, ['key'=>$imageCol[$i],'name'=>$cast[$imageCol[$i]]]);
                }
            }
            $this->set(compact('cast', 'activeTab','imageList', 'ajax'));
            $this->render('/Cast/Casts/image');
            $this->response->body();
            return;

        }

        $array = array('id','name','image1','image2','image3','image4','image5','image6','image7','image8','dir');
        $cast = $this->Casts->find('all')->select($array)->where(['id' => $id])->first()->toArray();
        $imageCol = array_values(preg_grep('/^image/', $this->Casts->schema()->columns()));
        $imageList = array(); // 画面側でjsonとして使う画像リスト
        // 画像リストを作成する
        for ($i = 0; $i < count($imageCol); $i++) {
            if (!empty($cast[$imageCol[$i]])) {
                array_push($imageList, ['key'=>$imageCol[$i],'name'=>$cast[$imageCol[$i]]]);
            }
        }
        $this->set(compact('cast', 'activeTab','imageList', 'ajax'));
        $this->render();
    }

    public function diary($id = null)
    {

            $this->request->session()->write('activeTab', 'cast');
            // 引数にID(URLパラメータにID)がセットされていない場合は、authから直接セット
            if (!isset($id)) {
                $id = $this->request->getSession()->read('Auth.Cast.id');
            }
            if ($this->request->is('ajax')) {

                $errors = '';
                $fileMax = CAST_CONFIG['FILE_MAX'];
                $files_befor = (array)json_decode($this->request->data["diary_json"]);
                $imageCol = array_values(preg_grep("/^image/", $this->Diarys->schema()->columns()));

                // エンティティにマッピングする
                $diary = $this->Diarys->newEntity($this->request->getData());
                // バリデーションチェック
                if (!$diary->errors()) {

                    // キャスト取得
                    $cast = $this->Casts->get($id);
                    // 日記用のディレクトリを掘る
                    $rootDir = WWW_ROOT. $this->viewVars['shopInfo']['dir_path']
                        .PATH_ROOT['CAST']. PATH_ROOT['DIARY'] . DS;
                    $rootDirClone = $rootDir;

                    // 登録の場合
                    if ($this->request->data["crud_type"] == "create") {

                        $date = new Time();
                        //$date->setDate(2019,5,1); //日時切り替えテスト
                        $date->format('Ymd_His');
                        $diaryDir = $date->format('Y'). DS .$date->format('Ym'). DS .$date->format('Ymd');
                        $insertDir = $diaryDir;
                        $diaryDir = $rootDirClone.$diaryDir;
                        $diaryDirClone = $diaryDir;

                        $insertDir = $insertDir. DS .$date->format('Ymd_His');
                        $dir = $diaryDirClone. DS .$date->format('Ymd_His');
                        $dir = new Folder($dir, true, 0755);
                        $diary->dir = $insertDir; // 日記のパスをセット
                        //nullの場合は配列にキャスト
                        isset($this->request->data['image']) ? $files = $this->request->data['image'] :$files = array();
                        foreach ($files as $key => $file) {

                            // ファイルが入力されたとき
                            if (count($file["name"]) > 0) {
                                $limitFileSize = 1024 * 1024;
                                try {
                                    // TODO: 検証用
                                    // if($key == 2) {
                                    //     throw new RuntimeException(RESULT_M['SIGNUP_FAILED']);
                                    // }
                                    // ファイル名を取得する
                                    $convertFile = $this->Util->file_upload($file, $files_befor, $dir->path, $limitFileSize);

                                    // ファイル名が同じ場合は処理をスキップする
                                    if ($convertFile === false) {
                                        $errors = '同じ画像はアップできません。'."\n";
                                        continue;
                                    }
                                } catch (RuntimeException $e) {
                                    // アップロード失敗の時、処理を中断する
                                    if (is_dir($dir->path)) {
                                        $dir->delete();// 日記フォルダ削除
                                    }
                                    $this->confReturnJson(); // json返却用の設定
                                    $errors = $e->getMessage(); // TODO:　運用でログに出す？
                                    $errors = RESULT_M['SIGNUP_FAILED'];
                                    $response = array('result'=>false,'errors'=>$errors);
                                    $this->response->getBody()->write(json_encode($response));
                                    return;
                                }

                            }
                            // カラムimage1～image8の空いてる場所に入れる
                            for ($i = 0; $i < $fileMax; $i++) {
                                if (empty($diary->get($imageCol[$i]))) {
                                    $diary->set($imageCol[$i], $convertFile);
                                    break;
                                }
                            }
                        }
                    }
                    // データべース登録
                    if($this->Diarys->save($diary)) {
                        $this->Flash->success(RESULT_M['SIGNUP_SUCCESS']);
                    } else {
                        // 失敗: フォルダを削除
                        if (is_dir($dir->path)) {
                            $dir->delete();// フォルダを削除
                        }
                        $this->confReturnJson(); // json返却用の設定
                        $response = array('result'=>false,'errors'=>RESULT_M['SIGNUP_FAILED']);
                        $this->response->getBody()->write(json_encode($response));
                        return;
                    }

                } else {
                    // 入力エラーがあれば、メッセージをセットして返す
                    $errors = $this->Util->setErrMessage($diary); // エラーメッセージをセット
                    $this->confReturnJson(); // json返却用の設定
                    $response = array('result'=>false,'errors'=>$errors);
                    $this->response->body(json_encode($response));
                    return;
                }
                // アクティブタブを空で初期化
                $activeTab = "";

                if (isset($this->request->query["activeTab"])) {
                    $activeTab = $this->request->getQuery("activeTab");
                }
                $this->viewBuilder()->autoLayout(false);
                $this->autoRender = false;
                $diarys = $this->Util->getDiarys($id);

                $dir = $this->viewVars["shopInfo"]["dir_path"].$cast['dir'].DS;
                $cast = $this->Casts->find('all')->where(['id' => $id])->first();
                $dir = $this->viewVars['shopInfo']['dir_path'].PATH_ROOT['CAST'].DS.$cast["dir"].DS.PATH_ROOT['DIARY'].DS;
                $this->set(compact('cast','dir', 'diarys', 'activeTab', 'ajax'));
                $this->render('/Cast/Casts/diary');
                $this->response->body();
                return;
            }
            $diarys = $this->Util->getDiarys($id);
            $cast = $this->Casts->find('all')->where(['id' => $id])->first();
            $dir = $this->viewVars['shopInfo']['dir_path'].PATH_ROOT['CAST'].DS.$cast["dir"].DS.PATH_ROOT['DIARY'].DS;
            $this->set(compact('cast','dir', 'diarys', 'activeTab', 'ajax'));
            $this->render();
    }

    /**
     * 日記アーカイブ表示画面の処理
     *
     * @param [type] $id
     * @return void
     */
    public function diaryView($id = null)
    {
        $this->request->session()->write('activeTab', 'cast');
        // 引数にID(URLパラメータにID)がセットされていない場合は、authから直接セット
        if (!isset($id)) {
            $id = $this->request->getSession()->read('Auth.Cast.id');
        }
        if ($this->request->is('ajax')) {
            $this->confReturnJson(); // json返却用の設定
            $diary = $this->Util->getDiary($this->request->query["id"]);
            $this->response->body(json_encode($diary));
            return;
        }
    }

    /**
     * 日記アーカイブ更新処理
     *
     * @param [type] $id
     * @return void
     */
    public function diaryUpdate($id = null)
    {
        $errors = '';

        $this->request->session()->write('activeTab', 'cast');
        // 引数にID(URLパラメータにID)がセットされていない場合は、authから直接セット
        if (!isset($id)) {
            $id = $this->request->getSession()->read('Auth.Cast.id');
        }
        if ($this->request->is('ajax')) {
            // バリデーションチェック
            $validate = $this->Diarys->newEntity($this->request->getData());
            // 入力エラーがあれば、メッセージをセットして返す
            if ($validate->errors()) {
                $errors = $this->Util->setErrMessage($validate); // エラーメッセージをセット
                $this->confReturnJson(); // json返却用の設定
                $response = array('result'=>false,'errors'=>$errors);
                $this->response->body(json_encode($response));

                return;
            }

            $tmpDir = new Folder; // バックアップ用
            $files = array();
            $fileMax = CAST_CONFIG['FILE_MAX'];
            // キャスト取得
            $cast = $this->Casts->get($id);
            // 本登録をもってキャスト用のディレクトリを掘る
            $diary = $this->Diarys->get($this->request->data["diary_id"]);
            $dir = WWW_ROOT. $this->viewVars['shopInfo']['dir_path'].PATH_ROOT['CAST']
                .DS.$cast->dir.DS.PATH_ROOT['DIARY'].DS.$diary->dir;
            $deleteFiles = json_decode($this->request->data["del_list"], true);
            // 既に登録された画像があればデコードし格納、無ければ空の配列を格納する
            ($diary_befor = json_decode($this->request->data["diary_json"], true)) > 0 ? : $diary_befor = array();
            // カラム「image*」を格納する
            $imageCol = array_values(preg_grep("/^image/", $this->Diarys->schema()->columns()));

            // 追加画像がある場合
            if (isset($this->request->data["image"])) {
                $files = $this->request->data['image'];
            }
            // 既に登録された画像がある場合は、ファイルのバックアップを取得
            if(count($diary_befor) > 0) {
                $dirClone = new Folder($dir, true, 0755);
                // ロールバック用に一時フォルダにバックアップする。
                $tmpDir = $this->Util->createTmpDirectoy(WWW_ROOT.PATH_ROOT['TMP'], $dirClone);
                $fileList = glob($tmpDir->path.'/*'); // ディレクトリ配下のファイルを取得
                // if(count($fileList) > 0 ) {
                //     $tmpDir->delete();
                // }
            }
            // 日記のタイトルと内容をセット
            $diary->title = $this->request->data["title"];
            $diary->content = $this->request->data["content"];

            // 削除画像分処理する
            if(count($deleteFiles) > 0) {

                foreach ($deleteFiles as $key => $file) {
                    try {
                        // TODO: 検証用
                        // if($key == 1) {
                        //     throw new RuntimeException('画像の削除に失敗しました。');
                        // }
                        $this->log("file_delete", "debug");
                        $delFile = new File($dir . DS .$file['name']);
                        // ファイル削除処理実行
                        if ($delFile->delete()) {
                            $diary->set($file['key'], "");
                        } else {
                            throw new RuntimeException('画像の削除に失敗しました。');
                        }
                    } catch (RuntimeException $e) {
                        if (is_dir($tmpDir->path)) {
                            $tmpDir->copy($dirClone->path);
                            $tmpDir->delete();// tmpフォルダ削除
                        }
                        $errors = $e->getMessage(); // TODO:　運用でログに出す？
                        $errors = RESULT_M['UPDATE_FAILED'];
                        $this->confReturnJson(); // json返却用の設定
                        $response = array('result'=>false,'errors'=>$errors);
                        $this->response->getBody()->write(json_encode($response));

                        return;
                    }
                }
                $moveImageList = array(); // 移動対象リスト
                // 削除したカラムの空きを詰める。
                for ($i = 0; $i < $fileMax; $i++) {
                    if (!empty($diary->get($imageCol[$i]))) {
                        array_push($moveImageList, $diary->get($imageCol[$i]));
                        $diary->set($imageCol[$i], '');
                    }
                }
                // カラムimage1から詰めてセットする
                for ($i = 0; $i < count($moveImageList); $i++) {
                    if (empty($diary->get($imageCol[$i]))) {
                        $diary->set($imageCol[$i], $moveImageList[$i]);
                    }
                }
            }

            // 追加画像分処理する
            foreach ($files as $key => $file) {
                // ファイルが入力されたとき
                if (count($file["name"]) > 0) {
                    $limitFileSize = 1024 * 1024;
                    try {
                        // TODO: 検証用
                        // if($key == 1) {
                        //     throw new RuntimeException('画像のアップロードに失敗しました。');
                        // }
                        // ファイル名を取得する
                        $convertFile = $this->Util->file_upload($file, $diary_befor, $dir, $limitFileSize);

                        // ファイル名が同じ場合は処理をスキップする
                        if ($convertFile === false) {
                            $errors = '同じ画像はアップできませんでした。'."\n";
                            continue;
                        }
                    } catch (RuntimeException $e) {
                        // アップロード失敗の時、処理を中断する
                        if (is_dir($tmpDir->path)) {
                             // ファイルを戻す前にアップロードされたファイルがある場合があるため、空にしておく
                             $files = $dirClone->find('.*\.*');
                             foreach ($files as $file) {
                                $file = new File($dir . DS . $file);
                                $file->delete(); // このファイルを削除します
                            }
                            $tmpDir->copy($dirClone->path);
                            $tmpDir->delete();// tmpフォルダ削除
                        }
                        $this->confReturnJson(); // json返却用の設定
                        $errors = $e->getMessage(); // TODO:　運用でログに出す？
                        $errors = RESULT_M['UPDATE_FAILED'];
                        $response = array('result'=>false,'errors'=>$errors);
                        $this->response->getBody()->write(json_encode($response));
                        return;
                    }

                }
                // カラムimage1～image8の空いてる場所に入れる
                for ($i = 0; $i < $fileMax; $i++) {
                    if (empty($diary->get($imageCol[$i]))) {
                        $diary->set($imageCol[$i], $convertFile);
                        break;
                    }
                }
            }
            // データべース登録
            if($this->Diarys->save($diary)) {
                // 成功: tmpフォルダ削除
                if (count($diary_befor) > 0) {

                    if (is_dir($tmpDir->path)) {
                        $tmpDir->delete();// tmpフォルダ削除
                    }

                }
                $this->Flash->success(RESULT_M['UPDATE_SUCCESS']);
           } else {
                // 失敗: ファイルを戻す
                if (count($diary_befor) > 0) {
                    if (is_dir($tmpDir->path)) {
                        // ファイルを戻す前にアップロードされたファイルがある場合があるため、空にしておく
                        $files = $dirClone->find('.*\.*');
                        foreach ($files as $file) {
                           $file = new File($dir . DS . $file);
                           $file->delete(); // このファイルを削除します
                       }
                       $tmpDir->copy($dirClone->path);
                       $tmpDir->delete();// tmpフォルダ削除
                   }
                }
                $this->confReturnJson(); // json返却用の設定
                $response = array('result'=>false,'errors'=>RESULT_M['UPDATE_FAILED']);
                $this->response->getBody()->write(json_encode($response));
                return;

            }
                // アクティブタブを空で初期化
                $activeTab = "";

                if (isset($this->request->query["activeTab"])) {
                    $activeTab = $this->request->getQuery("activeTab");
                }
                $this->viewBuilder()->autoLayout(false);
                $this->autoRender = false;
                $diarys = $this->Util->getDiarys($id);
                $cast = $this->Casts->find('all')->where(['id' => $id])->first();
                $dir = $this->viewVars['shopInfo']['dir_path'].PATH_ROOT['CAST'].DS.$cast["dir"].DS.PATH_ROOT['DIARY'].DS;
                $this->set(compact('cast','dir', 'diarys', 'activeTab', 'ajax'));
                $this->render('/Cast/Casts/diary');
                $this->response->body();
                return;
        }
    }

    /**
     * 日記アーカイブ削除処理
     *
     * @param [type] $id
     * @return void
     */
    public function diaryDelete($id = null)
    {
        $this->request->session()->write('activeTab', 'cast');
        // 引数にID(URLパラメータにID)がセットされていない場合は、authから直接セット
        if (!isset($id)) {
            $id = $this->request->getSession()->read('Auth.Cast.id');
        }
        if ($this->request->is('ajax')) {

            $tmpDir = new Folder; // バックアップ用
            $del_path = preg_replace('/(^\/)/', '', $this->request->getData('del_path'));
            $delFolder = new Folder(WWW_ROOT.$del_path);

            $dirClone = new Folder($delFolder->path, true, 0755);
            // ロールバック用に一時フォルダにバックアップする。
            $tmpDir = $this->Util->createTmpDirectoy(WWW_ROOT.PATH_ROOT['TMP'], $dirClone);

            try {

                // 日記フォルダ削除処理実行
                if (!$delFolder->delete()) {
                    throw new RuntimeException('日記の削除ができませんでした。');
                }
            } catch (RuntimeException $e) {
                if (is_dir($tmpDir->path)) {
                    $tmpDir->copy($dirClone->path);
                    $tmpDir->delete();// tmpフォルダ削除
                }
                $errors = $e->getMessage(); // TODO:　運用でログに出す？
                $errors = RESULT_M['DELETE_FAILED'];
                $this->confReturnJson(); // json返却用の設定
                $response = array('result'=>false,'errors'=>$errors);
                $this->response->getBody()->write(json_encode($response));
                return;
            }
            $diary = $this->Diarys->get($this->request->getData('diary_id'));
            // データべース削除
            if($this->Diarys->delete($diary)) {
                // 成功: tmpフォルダ削除
                if (is_dir($tmpDir->path)) {
                    $tmpDir->delete();// tmpフォルダ削除
                }
                $this->Flash->success(RESULT_M['DELETE_SUCCESS']);
           } else {
                // 失敗: ファイルを戻す
                if (is_dir($tmpDir->path)) {
                    $tmpDir->copy($dirClone->path);
                    $tmpDir->delete();// tmpフォルダ削除
                }
                $this->Flash->error(RESULT_M['DELETE_FAILED']);
            }

            $this->viewBuilder()->enableAutoLayout(false);
            $this->autoRender = false;
            $diarys = $this->Util->getDiary($id);
            $cast = $this->Casts->find('all')->where(['id' => $id])->first();
            $dir = $this->viewVars['shopInfo']['dir_path'].PATH_ROOT['CAST'].DS.$cast["dir"].DS.PATH_ROOT['DIARY'].DS;
            $this->set(compact('cast','dir', 'diarys', 'activeTab', 'ajax'));
            $this->render('/Cast/Casts/diary');
            $this->response->getBody();
            return;
        }
    }

    public function login()
    {
        // レイアウトを使用しない
        $this->viewBuilder()->autoLayout(false);

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
            $cast = $this->Casts->newEntity();
        }
        $this->set('cast', $cast);
    }

    public function logout()
    {
        // レイアウトを使用しない
        $this->viewBuilder()->autoLayout(false);

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
        $cast = $this->Casts->get(Token::getId($token));
        if (!$cast->tokenVerify($token)) {

            $this->Flash->success("cast->tokenVerify(token)メソッドを確認すること");
            return $this->redirect(['action' => 'signup']);
        }
        if ($cast->delete_flag == 1 ) {
            $shop = $this->Shops->get($cast->shop_id);
            // 本登録をもってキャスト用のディレクトリを掘る
            $dir = WWW_ROOT.PATH_ROOT['IMG'].DS.$shop->area.DS.$shop->genre
                .DS.$shop->dir.DS.PATH_ROOT['CAST'].DS;
            // TODO: scandirは、リストがないと、falseだけじゃなく
            // warningも吐く。後で対応を考える。
            // 指定フォルダ配下にあればラストの連番に+1していく
            if (file_exists($dir)) {
                $dirArray = scandir($dir);
                for($i = 0; $i < count($dirArray); $i++) {
                    if(strpos($dirArray[$i],'.') !== false) {
                        unset($dirArray[$i]);
                    }
                }
                $nextDir = sprintf("%05d",count($dirArray) + 1);

            } else {
                // 指定フォルダが空なら00001連番から振る
                $nextDir = sprintf("%05d", 1);

            }
            // パスが存在しなければディレクトリを掘ってDB登録
            if (!realpath($dir.$nextDir)) {
                $connection = ConnectionManager::get('default');
                // トランザクション処理開始
                $connection->begin();
                $cast->dir = $nextDir;
                $cast->delete_flag = 0;
                $result = true;
                if(!$this->Casts->save($cast)) {
                    $this->Flash->error(RESULT_M['AUTH_FAILED']);
                    $result = false;
                }
                if($result) {
                    // 成功: commit
                    $connection->commit();
                    // commit出来たらディレクトリを掘る
                    $dir = new Folder($dir.$nextDir, true, 0755);
                    // ステータスを本登録にする。(statusカラムを本登録に更新する)
                    $this->Flash->success(RESULT_M['AUTH_SUCCESS']);
                    return $this->redirect(['action' => 'login']);
                } else {
                    // 失敗: rollback
                    $connection->rollback();
                    $this->Flash->error(RESULT_M['AUTH_FAILED']);
                    return $this->redirect(['action' => 'login']);
                }
            } else {
                $this->Flash->error(RESULT_M['AUTH_FAILED']);
                return $this->redirect(['action' => 'login']);
            }

        }
        $this->Flash->success(RESULT_M['REGISTERED_FAILED']);
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
