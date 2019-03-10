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
          <!-- <form id="edit-top-image" name="edit_top_image" method="post" accept-charset="utf-8" enctype="multipart/form-data" action="/owner/shops/edit_top_image/<?= $shopRow->owner_id ?>" style="display:none;">
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
                <input class="file-path validate" name="top_image" type="text">
              </div>
            </div>
            <div id="result"></div>
            <div class="card-content" style="text-align:center;">
              <button type="button" class="waves-effect waves-light btn-large" onclick="topImageChangeBtn($('#top-image'));return false;">やめる</button>
              <button type="button" class="waves-effect waves-light btn-large saveBtn" onclick="topImageSaveBtn(); return false;">確定</button>
            </div>
          </form> -->
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
              <div class="description"><?php echo($catch) ?></div>
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
              <textarea id="catch-copy" class="validate materialize-textarea" name="catch" data-length="120"><?=$shopRow->catch?></textarea>
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
                  <?php foreach ($shopRow->coupons as $coupons): ?>
                    <?php $i++; ?>
                    <div class="col s12 m6 l8 coupon-box">
                      <form id="delete-coupon<?=$i?>" name="delete_coupon" method="post" style="display:none;" action="/owner/shops/edit_coupon/<?= $coupons->id ?>">
                        <input type="hidden" name="_method" value="POST">
                        <input type="hidden" name="coupon_delete" value="<?=$coupons->id ?>">
                        <input type="hidden" name="coupon_title" value="<?=$coupons->title ?>">
                      </form>
                      <form id="switch-coupon<?=$i?>" name="switch_coupon<?=$coupons->id ?>" method="post" style="display:none;" action="/owner/shops/edit_coupon/<?= $coupons->id ?>">
                        <input type="hidden" name="_method" value="POST">
                      </form>
                      <ul class="collection z-depth-2 <?php if(count($shopRow->coupons) == $i) { echo('targetScroll');}?>">
                        <li class="collection-item">
                          <img src="/img/common/coupon/coupon1.jpg" alt="" class="circle" width="50" height="50">
                          <table class="highlight">
                            <thead>
                              <td colspan="2">
                                <input type="checkbox" class="check-coupon-group" name="check_coupon" id="check-coupon<?=$i?>" />
                                <label for="check-coupon<?=$i?>">編集する</label>
                                <div style="display:none;">
                                  <input type="hidden" name="coupon_copy" value='<?=$coupons ?>'>
                                </div>
                                <a href="#!" class="secondary-content">
                                  <div class="switch">
                                    <label>OFF<input type="checkbox" value="<?=$coupons->status ?>" name="switch_coupon<?=$coupons->id ?>" class="switch_coupon" <?php if ($coupons->status == 1) { echo 'checked'; }?>><span class="lever"></span>ON</label>
                                  </div>
                                </a>
                              </td>
                            </thead>
                            <tbody>
                              <tr><th>有効期間</th>
                                <td><?= $this->Time->format($coupons->from_day, 'Y/M/d') ?>～<?= $this->Time->format($coupons->to_day, 'Y/M/d') ?>
                                </td>
                              </tr>
                              <tr><th>タイトル</th>
                                <td><?=$coupons->title ?></td>
                              </tr>
                              <tr><th>内容</th>
                                <td><?= $this->Text->autoParagraph($coupons->content)?></td>
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
              <textarea id="coupon-content" class="validate materialize-textarea" name="content"></textarea>
              <label for="coupon-content">内容</label>
            </div>
          </div>
          <div class="card-content" style="text-align:center;">
            <button type="button" href="#" class="waves-effect waves-light btn-large" onclick="couponChangeBtn($('#coupon'));return false;">やめる</button>
            <button type="button" class="waves-effect waves-light btn-large saveBtn" onclick="couponSaveBtn(); return false;">確定</button>
          </div>
        </form>
      </div>

      <div id="cast" class="col s12">
        <!-- <h5>トップ画像</h5>
        <?= $this->Form->create() ?>
        <?= $this->Form->control('email3') ?>
        <?= $this->Form->control('password3') ?>
        <?= $this->Form->button('ログイン') ?>
        <?= $this->Form->end() ?> -->
      </div>
      <div id="tenpo" class="col s12">
        <h5>店舗情報</h5>
        <div id="show-tenpo">
          <div class="row">
            <div class="col s12 m6 l6">
              <?php foreach ($shop as $shopRow): ?>
                <div style="display:none;">
                  <input type="hidden" name="tenpo_copy" value='<?=$shopRow ?>'>
                  <input type="hidden" name="chip_hidden" value='<?=$creditsHidden?>'>
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
                              ."～".$this->Time->format($shopRow->bus_to_time, 'HH:mm')
                              ."</br>".$shopRow->bus_hosoku;
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
        <!-- <h5>トップ画像</h5>
        <?= $this->Form->create() ?>
        <?= $this->Form->control('email7') ?>
        <?= $this->Form->control('password7') ?>
        <?= $this->Form->button('ログイン') ?>
        <?= $this->Form->end() ?> -->
      </div>
    </div>
  </div>
</div>
