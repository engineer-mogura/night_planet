<!DOCTYPE html>
<html>
<head>
  <?= $this->Html->charset() ?>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"> -->
  <title>
    <?= LT['001'] ?>:
    <?= $this->fetch('title') ?>
  </title>
  <?= $this->Html->meta('icon') ?>
  <?= $this->Html->script('jquery-3.1.0.min.js') ?>
  <?= $this->Html->script('materialize.min.js') ?>

  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <?= $this->Html->css('materialize.css') ?>
  <?= $this->Html->css('okiyoru.css') ?>

  <?= $this->fetch('meta') ?>
  <?= $this->fetch('css') ?>
  <?= $this->fetch('script') ?>
<body>
  <ul id="slide-out" class="side-nav">
    <li>
      <div class="user-view">
        <div class="background">
          <img src="/img/common/top/top1.jpg">
        </div>
        <a href="#!user"><img class="circle" src="/img/common/top/top1.jpg"></a>
        <a href="#!name"><span class="white-text name">John Doe</span></a>
        <a href="#!email"><span class="white-text email">jdandturk@gmail.com</span></a>
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
  <nav>
    <div class="nav-wrapper">
      <a href="#" data-activates="slide-out" class="button-collapse oki-button-collapse"><i class="material-icons">menu</i></a>
      <a href="#!" class="brand-logo oki-brand-logo"><?= LT['001'] ?></a>
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
  <div class="container row">
    <h1>位置情報取得サンプル</h1>
    <button onclick="getPosition();">位置情報を取得する</button>
    <div style="width:100%;height:300px;" id="google_map"></div>
  </div>
  <footer class="page-footer">
    <div class="container row">
      <div class="col s12 l6">
        <h5 class="white-text">Links</h5>
        <ul class="collection">
          <li><a class="grey-text link-box text-lighten-3 collection-item" href="#!"><i class="material-icons">info_outline</i><?= USER_LM['001'] ?></a></li>
          <li><a class="grey-text link-box text-lighten-3 collection-item" href="#!"><i class="material-icons">event_available</i><?= USER_LM['002'] ?></a></li>
          <li><a class="grey-text link-box text-lighten-3 collection-item" href="#!"><i class="material-icons">trending_up</i><?= USER_LM['003'] ?></a></li>
          <li><a class="grey-text link-box text-lighten-3 collection-item" href="/"><i class="material-icons">vertical_align_top</i><?= USER_LM['004'] ?></a></li>
        </ul>
      </div>
      <div class="col s12 l6">
        <h5 class="white-text">Links</h5>
        <ul class="collection">
          <li><a class="grey-text link-box text-lighten-3 collection-item" href="#!"><i class="material-icons">cloud</i><?= USER_LM['005'] ?></a></li>
          <li><a class="grey-text link-box text-lighten-3 collection-item" href="#!"><i class="material-icons">help_outline</i><?= COMMON_LM['001'] ?></a></li>
          <li><a class="grey-text link-box text-lighten-3 collection-item" href="/pages/contract"><i class="material-icons">contact_mail</i><?= COMMON_LM['002'] ?></a></li>
          <li><a class="grey-text link-box text-lighten-3 collection-item" href="/pages/privacy_policy"><i class="material-icons">note</i><?= COMMON_LM['003'] ?></a></li>
          <li><a class="grey-text link-box text-lighten-3 collection-item" href="/pages/contract"><i class="material-icons">note</i><?= COMMON_LM['005'] ?></a></li>
          <li><a class="grey-text link-box text-lighten-3 collection-item" href="/pages/membership_join"><i class="material-icons">star_half</i><?= USER_LM['006'] ?></a></li>
          <li><a class="grey-text link-box text-lighten-3 collection-item" href="/owner/owners/login"><i class="material-icons">vpn_key</i><?= USER_LM['007'] ?></a></li>
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
    </div>
  </footer>

 <script src="/js/okiyoru.js"></script>
</body>
</html>
