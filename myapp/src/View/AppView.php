<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     3.0.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\View;

use Cake\View\View;

/**
 * Application View
 *
 * Your applicationâ€™s default view class
 *
 * @link https://book.cakephp.org/3.0/en/views.html#the-app-view
 */
class AppView extends View{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading helpers.
     *
     * e.g. `$this->loadHelper('Html');`
     *
     * @return void
     */
    public function initialize(){
      $this->loadHelper('Form', [
        'templates' => 'app_form',
      ]);

      $title = '';
      if (!empty($this->viewVars['title'])) {
          // testtitleテスト
          $title .=  $this->viewVars['title'] . ' | ';
      }
      $title .= LT['001'];
      $this->assign('title', $title);

      $breadcrumbList = explode('/', rtrim($this->request->url, "/"));
      if(array_key_exists($breadcrumbList[0], AREA)) {
        $this->Breadcrumbs->add([
          ['title' => '<i class="material-icons">home</i>', 'url' => '/'],
          ['title' => AREA[$breadcrumbList[0]]['label'], 'url' => ['controller' => $breadcrumbList[0], 'action' => 'index']]
        ]);
        // リストの最後に追加
        $this->Breadcrumbs->add(
          GENRE[$this->request->query('genre')]['label'],
          ['controller' => 'search', 'action' => 'index'],
          ['class' => 'breadcrumbs-tail']
        );
      }
      // パンくず設定
      if($this->template == 'top') {
        $this->Breadcrumbs->add(
          '<i class="material-icons">home</i>',
          '/'
        );
      }
      // 検索画面のパンくず設定
      if($breadcrumbList[0] == 'search') {
        $this->Breadcrumbs->add([
          ['title' => '<i class="material-icons">home</i>', 'url' => '/'],
          // ['title' => '検索', 'url' => ['controller' => 'search', 'action' => 'index']]
        ]);
        // リストの最後に追加
        $this->Breadcrumbs->add(
          '検索',
          ['controller' => 'search', 'action' => 'index'],
          ['class' => 'breadcrumbs-tail']
        );
      }

    }
  }
