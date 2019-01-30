<?php
$cakeDescription = '管理画面';
?>
<!DOCTYPE html>
<html>
<head>
  <?= $this->Html->charset() ?>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"> -->
  <title>
    <?= $cakeDescription ?>:
    <?= $this->fetch('title') ?>
  </title>
  <?= $this->Html->meta('icon') ?>
  <?= $this->Html->script('jquery-3.1.0.min.js') ?>
  <?= $this->Html->script('materialize.min.js') ?>
  <?= $this->Html->script('map.js') ?>
  <?= $this->Html->script("https://maps.googleapis.com/maps/api/js?key=AIzaSyDgd-t3Wa40gScJKC3ZH3ithzuUUapElu4") ?>

  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
  <?= $this->Html->css('fontello-3eba660b/css/fontello.css') ?>
  <?= $this->Html->css('materialize.css') ?>
  <?= $this->Html->css('okiyoru.css') ?>

  <?= $this->fetch('meta') ?>
  <?= $this->fetch('css') ?>
  <?= $this->fetch('script') ?>
  <?php $id = $this->request->getSession()->read('Auth.Owner.id') ?>
<body>
  <ul id="slide-out" class="side-nav">
    <li>
      <div class="user-view">
        <div class="background">
          <img src="/img/common/top/top1.jpg">
        </div>
        <a href="#!user"><img class="circle" src="/img/common/top/top1.jpg"></a>
        <a href="#!name"><span class="white-text name">John Doe</span></a>
        <a href="#!email"><span class="white-text email"><?=$this->request->getSession()->read('Auth.Owner.email')?></span></a>
      </div>
    </li>
    <li>
      <a href="/owner/shops/index/<?=$id?>?targetEdit=topImage" class="waves-effect modal-trigger">
        <i class="material-icons" href="">info_outline</i>トップ画像を編集する
      </a>
    </li>
    <li><a href="/owner/shops/<?=$id?>?targetEdit=topImage" class="waves-effect modal-trigger"><i class="material-icons">event_available</i>キャッチコピーを編集する</a></li>
    <li><a href="/owner/shops/view/<?=$id?>?targetEdit=topImage" class="waves-effect modal-trigger"><i class="material-icons">event_available</i>クーポンを編集する</a></li>
    <li><a href="/owner/shops/view/<?=$id?>?targetEdit=topImage" class="waves-effect modal-trigger"><i class="material-icons">event_available</i>キャストを編集する</a></li>
    <li><a href="/owner/shops/view/<?=$id?>?targetEdit=topImage" class="waves-effect modal-trigger"><i class="material-icons">trending_up</i>店舗情報を編集する</a></li>
    <li><a href="/owner/shops/view/<?=$id?>?targetEdit=topImage" class="waves-effect modal-trigger"><i class="material-icons">trending_up</i>店内を編集する</a></li>
    <li><a href="/owner/shops/view/<?=$id?>?targetEdit=topImage" class="waves-effect modal-trigger"><i class="material-icons">vertical_align_top</i>マップを編集する</a></li>
    <li><a href="/owner/shops/view/<?=$id?>?targetEdit=topImage" class="waves-effect modal-trigger"><i class="material-icons">cloud</i>求人情報を編集する</a></li>
    <li><a class="waves-effect" href="#!"><i class="material-icons">help_outline</i>よくある質問</a></li>
    <li><a class="waves-effect" href="#!"><i class="material-icons">contact_mail</i>お問い合わせ</a></li>
    <li><a class="waves-effect" href="#!"><i class="material-icons">note</i>プライバシーポリシー</a></li>
    <li><a class="waves-effect" href="#!"><i class="material-icons">note</i>ご利用規約</a></li>
    <li><div class="divider"></div></li>
    <li><a href="/owner/owners/logout" class="waves-effect modal-trigger"><i class="material-icons" href="">info_outline</i>ログアウト</a></li>
    <li><a class="waves-effect" href="#!">Third Link With Waves</a></li>
  </ul>
  <nav>
    <div class="nav-wrapper">
      <a href="#" data-activates="slide-out" class="button-collapse oki-button-collapse"><i class="material-icons">menu</i></a>
      <a href="#!" class="brand-logo oki-brand-logo">OKIYORU Go</a>
      <ul class="right">
        <li><a data-target="modal-help" class="modal-trigger"><i class="material-icons">help</i></a></li>
      </ul>
    </div>
  </nav>
  <!-- ログインモーダル -->
  <div id="modal-help" class="modal">
    <div class="modal-content">
      <h4>Modal Header</h4>
      <p>A bunch of text</p>
      <div class="search-box">
        <div class="row">
          <li class="search col s12 l12">
            <div class="input-field oki-input-field">
              <input id="email" type="email" class="validate">
              <label for="email">Email</label>
            </div>
          </li>
          <li class="search col s12 l12">
            <div class="input-field oki-input-field">
              <input id="password" type="password" class="validate">
              <label for="password">Password</label>
            </div>
          </li>
          <li class="search col s12 l12">
            <button class="modal-help btn waves-effect waves-light" type="submit" name="action">ログイン
              <i class="material-icons right">send</i>
            </button>
          </li>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <a href="#!" class="modal-close waves-effect waves-green btn-flat">閉じる</a>
    </div>
  </div>
  <?= $this->fetch('content') ?>
  <footer class="page-footer">
    <div class="container row">
      <div class="col s12 l6">
        <h5 class="white-text">Links</h5>
        <ul class="collection">
          <li><a href="/owner/shops/view/<?=$id?>?targetEdit=topImage" class="grey-text link-box text-lighten-3 collection-item modal-trigger"><i class="material-icons">info_outline</i>トップ画像を編集する</a></li>
          <li><a href="/owner/shops/view/<?=$id?>?targetEdit=topImage" class="grey-text link-box text-lighten-3 collection-item modal-trigger"><i class="material-icons">event_available</i>キャッチコピーを編集する</a></li>
          <li><a href="/owner/shops/view/<?=$id?>?targetEdit=topImage" class="grey-text link-box text-lighten-3 collection-item modal-trigger"><i class="material-icons">event_available</i>クーポンを編集する</a></li>
          <li><a href="/owner/shops/view/<?=$id?>?targetEdit=topImage" class="grey-text link-box text-lighten-3 collection-item modal-trigger"><i class="material-icons">event_available</i>キャストを編集する</a></li>
          <li><a href="/owner/shops/view/<?=$id?>?targetEdit=topImage" class="grey-text link-box text-lighten-3 collection-item modal-trigger"><i class="material-icons">vertical_align_top</i>店舗情報を編集する</a></li>
          <li><a href="/owner/shops/view/<?=$id?>?targetEdit=topImage" class="grey-text link-box text-lighten-3 collection-item modal-trigger"><i class="material-icons">trending_up</i>店内を編集する</a></li>
          <li><a href="/owner/shops/view/<?=$id?>?targetEdit=topImage" class="grey-text link-box text-lighten-3 collection-item modal-trigger"><i class="material-icons">trending_up</i>マップを編集する</a></li>
          <li><a href="/owner/shops/view/<?=$id?>?targetEdit=topImage" class="grey-text link-box text-lighten-3 collection-item modal-trigger"><i class="material-icons">vertical_align_top</i>求人情報を編集する</a></li>
        </ul>
      </div>
      <div class="col s12 l6">
        <h5 class="white-text">Links</h5>
        <ul class="collection">
          <li><a class="grey-text link-box text-lighten-3 collection-item" href="#!"><i class="material-icons">cloud</i>facebook</a></li>
          <li><a class="grey-text link-box text-lighten-3 collection-item" href="#!"><i class="material-icons">help_outline</i>よくある質問</a></li>
          <li><a class="grey-text link-box text-lighten-3 collection-item" href="/pages/contract"><i class="material-icons">contact_mail</i>お問い合わせ</a></li>
          <li><a class="grey-text link-box text-lighten-3 collection-item" href="/pages/privacy_policy"><i class="material-icons">note</i>プライバシーポリシー</a></li>
          <li><a class="grey-text link-box text-lighten-3 collection-item" href="/pages/contract"><i class="material-icons">note</i>ご利用規約</a></li>
        </ul>
      </div>
      <div class="col s12">
        <span class="grey-text text-lighten-3">【おきよる】では、県内特化型ポータルサイトとして、沖縄全域のナイト情報を提供しております。(※ソープ、デリヘル等の風俗情報を除く)。
          高機能な検索システムを採用しておりますので、お客様にピッタリな情報がすぐに見つかります。
        更に店舗ごとに多彩なクーポン券などご用意しておりますのでお店に行く前に検索してクーポン券があるのかチェックしてみるのもいいでしょう。</span>
        <span><?= $this->Html->link(__('　　　　'), ['controller' => 'developer/Developers', 'action' => 'index']) ?></li>
</span>
      </div>
    </div>
      <div class="footer-copyright oki-footer-copyright">
        Copyright 2018
        <?=(2018-date('Y'))?' - '.date('Y'):'';?> OKIYORU Go All Rights Reserved.
      </div>
    <div id="return_top">
      <a href="#body" class="red"><i class="medium material-icons return_top">keyboard_arrow_up</i></a>
    </div><!-- END #return_top -->
  </footer>
  <?= $this->Html->scriptstart() ?>
$(document).ready(function(){
  googlemap_init('google_map', '沖縄県浦添市屋富祖３丁目１５');
});
<?= $this->Html->scriptend() ?>

 <script src="/js/okiyoru.js"></script>
</body>
</html>
