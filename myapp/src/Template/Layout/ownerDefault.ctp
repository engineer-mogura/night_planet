<!DOCTYPE html>
<html>
<head>
  <?= $this->Html->charset() ?>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"> -->
  <title>
    <?= LT['004'] ?>:
    <?= $this->fetch('title') ?>
  </title>
  <?= $this->Html->meta('icon') ?>
  <?= $this->Html->script('jquery-3.1.0.min.js') ?>
  <?= $this->Html->script('materialize.min.js') ?>
  <?= $this->Html->script('map.js') ?>
  <?= $this->Html->script('okiyoru.js') ?>
  <?= $this->Html->script('ja_JP.js') ?>
  <?= $this->Html->script('jquery.notifyBar.js') ?>
  <?= $this->Html->script('ajaxzip3.js') ?>
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

  <?= $this->fetch('meta') ?>
  <?= $this->fetch('css') ?>
  <?= $this->fetch('script') ?>
</head>
  <?php !empty($userInfo['main_image'])? $mainImage = $userInfo['image_path'].DS.$userInfo['main_image'] : $mainImage = "/img/common/noimage.jpg"; ?>
  <?php $id = $this->request->getSession()->read('Auth.Owner.id') ?>
  <?php $role = $this->request->getSession()->read('Auth.Owner.role') ?>
<body id="owner-default">
  <ul id="slide-out" class="side-nav fixed">
    <li>
      <div class="user-view">
        <div class="background" style="background-color: orange;">
        <!-- <img src="/img/common/area/top1.jpg"> -->
        </div>
        <a href="#!user"><img class="circle" src="<?=$mainImage?>"></a>
        <a href="#!name"><span class="white-text name"><?=$this->request->getSession()->read('Auth.Owner.name')?></span></a>
        <a href="#!email"><span class="white-text email"><?=$this->request->getSession()->read('Auth.Owner.email')?></span></a>
      </div>
    </li>
    <li><a href="/owner/owners/index/?activeTab=topImage" class="waves-effect <?php if($role != 'owner'){echo "btn-disabled";}?>"><i class="material-icons" href="">info_outline</i><?= OWNER_LM['001'] ?></a></li>
    <li><a href="/owner/owners/index/?activeTab=catch" class="waves-effect <?php if($role != 'owner'){echo "btn-disabled";}?>"><i class="material-icons">info_outline</i><?= OWNER_LM['002'] ?></a></li>
    <li><a href="/owner/owners/index/?activeTab=coupon" class="waves-effect <?php if($role != 'owner'){echo "btn-disabled";}?>"><i class="material-icons">info_outline</i><?= OWNER_LM['003'] ?></a></li>
    <li><a href="/owner/owners/index/?activeTab=cast" class="waves-effect <?php if($role != 'owner'){echo "btn-disabled";}?>"><i class="material-icons">info_outline</i><?= OWNER_LM['004'] ?></a></li>
    <li><a href="/owner/owners/index/?activeTab=tenpo" class="waves-effect <?php if($role != 'owner'){echo "btn-disabled";}?>"><i class="material-icons">info_outline</i><?= OWNER_LM['005'] ?></a></li>
    <li><a class="waves-effect" href="/owner/owners"><i class="material-icons">home</i><?= COMMON_LM['004'] ?></a></li>
    <li><a class="waves-effect" href="#!"><i class="material-icons">help_outline</i><?= COMMON_LM['001'] ?></a></li>
    <li><a class="waves-effect" href="#!"><i class="material-icons">contact_mail</i><?= COMMON_LM['002'] ?></a></li>
    <li><a class="waves-effect" href="#!"><i class="material-icons">priority_high</i><?= COMMON_LM['003'] ?></a></li>
    <li><a class="waves-effect" href="#!"><i class="material-icons">description</i><?= COMMON_LM['005'] ?></a></li>
    <li><div class="divider"></div></li>
    <li><a href="/owner/owners/logout" class="waves-effect"><i class="material-icons" href="">keyboard_backspace</i><?= COMMON_LM['007'] ?></a></li>
    <li><a class="waves-effect" href="#!">Third Link With Waves</a></li>
  </ul>
  <div class="nav-header-cron-dummy"></div>
  <nav id="nav-header-menu" class="nav-header-menu">
    <div class="nav-wrapper">
      <a href="#!" data-activates="slide-out" class="button-collapse oki-button-collapse"><i class="material-icons">menu</i></a>
      <a href="#!" class="brand-logo oki-brand-logo"><?= LT['001'] ?></a>
      <ul class="right">
        <li><a data-target="modal-help" class="modal-trigger"><i class="material-icons">help</i></a></li>
      </ul>
    </div>
  </nav>
  <!-- ヘルプモーダル -->
  <?= $this->element('modal/helpModal'); ?>
  <?= $this->fetch('content') ?>
  <footer class="page-footer">
    <div class="footer-copyright oki-footer-copyright">
      <?= LT['002']; ?>
      <?=(2018-date('Y'))?' - '.date('Y'):'';?> <?= LT['003'] ?>
    </div>
    <div id="return_top">
      <a href="#body" class="red"><i class="medium material-icons return_top">keyboard_arrow_up</i></a>
    </div><!-- END #return_top -->
  </footer>
</body>
</html>
