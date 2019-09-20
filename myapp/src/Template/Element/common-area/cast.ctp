<?php
/**
* @var \App\View\AppView $this
* @var \App\Model\Entity\shop[]|\Cake\Collection\CollectionInterface $owners
*/
?>
<div id="cast" class="container">
  <?= $this->Flash->render() ?>
  <?= $this->element('nav-breadcrumb'); ?>
  <div class="row">
    <div id="cast-main" class="col s12 m12 l8">
      <?php !empty($cast->top_image) ? $top_image = $shopInfo['cast_path'].DS.$cast->dir.DS.PATH_ROOT['TOP_IMAGE'].DS.$cast->top_image
          : $top_image = PATH_ROOT['CAST_TOP_IMAGE']?>
      <?php !empty($cast->icon) ? $icon = $shopInfo['cast_path'].DS.$cast->dir.DS.PATH_ROOT['PROFILE'].DS.$cast->icon
          : $icon = PATH_ROOT['NO_IMAGE02']?>
      <!-- ヘッダー START -->
      <div class="cast-header">
        <img class="responsive-img" width="100%" src=<?=$top_image?> />
        <div class="cast-header-icon" style="height:70px;">
          <div class="col s5 m5" style="position:relative;bottom:50px;">
            <img src="<?=$icon?>" width="70px" height="70px" class="circle">
            <span class="cast-name-box icon-vertical-align"><?= !empty($cast->nickname)? $cast->nickname: h("ー")?></span>
          </div>
          <div class="col s4 m4">
            <span class="header-diary icon-vertical-align"><?="20"?></span>
            <span class="header-gallery icon-vertical-align"><?="20"?></span>
          </div>
          <div class="col s3 m3">
            <span class="header-working icon-vertical-align bound">本日出勤</span>
          </div>
        </div>
      </div>
      <!-- ヘッダー END -->
      <!-- メッセージ START -->
      <div class="row section">
        <div class="card-panel light-blue">
          <?= !empty($cast->message) ? $this->Text->autoParagraph($cast->message) : 
            $cast->nickname.'さんからのメッセージがありません。';
          ?>
        </div>
      </div>
      <!-- メッセージ END -->
      <!-- 更新情報 START -->
      <?= $this->element('info-marquee'); ?>
      <!-- 更新情報 END -->
      <!-- キャストメニュー START -->
      <div id="shop-menu-section" class="row shop-menu section scrollspy">
        <div class="light-blue accent-2 card-panel col s12 center-align">
          <p class="shop-menu-label section-label"><span> CAST MENU </span></p>
        </div>
        <div class="col s4 m3 l3">
          <div class="cyan linkbox card-panel hoverable center-align">
          <?= in_array(SHOP_MENU_NAME['PROFILE'], $update_icon) ? '<div class="new-info"></div>' : ''?>
            <span class="shop-menu-label cast"></br>プロフ</span>
            <a class="waves-effect waves-light" href="#cast-section"></a>
          </div>
        </div>
        <div class="col s4 m3 l3">
          <div class="cyan linkbox card-panel hoverable center-align">
          <?= in_array(SHOP_MENU_NAME['WORK'], $update_icon) ? '<div class="new-info"></div>' : ''?>
          <span class="shop-menu-label work-schedule"></br>出勤予定</span>
            <a class="waves-effect waves-light" href="#!"></a>
          </div>
        </div>
        <div class="col s4 m3 l3">
          <div class="cyan linkbox card-panel hoverable center-align">
          <?= in_array(SHOP_MENU_NAME['DIARY'], $update_icon) ? '<div class="new-info"></div>' : ''?>
            <span class="shop-menu-label diary"></br>日記</span>
            <a class="waves-effect waves-light" href="#diary-section"></a>
          </div>
        </div>
        <div class="col s4 m3 l3">
          <div class="<?=empty($cast->snss[0]['instagram'])? 'grey ':'cyan '?>linkbox card-panel hoverable center-align">
            <span class="shop-menu-label instagram"></br>instagram</span>
            <a class="waves-effect waves-light" href="#instagram-section"></a>
          </div>
        </div>
        <div class="col s4 m3 l3">
          <div class="<?=empty($cast->snss[0]['facebook'])? 'grey ':'cyan '?>linkbox card-panel hoverable center-align">
            <span class="shop-menu-label facebook"></br>Facebook</span>
            <a class="waves-effect waves-light" href="#facebook-section"></a>
          </div>
        </div>
        <div class="col s4 m3 l3">
          <div class="<?=empty($cast->snss[0]['twitter'])? 'grey ':'cyan '?>linkbox card-panel hoverable center-align">
            <span class="shop-menu-label twitter"></br>Twitter</span>
            <a class="waves-effect waves-light" href="#twitter-section"></a>
          </div>
        </div>
        <div class="col s4 m3 l3">
          <div class="<?=empty($cast->snss[0]['line'])? 'grey ':'cyan '?>linkbox card-panel hoverable center-align">
            <span class="shop-menu-label line"></br>LINE</span>
            <a class="waves-effect waves-light" href="#line-section"></a>
          </div>
        </div>
        <div class="col s4 m3 l3">
          <div class="cyan linkbox card-panel hoverable center-align">
          <?= in_array(SHOP_MENU_NAME['CAST'], $update_icon) ? '<div class="new-info"></div>' : ''?>
            <span class="shop-menu-label casts"></br>キャスト</span>
            <a class="waves-effect waves-light" href="#casts-section"></a>
          </div>
        </div>
        <div class="col s4 m3 l3">
          <div class="cyan linkbox card-panel hoverable center-align">
          <?= in_array(SHOP_MENU_NAME['SHOP-GALLERY'], $update_icon) ? '<div class="new-info"></div>' : ''?>
            <span class="shop-menu-label shop-gallery"></br>Shop Gallery</span>
            <a class="waves-effect waves-light" href="#shop-gallery-section"></a>
          </div>
        </div>
      </div>
      <!-- キャストメニュー END -->
      <!-- プロフィール START -->
      <div id="cast-section" class="row shop-menu section scrollspy">
        <div class="light-blue accent-2 card-panel col s12 center-align">
          <p class="cast-label section-label"><span> プロフィール </span></p>
        </div>
        <div class="col s12 m12 l12">
          <table class="bordered shop-table z-depth-2" border="1">
            <tbody>
              <tr>
                <th class="table-header" colspan="2" align="center"><?= h($cast->nickname);?></th>
              </tr>
              <tr>
                <th align="center">誕生日</th>
                <td><?=!empty($cast->birthday) ? $this->Time->format($cast->birthday, 'M/d'):"-" ?></td>
              </tr>
              <tr>
                <th align="center">星座</th>
                <td><?=!empty($cast->constellation) ? CONSTELLATION[$cast->constellation]['label']:"-" ?></td>
              </tr>
              <tr>
                <th align="center">血液型</th>
                <td><?=!empty($cast->blood_type) ? BLOOD_TYPE[$cast->blood_type]['label']:"-" ?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <!-- プロフィール END -->
      <!-- キャストギャラリー START -->
      <div id="shop-gallery-section" class="row shop-menu section scrollspy">
        <div class="light-blue accent-2 card-panel col s12 center-align">
            <p class="shop-gallery-label section-label"><span> ギャラリー </span></p>
        </div>
        <?= count($imageList) == 0 ? '<p class="col">まだ投稿がありません。</p>': ""; ?>
        <div class="my-gallery" style="display:inline-block;">
          <?php foreach ($imageList as $key => $value): ?>
              <figure>
                <a href="<?=$shopInfo['cast_path'].DS.$cast->dir.DS.PATH_ROOT['IMAGE'].DS.$value['name']?>" data-size="800x1000">
                  <img width="100%" src="<?=$shopInfo['cast_path'].DS.$cast->dir.DS.PATH_ROOT['IMAGE'].DS.$value['name']?>" alt="" />
                </a>
              </figure>
          <?php endforeach; ?>
        </div>
      </div>
      <!-- キャストギャラリー END -->
      <!-- 日記 START -->
      <div id="diary-section" class="row shop-menu section scrollspy">
        <div class="light-blue accent-2 card-panel col s12 center-align">
          <p class="diary-label section-label"><span> 日記 </span></p>
        </div>
        <?php if (count($cast->diarys) > 0): ?>
          <div class="my-gallery" style="display:inline-block;">
            <?php foreach ($dImageList as $key => $value): ?>
              <figure>
                <a href="<?=$shopInfo['cast_path'].DS.$cast->dir.DS.PATH_ROOT['DIARY'].DS.$cast->diarys[0]->dir.DS.$value['name']?>" data-size="800x1000">
                  <img width="100%" src="<?=$shopInfo['cast_path'].DS.$cast->dir.DS.PATH_ROOT['DIARY'].DS.$cast->diarys[0]->dir.DS.$value['name']?>"/>
                </a>
              </figure>
            <?php endforeach; ?>
          </div>
          <div class="col s12">
            <p class="right-align"><?=$cast->diarys[0]->created->nice()?></p>
            <p class="title">
              <?=$cast->diarys[0]->title?>
            </p>
            <p class="content"><?=$this->Text->autoParagraph($cast->diarys[0]->content)?></p>
            <div class="card-action like-field"><p>
              <span class="icon-vertical-align color-blue"><i class="material-icons">favorite_border</i><span class="like-field-span like-count"><?=count($cast->diarys[0]->likes)?></span></span>
              <a href="<?=DS.$shopInfo['area']['path'].DS.PATH_ROOT['DIARY'].DS.$cast->id."?area=".$cast->shop->area."&genre=".$cast->shop->genre.
                      "&shop=".$cast->shop->id."&name=".$cast->shop->name."&cast=".$cast->id."&nickname=".$cast->nickname?>"
                      class="right waves-effect waves-light btn-large createBtn"><i class="material-icons right">chevron_right</i><?=COMMON_LB['052']?>
              </a>
            </p>
          </div>
        </div>
        <?php else: ?>
          <p class="col">日記はありません。</p>
        <?php endif; ?>
      </div>
      <!-- 日記 END -->
            <!-- instagram START -->
            <?php if(!empty($cast->snss[0]['instagram'])): ?>
        <div id="instagram-section" class="row shop-menu section scrollspy">
          <div class="light-blue accent-2 card-panel col s12 center-align">
            <p class="instagram-label section-label"><span> instagram </span></p>
          </div>
          <?php if(!empty($ig_error)):
                  echo('<p class="col">'.$ig_error.'</p>');
                elseif($ig_data->media_count == 0):
                  echo('<p class="col">まだ投稿がありません。</p>');
                else:
          ?>
            <!-- photoSwipe START -->
            <?= $this->element('Instagram'); ?>
            <!-- photoSwipe END -->
          <?php endif;?>
        </div>
      <?php endif;?>
      <!-- instagram END -->
      <!-- facebook START -->
      <?php if(!empty($cast->snss[0]['facebook'])): ?>
        <div id="facebook-section" class="row shop-menu section scrollspy">
          <div class="light-blue accent-2 card-panel col s12 center-align">
            <p class="facebook-label section-label"><span> facebook </span></p>
          </div>
          <div id="fb-root"></div>
          <script async defer crossorigin="anonymous" src="https://connect.facebook.net/ja_JP/sdk.js#xfbml=1&version=v4.0&appId=2084171171889711&autoLogAppEvents=1"></script>        </div>
          <div class="fb-page" data-href="https://www.facebook.com/<?=$cast->snss[0]['facebook']?>" data-tabs="timeline,messages" data-width="500" data-height="" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><blockquote cite="https://www.facebook.com/<?=$cast->snss[0]['facebook']?>/" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/<?=$cast->snss[0]['facebook']?>/"></a></blockquote></div>
      <?php endif;?>
      <!-- facebook END -->
      <!-- twitter START -->
      <?php if(!empty($cast->snss[0]['twitter'])): ?>
        <div id="twitter-section" class="row shop-menu section scrollspy">
          <div class="light-blue accent-2 card-panel col s12 center-align">
            <p class="twitter-label section-label"><span> twitter </span></p>
          </div>
          <a class="twitter-timeline" href="https://twitter.com/<?=$cast->snss[0]['twitter']?>?ref_src=twsrc%5Etfw" data-tweet-limit="3">Tweets by <?=$shop->snss[0]['twitter']?></a>
          <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
        </div>
      <?php endif;?>
      <!-- twitter END -->
      <!-- キャストリスト START -->
      <div id="casts-section" class="row shop-menu section scrollspy">
        <div class="light-blue accent-2 card-panel col s12 center-align">
          <p class="casts-label section-label"><span> CAST </span></p>
        </div>
        <?php if(count($other_casts) > 0): ?>
            <?php foreach($other_casts as $other_cast): ?>
            <div class="center-align col s3 m3 l3">
              <div>
                <a href="<?=DS.$cast->shop['area'].DS.PATH_ROOT['CAST'].DS.$other_cast['id']."?genre=".$cast->shop['genre']."&name=".$cast->shop['name']."&shop=".$cast->shop['id']."&nickname=".$other_cast['nickname']?>">
                  <img src="<?=isset($other_cast->icon) ? $shopInfo['shop_path'].DS.PATH_ROOT['CAST'].DS.$other_cast->dir.DS.PATH_ROOT['PROFILE'].DS.$other_cast->icon : PATH_ROOT['NO_IMAGE02'] ?>" alt="" class="circle" width="80" height="80">
                </a>
                </div>
              <h6><?=$cast->nickname?></h6>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="col">キャストの登録はありません。</p>
        <?php endif; ?>
      </div>
      <!-- キャストリスト END -->
    </div>
    <!--デスクトップ用 サイドバー START -->
    <?= $this->element('sidebar'); ?>
    <!--デスクトップ用 サイドバー END -->
  </div>
</div>
<!-- キャスト用ボトムナビゲーション START -->
<?= $this->element('cast-bottom-navigation'); ?>
<!-- キャスト用ボトムナビゲーション END -->