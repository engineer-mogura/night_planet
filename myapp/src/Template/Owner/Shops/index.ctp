<div id="wrapper">
  <div class="container">
   <div class="row">
    <div class="col s12 m12 l12 edit-menu">
      <ul class="tabs">
        <li class="tab"><a class="active" href="#top-image">トップ画像</a></li>
        <li class="tab"><a href="#catch">キャッチコピー</a></li>
        <li class="tab"><a href="#coupon">クーポン</a></li>
        <li class="tab"><a href="#cast">キャスト</a></li>
        <li class="tab"><a href="#tenpo">店舗情報</a></li>
        <li class="tab"><a href="#tennai">店内</a></li>
        <li class="tab"><a href="#map">マップ</a></li>
        <li class="tab"><a href="#job">求人情報</a></li>
      </ul>
    </div>
    <div id="top-image" class="col s12 m8 l6">
      <?= $this->Flash->render() ?>
      <h5>トップ画像</h5>
      <?php foreach ($shop as $shopRow): ?>
        <div id="show-top-image" style="text-align:center">
          <?php if(!$shopRow->top_image == "") { ?>
          <img width="100%" height="300" src="<?= "/".$infoArray['imgPath'].$shopRow->top_image ?>" />
          <a href="#" class="waves-effect waves-light btn-large" onClick="topImageChangeBtn(document.getElementById('top-image'));return false;">変更</a>
        <form id="delete-top-image" name="delete_top_image" method="post" style="display:none;" action="/owner/shops/edit_top_image/<?= $shopRow->owner_id ?>">
            <input type="hidden" name="_method" value="POST">
            <input type="hidden" name="file_before" value="<?=$shopRow->top_image ?>">
            <input type="hidden" name="file_delete" value="delete">
        </form>
          <button type="button" class="waves-effect waves-light btn-large" onclick="if (confirm('トップ画像を削除してもよろしいですか？')) { document.delete_top_image.submit(); } event.returnValue = false; return false;">削除</button>
        <?php } else { ?>
          <p>まだ登録されていません。</p>
          <a href="#" class="waves-effect waves-light btn-large" onClick="topImageChangeBtn(document.getElementById('top-image'));return false;">登録</a>
        <?php } ?>
        </div>
        <form id="edit-top-image" name="edit_top_image" method="post" accept-charset="utf-8" enctype="multipart/form-data" action="/owner/shops/edit_top_image/<?= $shopRow->owner_id ?>" style="display:none;">
          <div style="display:none;">
            <input type="hidden" name="_method" value="POST">
            <input type="hidden" name="file_before" value="<?=$shopRow->top_image ?>">
          </div>
          <div class="file-field input-field">
            <div class="btn">
              <span>File</span>
              <input type="file" id="file" name="file">
            </div>
            <div class="file-path-wrapper">
              <input class="file-path validate" name="top_image" type="text" value="">
            </div>
          </div>
          <div id="result"></div>
          <div class="card-content" style="text-align:center;">
            <a href="#" class="waves-effect waves-light btn-large" onClick="topImageChangeBtn(document.getElementById('top-image'));return false;">やめる</a>
            <button type="button" class="waves-effect waves-light btn-large" onClick="check($('#edit-top-image'));">確定</button>
          </div>
        </form>
      <?php endforeach; ?>
    </div>

    <div id="catch" class="col s12 m8 l6">
      <?= $this->Flash->render() ?>
      <h5>キャッチコピー</h5>
      <?php foreach ($shop as $shopRow): ?>
        <div id="show-catch" style="text-align:center">
          <?php if(!$shopRow->catch == "") { ?>
            <?php $test = $this->Text->autoParagraph($shopRow->catch)?>
          <div class="description"><?php echo($test) ?></div>
          <a href="#" class="waves-effect waves-light btn-large" onClick="catchChangeBtn(document.getElementById('catch'));return false;">変更</a>
        <form id="delete-catch" name="delete_catch" method="post" style="display:none;" action="/owner/shops/edit_catch/<?= $shopRow->owner_id ?>">
            <input type="hidden" name="_method" value="POST">
            <input type="hidden" name="catch_before" value="<?=$shopRow->catch ?>">
            <input type="hidden" name="catch_delete" value="delete">
        </form>
          <button type="button" class="waves-effect waves-light btn-large" onclick="if (confirm('キャッチコピーを削除してもよろしいですか？')) { document.delete_catch.submit(); } event.returnValue = false; return false;">削除</button>
        <?php } else { ?>
          <p>まだ登録されていません。</p>
          <a href="#" class="waves-effect waves-light btn-large" onClick="catchChangeBtn(document.getElementById('catch'));return false;">登録</a>
        <?php } ?>
        </div>
        <form id="edit-catch" name="edit_catch" method="post" accept-charset="utf-8" enctype="multipart/form-data" action="/owner/shops/edit_catch/<?= $shopRow->owner_id ?>" style="display:none;">
          <div style="display:none;">
            <input type="hidden" name="_method" value="POST">
            <input type="hidden" name="catch_before" value="<?=$shopRow->catch ?>">
          </div>
              <div class="input-field">
                <textarea id="textarea1" class="materialize-textarea" name="catch" value="" data-length="120"><?=$shopRow->catch?></textarea>
                <label for="textarea1">Textarea</label>
              </div>
          <div class="card-content" style="text-align:center;">
            <a href="#" class="waves-effect waves-light btn-large" onClick="catchChangeBtn(document.getElementById('catch'));return false;">やめる</a>

          <button type="button" class="waves-effect waves-light btn-large" onclick="if (confirm('こちらのキャッチコピーに変更でよろしいですか？')) { document.edit_catch.submit(); } event.returnValue = false; return false;">確定</button>
          </div>
        </form>
      <?php endforeach; ?>
    </div>

    <div id="coupon" class="col s12">
      <?= $this->Flash->render() ?>
      <h5>トップ画像</h5>
      <?= $this->Form->create() ?>
      <?= $this->Form->control("email2") ?>
      <?= $this->Form->control("password2") ?>
      <?= $this->Form->button('ログイン') ?>
      <?= $this->Form->end() ?>
    </div>
    <div id="cast" class="col s12">
      <?= $this->Flash->render() ?>
      <h5>トップ画像</h5>
      <?= $this->Form->create() ?>
      <?= $this->Form->control('email3') ?>
      <?= $this->Form->control('password3') ?>
      <?= $this->Form->button('ログイン') ?>
      <?= $this->Form->end() ?>
    </div>
    <div id="tenpo" class="col s12">
      <?= $this->Flash->render() ?>
      <h5>トップ画像</h5>
      <?= $this->Form->create() ?>
      <?= $this->Form->control('email4') ?>
      <?= $this->Form->control('password4') ?>
      <?= $this->Form->button('ログイン') ?>
      <?= $this->Form->end() ?>
    </div>
    <div id="tennai" class="col s12">
      <?= $this->Flash->render() ?>
      <h5>トップ画像</h5>
      <?= $this->Form->create() ?>
      <?= $this->Form->control('email5') ?>
      <?= $this->Form->control('password5') ?>
      <?= $this->Form->button('ログイン') ?>
      <?= $this->Form->end() ?>
    </div>
    <div id="map" class="col s12">
      <?= $this->Flash->render() ?>
      <h5>トップ画像</h5>
      <?= $this->Form->create() ?>
      <?= $this->Form->control('email6') ?>
      <?= $this->Form->control('password6') ?>
      <?= $this->Form->button('ログイン') ?>
      <?= $this->Form->end() ?>
    </div>
    <div id="job" class="col s12">
      <?= $this->Flash->render() ?>
      <h5>トップ画像</h5>
      <?= $this->Form->create() ?>
      <?= $this->Form->control('email7') ?>
      <?= $this->Form->control('password7') ?>
      <?= $this->Form->button('ログイン') ?>
      <?= $this->Form->end() ?>
    </div>
  </div>
</div>
</div>