<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Configure;
use Cake\Filesystem\File;

class UtilComponent extends Component {

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
      function ifnull($target=null)
      {
        if(!is_null($target)) return $target;
      
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
      function ifnullString($target=null)
      {
        if($target !== '') return $target;
      
        return null;
      }
  
    /**
     * オーナーのディレクトリを取得する。
     *
     * @return void
     */
    public function getItem()
    {
        // TODO: Authセッションからオーナー情報を取得せず、Ownersテーブルから取る？
        $ownerArea = $this->request->getSession()->read('Auth.Owner.area');
        $ownerGenre = $this->request->getSession()->read('Auth.Owner.genre');
        $ownerDir = $this->request->getSession()->read('Auth.Owner.dir');
        $areas = array('miyako','ishigaki','naha','nanjo','tomigusuku',
                        'urasoe','ginowan','okinawashi','uruma','nago');
        $genres = array('caba','snack','girlsbar','bar');

        $infoArray = array();
        foreach ($areas as $area) {
            if ($area == $ownerArea) {
                $infoArray = $infoArray + Configure::read('area.'.$area);
                break;
            }
        }
        foreach ($genres as $genre) {
            if ($genre == $ownerGenre) {
                $infoArray = $infoArray + Configure::read('genre.'.$genre);
                break;
            }
        }
        $infoArray = $infoArray + array('dir'=> $ownerDir);
        $path = "img/".$infoArray['area_path']."/".$infoArray['genre_path']."/".$infoArray['dir']."/";
        $infoArray = $infoArray + array("dir_path"=> $path);
        return  $infoArray;
    }

    /**
     * クレジットリストを作成する
     *
     * @param [type] $shop
     * @param [type] $credits
     * @return void
     */
    public function getCredit($shop, $masterCodeResult) {

        $array = array();
        $creditsHidden = array();
        foreach ($shop as $key => $value) {
            $array = explode(',',$value->credit);
        }

        for($i = 0; $i < count($array); $i++) {
            foreach ($masterCodeResult as $key => $value) {
                if($array[$i] == $value->code) {
                    $creditsHidden[] = array('tag'=>$value->code,'image'=>"/img/common/credit/".$value->code.".png",'id'=>$value->id);
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
    public function getTreatment($shop, $masterCodeResult) {

        $array = array();
        $treatmentsHidden = array();
        foreach ($shop as $key => $value) {
            $array = explode(',',$value->job->treatment);
        }

        for($i = 0; $i < count($array); $i++) {
            foreach ($masterCodeResult as $key => $value) {
                if($array[$i] == $value->code_name) {
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
    public function getSelectList($masterCodesFind, $masterCodeEntity, $flag = null) {

        $result = array();
        for($i = 0; $i < count($masterCodesFind); $i++) {
            $query = $masterCodeEntity->find('list', [
                'keyField' => 'code',
                'valueField' => 'code_name'
            ])->where(['code_group' => $masterCodesFind[$i]]);
            $result = array_merge($result,array($masterCodesFind[$i] => $query->toArray()));
        }
        // 年齢リストを作成するか
        if (!empty($flag)) {
            $ageList = array();
            for($i = 18; $i <= 99; $i++) {
                $ageList[$i] = $i;
            }
            $result = array_merge($result,array('age' => $ageList));
        }

        return $result;
    }

    /**
     * ファイルアップロードの処理
     *
     * @param [type] $file
     * @param [type] $dir
     * @param [type] $limitFileSize
     * @return void
     */
    public function file_upload($file = null, $dir = null, $limitFileSize = 1024 * 1024)
    {
        try {
            // ファイルを保存するフォルダ $dirの値のチェック
            if ($dir) {
                debug("file_upload", "debug");
                debug($dir, "debug");
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

            // ファイルの移動
            if (!@move_uploaded_file($file["tmp_name"], $dir . "/" . $uploadFile)) {
                throw new RuntimeException('Failed to move uploaded file.');
            }

            // 処理を抜けたら正常終了
//            echo 'File is uploaded successfully.';
        } catch (RuntimeException $e) {
            throw $e;
        }
        return $uploadFile;
    }

}