<?php

namespace App\Controller\Component;

use Cake\Log\Log;
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
     * 更新日時順で並び替える関数
     * @param mixed
     * @param mixed
     * @return mixed
     */
    function sortByLastmod($a, $b) {
        return filemtime($b) - filemtime($a);
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
            ,'dir'=>$owner['dir']);
        $path = DS.PATH_ROOT['IMG'].DS.PATH_ROOT['OWNER'].DS.$owner['dir'];

        $ownerInfo = $ownerInfo + array('owner_path'=> $path
        ,'image_path'=> $path.DS.PATH_ROOT['IMAGE'],'profile_path'=> $path.DS.PATH_ROOT['PROFILE']);

        // ディクレトリ取得
        $dir = new Folder(preg_replace('/(\/\/)/', '/',
            WWW_ROOT.$ownerInfo['profile_path']), true, 0755);

        // ファイルは１つのみだけど配列で取得する
        $files = glob($dir->path.DS.'*.*');
        $ownerInfo = $ownerInfo + array('icon_name'=>basename($files[0]));

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
            ,'dir'=>$shop['dir']);
        $path = DS.PATH_ROOT['IMG'].DS.$shopInfo['area']['path']
                .DS.$shopInfo['genre']['path'].DS.$shop['dir'];

        $shop_url = APP_PATH['APP'].$shopInfo['area']['path']
            .DS.PATH_ROOT['SHOP'].DS.$shop['id']
            .'?area='.$shop['area'].'&genre='.$shop['genre'].'&name='.$shop['name'];

        $shopInfo = $shopInfo + array('shop_path'=> $path
        ,'image_path'=> $path.DS.PATH_ROOT['IMAGE'],'cast_path'=> $path.DS.PATH_ROOT['CAST']
        ,'top_image_path'=> $path.DS.PATH_ROOT['TOP_IMAGE'],'notice_path'=>$path.DS.PATH_ROOT['NOTICE']
        ,'cache_path'=>$path.DS.PATH_ROOT['CACHE'],'tmp_path'=>$path.DS.PATH_ROOT['TMP']
        ,'shop_url'=>$shop_url);
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
            ,'dir'=>$cast['dir'], 'shop_dir'=> $shopDir);
        $path = DS.PATH_ROOT['IMG'].DS.$castInfo['area']['path']
                .DS.$castInfo['genre']['path'].DS.$shop['dir']
                .DS.PATH_ROOT['CAST'].DS.$cast['dir'];
        $shop_url = APP_PATH['APP'].$castInfo['area']['path']
            .DS.PATH_ROOT['SHOP'].DS.$shop['id']
            .'?genre='.$shop['genre'].'&name='.$shop['name'];

        $castInfo = $castInfo + array('cast_path'=> $path,'top_image_path'=> $path.DS.PATH_ROOT['TOP_IMAGE']
            , 'image_path'=> $path.DS.PATH_ROOT['IMAGE'], 'profile_path'=> $path.DS.PATH_ROOT['PROFILE']
            , 'schedule_path'=> $path.DS.PATH_ROOT['SCHEDULE'], 'diary_path'=> $path.DS.PATH_ROOT['DIARY']
            , 'tmp_path'=> $path.DS.PATH_ROOT['TMP'], 'cache_path'=>$path.DS.PATH_ROOT['CACHE'], 'shop_url'=>$shop_url);

        // ディクレトリ取得
        $dir = new Folder(preg_replace('/(\/\/)/', '/',
            WWW_ROOT.$castInfo['profile_path']), true, 0755);

        // ファイルは１つのみだけど配列で取得する
        $files = glob($dir->path.DS.'*.*');
        $castInfo = $castInfo + array('icon_name'=>basename($files[0]));

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
     * @param [type] $diaryPath
     * @return array
     */
    public function getDiarys($id, $diaryPath)
    {
        $diarys = TableRegistry::get('diarys');
        // キャスト情報、最新の日記情報とイイネの総数取得
        // 過去の日記をアーカイブ形式で取得する
        $query = $diarys->find('all')->select($diarys->Schema()->columns());
        $ym = $query->func()->date_format([
            'created' => 'identifier',
            "'%Y/%c'" => 'literal']);
        $md = $query->func()->date_format([
            'created' => 'identifier',
            "'%c/%e'" => 'literal']);
        $archives = $query->select([
            'ym_created' => $ym,
            'md_created' => $md])
            ->where(['cast_id' => $id])
            ->contain(['diary_likes'])
            ->order(['created' => 'DESC'])->all();
        $archives = $this->groupArray($archives, 'ym_created');
        $archives = array_values($archives);

        // ディクレトリ取得
        $dir = new Folder(preg_replace('/(\/\/)/', '/'
            , WWW_ROOT.$diaryPath), true, 0755);

        foreach ($archives as $key => $archive) {

            foreach ($archive as $key => $value) {

                $gallery = array();

                /// 並び替えして出力
                $files = glob($dir->path.$value['dir'].DS.'*.*');
                usort( $files, $this->sortByLastmod );

                foreach ($files as $file) {
                    $timestamp = date('Y/m/d H:i', filemtime($file));
                    array_push($gallery, array(
                    "file_path"=>$diaryPath.$value->dir.DS.(basename($file))
                    ,"date"=>$timestamp));
                    continue; // １件のみ取得できればよい
                }
                $value->set('gallery', $gallery);
                // 画像数をセット
                $value->set('gallery_count', count($files));
            }
        }


        return $archives;
    }

    /**
     * 指定した１件の日記情報を取得する処理
     *
     * @param [type] $id
     * @param [type] $diaryPath
     * @return array
     */
    public function getDiary($id, $diaryPath)
    {
        $diary = TableRegistry::get('diarys');
        $query = $diary->find('all')->select($diary->Schema()->columns());
        $ymd = $query->func()->date_format([
            'created' => 'identifier',
            "'%Y年%c月%e日'" => 'literal']);
        $diary = $query->select([
            'ymd_created' => $ymd])
            ->where(['id' => $id])
            ->contain(['diary_likes'])
            ->first();

        // ディクレトリ取得
        $dir = new Folder(preg_replace('/(\/\/)/', '/'
            , WWW_ROOT.$diaryPath), true, 0755);

        $gallery = array();

        /// 並び替えして出力
        $files = glob($dir->path.$diary['dir'].DS.'*.*');
        usort( $files, $this->sortByLastmod );
        foreach ($files as $file) {
            $timestamp = date('Y/m/d H:i', filemtime($file));
            array_push($gallery, array(
            "file_path"=>$diaryPath.$diary->dir.DS.(basename($file))
            ,"date"=>$timestamp));
        }
        $diary->set('gallery', $gallery);

        return $diary;
    }


    /**
     * 日記テーブルから最新の日記情報とイイネの総数を取得する処理
     *
     * @param [type] $rowNum
     * @param [type] $isArea
     * @param [type] $shop_id
     * @return array
     */
    public function getNewDiarys($rowNum, $isArea = null, $shop_id = null)
    {
        $diarys = TableRegistry::get('diarys');

        if(!empty($isArea)) {
            $diarys = $diarys->find('all')
            ->contain(['diary_likes','casts','casts.shops'])
            ->matching('casts.shops', function($q) use ($isArea){
                return $q->where(['shops.area'=>$isArea]);
            })
            ->order(['diarys.created' => 'DESC'])
            ->limit($rowNum)->all();
        } else if(!empty($shop_id)) {
            $diarys = $diarys->find('all')
                ->contain(['diary_likes','casts','casts.shops'])
                ->matching('casts.shops', function($q) use ($shop_id){
                    return $q->where(['shops.id'=>$shop_id]);
                })
                ->order(['diarys.created' => 'DESC'])
                ->limit($rowNum)->all();
        } else {
            $diarys = $diarys->find('all')
                ->contain(['diary_likes','casts','casts.shops'])
                ->order(['diarys.created' => 'DESC'])
                ->limit($rowNum)->all();
        }
        foreach ($diarys as $key => $diary) {
            $diaryPath = WWW_ROOT . PATH_ROOT['IMG']
                . DS . AREA[$diary->cast->shop->area]['path']
                . DS . GENRE[$diary->cast->shop->genre]['path']
                . DS . $diary->cast->shop->dir
                . DS . PATH_ROOT['CAST']
                . DS . $diary->cast->dir
                . DS . PATH_ROOT['DIARY'] . $diary->dir;
            // ディクレトリ取得
            $dir = new Folder(preg_replace('/(\/\/)/', '/'
            , $diaryPath), true, 0755);
            // 画像数をセット
            $diary->set('gallery_count', count(glob($diaryPath . DS . '*.*')));

            $profileFullPath = WWW_ROOT . PATH_ROOT['IMG']
                . DS . AREA[$diary->cast->shop->area]['path']
                . DS . GENRE[$diary->cast->shop->genre]['path']
                . DS . $diary->cast->shop->dir
                . DS . PATH_ROOT['CAST']
                . DS . $diary->cast->dir
                . DS . PATH_ROOT['PROFILE'];

            $profilePath = DS . PATH_ROOT['IMG']
                . DS . AREA[$diary->cast->shop->area]['path']
                . DS . GENRE[$diary->cast->shop->genre]['path']
                . DS . $diary->cast->shop->dir
                . DS . PATH_ROOT['CAST']
                . DS . $diary->cast->dir
                . DS . PATH_ROOT['PROFILE'];
            $dir = new Folder(preg_replace('/(\/\/)/', '/'
                , $profileFullPath), true, 0755);
            $files = glob($dir->path.DS.'*.*');
            count($files) > 0 ? $icon = $profilePath. DS. basename($files[0])
                 : $icon = PATH_ROOT['NO_IMAGE01'];
            // アイコン画像をセット
            $diary->set('icon', $icon);
        }


        return $diarys->toArray();
    }

    /**
     * 店舗の全てのお知らせ情報を取得する処理
     *
     * @param [type] $id
     * @param [type] $noticePath
     * @return array
     */
    public function getNotices($id, $noticePath)
    {
        $shopInfos = TableRegistry::get('shop_infos');
        // キャスト情報、最新の日記情報とイイネの総数取得
        // 過去の日記をアーカイブ形式で取得する
        $query = $shopInfos->find('all')->select($shopInfos->Schema()->columns());
        $ym = $query->func()->date_format([
            'created' => 'identifier',
            "'%Y/%c'" => 'literal']);
        $md = $query->func()->date_format([
            'created' => 'identifier',
            "'%c/%e'" => 'literal']);
        $archives = $query->select([
            'ym_created' => $ym,
            'md_created' => $md])
            ->where(['shop_id' => $id])
            ->contain(['shop_info_likes'])
            ->order(['created' => 'DESC'])
            ->all();

        $archives = $this->groupArray($archives, 'ym_created');
        $archives = array_values($archives);

        // ディクレトリ取得
        $dir = new Folder(preg_replace('/(\/\/)/', '/'
            , WWW_ROOT.$noticePath), true, 0755);

        foreach ($archives as $key => $archive) {

            foreach ($archive as $key => $value) {

                $gallery = array();

                /// 並び替えして出力
                $files = glob($dir->path.$value['dir'].DS.'*.*');
                usort( $files, $this->sortByLastmod );

                foreach ($files as $file) {
                    $timestamp = date('Y/m/d H:i', filemtime($file));
                    array_push($gallery, array(
                    "file_path"=>$noticePath.$value->dir.DS.(basename($file))
                    ,"date"=>$timestamp));
                    continue; // １件のみ取得できればよい
                }
                $value->set('gallery', $gallery);
                // 画像数をセット
                $value->set('gallery_count', count($files));
            }
        }

        return $archives;
    }

     /**
     * 指定した１件のお知らせ情報を取得する処理
     *
     * @param [type] $id
     * @param [type] $noticePath
     * @return array
     */
    public function getNotice($id, $noticePath)
    {
        $shopInfos = TableRegistry::get('shop_infos');
        $query = $shopInfos->find('all')->select($shopInfos->Schema()->columns());
        $ymd = $query->func()->date_format([
            'created' => 'identifier',
            "'%Y年%c月%e日'" => 'literal']);
        $shopInfo = $query->select([
            'ymd_created' => $ymd])
            ->where(['id' => $id])
            ->contain(['shop_info_likes'])
            ->first();

        // ディクレトリ取得
        $dir = new Folder(preg_replace('/(\/\/)/', '/'
            , WWW_ROOT.$noticePath), true, 0755);

        $gallery = array();

        /// 並び替えして出力
        $files = glob($dir->path.$shopInfo['dir'].DS.'*.*');
        usort( $files, $this->sortByLastmod );
        foreach ($files as $file) {
            $timestamp = date('Y/m/d H:i', filemtime($file));
            array_push($gallery, array(
            "file_path"=>$noticePath.$shopInfo->dir.DS.(basename($file))
            ,"date"=>$timestamp));
        }
        $shopInfo->set('gallery', $gallery);

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
                ->contain(['shop_info_likes','shops'])
                ->matching('shops', function($q) use ($isArea){
                    return $q->where(['shops.area'=>$isArea]);
                })
                ->order(['shop_infos.created' => 'DESC'])
                ->limit($rowNum)->all();
        } else {
            $shopInfos = $shopInfos->find('all')
                ->contain(['shop_info_likes','shops'])
                ->order(['shop_infos.created' => 'DESC'])
                ->limit($rowNum)->all();
        }

        foreach ($shopInfos as $key => $shopInfo) {
            $noticePath = WWW_ROOT . PATH_ROOT['IMG']
                . DS . AREA[$shopInfo->shop->area]['path']
                . DS . GENRE[$shopInfo->shop->genre]['path']
                . DS . $shopInfo->shop->dir
                . DS . PATH_ROOT['NOTICE'] . $shopInfo->dir;

            // ディクレトリ取得
            $dir = new Folder(preg_replace('/(\/\/)/', '/', $noticePath), true, 0755);
            // 画像数をセット
            $shopInfo->set('gallery_count', count(glob($noticePath . DS . '*.*')));

            $topImageFullPath = WWW_ROOT . PATH_ROOT['IMG']
                . DS . AREA[$shopInfo->shop->area]['path']
                . DS . GENRE[$shopInfo->shop->genre]['path']
                . DS . $shopInfo->shop->dir
                . DS . PATH_ROOT['TOP_IMAGE'];

            $topImagePath = DS . PATH_ROOT['IMG']
                . DS . AREA[$shopInfo->shop->area]['path']
                . DS . GENRE[$shopInfo->shop->genre]['path']
                . DS . $shopInfo->shop->dir
                . DS . PATH_ROOT['TOP_IMAGE'];

            $dir = new Folder(preg_replace('/(\/\/)/', '/', $topImageFullPath), true, 0755);
            $files = glob($dir->path.DS.'*.*');
            count($files) > 0 ? $icon = $topImagePath. DS. basename($files[0])
                 : $icon = PATH_ROOT['NO_IMAGE01'];
            // アイコン画像をセット
            $shopInfo->set('icon', $icon);
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
     * 日付けで取得(yyyy-mm-dd (day)))
     *
     * @return void
     */
    function getPeriodDate() {

        $week = [
            '日', //0
            '月', //1
            '火', //2
            '水', //3
            '木', //4
            '金', //5
            '土', //6
          ];
        $date = date('Y-m');
        $startDate = date('Y-m-d', strtotime('first day of '. $date));
        $endDate = date('Y-m-d', strtotime(date('Y-m-t', strtotime($startDate . '+' . 1 . 'month'))));
        $diff = (strtotime($endDate) - strtotime($startDate)) / ( 60 * 60 * 24);
        for($i = 0; $i <= $diff; $i++) {
            $dateFormat = date('m/d w', strtotime($startDate . '+' . $i . 'days'));
            $period[] = substr_replace($dateFormat, '('.$week[substr($dateFormat, -1)].')', -1);
        }
        return $period;
    }

    /**
     * 月(yyyy-mm)で取得
     *
     * @return void
     */
    function getPeriodMonth() {

        $startDate = date('Y-m-d', strtotime('2017-01-06'));
        $endDate = date('Y-m-d', strtotime("2018-01-09"));
        $diff = (strtotime($endDate) - strtotime($startDate)) / ( 60 * 60 * 24 * 30);
        for($i = 0; $i <= $diff; $i++) {
            $period[] = date('Y-m', strtotime($startDate . '+' . $i . 'month'));
        }
        return $period;
    }

    /**
     * 指定範囲内の日付かチェック
     *
     * @param [type] $start_date
     * @param [type] $end_date
     * @param [type] $date_from_user
     * @return void
     */
    function check_in_range($start_date, $end_date, $date_from_user)
    {
        // Convert to timestamp
        $start_ts = strtotime($start_date);
        $end_ts = strtotime($end_date);
        $user_ts = strtotime($date_from_user);

        // Check that user date is between start & end
        return (($user_ts >= $start_ts) && ($user_ts <= $end_ts));
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
    public function getInstagram($insta_user_name = null
        , $insta_business_name = null, $cache_path) {

        //////////////////////
        /*     初期設定     */
        //////////////////////

        // 投稿の最大取得数
        $max_posts      = API['INSTAGRAM_MAX_POSTS'];

        // Graph API の URL
        $graph_api      = API['INSTAGRAM_GRAPH_API'];

        // ビジネスID
        $ig_buisiness   = API['INSTAGRAM_BUSINESS_ID'];

        // 無期限のページアクセストークン
        $access_token   = API['INSTAGRAM_GRAPH_API_ACCESS_TOKEN'];

        //ここに取得したいInstagramビジネスアカウントのユーザー名を入力してください。
        //https://www.instagram.com/nightplanet91/なので
        //「nightplanet91」がユーザー名になります
        $target_user    = $insta_user_name;

        // キャッシュ時間の設定 (最短更新間隔 [sec])
        // 更新頻度が高いと Graph API の時間当たりの利用制限に引っかかる可能性があるので、30sec以上を推奨
        $cache_lifetime = API['INSTAGRAM_CACHE_TIME'];

        // 表示形式の初期設定 (グリッド表示の時は 'grid'、一覧表示の時は 'list' を指定)
        $show_mode      = API['INSTAGRAM_SHOW_MODE'];

        //自分が所有するアカウント以外のInstagramビジネスアカウントが投稿している写真も取得したい場合は以下
        if(!empty($target_user)) {
            $fields      = 'business_discovery.username('.$target_user.'){id,name,username,profile_picture_url,followers_count,follows_count,media_count,ig_id,media{caption,media_url,media_type,children,like_count,comments_count,timestamp,id}}';
        }
        //自分のアカウントの画像が取得できればOKな場合は$queryを以下のようにしてください。
        if(!empty($insta_business_name)) {
            $fields      = 'name,media{caption,like_count,media_url,permalink,timestamp,username}&access_token='.$access_token;
        }

        //////////////////////
        /* 初期設定ここまで */
        //////////////////////

        //////////////////////
        /*     取得処理     */
        //////////////////////

        /*
        キャッシュしておいたファイルが指定時間以内に更新されていたらキャッシュしたファイルのデータを使用する
        指定時間以上経過していたら新たに Instagaram Graph API へリクエストする
        */

        // キャッシュ用のディレクトリが存在するか確認
        // なければ作成する
        if(!file_exists(dirname($cache_path). '/cache/')){
            if(mkdir(dirname($cache_path). '/cache/', 0774)){
                chmod(dirname($cache_path). '/cache/', 0774);
            }
        }

        // キャッシュファイルの最終更新日時を取得
        $cache_lastmodified = @filemtime(dirname($cache_path). '/cache/instagram_graph_api.dat');

        // 更新日時の比較
        if(!$cache_lastmodified){
            // Graph API から JSON 形式でデータを取得
            $ig_json = @file_get_contents($graph_api. $ig_buisiness. '?fields='. $fields. '&access_token='. $access_token);
            // 取得したデータをキャッシュに保存する
            file_put_contents(dirname($cache_path). '/cache/instagram_graph_api.dat', $ig_json, LOCK_EX);
        } else{
            if(time() - $cache_lastmodified > $cache_lifetime){
                // キャッシュの最終更新日時がキャッシュ時間よりも古い場合は再取得する
                $ig_json = @file_get_contents($graph_api. $ig_buisiness. '?fields='. $fields. '&access_token='. $access_token);
                // 取得したデータをキャッシュに保存する
                file_put_contents(dirname($cache_path). '/cache/instagram_graph_api.dat', $ig_json, LOCK_EX);
            } else{
                // キャッシュファイルが新しければキャッシュデータを使用する
                $ig_json = @file_get_contents(dirname($cache_path). '/cache/instagram_graph_api.dat');
            }
        }

        // 取得したJSON形式データを配列に展開する
        if($ig_json){
            $ig_data = json_decode($ig_json);
            if(isset($ig_data->error)){
                $ig_data = null;
            }
        }

        // データ取得に失敗した場合
        // サーバエラーを返す
        if(!$ig_json || !$ig_data){
        //	exit('データ取得に失敗しました');
            // 500 Internal Server Error
            header('HTTP', true, 500);
            exit;
        }

        // 初期表示の設定確認
        if($show_mode !== 'grid' && $show_mode !== 'list'){
            $show_mode = 'grid';
        }

        return $ig_data;
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
        $log = "ID：【".$user['id']."】, ロールユーザー：【".$user['role']."】, アドレス：【".$user['email']."】\n";
        $log = $log.$e;
        return $log;
    }

    /**
     * アクセスログを加工してセットする
     *
     * @param Array $user
     * @param Array $e
     * @return String $log
     */
    public function setAccessLog($user, $action)
    {
        $log = "";
        $log = "ID：【".$user['id']."】, ロールユーザー：【".$user['role']."】, アドレス：【".$user['email']."】";
        $action == 'login' ? $log .= "ログイン" : $log .= "ログアウト";
        return $log;
    }

}