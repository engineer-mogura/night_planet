<!DOCTYPE html>
<html>
<head>
  <?= $this->Html->charset() ?>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"> -->
  <title>
    <?= $this->fetch('title') ?>
  </title>
  <?= $this->Html->meta('icon') ?>
  <?= $this->Html->script('jquery-3.1.0.min.js') ?>
  <!-- <?= $this->Html->script('materialize.js') ?> --><!-- 検証用 -->
 <?= $this->Html->script('materialize.min.js') ?>
  <?= $this->Html->script('map.js') ?>
  <?= $this->Html->script('okiyoru.js') ?>
  <?= $this->Html->script('ja_JP.js') ?>
  <?= $this->Html->script('jquery.notifyBar.js') ?>
  <?= $this->Html->script('ajaxzip3.js') ?>
  <?= $this->Html->script('moment.min.js') ?><!-- fullcalendar-3.9.0 -->
  <?= $this->Html->script('fullcalendar.js') ?><!-- fullcalendar-3.9.0 --><!-- TODO: minの方を読み込むようにする。軽量化のため -->
  <?= $this->Html->script('fullcalendar_locale/ja.js') ?><!-- fullcalendar-3.9.0 -->
  <?= $this->Html->script("https://maps.googleapis.com/maps/api/js?key=AIzaSyDgd-t3Wa40gScJKC3ZH3ithzuUUapElu4") ?>
  <script src='/PhotoSwipe-master/dist/photoswipe.min.js'></script> <!-- PhotoSwipe 4.1.3 -->
  <script src='/PhotoSwipe-master/dist/photoswipe-ui-default.min.js'></script> <!-- PhotoSwipe 4.1.3 -->
  <link href='/PhotoSwipe-master/dist/default-skin/default-skin.css' rel='stylesheet' /> <!-- PhotoSwipe 4.1.3 -->
  <link href='/PhotoSwipe-master/dist/photoswipe.css' rel='stylesheet' /> <!-- PhotoSwipe 4.1.3 -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
  <?= $this->Html->css('fontello-3eba660b/css/fontello.css') ?>
  <?= $this->Html->css('materialize.css') ?>
  <?= $this->Html->css('okiyoru.css') ?>
  <?= $this->Html->css('jquery.notifyBar.css') ?>
  <?= $this->Html->css('fullcalendar.css') ?><!-- fullcalendar-3.9.0 --><!-- TODO: minの方を読み込むようにする。軽量化のため -->

  <?= $this->fetch('meta') ?>
  <?= $this->fetch('css') ?>
  <?= $this->fetch('script') ?>
  <?php $id = $this->request->getSession()->read('Auth.User.id') ?>
  <?php $role = $this->request->getSession()->read('Auth.User.role') ?>

<body id="user-default">
  <ul id="slide-out" class="side-nav fixed">
    <li>
      <div class="user-view">
        <div class="background">
          <img src="/img/common/top/top1.jpg">
        </div>
        <a href="#!user"><img class="circle" src="/img/common/top/top1.jpg"></a>
        <a href="#!name"><span class="white-text name"><?=$this->request->getSession()->read('Auth.Cast.name')?></span></a>
        <a href="#!email"><span class="white-text email"><?=$this->request->getSession()->read('Auth.Cast.email')?></span></a>
      </div>
    </li>
    <li><a class="waves-effect" href="#!"><i class="material-icons">info_outline</i><?= USER_LM['001'] ?></a></li>
    <li><a class="waves-effect" href="#!"><i class="material-icons">event_available</i><?= USER_LM['002'] ?></a></li>
    <li><a class="waves-effect" href="#!"><i class="material-icons">trending_up</i><?= USER_LM['003'] ?></a></li>
    <li><a class="waves-effect" href="http://okiyoru.local"><i class="material-icons">vertical_align_top</i><?= USER_LM['004'] ?></a></li>
    <li><a class="waves-effect" href="#!"><i class="material-icons">cloud</i><?= USER_LM['005'] ?></a></li>
    <li><a class="waves-effect" href="#!"><i class="material-icons">help_outline</i><?= COMMON_LM['001'] ?></a></li>
    <li><a class="waves-effect" href="#!"><i class="material-icons">contact_mail</i><?= COMMON_LM['002'] ?></a></li>
    <li><a class="waves-effect" href="#!"><i class="material-icons">note</i><?= COMMON_LM['003'] ?></a></li>
    <li><a class="waves-effect" href="#!"><i class="material-icons">note</i><?= COMMON_LM['005'] ?></a></li>
    <li><a class="waves-effect" href="#!"><i class="material-icons">star_half</i><?= USER_LM['006'] ?></a></li>
    <li><a class="waves-effect" href="#!"><i class="material-icons">vpn_key</i><?= USER_LM['007'] ?></a></li>
    <li><div class="divider"></div></li>
    <li><a class="subheader">Subheader</a></li>
    <li><a class="waves-effect" href="#!">Third Link With Waves</a></li>
  </ul>
  <div class="nav-header-cron-dummy"></div>
  <nav id="nav-header-menu" class="nav-header-menu">
    <div class="nav-wrapper">
      <a href="#!" data-activates="slide-out" class="button-collapse oki-button-collapse"><i class="material-icons">menu</i></a>
      <a href="#!" class="brand-logo oki-brand-logo"><?= LT['001'] ?><?=!empty($areaTitle)?'<span class="area-logo"> '.$areaTitle.'</span><span class="area-logo-tail">エリア</span>':LT['005'] ?></a>
      <ul class="right">
        <li><a data-target="modal-search" class="modal-trigger"><i class="material-icons">search</i></a></li>
        <li><a data-target="modal-login" class="modal-trigger"><i class="material-icons">vpn_key</i></a></li>
      </ul>
    </div>
  </nav>
  <!-- 検索モーダル -->
  <?= $this->element('modal/searchModal'); ?>
  <!-- ログインモーダル -->
  <?= $this->element('modal/loginModal'); ?>
  <?= $this->fetch('content') ?>
  <footer class="page-footer">
    <div class="row">
      <div class="col s12 l6">
        <h5 class="white-text">Links</h5>
        <ul class="collection">
          <li><a class="footer-item collection-item" href="#!"><i class="material-icons">info_outline</i><?= USER_LM['001'] ?></a></li>
          <li><a class="footer-item collection-item" href="#!"><i class="material-icons">event_available</i><?= USER_LM['002'] ?></a></li>
          <li><a class="footer-item collection-item" href="#!"><i class="material-icons">trending_up</i><?= USER_LM['003'] ?></a></li>
          <li><a class="footer-item collection-item" href=DS><i class="material-icons">vertical_align_top</i><?= USER_LM['004'] ?></a></li>
        </ul>
      </div>
      <div class="col s12 l6">
        <h5 class="white-text">Links</h5>
        <ul class="collection">
          <li><a class="footer-item collection-item" href="#!"><i class="material-icons">cloud</i><?= USER_LM['005'] ?></a></li>
          <li><a class="footer-item collection-item" href="#!"><i class="material-icons">help_outline</i><?= COMMON_LM['001'] ?></a></li>
          <li><a class="footer-item collection-item" href="/Users/contract"><i class="material-icons">contact_mail</i><?= COMMON_LM['002'] ?></a></li>
          <li><a class="footer-item collection-item" href="/Users/privacy_policy"><i class="material-icons">note</i><?= COMMON_LM['003'] ?></a></li>
          <li><a class="footer-item collection-item" href="/Users/contract"><i class="material-icons">note</i><?= COMMON_LM['005'] ?></a></li>
          <li><a class="footer-item collection-item" href="/Users/membership_join"><i class="material-icons">star_half</i><?= USER_LM['006'] ?></a></li>
          <li><a class="footer-item collection-item" href="/owner/owners/login"><i class="material-icons">vpn_key</i><?= USER_LM['007'] ?></a></li>
        </ul>
      </div>
      <div class="col s12">
        <span class="grey-text text-lighten-3"><?= CATCHCOPY ?></span>
        <span><?= $this->Html->link(__('　　　　'), ['controller' => 'developer/Developers', 'action' => 'index']) ?></span>
      </div>
    </div>
    <div class="footer-copyright oki-footer-copyright">
      <?= LT['002']; ?>
      <?=(2018-date('Y'))?' - '.date('Y'):'';?> <?= LT['003'] ?>
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

</body>
</html>
