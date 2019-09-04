<div id="wrapper">
  <div id="genre" class="container">
    <span id="dummy" style="display: hidden;"></span>
    <?= $this->Flash->render() ?>
    <?= $this->element('nav-breadcrumb'); ?>
    <?= $this->element('shopCard'); ?>
  </div>
</div>
<!-- ショップ用ボトムナビゲーション START -->
<?= $this->element('bottom-navigation'); ?>
<!-- ショップ用ボトムナビゲーション END -->