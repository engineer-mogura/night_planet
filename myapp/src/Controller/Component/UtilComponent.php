<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Configure;

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
     * オーナーのディレクトリを取得する。
     *
     * @return void
     */
    public function getItem()
    {
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
     * test
     *
     * @param [type] $shop
     * @param [type] $credits
     * @return void
     */
    public function getCredit($shop = null, $credits = null) {

        $array = array();
        $creditsHidden = array();
        foreach ($shop as $key => $value) {
            $array = explode(',',$value->credit);
        }
        for($i = 0; $i < count($array); $i++) {
            foreach ($credits as $key => $value) {
                if($array[$i] == $value->code) {
                    $creditsHidden[] = array('tag'=>$value->code,'image'=>"/img/common/credit/".$value->code.".png",'id'=>$value->id);
                    continue;
                }
            }
        }
        return $creditsHidden;
    }

}