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
class AppView extends View
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading helpers.
     *
     * e.g. `$this->loadHelper('Html');`
     *
     * @return void
     */
    public function initialize()
    {
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

        // パンくずを設定する
        $this->setBreadcrumb(explode(DS, rtrim($this->request->url, DS)));
    }

    /**
     * パンくずを設定する
     *
     * @param array $breadcrumbList
     * @return void
     */
    public function setBreadcrumb($breadcrumbList)
    {
        // 遷移先がエリアのトップ画面の場合
        if (array_key_exists($breadcrumbList[0], AREA)) {
            $this->Breadcrumbs->add([
                ['title' => '<i class="material-icons">home</i>', 'url' => DS],
                ['title' => AREA[$breadcrumbList[0]]['label'], 'url' => ['controller' => $breadcrumbList[0], 'action' => 'index']]
            ]);
        }
        // 遷移先がエリア ⇒ ジャンル画面の場合
        if ($breadcrumbList[1] == 'genre') {
            // リストの最後に追加
            $this->Breadcrumbs->add(
                GENRE[$this->request->query('genre')]['label'],
                "#!", ['class' => 'breadcrumbs-tail']
            );
        }
        // 遷移先がエリア ⇒ ショップ画面の場合
        if ($breadcrumbList[1] == 'shop') {
            // リストの最後に追加
            $this->Breadcrumbs->add([
                ['title' => GENRE[$this->request->query['genre']]['label'],
                    'url' => ['controller' => $breadcrumbList[0], 'action' => 'genre','?'=> ['genre' =>$this->request->query['genre']]]],
            ]);
            // リストの最後に追加
            $this->Breadcrumbs->add(
                $this->request->query['name'],
                "#!", ['class' => 'breadcrumbs-tail']
            );
        }
        // 遷移先がエリア ⇒ キャスト画面の場合
        if ($breadcrumbList[1] == 'cast') {
            // リストの最後に追加
            $this->Breadcrumbs->add([
                ['title' => GENRE[$this->request->query['genre']]['label'],
                    'url' => ['controller' => $breadcrumbList[0], 'action' => 'genre/','?'=> ['genre' =>$this->request->query['genre']]]],
                ['title' => $this->request->query['name'],
                    'url' => ['controller' => $breadcrumbList[0], 'action' => 'shop/'.$this->request->query['shop'],
                    '?' => ['genre' => $this->request->query['genre'], 'name' => $this->request->query['name']]]],
            ]);
            // リストの最後に追加
            $this->Breadcrumbs->add(
                $this->request->query['nickname'], "#!", ['class' => 'breadcrumbs-tail']
            );
        }
        // 遷移先がエリア ⇒ キャスト ⇒ 日記画面の場合
        if ($breadcrumbList[1] == 'diary') {
            // リストの最後に追加
            $this->Breadcrumbs->add([
                ['title' => GENRE[$this->request->query['genre']]['label'],
                    'url' => ['controller' => $breadcrumbList[0], 'action' => 'genre/',
                    '?'=> ['genre' =>$this->request->query['genre']]]],
                ['title' => $this->request->query['name'],
                    'url' => ['controller' => $breadcrumbList[0], 'action' => 'shop/'.$this->request->query['shop'],
                    '?' => ['shop' => $this->request->query['shop'],'genre' => $this->request->query['genre'], 'name' => $this->request->query['name']]]],
                ['title' => $this->request->query['nickname'],
                    'url' => ['controller' => $breadcrumbList[0], 'action' => 'cast/'.$this->request->query['cast'],
                    '?' => ['shop' => $this->request->query['shop'], 'cast' => $this->request->query['cast'], 'genre' => $this->request->query['genre'] , 'name' => $this->request->query['name'],
                    'nickname' => $this->request->query['nickname']]]],
            ]);
            // リストの最後に追加
            $this->Breadcrumbs->add(
                '日記', "#!", ['class' => 'breadcrumbs-tail']
            );
        }

        // パンくず設定
        if ($this->template == 'top') {
            $this->Breadcrumbs->add(
            '<i class="material-icons">home</i>',
            DS
        );
        }
        // 検索画面のパンくず設定
        if ($breadcrumbList[0] == 'search') {
            $this->Breadcrumbs->add([
          ['title' => '<i class="material-icons">home</i>', 'url' => DS],
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