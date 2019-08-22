<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\ORM\TableRegistry;
use Cake\Controller\Controller;
use Cake\Event\Event;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller{

  public $components = array('Util');

  /**
   * Initialization hook method.
   *
   * Use this method to add common initialization code like loading components.
   *
   * e.g. `$this->loadComponent('Security');`
   *
   * @return void
   */
  public function initialize(){
    parent::initialize();
    $this->loadComponent('RequestHandler', [
      'enableBeforeRedirect' => false,
    ]);
    $this->loadComponent('Flash');

    // $query = $this->request->getQuery();
    // // 検索結果でタイトルで決める
    // $title = '';
    // if (!empty($query['area']) && !empty($query['genre'])) {
    //     // コントローラでセットされたtitleを代入してセパレータを追加
    //     $title .=  AREA[$query['area']]['label'] . 'の'.
    //                 GENRE[$query['genre']]['label'].'一覧';
    // } else if(!empty($query['area'])) {
    //     $title .=  AREA[$query['area']]['label'] . '一覧';
    // } else if(!empty($query['genre'])) {
    //     $title .=  GENRE[$query['genre']]['label'] . '一覧';
    // }
    // $this->set('title', $title);
  }

    // public function isAuthorized($user){
    //   // デフォルトでは、アクセスを拒否します。
    //   return false;
    // }
    public function beforeFilter(Event $event)
    {

      //$this->Auth->allow(['login', 'display']);

    }

  }
