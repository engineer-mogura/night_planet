<div id="wrapper">
  <div class="container">
    <span id="dummy" style="display: hidden;"></span>
    <div class="row">
      <div class="col s12 m12 l12 edit-menu">
      <?= $this->Form->hidden('activeTab',['id'=>'activeTab','value' => $activeTab]); ?>
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
      <!-- トップ画像タブ -->
      <div id="top-image" class="col s12 m8 l6">
        <?php if($activeTab === 'top-image') {
          echo $this->Flash->render(); } ?>
        <h5>トップ画像</h5>
        <?php foreach ($shop as $shopRow): ?>
          <div id="show-top-image" style="text-align:center">
            <?php if(!$shopRow->top_image == "") { ?>
              <img width="100%" height="300" src="<?= "/".$infoArray['dir_path'].$shopRow->top_image ?>" />
              <button type="button" class="waves-effect waves-light btn-large" onclick="topImageChangeBtn($('#top-image'));return false;">変更</button>
              <form id="delete-top-image" name="delete_top_image" method="post" style="display:none;" action="/owner/shops/edit_top_image/<?= $shopRow->owner_id ?>">
                <input type="hidden" name="_method" value="POST">
                <input type="hidden" name="file_before" value="<?=$shopRow->top_image ?>">
                <input type="hidden" name="file_delete" value="delete">
              </form>
              <button type="button" class="waves-effect waves-light btn-large deleteBtn" onclick="topImageDeleteBtn(); return false;">削除</button>
            <?php } else { ?>
              <p>まだ登録されていません。</p>
              <button type="button" class="waves-effect waves-light btn-large" onclick="topImageChangeBtn($('#top-image'));return false;">登録</button>
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
                <input type="file" id="top-image-file" name="top_image_file" onChange="imgDisp();">
              </div>
              <div class="file-path-wrapper">
                <input class="file-path validate" name="top_image" type="text">
              </div>
            </div>
            <img src="" id="top-image-show" />
            <img src="" id="top-image-preview" style="display:none;" />
            <canvas id="top-image-canvas" style="display:none;"></canvas>
            <div class="card-content" style="text-align:center;">
              <button type="button" class="waves-effect waves-light btn-large" onclick="topImageChangeBtn($('#top-image'));return false;">やめる</button>
              <button type="button" class="waves-effect waves-light btn-large saveBtn" onclick="topImageSaveBtn(); return false;">確定</button>
            </div>
          </form>

        <?php endforeach; ?>
      </div>
      <!-- キャッチコピータブ -->
      <div id="catch" class="col s12 m8 l6">
        <?php if($activeTab === 'catch') {
          echo $this->Flash->render(); } ?>
        <h5>キャッチコピー</h5>
        <?php foreach ($shop as $shopRow): ?>
          <div id="show-catch">
            <?php if(!$shopRow->catch == "") { ?>
              <?php $catch = $this->Text->autoParagraph($shopRow->catch); ?>
              <div class="card-panel light-blue accent-1"><?php echo($catch) ?></div>
              <form id="delete-catch" name="delete_catch" method="post" style="display:none;" action="/owner/shops/edit_catch/<?= $shopRow->owner_id ?>">
                <input type="hidden" name="_method" value="POST">
                <input type="hidden" name="catch_before" value="<?=$shopRow->catch ?>">
                <input type="hidden" name="catch_delete" value="delete">
              </form>
              <div style="display:none;">
                <input type="hidden" name="catch_copy" value='<?=$shopRow->catch ?>'>
              </div>
              <p style="text-align:center;">
                <button type="button" class="waves-effect waves-light btn-large" onclick="catchChangeBtn($('#catch'));return false;">変更</button>
                <button type="button" class="waves-effect waves-light btn-large" onclick="catchDeleteBtn(); return false;">削除</button>
              </p>
            <?php } else { ?>
              <p>まだ登録されていません。</p>
              <button type="button" class="waves-effect waves-light btn-large" onclick="catchChangeBtn($('#catch'));return false;">登録</button>
            <?php } ?>
          </div>
          <form id="edit-catch" name="edit_catch" method="post" action="/owner/shops/edit_catch/<?= $shopRow->owner_id ?>" style="display:none;">
            <div style="display:none;">
              <input type="hidden" name="_method" value="POST">
              <input type="hidden" name="catch_before" value="<?=$shopRow->catch ?>">
            </div>
            <div class="input-field">
              <textarea id="catch-copy" class="materialize-textarea" name="catch" data-length="120"><?=$shopRow->catch?></textarea>
              <label for="catch-copy">キャッチコピー</label>
            </div>
            <div class="card-content" style="text-align:center;">
              <button type="button" class="waves-effect waves-light btn-large" onclick="catchChangeBtn($('#catch'));return false;">やめる</button>

              <button type="button" class="waves-effect waves-light btn-large" onclick="catchSaveBtn(); return false;">確定</button>
            </div>
          </form>
        <?php endforeach; ?>
      </div>
      <!-- クーポンタブ -->
      <div id="coupon" class="col s12">
        <?php 
          echo $this->Flash->render();  ?>
          <h5>クーポン</h5>
          <?php foreach ($shop as $shopRow): ?>
            <div id="show-coupon">
              <?php if(!$shopRow->coupons == "") { ?>
                <div class="row">
                  <?php $i = 0; ?>
                  <?php foreach ($shopRow->coupons as $coupon): ?>
                    <?php $i++; ?>
                    <div class="col s12 m6 l8 coupon-box">
                      <form id="delete-coupon<?=$i?>" name="delete_coupon" method="post" style="display:none;" action="/owner/shops/edit_coupon/<?= $coupon->id ?>">
                        <input type="hidden" name="_method" value="POST">
                        <input type="hidden" name="coupon_delete" value="<?=$coupon->id ?>">
                        <input type="hidden" name="coupon_title" value="<?=$coupon->title ?>">
                      </form>
                      <form id="switch-coupon<?=$i?>" name="switch_coupon<?=$coupon->id ?>" method="post" style="display:none;" action="/owner/shops/edit_coupon/<?= $coupon->id ?>">
                        <input type="hidden" name="_method" value="POST">
                      </form>
                      <ul class="collection z-depth-2 <?php if(count($shopRow->coupons) == $i) { echo('targetScroll');}?>">
                        <li class="collection-item">
                          <table class="highlight">
                            <thead>
                              <td colspan="2">
                                <img src="/img/common/coupon/coupon1.jpg" alt="" class="circle left" width="50" height="50">
                                <input type="checkbox" class="check-coupon-group" name="check_coupon" id="check-coupon<?=$i?>" />
                                <label for="check-coupon<?=$i?>">編集する</label>
                                <div style="display:none;">
                                  <input type="hidden" name="coupon_copy" value='<?=$coupon ?>'>
                                </div>
                                <a href="#!" class="secondary-content">
                                  <div class="switch">
                                    <label>OFF<input type="checkbox" value="<?=$coupon->status ?>" name="switch_coupon<?=$coupon->id ?>" class="switch_coupon" <?php if ($coupon->status == 1) { echo 'checked'; }?>><span class="lever"></span>ON</label>
                                  </div>
                                </a>
                              </td>
                            </thead>
                            <tbody class="tbody-coupon-group">
                              <tr><th>有効期間</th>
                                <td><?= $this->Time->format($coupon->from_day, 'Y/M/d') ?>～<?= $this->Time->format($coupon->to_day, 'Y/M/d') ?>
                                </td>
                              </tr>
                              <tr><th>タイトル</th>
                                <td><?=$coupon->title ?></td>
                              </tr>
                              <tr><th>内容</th>
                                <td><?= $this->Text->autoParagraph($coupon->content)?></td>
                              </tr>
                            </tbody>
                          </table>
                        </li>
                      </ul>
                    </div>
                <?php endforeach; ?>
              </div>
              <p style="text-align:center;">
                <button type="button" href="#" class="waves-effect waves-light btn-large addBtn" onclick="couponAddBtn($('#coupon'));return false;">追加</button>
                <button type="button" class="waves-effect waves-light btn-large disabled changeBtn" onclick="couponChangeBtn($('#coupon'));return false;">変更</button>
                <button type="button" class="waves-effect waves-light btn-large disabled deleteBtn" onclick="couponDeleteBtn();return false;">削除</button>
              </p>
            <?php } else { ?>
              <p>まだ登録されていません。</p>
              <button type="button" href="#" class="waves-effect waves-light btn-large" onclick="couponAddBtn($('#coupon'));return false;">登録</button>
            <?php } ?>
          </div>
        <?php endforeach; ?>
        <form id="edit-coupon" name="edit_coupon" method="post" action="/owner/shops/edit_coupon/<?= $shopRow->owner_id ?>" style="display:none;">
          <div style="display:none;">
            <input type="hidden" name="_method" value="POST">
            <input type="hidden" name="coupon_edit" value="">
            <input type="hidden" name="coupon_edit_id" value="">
          </div>
          <div class="switch">
            <label>
              OFF
              <?=$this->Form->checkbox('status');?>
              <span class="lever"></span>
              ON
            </label>
          </div>
          <p>対象期間</p>
          <div class="row">
            <div class="col s5 m4 l3"><input id="from-day" class="datepicker" name="from_day"></div>
            <div class="col s2 m1 l1 center-align">～</div>
            <div class="col s5 m4 l3"><input id="to-day" class="datepicker" name="to_day"></div>
          </div>
          <div class="row">
            <div class="input-field col s12 m6 l6">
              <input type="text" id="coupon-title" class="validate" name="title" data-length="50">
              <label for="coupon-title">タイトル</label>
            </div>
          </div>
          <div class="row">
            <div class="input-field col s12 m10 l6">
              <textarea id="coupon-content" class="validate materialize-textarea" name="content" data-length="255"></textarea>
              <label for="coupon-content">内容</label>
            </div>
          </div>
          <div class="card-content" style="text-align:center;">
            <button type="button" href="#" class="waves-effect waves-light btn-large" onclick="couponChangeBtn($('#coupon'));return false;">やめる</button>
            <button type="button" class="waves-effect waves-light btn-large saveBtn" onclick="couponSaveBtn(); return false;">確定</button>
          </div>
        </form>
      </div>
      <!-- キャストタブ -->
      <div id="cast" class="col s12">
        <?php
          echo $this->Flash->render();  ?>
          <h5>キャスト</h5>
          <?php foreach ($shop as $shopRow): ?>
            <div id="show-cast">
              <?php if(!$shopRow->casts == "") { ?>
                <div class="row">
                  <?php $i = 0; ?>
                  <?php foreach ($shopRow->casts as $cast): ?>
                    <?php $i++; ?>
                    <div class="col s12 m6 l8 cast-box">
                      <form id="delete-cast<?=$i?>" name="delete_cast" method="post" style="display:none;" action="/owner/shops/edit_cast/<?= $cast->id ?>">
                        <input type="hidden" name="_method" value="POST">
                        <input type="hidden" name="cast_delete" value="<?=$cast->id ?>">
                        <input type="hidden" name="cast_title" value="<?=$cast->title ?>">
                      </form>
                      <form id="switch-cast<?=$i?>" name="switch_cast<?=$cast->id ?>" method="post" style="display:none;" action="/owner/shops/edit_cast/<?= $cast->id ?>">
                        <input type="hidden" name="_method" value="POST">
                      </form>
                      <ul class="collection z-depth-2 <?php if(count($shopRow->casts) == $i) { echo('targetScroll');}?>">
                        <li class="collection-item">
                          <table class="highlight">
                            <thead>
                              <td colspan="2">
                                <img src="/img/common/noimage.jpg" alt="" class="circle left" width="80" height="80">
                                <input type="checkbox" class="check-cast-group" name="check_cast" id="check-cast<?=$i?>" />
                                <label for="check-cast<?=$i?>">編集する</label>
                                <div style="display:none;">
                                  <input type="hidden" name="cast_copy" value='<?=$cast ?>'>
                                </div>
                                <a href="#!" class="secondary-content">
                                  <div class="switch">
                                    <label>OFF<input type="checkbox" value="<?=$cast->status ?>" name="switch_cast<?=$cast->id ?>" class="switch_cast" <?php if ($cast->status == 1) { echo 'checked'; }?>><span class="lever"></span>ON</label>
                                  </div>
                                </a>
                              </td>
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
              </div>
              <p style="text-align:center;">
                <button type="button" href="#" class="waves-effect waves-light btn-large addBtn" onclick="castAddBtn($('#cast'));return false;">追加</button>
                <button type="button" class="waves-effect waves-light btn-large disabled changeBtn" onclick="castChangeBtn($('#cast'));return false;">変更</button>
                <button type="button" class="waves-effect waves-light btn-large disabled deleteBtn" onclick="castDeleteBtn();return false;">削除</button>
              </p>
            <?php } else { ?>
              <p>まだ登録されていません。</p>
              <button type="button" href="#" class="waves-effect waves-light btn-large" onclick="castAddBtn($('#cast'));return false;">登録</button>
            <?php } ?>
          </div>
        <?php endforeach; ?>
        <form id="edit-cast" name="edit_cast" method="post" action="/owner/shops/edit_cast/<?= $shopRow->owner_id ?>" style="display:none;">
          <div style="display:none;">
            <input type="hidden" name="_method" value="POST">
            <input type="hidden" name="cast_edit" value="">
            <input type="hidden" name="cast_edit_id" value="">
          </div>
          <div class="switch">
            <label>
              OFF
              <?=$this->Form->checkbox('status');?>
              <span class="lever"></span>
              ON
            </label>
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
          <input type="hidden" name="password" value="pass">
          <input type="hidden" name="role" value="cast">
          </div>
          <div class="card-content" style="text-align:center;">
            <button type="button" href="#" class="waves-effect waves-light btn-large" onclick="castChangeBtn($('#cast'));return false;">やめる</button>
            <button type="button" class="waves-effect waves-light btn-large saveBtn" onclick="castSaveBtn(); return false;">確定</button>
          </div>
        </form>
      </div>
      <!-- 店舗情報タブ -->
      <div id="tenpo" class="col s12">
        <h5>店舗情報</h5>
        <div id="show-tenpo">
          <div class="row">
            <div class="col s12 m6 l6">
              <?php foreach ($shop as $shopRow): ?>
                <div style="display:none;">
                  <input type="hidden" name="tenpo_copy" value='<?=$shopRow ?>'>
                  <input type="hidden" name="credit_hidden" value='<?=$masterCodeHidden['credit']?>'>
                </div>
                <table class="bordered shop-table z-depth-2" border="1">
                <tr>
                  <th align="center">店舗名</th>
                  <td><?php if(!$shopRow->name == '') {
                    echo ($shopRow->name);
                  } else {echo ('登録されていません。');}?>
                  </td>
                </tr>
                <tr>
                  <th align="center">所在地</th>
                  <td><?php if(!$shopRow->pref21 == '') {
                    echo ($shopRow->pref21.$shopRow->addr21.$shopRow->strt21);
                  } else {echo ('登録されていません。');} ?>
                  </td>
                </tr>
                <tr>
                  <th align="center">連絡先</th>
                  <td><?php if(!$shopRow->tel == '') {
                    echo ($shopRow->tel);
                  } else {echo ('登録されていません。');} ?>
                  </td>
                </tr>
                <tr>
                  <th align="center">営業時間</th>
                  <td><?php if((!$shopRow->bus_from_time == '')
                            && (!$shopRow->bus_to_time == '')
                            && (!$shopRow->bus_hosoku == '')) {
                              $busTime = $this->Time->format($shopRow->bus_from_time, 'HH:mm')
                              ."～".$this->Time->format($shopRow->bus_to_time, 'HH:mm');
                              if (!$shopRow->bus_hosoku == '') {
                                  $busTime = $busTime.="</br>".$shopRow->bus_hosoku;
                              }
                              echo ($busTime);
                            } else { echo ('登録されていません。'); } ?>
                  </td>
                </tr>
                <tr>
                  <th align="center">スタッフ</th>
                  <td><?php if(!$shopRow->staff == '') {
                    echo ($this->Text->autoParagraph($shopRow->staff));
                  } else {echo ('登録されていません。');} ?>
                  </td>
                </tr>
                <tr>
                  <th align="center" valign="top">システム</th>
                  <td><?php if(!$shopRow->system == '') {
                    echo ($this->Text->autoParagraph($shopRow->system));
                  } else {echo ('登録されていません。');} ?>
                  </td>
                </tr>
                <tr>
                  <th align="center">ご利用できるクレジットカード</th>
                  <td><?php if(!$shopRow->credit == '') { ?>
                      <?php $array =explode(',', $shopRow->credit); ?>
                      <?php for ($i = 0; $i < count($array); $i++) { ?>
                      <div class="chip" name="" value="">
                        <img src="/img/common/credit/<?=$array[$i]?>.png" id="<?=$array[$i]?>" alt="<?=$array[$i]?>">
                        <?=$array[$i]?>
                      </div>
                      <?php } ?>
                      <?php } else {echo ('登録されていません。');} ?>
                  </td>
                </tr>
              </table>
              <?php endforeach; ?>
              <p style="text-align:center;">
                <button type="button" class="waves-effect waves-light btn-large changeBtn" onclick="tenpoChangeBtn($('#tenpo'));return false;">編集</button>
              </p>
            </div>
          </div>
        </div>
        <form id="edit-tenpo" name="edit_tenpo" method="post" action="/owner/shops/edit_tenpo/<?= $shopRow->owner_id ?>" style="display:none;">
          <div style="display:none;">
            <input type="hidden" name="_method" value="POST">
            <input type="hidden" name="tenpo_edit" value="">
            <input type="hidden" name="tenpo_edit_id" value="">
            <input type="hidden" name="credit" value="">
          </div>
          <div class="row">
            <div class="input-field col s12 m6 l6">
              <input type="text" id="name" class="validate" name="name" data-length="50">
              <label for="name">店舗名</label>
            </div>
          </div>
          <div class="row">
            <div class="input-field col s12 m12 l12">
              <span>郵便番号からも検索できます。</span>
            </div>
            <div class="input-field col s5 m2 l2">
              <input type="text" id="zip21" name="zip21" size="4" maxlength="3">
              <label for="zip21">郵便番号(3桁)</label>
            </div>
            <div class="col s1 m1 l1 center-align">ー</div>
            <div class="input-field col s6 m2 l2">
              <input type="text" id="zip22" name="zip22" size="5" maxlength="4" onKeyUp="AjaxZip3.zip2addr('zip21','zip22','pref21','addr21','strt21');">
              <label for="zip22">郵便番号(4桁)</label>
            </div>
          </div>
          <div class="row">
            <div class="input-field col s12 m6 l6">
              <input type="text" id="pref21" name="pref21" class="validate" size="3">
              <label for="pref21">都道府県</label>
            </div>
          </div>
          <div class="row">
            <div class="input-field col s12 m6 l6">
              <input type="text" id="addr21" name="addr21" class="validate" size="10">
              <label for="addr21">市区町村</label>
            </div>
          </div>
          <div class="row">
            <div class="input-field col s12 m6 l6">
              <input type="text" id="strt21" name="strt21" class="validate" size="20">
              <label for="strt21">以降の住所</label>
            </div>
          </div>

          <div class="row">
            <div class="input-field col s12 m6 l6">
              <input type="tel" id="tel" class="validate" name="tel">
              <label for="tel">連絡先</label>
            </div>
          </div>
          <div class="row">
            <div class="col s12 m12 l12"><label style="font-size: 1rem;">営業時間</label></div>
            <div class="col s5 m1 l1"><input id="bus-from-time" class="timepicker" name="bus_from_time"></div>
            <div class="col s2 m1 l1 center-align">～</div>
            <div class="col s5 m1 l1"><input id="bus-to-time" class="timepicker" name="bus_to_time"></div>
          </div>
          <div class="row">
            <div class="input-field col s12 m6 l6">
              <input type="text" id="bus-hosoku" class="validate" name="bus_hosoku" data-length="50">
              <label for="bus-hosoku">営業時間についての補足</label>
            </div>
          </div>
          <div class="row">
            <div class="input-field col s12 m6 l6">
              <textarea id="staff" class="validate materialize-textarea" name="staff" data-length="50"></textarea>
              <label for="staff">スタッフ</label>
            </div>
          </div>
          <div class="row">
            <div class="input-field col s12 m6 l6">
              <textarea id="system" class="validate materialize-textarea" name="system" data-length="250"></textarea>
              <label for="system">システム</label>
            </div>
          </div>
          <div class="row">
            <div class="input-field col s12 m6 l6">
            <div class="chips chips-initial disabled" name="credit"></div>
              <label for="chips-autocomplete">クレジットカード</label>
              <span>クレジット一覧から選択してください。</span>
                <div class="card-panel">
                <div class="chip-box">
                <?php foreach ($credits as $key => $value): ?>
                  <div class="chip" name="" value="">
                    <img src="/img/common/credit/<?=$value->code?>.png" id="<?=$value->id?>" alt="<?=$value->code?>">
                    <?=$value->code?>
                  </div>
                <?php endforeach ?>
                </div>
              </div>
            </div>
          </div>
          <div class="card-content" style="text-align:center;">
            <button type="button" href="#" class="waves-effect waves-light btn-large changeBtn" onclick="tenpoChangeBtn($('#tenpo'));return false;">やめる</button>
            <button type="button" class="waves-effect waves-light btn-large saveBtn" onclick="tenpoSaveBtn();return false;">確定</button>
          </div>
        </form>
      </div>
      <div id="tennai" class="col s12">
        <!-- <h5>トップ画像</h5>
        <?= $this->Form->create() ?>
        <?= $this->Form->control('email5') ?>
        <?= $this->Form->control('password5') ?>
        <?= $this->Form->button('ログイン') ?>
        <?= $this->Form->end() ?> -->
      </div>
      <div id="map" class="col s12">
        <!-- <h5>トップ画像</h5>
        <?= $this->Form->create() ?>
        <?= $this->Form->control('email6') ?>
        <?= $this->Form->control('password6') ?>
        <?= $this->Form->button('ログイン') ?>
        <?= $this->Form->end() ?> -->
      </div>
      <div id="job" class="col s12">
        <h5>求人情報</h5>
        <div id="show-job">
          <div class="row">
            <div class="col s12 m6 l6">
              <?php foreach ($shop as $shopRow): ?>
                <div style="display:none;">
                  <input type="hidden" name="job_copy" value='<?=$shopRow->job ?>'>
                  <input type="hidden" name="treatment_hidden" value='<?=$masterCodeHidden['treatment']?>'>
                </div>
                <table class="bordered shop-table z-depth-2" border="1">
                <tr>
                  <th align="center">店舗名</th>
                  <td class="show-job-name"><?php if(!$shopRow->name == '') {
                    echo ($shopRow->name);
                  } else {echo ('登録されていません。');}?>
                  </td>
                </tr>
                <tr>
                  <th align="center">業種</th>
                  <td>
                    <?php if(!$shopRow->job->industry == '') {
                            echo ($this->Text->autoParagraph($shopRow->job->industry));
                          } else {echo ('登録されていません。');} ?>
                  </td>
                </tr>
                <tr>
                  <th align="center">職種</th>
                  <td>
                    <?php if(!$shopRow->job->job_type == '') {
                            echo ($this->Text->autoParagraph($shopRow->job->job_type));
                          } else {echo ('登録されていません。');} ?>
                  </td>
                </tr>
                <th align="center">時間</th>
                  <td><?php if((!$shopRow->job->work_from_time == '')
                            && (!$shopRow->job->work_to_time == '')) {
                              $workTime = $this->Time->format($shopRow->job->work_from_time, 'HH:mm')
                              ."～".$this->Time->format($shopRow->job->work_to_time, 'HH:mm');
                              if (!$shopRow->job->work_time_hosoku == '') {
                                $workTime = $workTime.="</br>".$shopRow->job->work_time_hosoku;
                              }
                              echo ($workTime);
                            } else { echo ('登録されていません。'); } ?>
                  </td>
                </tr>
                <th align="center">資格</th>
                <td><?php if((!$shopRow->job->from_age == '')
                            && (!$shopRow->job->to_age == '')) {
                              $qualification = $shopRow->job->from_age."歳～".$shopRow->job->to_age."歳くらいまで";
                              if (!$shopRow->job->qualification_hosoku == '') {
                                $qualification = $qualification.="</br>".$shopRow->job->qualification_hosoku;
                              }
                              echo ($qualification);
                            } else { echo ('登録されていません。'); } ?>
                  </td>
                </tr>
                <th align="center">休日</th>
                  <td><?php if(!$shopRow->job->holiday == '') {
                              $holiday = $shopRow->job->holiday;
                              if (!$shopRow->job->holiday_hosoku == '') {
                                $holiday = $holiday.="</br>".$shopRow->job->holiday_hosoku;
                              }
                              echo ($holiday);
                            } else { echo ('登録されていません。'); } ?>
                  </td>
                </tr>
                  <th align="center">待遇</th>
                  <td>
                    <?php if(!$shopRow->job->treatment == '') { ?>
                      <?php $array =explode(',', $shopRow->job->treatment); ?>
                      <?php for ($i = 0; $i < count($array); $i++) { ?>
                      <div class="chip" name=""id="<?=$array[$i]?>" value="<?=$array[$i]?>"><?=$array[$i]?></div>
                      </div>
                      <?php } ?>
                    <?php } else {echo ('登録されていません。');} ?>
                  </td>
                </tr>
                <tr>
                  <th align="center">連絡先1</th>
                  <td><?php if(!$shopRow->job->tel1 == '') {
                    echo ($shopRow->job->tel1);
                  } else {echo ('登録されていません。');} ?>
                  </td>
                </tr>
                <tr>
                  <th align="center">連絡先2</th>
                  <td><?php if(!$shopRow->job->tel2 == '') {
                    echo ($shopRow->job->tel2);
                  } else {echo ('登録されていません。');} ?>
                  </td>
                </tr>
                <tr>
                  <th align="center">メール</th>
                  <td><?php if(!$shopRow->job->email == '') {
                    echo ($shopRow->job->email);
                  } else {echo ('登録されていません。');} ?>
                  </td>
                </tr>
                <tr>
                  <th align="center">LINEID</th>
                  <td><?php if(!$shopRow->job->lineid == '') {
                    echo ($shopRow->job->lineid);
                  } else {echo ('登録されていません。');} ?>
                  </td>
                </tr>
                <tr>
                  <th align="center">PR</th>
                  <td><?php if(!$shopRow->job->pr == '') {
                    echo ($shopRow->job->pr);
                  } else {echo ('登録されていません。');}?>
                  </td>
                </tr>
              </table>
              <?php endforeach; ?>
              <p style="text-align:center;">
                <button type="button" class="waves-effect waves-light btn-large changeBtn" onclick="jobChangeBtn($('#job'));return false;">編集</button>
              </p>
            </div>
          </div>
        </div>
        <form id="edit-job" name="edit_job" method="post" action="/owner/shops/edit_job/<?= $shopRow->job->id ?>" style="display:none;">
          <div style="display:none;">
            <input type="hidden" name="_method" value="POST">
            <input type="hidden" name="job_edit" value="">
            <input type="hidden" name="job_edit_id" value="">
            <input type="hidden" name="treatment" value="">
          </div>
          <div class="row">
            <div class="input-field col s12 m10 l6">
              <p id="name" name="name"></p>
            </div>
          </div>
          <div class="row">
            <div class="input-field col s12 m10 l6">
            <select name="industry">
                <option value="" disabled selected>業種を選択してください。</option>
                <?php foreach ($selectList['industry'] as $key => $value) {
                  echo('<option value="' .$value.'">'.$value.'</option>');
                  }?>
              </select>
              <label>業種</label>
            </div>
          </div>
          <div class="row">
            <div class="input-field col s12 m10 l6">
            <select name="job_type">
                <option value="" disabled selected>職種を選択してください。</option>
                <?php foreach ($selectList['job_type'] as $key => $value) {
                  echo('<option value="' .$value.'">'.$value.'</option>');
                  }?>
              </select>
              <label>職種</label>
            </div>
          </div>
          <div class="row">
            <div class="col s12 m12 l12"><label style="font-size: 1rem;">時間</label></div>
            <div class="col s5 m2 l2"><input id="work-from-time" class="timepicker" name="work_from_time"></div>
            <div class="col s2 m2 l2 center-align">～</div>
            <div class="col s5 m2 l2"><input id="work-to-time" class="timepicker" name="work_to_time"></div>
          </div>
          <div class="row">
            <div class="input-field col s12 m10 l6">
              <input type="text" id="work-time-hosoku" class="validate" name="work_time_hosoku" data-length="50">
              <label for="work-time-hosoku">時間についての補足</label>
            </div>
          </div>
          <div class="row">
          <div class="input-field col s12 m4 l2">
            <select name="from_age">
                <option value="" selected>年齢を選択してください</option>
                <?php foreach ($selectList['age'] as $key => $value) {
                  echo('<option value="' .$key.'">'.$value.'</option>');
                  }?>
              </select>
              <label>資格</label>
            </div>
            <label class="col s12 m1 l1">歳から</label>
            <div class="input-field col s12 m4 l2">
            <select name="to_age">
                <option value="" selected>年齢を選択してください</option>
                <?php foreach ($selectList['age'] as $key => $value) {
                  echo('<option value="' .$key.'">'.$value.'</option>');
                  }?>
              </select>
            </div>
            <label class="col s12 m2 l1">歳くらいまで</label>
          </div>
          <div class="row">
            <div class="input-field col s12 m10 l6">
              <input type="text" id="qualification-hosoku" class="validate" name="qualification_hosoku" data-length="50">
              <label for="qualification-hosoku">資格についての補足</label>
            </div>
          </div>
          <div class="row">
            <div class="col s12 m10 l6">
              <label>休日</label>
              <ul>
                <?php foreach ($selectList['day'] as $key => $value) {
                        echo('<li class="daylist"><input type="checkbox" name="holiday[]" id="'.$key.'" value="'.$value.'" /><label for="'.$key.'">'.$value.'</label></li>');
                      }
                ?>
              </ul>
            </div>
          </div>
          <div class="row">
            <div class="input-field col s12 m10 l6">
              <input type="text" id="holiday_hosoku" class="validate" name="holiday_hosoku">
              <label for="holiday_hosoku">休日についての補足</label>
            </div>
          </div>
          <div class="row">
            <div class="input-field col s12 m10 l6">
            <div class="chips chips-initial disabled" name="credit"></div>
              <label for="chips-autocomplete">待遇</label>
              <a data-target="modal-job" onClick="modalJobTriggerBtn($('#job'));" class="waves-effect waves-light btn-large modal-trigger">リストから選ぶ</a>
            </div>
          </div>
          <div class="row">
            <div class="input-field col s12 m10 l6">
              <textarea id="pr" class="materialize-textarea" name="pr" data-length="120"></textarea>
              <label for="pr">PR文</label>
            </div>
          </div>
          <div class="row">
            <div class="input-field col s12 m10 l6">
              <input type="tel" id="tel1" class="validate" name="tel1">
              <label for="tel1">連絡先１</label>
            </div>
          </div>
          <div class="row">
            <div class="input-field col s12 m10 l6">
              <input type="tel" id="tel2" class="validate" name="tel2">
              <label for="tel2">連絡先２</label>
            </div>
          </div>
          <div class="row">
            <div class="input-field col s12 m10 l6">
              <input type="text" id="email" class="validate" name="email">
              <label for="email">email</label>
            </div>
          </div>
          <div class="row">
            <div class="input-field col s12 m10 l6">
              <input type="text" id="lineid" class="validate" name="lineid">
              <label for="lineid">LINEID</label>
            </div>
          </div>
          <div class="card-content" style="text-align:center;">
            <button type="button" href="#" class="waves-effect waves-light btn-large changeBtn" onclick="jobChangeBtn($('#job'));return false;">やめる</button>
            <button type="button" class="waves-effect waves-light btn-large saveBtn" onclick="jobSaveBtn();return false;">確定</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
