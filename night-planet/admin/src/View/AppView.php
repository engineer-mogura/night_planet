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

        // $title = '';
        // if (!empty($this->viewVars['title'])) {
        //     // testtitleテスト
        //     $title .=  $this->viewVars['title'] . ' | ';
        // }
        // $title .= LT['001'];
        $this->assign('title', $this->viewVars['title']);

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
        // 次の画面がエリアのトップページの場合
        if ($this->viewVars['next_view'] == 'area') {
            $this->Breadcrumbs->add([
                ['title' => '<i class="material-icons">home</i>', 'url' => DS],
                ['title' => AREA[$breadcrumbList[0]]['label'], 'url' => ['controller' => $breadcrumbList[0], 'action' => 'index']]
            ]);
        } else if ($this->viewVars['next_view'] == 'genre') {
        // 次の画面がエリアのジャンルの場合
            // リストに追加
            $this->Breadcrumbs->add([
                ['title' => '<i class="material-icons">home</i>', 'url' => DS]
                , ['title' => AREA[$breadcrumbList[0]]['label']
                    , 'url' => ['controller' => $breadcrumbList[0]]],
            ]);
            // リストの最後に追加
            $this->Breadcrumbs->add(
                GENRE[$breadcrumbList[1]]['label'],
                "#!",
                ['class' => 'breadcrumbs-tail']
            );
        } else if ($this->viewVars['next_view'] == PATH_ROOT['SHOP']) {
        // 次の画面が店舗の場合
            // リストに追加
            $this->Breadcrumbs->add([
                ['title' => '<i class="material-icons">home</i>', 'url' => DS]
                , ['title' => $this->viewVars['shopInfo']['area']['label']
                    , 'url' => ['controller' => $this->viewVars['shopInfo']['area']['path']]]
                , ['title' => $this->viewVars['shopInfo']['genre']['label']
                    ,'url' => ['controller' => $this->viewVars['shopInfo']['area']['path']
                         .DS . $this->viewVars['shopInfo']['genre']['path']]],
            ]);
            // リストの最後に追加
            $this->Breadcrumbs->add(
                $this->viewVars['shop']['name'],
                "#!",
                ['class' => 'breadcrumbs-tail']
            );
        } else if ($this->viewVars['next_view'] == PATH_ROOT['CAST']) {
        // 次の画面がキャストの場合
            // リストに追加
            $this->Breadcrumbs->add([
                ['title' => '<i class="material-icons">home</i>', 'url' => DS]
                , ['title' => $this->viewVars['shopInfo']['area']['label']
                    , 'url' => ['controller' => $this->viewVars['shopInfo']['area']['path']]]
                , ['title' => $this->viewVars['shopInfo']['genre']['label']
                ,'url' => ['controller' => $this->viewVars['shopInfo']['area']['path']
                        .DS . $this->viewVars['shopInfo']['genre']['path']]]
                , ['title' => $this->viewVars['shopInfo']['name']
                , 'url' => ['controller' => $this->viewVars['shopInfo']['area']['path']
                        .DS . $this->viewVars['shopInfo']['genre']['path']
                        .DS . $this->viewVars['shopInfo']['id']]],
            ]);
            // リストの最後に追加
            $this->Breadcrumbs->add(
                $this->viewVars['cast']['nickname'],
                "#!",
                ['class' => 'breadcrumbs-tail']
            );
        } else if ($this->viewVars['next_view'] == PATH_ROOT['DIARY']) {
        // 次の画面が日記の場合
            // リストに追加
            $this->Breadcrumbs->add([
                ['title' => '<i class="material-icons">home</i>', 'url' => DS]
                , ['title' => $this->viewVars['shopInfo']['area']['label']
                    , 'url' => ['controller' => $this->viewVars['shopInfo']['area']['path']]]
                , ['title' => $this->viewVars['shopInfo']['genre']['label']
                    ,'url' => ['controller' => $this->viewVars['shopInfo']['area']['path']
                    .DS . $this->viewVars['shopInfo']['genre']['path']]]
                , ['title' => $this->viewVars['shopInfo']['name']
                    , 'url' => ['controller' => $this->viewVars['shopInfo']['area']['path']
                    .DS . $this->viewVars['shopInfo']['genre']['path']
                    .DS . $this->viewVars['shopInfo']['id']]]
                , ['title' => $this->viewVars['cast']['nickname']
                    , 'url' => ['controller' => $this->viewVars['shopInfo']['area']['path']
                    .DS . PATH_ROOT['CAST']
                    .DS . $this->viewVars['cast']['id']]],
            ]);
            // リストの最後に追加
            $this->Breadcrumbs->add(
                '日記',
                "#!",
                ['class' => 'breadcrumbs-tail']
            );
        } else if ($this->viewVars['next_view'] == PATH_ROOT['GALLERY']) {
        // 次の画面がギャラリーの場合
            // リストに追加gallery
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
                'ギャラリー',
                "#!",
                ['class' => 'breadcrumbs-tail']
            );
        } else if ($this->viewVars['next_view'] == PATH_ROOT['NOTICE']) {
        // 次の画面がお知らせの場合
            // リストに追加
            $this->Breadcrumbs->add([
                ['title' => '<i class="material-icons">home</i>', 'url' => DS]
                , ['title' => $this->viewVars['shopInfo']['area']['label']
                    , 'url' => ['controller' => $this->viewVars['shopInfo']['area']['path']]]
                , ['title' => $this->viewVars['shopInfo']['genre']['label']
                    ,'url' => ['controller' => $this->viewVars['shopInfo']['area']['path']
                    .DS . $this->viewVars['shopInfo']['genre']['path']]]
                , ['title' => $this->viewVars['shopInfo']['name']
                    , 'url' => ['controller' => $this->viewVars['shopInfo']['area']['path']
                    .DS . $this->viewVars['shopInfo']['genre']['path']
                    .DS . $this->viewVars['shopInfo']['id']]],
            ]);
            // リストの最後に追加
            $this->Breadcrumbs->add(
                'お知らせ',
                "#!",
                ['class' => 'breadcrumbs-tail']
            );
        } else if ($this->viewVars['next_view'] == 'search') {
        // 次の画面が検索の場合
            // リストに追加
            $this->Breadcrumbs->add([
                ['title' => '<i class="material-icons">home</i>', 'url' => DS],
            ]);
            // リストの最後に追加
            $this->Breadcrumbs->add(
                '検索',
                "#!",
                ['class' => 'breadcrumbs-tail']
            );
        }

    }
}
