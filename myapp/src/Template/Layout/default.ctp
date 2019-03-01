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
  <div id="modal-search" class="modal">
    <div class="modal-content">
      <h4>Modal Header</h4>
      <p>A bunch of text</p>
      <div class="search-box">
        <div class="row">
          <li class="search col s12 l12">
            <div class="input-field oki-input-field">
              <input placeholder="キーワード" id="modal-key-word" type="text" class="validate input-search">
            </div>
          </li>
          <li class="search col s6 l6">
            <div class="input-field oki-input-field">
              <select>
                <option value="" disabled selected>エリア</option>
                <option value="naha">那覇</option>
                <option value="miyakojima">宮古島</option>
                <option value="ishigakijima">石垣島</option>
                <option value="tomigusuku">豊見城</option>
                <option value="nanjo">南城</option>
                <option value="itoman">糸満</option>
                <option value="urasoe">浦添</option>
                <option value="ginowan">宜野湾</option>
                <option value="okinawashi">沖縄市</option>
                <option value="uruma">うるま</option>
                <option value="nago">名護</option>
              </select>
            </div>
          </li>
          <li class="search col s6 l6">
            <div class="input-field oki-input-field">
              <select>
                <option value="" disabled selected>ジャンル</option>
                <option value="cabacula">キャバクラ</option>
                <option value="girlsbar">ガールズバー</option>
                <option value="snack">スナック</option>
                <option value="club">クラブ</option>
                <option value="lounge">ラウンジ</option>
                <option value="pub">パブ</option>
                <option value="bar">バー</option>
              </select>
            </div>
          </li>
          <li class="search col s12 l12">
            <a class="waves-effect waves-light btn-large"><i class="material-icons right">search</i>検索</a>
          </li>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <a href="#!" class="modal-close waves-effect waves-green btn-flat">閉じる</a>
    </div>
  </div>
  <!-- ログインモーダル -->
  <div id="modal-login" class="modal">
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
            <button class="modal-login btn waves-effect waves-light" type="submit" name="action">ログイン
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
    </div><!-- END #return_top -->
  </footer>

 <script src="/js/okiyoru.js"></script>
</body>
</html>
