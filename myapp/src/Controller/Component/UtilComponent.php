<?php

namespace App\Controller\Component;

use Cake\Controller\Component;

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
}