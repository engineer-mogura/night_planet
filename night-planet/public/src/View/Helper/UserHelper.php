<?php
namespace App\View\Helper;

use Cake\View\Helper;
use Cake\View\View;

/**
 * User helper
 */
class UserHelper extends Helper
{
    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    /**
     * ユーザ情報を返す.
     *
     * @var array
     */
    function get_u_info(String $str)
    {
      switch ($str) {
        case 'name';
          $rtn = !empty($this->_View->viewVars['userInfo']['name']) ?
              $this->_View->viewVars['userInfo']['name'] : 'ゲストさん';
          return $rtn;
        break;
        case 'email';
          $rtn = $this->_View->viewVars['userInfo']['email'];
          return $rtn;
        break;
        case 'created';
          $rtn = $this->_View->viewVars['userInfo']['created'];
          return $rtn;
        break;
        case 'icon';
          $rtn = !empty($this->_View->viewVars['userInfo']['icon_path']) ?
              $this->_View->viewVars['userInfo']['icon_path'] : PATH_ROOT['NO_IMAGE02'];
          return $rtn;
        break;
        case 'auth';
          if (empty($this->_View->viewVars['userInfo'])) {
            $rtn = '<li class="nav-account-login lock"><a data-target="modal-login" class="modal-trigger">'
                  . '<i class="material-icons">lock</i><span>ログイン</span></a></li>';
          } else {
            $rtn = '<li class="nav-account-login unlock"><a href="/user/users/mypage">'
                  . '<i class="material-icons">lock_open</i><span>マイページ</span></a></li>';
          }
          return $rtn;
        break;
       default:
          return '';
          break;
      }
    }
}
