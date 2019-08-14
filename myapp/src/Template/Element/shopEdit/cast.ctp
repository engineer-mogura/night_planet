<div id="cast" class="col s12">
  <?php echo $this->Flash->render();  ?>
  <h5>キャスト</h5>
  <div id="show-cast">
    <div class="row">
      <form id="delete-cast" name="delete_cast" method="post" style="display:none;" action="/owner/shops/delete_cast">
        <input type="hidden" name="_method" value="POST">
        <input type="hidden" name="id" value="">
        <input type="hidden" name="dir" value="">
      </form>
      <?php foreach ($shop->casts as $key => $cast): ?>
        <div class="col s12 m6 l6 cast-box">
          <div style="display:none;">
            <input type="hidden" name="json_data" value='<?=$cast ?>'>
          </div>
          <ul class="collection z-depth-2 <?php if(count($shop->casts) == $key + 1) { echo('targetScroll');}?>">
            <li class="collection-item">
              <table class="highlight">
                <thead>
                  <tr>
                    <td colspan="2">
                        <span class="cast-num">キャスト＃<?=$key + 1?></span>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2">
                      <img src="<?=isset($cast->image1) ? $shopInfo['cast_path'].DS.$cast->dir.DS.PATH_ROOT['IMAGE'].DS.$cast->image1: PATH_ROOT['NO_IMAGE02'] ?>" alt="" class="circle left" width="80" height="80">
                      <input type="checkbox" class="check-cast-group" name="check_cast" id="check-cast<?=$key?>" />
                      <label for="check-cast<?=$key?>">編集する</label>
                      <div style="display:none;">
                        <input type="hidden" name="json_data" value='<?=$cast ?>'>
                      </div>
                      <a href="#!" class="secondary-content">
                        <div class="switch">
                          <label>OFF<input type="checkbox" value="<?=$cast->status ?>" name="cast_switch<?=$cast->id ?>" class="cast-switchBtn" <?php if ($cast->status == 1) { echo 'checked'; }?>><span class="lever"></span>ON</label>
                        </div>
                      </a>
                    </td>
                  </tr>
                </thead>
                <tbody class="tbody-cast-group">
                  <tr><th>名前</th>
                    <td><?=$cast->name ?></td>
                  </tr>
                  <tr><th>ニックネーム</th>
                    <td><?=$cast->nickname ?></td>
                  </tr>
                  <tr><th>メールアドレス</th>
                    <td><?=$cast->email ?></td>
                  </tr>
                </tbody>
              </table>
            </li>
          </ul>
        </div>
      <?php endforeach; ?>
      <div class="col s12 m12 l12">
        <?php if(count($shop->casts) == 0) { ?>
            <p>まだ登録されていません。</p>
            <button type="button" class="waves-effect waves-light btn-large cast-addBtn">登録</button>
        <?php } else { ?>
            <p style="text-align:center;">
              <button type="button" class="waves-effect waves-light btn-large cast-addBtn">追加</button>
              <button type="button" class="waves-effect waves-light btn-large disabled cast-changeBtn">変更</button>
              <button type="button" class="waves-effect waves-light btn-large disabled cast-deleteBtn">削除</button>
            </p>
        <?php } ?>
      </div>
    </div>
  </div>
  <form id="save-cast" name="save_cast" method="post" action="/owner/shops/save_cast" style="display:none;">
    <div style="display:none;">
      <input type="hidden" name="_method" value="POST">
      <input type="hidden" name="crud_type" value="">
      <input type="hidden" name="id" value="">
    </div>
    <div class="row">
      <div class="input-field col s12 m6 l6">
        <input type="text" id="cast-name" class="validate" name="name" data-length="50">
        <label for="cast-name">名前</label>
      </div>
    </div>
    <div class="row">
      <div class="input-field col s12 m6 l6">
        <input type="text" id="cast-nickname" class="validate" name="nickname" data-length="50">
        <label for="cast-nickname">ニックネーム</label>
      </div>
    </div>
    <div class="row">
      <div class="input-field col s12 m10 l6">
        <input type="text" id="cast-email" class="validate" name="email" data-length="255">
        <label for="cast-email">メールアドレス</label>
      </div>
    </div>
    <div style="display:none;">
    <!-- TODO: 暫定でキャストのパスワード初期値を決めている -->
    <input type="hidden" name="password" value="pass">
    <input type="hidden" name="role" value="cast">
    </div>
    <div class="card-content" style="text-align:center;">
      <button type="button" class="waves-effect waves-light btn-large cast-changeBtn">やめる</button>
      <button type="button" class="waves-effect waves-light btn-large cast-saveBtn">登録</button>
    </div>
  </form>
</div>