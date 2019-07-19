<div id="top-image" class="col s12 m8 l6">
  <?php echo $this->Flash->render(); ?>
  <h5>トップ画像</h5>
  <div id="show-top-image" style="text-align:center">
    <div style="display:none;">
      <input type="hidden" name="json_data" value='<?=$shop ?>'>
    </div>
    <?php if(!$shop->top_image == "") { ?>
      <img width="100%" height="300" src="<?= $shopInfo['dir_path'].$shop->top_image ?>" />
      <button type="button" class="waves-effect waves-light btn-large top-image-changeBtn">変更</button>
      <form id="delete-top-image" name="delete_top_image" method="post" style="display:none;" action="/owner/shops/delete_top_image?id=<?=$shop->id?>">
        <input type="hidden" name="_method" value="POST">
        <input type="hidden" name="file_before" value="">
        <input type="hidden" name="id" value="">
      </form>
      <button type="button" class="waves-effect waves-light btn-large top-image-deleteBtn">削除</button>
    <?php } else { ?>
      <p>まだ登録されていません。</p>
      <button type="button" class="waves-effect waves-light btn-large top-image-changeBtn">登録</button>
    <?php } ?>
  </div>
  <form id="save-top-image" name="save_top_image" method="post" accept-charset="utf-8" enctype="multipart/form-data" action="/owner/shops/save_top_image?id=<?=$shop->id?>" style="display:none;">
    <div style="display:none;">
      <input type="hidden" name="_method" value="POST">
      <input type="hidden" name="id" value="">
      <input type="hidden" name="file_before" value="">
    </div>
    <div class="file-field input-field">
      <div class="btn">
        <span>File</span>
        <input type="file" id="top-image-file" name="top_image_file" onChange="imgDisp();">
      </div>
      <div class="file-path-wrapper">
        <input class="file-path validate" name="top_image" type="text">
      </div>
    </div>
    <img src="" id="top-image-show" />
    <img src="" id="top-image-preview" class="top-image-preview" style="display:none;" />
    <canvas id="top-image-canvas" style="display:none;"></canvas>
    <div class="card-content" style="text-align:center;">
      <button type="button" class="waves-effect waves-light btn-large top-image-changeBtn">やめる</button>
      <button type="button" class="waves-effect waves-light btn-large top-image-saveBtn">更新</button>
    </div>
  </form>
</div>