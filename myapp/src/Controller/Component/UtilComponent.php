<?php

namespace App\Controller\Component;

use Cake\Core\Configure;
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
     * オーナーのディレクトリを取得する。
     *
     * @return void
     */
    public function getItem($shop)
    {
        // TODO: Authセッションからオーナー情報を取得せず、shopsテーブルから取る？
        $shopArea = $shop['area'];
        $shopGenre = $shop['genre'];
        $shopDir = $shop['dir'];
        $areas = AREA;
        $genres = GENRE;
        $infoArray = array();
        foreach ($areas as $area) {
            if ($area['path'] == $shopArea) {
                $infoArray = $infoArray + array('area' => $area);
                break;
            }
        }
        foreach ($genres as $genre) {
            if ($genre['path'] == $shopGenre) {
                $infoArray = $infoArray + array('genre' => $genre);
                break;
            }
        }
        $infoArray = $infoArray + array('dir'=> $shopDir);
        $path = DS.PATH_ROOT['IMG'].DS.$infoArray['area']['path'].DS.$infoArray['genre']['path'].DS.$infoArray['dir'].DS;
        $infoArray = $infoArray + array("dir_path"=> $path);
        return  $infoArray;
    }

    /**
     * クレジットリストを作成する
     *
     * @param object $shop
     * @param array $masterCodeResult
     * @return void
     */
    public function getCredit($shop, $masterCodeResult)
    {
        $array = array();
        $creditsHidden = array();
        foreach ($shop as $key => $value) {
            $array = explode(',', $value->shop->credit);
        }

        for ($i = 0; $i < count($array); $i++) {
            foreach ($masterCodeResult as $key => $value) {
                if ($array[$i] == $value->code) {
                    $creditsHidden[] = array('tag'=>$value->code,'image'=>PATH_ROOT['CREDIT'].$value->code.".png",'id'=>$value->id);
                    continue;
                }
            }
        }
        return $creditsHidden;
    }

    /**
     * 待遇リストを作成する
     *
     * @param [type] $shop
     * @param [type] $masterCodeResult
     * @return void
     */
    public function getTreatment($shop, $masterCodeResult)
    {
        $array = array();
        $treatmentsHidden = array();
        foreach ($shop as $key => $value) {
            $array = explode(',', $value->job->treatment);
        }

        for ($i = 0; $i < count($array); $i++) {
            foreach ($masterCodeResult as $key => $value) {
                if ($array[$i] == $value->code_name) {
                    $treatmentsHidden[] = array('tag'=>$value->code_name,'id'=>$value->code);
                    continue;
                }
            }
        }
        return $treatmentsHidden;
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
            "'%Y年%c月'" => 'literal']);
        $md = $query->func()->date_format([
            'created' => 'identifier',
            "'%c月%e日'" => 'literal']);
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
     * ファイルアップロードの処理
     *
     * @param array $file
     * @param array $files_befor
     * @param string $dir
     * @param integer $limitFileSize
     * @return void
     */
    public function file_upload(array $file = null, array $files_befor = null,
        string $dir = null, int $limitFileSize = 1024 * 1024)
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

            // ファイルの移動
            if (!@move_uploaded_file($file["tmp_name"], $dir . DS . $uploadFile)) {
                throw new RuntimeException('Failed to move uploaded file.');
            }

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
    public function createTmpDirectoy(string $tmpPath, folder $dir)
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

}
