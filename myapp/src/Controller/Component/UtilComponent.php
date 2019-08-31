<?php

namespace App\Controller\Component;

use Cake\Filesystem\File;
use Cake\Filesystem\Folder;
use Cake\ORM\TableRegistry;
use Cake\Controller\Component;

class UtilComponent extends Component
{

    /**
     * arrの指定keyで同じ値をグループ化する
     *
     * @param [type] $arr
     * @param [type] $key
     * @return void
     */
    public function groupArray($arr, $key)
    {
        $retval = array();

        foreach ($arr as $value) {
            $group = $value[$key];

            if (!isset($retval[$group])) {
                $retval[$group] = array();
            }

            $retval[$group][] = $value;
        }

        return $retval;
    }


    /**
     * null値の時にデフォルト値を返却する
     *
     * 引数1がnull値なら戻り値は引数2の値を返す。
     * 引数1がnull値じゃない場合は戻り値は引数1の値を返す。
     *
     * @param mixed
     * @param mixed
     * @return mixed
     */
    public function ifnull($target=null)
    {
        if (!is_null($target)) {
            return $target;
        }

        return '';
    }
    /**
     * null値の時にデフォルト値を返却する
     *
     * 引数1がnull値なら戻り値は引数2の値を返す。
     * 引数1がnull値じゃない場合は戻り値は引数1の値を返す。
     *
     * @param mixed
     * @param mixed
     * @return mixed
     */
    public function ifnullString($target=null)
    {
        if ($target !== '') {
            return $target;
        }

        return null;
    }

    /**
     * オーナー情報を取得する。
     *
     * @return void
     */
    public function getOwnerInfo($owner)
    {
        // TODO: Authセッションからオーナー情報を取得せず、shopsテーブルから取る？
        $ownerInfo = array();

        $ownerInfo = $ownerInfo + array('id'=>$owner['id']
            ,'dir'=>$owner['dir'],'main_image'=>$owner['image']);
        $path = DS.PATH_ROOT['IMG'].DS.PATH_ROOT['OWNER'].DS.$owner['dir'];

        $ownerInfo = $ownerInfo + array('owner_path'=> $path
            ,'image_path'=> $path.DS.PATH_ROOT['IMAGE']);
        return  $ownerInfo;

    }

    /**
     * 店舗情報を取得する。
     *
     * @return void
     */
    public function getShopInfo($shop)
    {
        // TODO: Authセッションからオーナー情報を取得せず、shopsテーブルから取る？
        $shopArea = $shop['area'];
        $shopGenre = $shop['genre'];
        $shopDir = $shop['dir'];
        $areas = AREA;
        $genres = GENRE;
        $shopInfo = array();
        foreach ($areas as $area) {
            if ($area['path'] == $shopArea) {
                $shopInfo = $shopInfo + array('area' => $area);
                break;
            }
        }
        foreach ($genres as $genre) {
            if ($genre['path'] == $shopGenre) {
                $shopInfo = $shopInfo + array('genre' => $genre);
                break;
            }
        }
        $shopInfo = $shopInfo + array('id'=>$shop['id']
            ,'dir'=>$shop['dir'],'main_image'=>$shop['top_image']);
        $path = DS.PATH_ROOT['IMG'].DS.$shopInfo['area']['path']
                .DS.$shopInfo['genre']['path'].DS.$shop['dir'];

        $shopInfo = $shopInfo + array('shop_path'=> $path
            ,'image_path'=> $path.DS.PATH_ROOT['IMAGE'],'cast_path'=> $path.DS.PATH_ROOT['CAST']
            ,'notice_path'=>$path.DS.PATH_ROOT['NOTICE']);
        return  $shopInfo;

    }

    /**
     * キャスト情報を取得する。
     *
     * @return void
     */
    public function getCastItem($cast, $shop)
    {
        // TODO: Authセッションからオーナー情報を取得せず、shopsテーブルから取る？
        $shopArea = $shop['area'];
        $shopGenre = $shop['genre'];
        $shopDir = $shop['dir'];
        $areas = AREA;
        $genres = GENRE;
        $castInfo = array();
        foreach ($areas as $area) {
            if ($area['path'] == $shopArea) {
                $castInfo = $castInfo + array('area' => $area);
                break;
            }
        }
        foreach ($genres as $genre) {
            if ($genre['path'] == $shopGenre) {
                $castInfo = $castInfo + array('genre' => $genre);
                break;
            }
        }
        $castInfo = $castInfo + array('id'=>$cast['id'],'shop_id'=>$shop['id']
            ,'dir'=>$cast['dir'], 'shop_dir'=> $shopDir,'main_image'=>$cast['image1']);
        $path = DS.PATH_ROOT['IMG'].DS.$castInfo['area']['path']
                .DS.$castInfo['genre']['path'].DS.$shop['dir']
                .DS.PATH_ROOT['CAST'].DS.$cast['dir'];

        $castInfo = $castInfo + array('cast_path'=> $path,'image_path'=> $path.DS.PATH_ROOT['IMAGE']
        , 'diary_path'=> $path.DS.PATH_ROOT['DIARY'], 'event_path'=> $path.DS.PATH_ROOT['EVENT']);
        return  $castInfo;
    }


    /**
     * クレジットリストを作成する
     *
     * @param object $credit
     * @param array $masCredit
     * @return void
     */
    public function getCredit($credit, $masCredit)
    {
        $creditsList = array();
        // クレジットが登録されてる場合は配列にセットする
        !empty($credit) ? $array = explode(',', $credit) : $array = array();

        for ($i = 0; $i < count($array); $i++) {
            foreach ($masCredit as $key => $value) {
                if ($array[$i] == $value->code) {
                    $creditsList[] = array('tag'=>$value->code,'image'=>PATH_ROOT['CREDIT'].$value->code.".png",'id'=>$value->id);
                    continue;
                }
            }
        }
        return $creditsList;
    }

    /**
     * 待遇リストを作成する
     *
     * @param [type] $treatment
     * @param [type] $query
     * @return void
     */
    public function getTreatment($treatment, $masTreatment)
    {
        $treatmentsList = array();
        // 待遇が登録されてる場合は配列にセットする
        !empty($treatment) ? $array = explode(',', $treatment) : $array = array();

        for ($i = 0; $i < count($array); $i++) {
            foreach ($masTreatment as $key => $value) {
                if ($array[$i] == $value->code_name) {
                    $treatmentsList[] = array('tag'=>$value->code_name,'id'=>$value->code);
                    continue;
                }
            }
        }
        return $treatmentsList;
    }

    /**
     * セレクトボックス用リストを作成する
     *
     * @param [type] $masterCodesFind
     * @param [type] $masterCodeEntity
     * @param [type] $flag
     * @return void
     */
    public function getSelectList($masterCodesFind = null, $masterCodeEntity = null, $flag = null)
    {
        $result = array();
        for ($i = 0; $i < count($masterCodesFind); $i++) {
            $query = $masterCodeEntity->find('list', [
                'keyField' => 'code',
                'valueField' => 'code_name'
            ])->where(['code_group' => $masterCodesFind[$i]]);
            $result = array_merge($result, array($masterCodesFind[$i] => $query->toArray()));
        }
        // 年齢リストを作成するか
        if (!empty($flag)) {
            $ageList = array();
            for ($i = 18; $i <= 99; $i++) {
                $ageList[$i] = $i;
            }
            $result = array_merge($result, array('age' => $ageList));
        }

        return $result;
    }

    /**
     * キャストの全ての日記情報を取得する処理
     *
     * @param [type] $id
     * @return array
     */
    public function getDiarys($id = null)
    {
        $diarys = TableRegistry::get('Diarys');
        $columns = array('id','cast_id','title','content','image1','dir');
        // キャスト情報、最新の日記情報とイイネの総数取得
        // 過去の日記をアーカイブ形式で取得する
        $query = $diarys->find('all')->select($columns);
        $ym = $query->func()->date_format([
            'created' => 'identifier',
            "'%Y/%c'" => 'literal']);
        $md = $query->func()->date_format([
            'created' => 'identifier',
            "'%c/%e'" => 'literal']);
        $archive = $query->select([
            'ym_created' => $ym,
            'md_created' => $md])
            ->where(['cast_id' => $id])
            ->contain(['Likes'])
            ->order(['created' => 'DESC'])->all();
        $archive = $this->groupArray($archive, 'ym_created');
        $archive = array_values($archive);
        return $archive;
    }

    /**
     * 指定した１件の日記情報を取得する処理
     *
     * @param [type] $id
     * @return array
     */
    public function getDiary($id = null)
    {
        $diary = TableRegistry::get('Diarys');
        $query = $diary->find('all')->select($diary->Schema()->columns());
        $ymd = $query->func()->date_format([
            'created' => 'identifier',
            "'%Y年%c月%e日'" => 'literal']);
        $diary = $query->select([
            'ymd_created' => $ymd])
            ->where(['id' => $id])
            ->contain(['Likes'])
            ->first();
        return $diary;
    }


    /**
     * 日記テーブルから最新の日記情報とイイネの総数を取得する処理
     *
     * @param [type] $rowNum
     * @param [type] $isArea
     * @return array
     */
    public function getNewDiarys($rowNum, $isArea = null)
    {
        $diarys = TableRegistry::get('Diarys');
        $columns = array('id','cast_id','title','content','image1','dir');
        if(!empty($isArea)) {
            $diarys = $diarys->find('all')
            ->contain(['Likes','Casts','Casts.Shops'])
            ->matching('Casts.Shops', function($q) use ($isArea){
                return $q->where(['Shops.area'=>$isArea]);
            })
            ->order(['Diarys.created' => 'DESC'])
            ->limit($rowNum)->all();
        } else {
            $diarys = $diarys->find('all')
                ->contain(['Likes','Casts','Casts.Shops'])
                ->order(['Diarys.created' => 'DESC'])
                ->limit($rowNum)->all();
        }

        return $diarys->toArray();
    }

    /**
     * 店舗の全てのお知らせ情報を取得する処理
     *
     * @param [type] $id
     * @return array
     */
    public function getNotices($id)
    {
        $shopInfos = TableRegistry::get('shop_infos');
        $columns = array('id','shop_id','title','content','image1','dir');
        // キャスト情報、最新の日記情報とイイネの総数取得
        // 過去の日記をアーカイブ形式で取得する
        $query = $shopInfos->find('all')->select($shopInfos->Schema()->columns());
        $ym = $query->func()->date_format([
            'created' => 'identifier',
            "'%Y/%c'" => 'literal']);
        $md = $query->func()->date_format([
            'created' => 'identifier',
            "'%c/%e'" => 'literal']);
        $shopInfos = $query->select([
            'ym_created' => $ym,
            'md_created' => $md])
            ->where(['shop_id' => $id])
            ->order(['created' => 'DESC'])->all();
        $shopInfos = $this->groupArray($shopInfos, 'ym_created');
        $shopInfos = array_values($shopInfos);
        return $shopInfos;
    }

     /**
     * 指定した１件のお知らせ情報を取得する処理
     *
     * @param [type] $id
     * @return array
     */
    public function getNotice($id = null)
    {
        $shopInfos = TableRegistry::get('shop_infos');
        $query = $shopInfos->find('all')->select($shopInfos->Schema()->columns());
        $ymd = $query->func()->date_format([
            'created' => 'identifier',
            "'%Y年%c月%e日'" => 'literal']);
        $shopInfo = $query->select([
            'ymd_created' => $ymd])
            ->where(['id' => $id])
            ->first();
        return $shopInfo;
    }

    /**
     * 日記テーブルから最新の日記情報を取得する処理
     *
     * @param [type] $rowNum
     * @return void
     */
    public function getNewNotices($rowNum, $isArea = null)
    {
        $shopInfos = TableRegistry::get('shop_infos');
        $query = $shopInfos->find('all')->select($shopInfos->Schema()->columns());
        // キャスト情報、最新の日記情報とイイネの総数取得
        // 過去の日記をアーカイブ形式で取得する
        if (!empty($isArea)) {
            $shopInfos = $shopInfos->find('all')
            ->contain(['Shops'])
            ->matching('Shops', function($q) use ($isArea){
                    return $q->where(['Shops.area'=>$isArea]);
                })
            ->order(['shop_infos.created' => 'DESC'])
            ->limit($rowNum)->all();
        } else {
            $shopInfos = $shopInfos->find('all')
            ->contain(['Shops'])
            ->order(['shop_infos.created' => 'DESC'])
            ->limit($rowNum)->all();
        }
        return $shopInfos->toArray();
    }

    /**
     * ファイルアップロードの処理
     *
     * @param array $file
     * @param array $files_befor
     * @param string $dir
     * @param integer $limitFileSize
     * @return void
     */
    public function file_upload(array $file = null, array $files_befor = null,
        string $dir = null, int $limitFileSize)
    {
        try {
            // ファイルを保存するフォルダ $dirの値のチェック
            if ($dir) {
                if (!file_exists($dir)) {
                    throw new RuntimeException('指定のディレクトリがありません。');
                }
            } else {
                throw new RuntimeException('ディレクトリの指定がありません。');
            }

            // 未定義、複数ファイル、破損攻撃のいずれかの場合は無効処理
            if (!isset($file['error']) || is_array($file['error'])) {
                throw new RuntimeException('Invalid parameters.');
            }

            // エラーのチェック
            switch ($file['error']) {
                case 0:
                break;
                case UPLOAD_ERR_OK:
                break;
                case UPLOAD_ERR_NO_FILE:
                throw new RuntimeException('No file sent.');
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                throw new RuntimeException('Exceeded filesize limit.');
                default:
                throw new RuntimeException('Unknown errors.');
            }

            // ファイル情報取得
            $fileInfo = new File($file["tmp_name"]);

            // ファイルサイズのチェック
            if ($fileInfo->size() > $limitFileSize) {
                throw new RuntimeException('Exceeded filesize limit.');
            }

            // ファイルタイプのチェックし、拡張子を取得
            if (false === $ext = array_search(
                $fileInfo->mime(),
                ['jpg' => 'image/jpeg',
              'png' => 'image/png',
              'gif' => 'image/gif',],
                true
            )) {
                throw new RuntimeException('Invalid file format.');
            }

            // ファイル名の生成
//            $uploadFile = $file["name"] . "." . $ext;
            $uploadFile = sha1_file($file["tmp_name"]) . "." . $ext;

            // DBにデータがある場合にアップされるファイルを比較する

            $isFile = array_search($uploadFile, array_column($files_befor, 'name'));
            // ファイル名が同じ場合は処理を中断する
            if ($isFile !== false) {
                return false;
            }
            $exif1 = exif_read_data($file["tmp_name"]);
            // ファイルの移動
            if (!@move_uploaded_file($file["tmp_name"], $dir . DS . $uploadFile)) {
                throw new RuntimeException('Failed to move uploaded file.');
            }
            $exif2 = exif_read_data($dir . DS . $uploadFile);

            // 処理を抜けたら正常終了
//            echo 'File is uploaded successfully.';
        } catch (RuntimeException $e) {
            throw $e;
        }
        return $uploadFile;
    }

    /**
     * 一時ディレクトリにバックアップを作成する
     *
     * @param String $tmpPath
     * @param File $dir
     * @return void
     */
    public function createTmpDirectoy(string $tmpPath, Folder $dir)
    {
        // "/$tmpPath/{現在の時間}"というディレクトリをパーミッション777で作ります
        $result = new Folder;
        $tmpDir = new Folder($tmpPath . DS . time(), true, 0777);
        $dir->copy($tmpDir->path);
        return $tmpDir;
    }

    /**
     * 一時ディレクトリにファイルのバックアップを作成する
     *
     * @param String $tmpPath
     * @param File $dir
     * @return void
     */
    // public function createFileTmpDirectoy(string $tmpPath, File $file)
    // {
    //     // "/$tmpPath/{現在の時間}"というディレクトリをパーミッション777で作ります
    //     $tmpDir = new File($tmpPath . DS . time(), true, 0777);
    //     $file->copy($tmpDir->path);
    //     return $tmpDir;
    // }

    /**
     * エラーメッセージをセットする
     *
     * @param Diary $validate
     * @return $errors
     */
    public function setErrMessage($validate)
    {
        $errors = ""; // メッセージ格納用
        foreach ($validate->errors() as $key1 => $value1) {
            foreach ($value1 as $key2 => $value2) {
                if (is_array($value2)) {
                    foreach ($value2 as $key3 => $value3) {
                        $errors .= $value3."<br/>";
                    }
                } else {
                    $errors .= $value2."<br/>";
                }
            }
        }
        return $errors;
    }

    /**
     * 検索対象、置換対象を配列で順番通りに置換する処理
     *
     * @param [type] $search
     * @param [type] $replace
     * @param [type] $target
     * @return void
     */
    public function strReplace($search, $replace, $target) {

        $result = "";
        $result = str_replace($search, $replace, $target);
        return $result;
    }

   /**
     * インスタAPIにアクセスし情報を取得する処理
     *
     * @param [type] $insta_user_name
     * @param [type] $insta_business_name
     * @param [type] $insta_business_id
     * @param [type] $access_token
     * @return $instagram_data
     */
    public function getInstagram($insta_user_name = null, $insta_business_name = null, $insta_business_id, $token) {

        $instagram_business_id = $insta_business_id; //ここにInstagramビジネスアカウントIDを入力してください。
        $access_token = $token; //ここに3段階目のアクセストークンを入力してください。
        $target_user = $insta_user_name; //ここに取得したいInstagramビジネスアカウントのユーザー名を入力してください。https://www.instagram.com/nightplanet91/なので「nightplanet91」がユーザー名になります

        //自分が所有するアカウント以外のInstagramビジネスアカウントが投稿している写真も取得したい場合は以下
        if(!empty($target_user)) {
            $query = 'business_discovery.username('.$target_user.'){id,name,username,profile_picture_url,followers_count,follows_count,media_count,ig_id,media{caption,media_url,media_type,like_count,comments_count,timestamp,id}}';
        }
        //自分のアカウントの画像が取得できればOKな場合は$queryを以下のようにしてください。
        if(!empty($insta_business_name)) {
            $query = 'name,media{caption,like_count,media_url,permalink,timestamp,username}&access_token='.$access_token;
        }
        $instagram_api_url = 'https://graph.facebook.com/v3.3/';
        $target_url = $instagram_api_url.$instagram_business_id."?fields=".$query."&access_token=".$access_token;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $target_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $instagram_data = curl_exec($ch);
        curl_close($ch);

        $insta_data = json_decode($instagram_data, true);

        return $insta_data;
    }

    /**
     * ログを加工してセットする
     *
     * @param Array $user
     * @param Array $e
     * @return String $log
     */
    public function setLog($user, $e)
    {
        $log = ""; // 例外内容格納用
        $log = "ロールユーザー：【".$user['role']."】, アドレス：【".$user['email']."】\n";
        $log = $log.$e;
        return $log;
    }

}
