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

  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
  <?= $this->Html->css('fontello-3eba660b/css/fontello.css') ?>
  <?= $this->Html->css('materialize.css') ?>
  <?= $this->Html->css('okiyoru.css') ?>
  <?= $this->Html->css('jquery.notifyBar.css') ?>
  <?= $this->Html->css('fullcalendar.css') ?><!-- fullcalendar-3.9.0 --><!-- TODO: minの方を読み込むようにする。軽量化のため -->

  <?= $this->fetch('meta') ?>
  <?= $this->fetch('css') ?>
  <?= $this->fetch('script') ?>
  <?php $id = $this->request->getSession()->read('Auth.Cast.id') ?>
  <?php $role = $this->request->getSession()->read('Auth.Cast.role') ?>

<body id="cast-default">
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
    <li><a href="/owner/casts/index/<?=$id?>?activeTab=dashbord" class="waves-effect <?php if($role != 'cast'){echo "btn-disabled";}?>"><i class="material-icons" href="">info_outline</i><?= CAST_LM['001'] ?></a></li>
    <li><a href="/owner/casts/profile/<?=$id?>?activeTab=profile" class="waves-effect <?php if($role != 'cast'){echo "btn-disabled";}?>"><i class="material-icons">event_available</i><?= CAST_LM['002'] ?></a></li>
    <li><a href="/owner/casts/diary/<?=$id?>?activeTab=diary" class="waves-effect <?php if($role != 'cast'){echo "btn-disabled";}?>"><i class="material-icons">event_available</i><?= CAST_LM['003'] ?></a></li>
    <li><a href="/owner/casts/image/<?=$id?>?activeTab=image" class="waves-effect <?php if($role != 'cast'){echo "btn-disabled";}?>"><i class="material-icons">event_available</i><?= CAST_LM['004'] ?></a></li>
    <li><a class="waves-effect" href="/owner/owners"><i class="material-icons">help_outline</i><?= COMMON_LM['004'] ?></a></li>
    <li><a class="waves-effect" href="#!"><i class="material-icons">help_outline</i><?= COMMON_LM['001'] ?></a></li>
    <li><a class="waves-effect" href="#!"><i class="material-icons">contact_mail</i><?= COMMON_LM['002'] ?></a></li>
    <li><a class="waves-effect" href="#!"><i class="material-icons">note</i><?= COMMON_LM['003'] ?></a></li>
    <li><a class="waves-effect" href="#!"><i class="material-icons">note</i><?= COMMON_LM['005'] ?></a></li>
    <li><div class="divider"></div></li>
    <li><a href="/owner/casts/logout" class="waves-effect"><i class="material-icons" href="">info_outline</i><?= COMMON_LM['007'] ?></a></li>
    <li><a class="waves-effect" href="#!">Third Link With Waves</a></li>
  </ul>
  <nav>
    <div class="nav-wrapper">
      <a href="#" data-activates="slide-out" class="button-collapse oki-button-collapse"><i class="material-icons">menu</i></a>
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
  <?= $this->Html->scriptstart() ?>
$(document).ready(function(){
  googlemap_init('google_map', '沖縄県浦添市屋富祖３丁目１５');
});
<?= $this->Html->scriptend() ?>

</body>
</html>
