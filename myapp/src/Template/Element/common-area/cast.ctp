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
      <img class="responsive-img" width="100%" src=<?php if(!empty($cast->image1)) {
        echo($shopInfo['shop_path'].DS.PATH_ROOT['CAST'].DS.$cast->dir.DS.PATH_ROOT['IMAGE'].DS.$cast->image1);} else {
        echo(PATH_ROOT['AREA01']);} ?> />
      <div class="fixed-action-btn share horizontal click-to-toggle">
        <a class="btn-floating btn-large red">
          <i class="material-icons">share</i>
        </a>
        <ul>
          <li>
            <a class="btn-floating blue"><i class="icon-facebook-rect">icon-facebook-rect</i></a>
          </li>
          <li>
            <a class="btn-floating blue darken-1"><i class="icon-twitter">icon-twitter</i></a>
          </li>
          <li>
            <a class="btn-floating chocolate"><i class="icon-instagram">icon-instagram</i></a>
          </li>
          <li>
            <a class="btn-floating green"><i class="icon-comment-alt">icon-comment-alt</i></a>
          </li>
        </ul>
      </div>
      <?= $this->element('shop-edit-form') ?>
      <h5 class="left-align"><?php if($cast->nickname != '') {
        echo($cast->nickname);} else {
        echo("店舗名を決めてください。");} ?>
      </h5>
      <div class="header-area">
        <div class="row share right-align">
          <a class="btn-floating blue btn-large waves-effect waves-light tooltipped" data-position="bottom" data-delay="50" data-tooltip="facebookでシェア">
            <i class="icon-facebook-rect">icon-facebook-rect</i>
          </a>
          <a class="btn-floating blue darken-1 btn-large waves-effect waves-light tooltipped" data-position="bottom" data-delay="50" data-tooltip="twitterでシェア">
            <i class="icon-twitter">icon-twitter</i>
          </a>
          <a class="btn-floating chocolate btn-large waves-effect waves-light tooltipped" data-position="bottom" data-delay="50" data-tooltip="instagramでシェア">
            <i class="icon-instagram">icon-instagram</i>
          </a>
          <a class="btn-floating green btn-large waves-effect waves-light tooltipped" data-position="bottom" data-delay="50" data-tooltip="lineでシェア">
            <i class="icon-comment-alt">icon-comment-alt</i>
          </a>
        </div>
      </div>
      <div class="row">
        <div class="col s12">
          <a class="waves-effect waves-light btn-large modal-trigger" style="width:100%" href="#coupons-modal">クーポン一覧を見る<i class="material-icons right">keyboard_arrow_right</i></a>
        </div>
      </div>
      <div class="row cast-profile">
        <div class="or-header-wrap card-panel red lighten-3">
          <span class="or-header">プロフィール</span>
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
              <tr>
                <th align="center">メッセージ</th>
                <td class="left-align"><?=!empty($cast->message) ? $this->Text->autoParagraph($cast->message):"-" ?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="row gallery-list">
        <div class="or-header-wrap card-panel red lighten-3">
          <span class="or-header"><?=$cast->nickname?>さんのギャラリー</span>
        </div>
        <div class="card gallery-card">
          <?php $isGalleryExists = false; ?>
          <?php foreach ($imageList as $key => $value): ?>
            <?= $value == reset($imageList) ?'<div class="my-gallery">':""?>
              <figure class="col <?=(count($imageList)==1?'s12 m12 l12':(count($imageList)==2?'s6 m6 l6':'s4 m4 l4'))?>">
                <a href="<?=$shopInfo['cast_path'].DS.$cast->dir.DS.PATH_ROOT['IMAGE'].DS.$value['name']?>" data-size="800x600"><img width="100%" src="<?=$shopInfo['cast_path'].DS.$cast->dir.DS.PATH_ROOT['IMAGE'].DS.$value['name']?>" alt="写真の説明でーす。" /></a>
              </figure>
            <?= $value == end($imageList) ?'</div>':""?>
            <?php $isGalleryExists = true; ?>
          <?php endforeach; ?>
          <?= $isGalleryExists ? "" : '<p class="col">ギャラリーの登録はありません。</p>';?>
        </div>
      </div>
      <div class="row diary-list">
        <div class="or-header-wrap card-panel red lighten-3">
          <span class="or-header"><?=$cast->nickname?>さんの日記</span>
        </div>
        <?php if (count($cast->diarys) > 0) { ?>
          <div class="card diary-card">
        <?php foreach ($dImageList as $key => $value): ?>
        <?= $value == reset($dImageList) ?'<div class="my-gallery">':""?>
            <figure class="col <?=(count($dImageList)==1?'s12 m12 l12':(count($dImageList)==2?'s6 m6 l6':'s4 m4 l4'))?>">
              <a href="<?=$shopInfo['cast_path'].DS.$cast->dir.DS.PATH_ROOT['DIARY'].DS.$cast->diarys[0]->dir.DS.$value['name']?>" data-size="800x600"><img width="100%" src="<?=$shopInfo['cast_path'].DS.$cast->dir.DS.PATH_ROOT['DIARY'].DS.$cast->diarys[0]->dir.DS.$value['name']?>" alt="写真の説明でーす。" /></a>
            </figure>
        <?= $value == end($dImageList) ?'</div>':""?>
        <?php endforeach; ?>
            <div class="card-content">
            <p class="right-align"><?=$cast->diarys[0]->ymd_created?></p>
            <p class="title">
              <?=$cast->diarys[0]->title?>
            </p>
              <p class="content"><?=$this->Text->autoParagraph($cast->diarys[0]->content)?></p>
            </div>
            <div class="card-action like-field"><p>
              <a class="btn-floating waves-effect waves-green btn-flat blue">
                    <i class="material-icons">thumb_up</i></a><span class="like-field-span like-count"><?=count($cast->diarys[0]->likes)?></span>
              <a class="btn-floating waves-effect waves-green btn-flat red">
                    <i class="material-icons">thumb_up</i></a><span class="like-field-span">LIKE?</span>
              <a href="<?=DS.$shopInfo['area']['path'].DS.PATH_ROOT['DIARY'].DS.$cast->id."?area=".$cast->shop->area."&genre=".$cast->shop->genre.
                      "&shop=".$cast->shop->id."&name=".$cast->shop->name."&cast=".$cast->id."&nickname=".$cast->nickname?>"
                      class="right waves-effect waves-light btn-large createBtn"><i class="material-icons right">chevron_right</i><?=COMMON_LB['052']?></a>
              </p>
            </div>
          </div>
            <?php
            } else { ?>
            <p class="col">日記がありません。</p>
        <?php } ?>
      </div>
      <div class="row">
        <div class="or-header-wrap card-panel red lighten-3">
          <span class="or-header">店舗情報</span>
        </div>
        <div class="col s12 m6 l6">
         <table id="basic-info" class="bordered shop-table z-depth-2" border="1">
          <tbody>
            <tr>
              <th class="table-header" colspan="2" align="center"><?= !empty($shop->name) ? h($shop->name) : h('-') ?></th>
            </tr>
            <tr>
              <th align="center">所在地</th>
              <td name="address"><?= !empty($cast->shop->full_address) ? h($cast->shop->full_address) : h('-') ?></td>
            </tr>
            <tr>
              <th align="center">連絡先</th>
              <td><?php if(!empty($cast->shop->tel)): ?>
                <a href="tel:<?= $cast->shop->tel?>"><?= $cast->shop->tel?></a>
                  <?php else: {h('-');} endif; ?>
              </td>
            </tr>
            <tr>
              <th align="center">営業時間</th>
              <td><?php if((!empty($shop->bus_from_time))) {
                      $busTime = $this->Time->format($shop->bus_from_time, 'HH:mm')
                      ." ～ ".(empty($shop->bus_to_time) ? 'ラスト' : $this->Time->format($shop->bus_to_time, 'HH:mm'));
                      if (!empty($shop->bus_hosoku)) {
                        $busTime = $busTime.="</br>".$shop->bus_hosoku;
                      }
                      echo (mb_convert_kana($busTime,'N'));
                    } else { echo ('-'); } ?>
              </td>
            </tr>
            <tr>
              <th align="center">スタッフ</th>
              <td><?= !empty($cast->shop->staff) ? h($cast->shop->staff) : h('-') ?></td>
            </tr>
            <tr>
              <th align="center" valign="top">システム</th>
              <td><?= !empty($cast->shop->system) ? $this->Text->autoParagraph($cast->shop->system) : h('-') ?></td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="col s12 m6 l6">
        <div class="post_col post_col-2">
          <table class="other-table bordered shop-table z-depth-2" border="1">
            <tbody>
              <tr>
                <th class="table-header" colspan="2" align="center">その他</th>
              </tr>
                <th align="center">ご利用できるクレジットカード</th>
                <td><?php if(!empty($cast->shop->credit)) { ?>
                      <?php $array =explode(',', $cast->shop->credit); ?>
                      <?php for ($i = 0; $i < count($array); $i++) { ?>
                      <div class="chip" name="" value="">
                        <img src="<?=PATH_ROOT['CREDIT'].$array[$i]?>.png" id="<?=$array[$i]?>" alt="<?=$array[$i]?>">
                        <?=$array[$i]?>
                      </div>
                      <?php } ?>
                      <?php } else {echo ('-');} ?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col s12">
        <div class="card-panel red lighten-3">
          <span class="or-header">マップ</span>
        </div>
        <div style="width:100%;height:300px;" id="google_map"></div>
      </div>
    </div>
  </div>
  <div id="shop-sidebar" class="col s12 m12 l4">
    <div class="card-panel red lighten-3">
      <span class="or-header">求人情報</span>
    </div>
    <div class="col s12 m6 l12">
      <table class="bordered shop-table z-depth-2" border="1">
        <tbody>
          <tr>
          <tr>
            <th  class="table-header" colspan="2" align="center"><?php if(!empty($cast->shop->name)) {
              echo ($cast->shop->name);
            } else {echo ('-');}?></th>
          </tr>
          <tr>
            <th align="center">業種</th>
            <td>
              <?= !empty($cast->shop->jobs[0]->industry) ? 
                $this->Text->autoParagraph($cast->shop->jobs[0]->industry): h('-') ?>
            </td>
          </tr>
          <tr>
            <th align="center">職種</th>
            <td>
              <?= !empty($cast->shop->jobs[0]->job_type) ? 
                $this->Text->autoParagraph($cast->shop->jobs[0]->job_type): h('-') ?>
            </td>
          </tr>
          <th align="center">時間</th>
            <td><?php if((!empty($cast->shop->jobs[0]->work_from_time))) {
                        $workTime = $this->Time->format($cast->shop->jobs[0]->work_from_time, 'HH:mm')
                        ." ～ ".(empty($cast->shop->jobs[0]->work_to_time) ? 'ラスト' : $this->Time->format($cast->shop->jobs[0]->work_to_time, 'HH:mm'));
                        if (!empty($cast->shop->jobs[0]->work_time_hosoku)) {
                          $workTime = $workTime.="</br>".$cast->shop->jobs[0]->work_time_hosoku;
                        }
                        echo (mb_convert_kana($workTime,'N'));
                      } else { echo ('-'); } ?>
            </td>
          </tr>
          <th align="center">資格</th>
          <td><?php if((!empty($cast->shop->jobs[0]->from_age))
                      && (!empty($cast->shop->jobs[0]->to_age))) {
                        $qualification = $cast->shop->jobs[0]->from_age."歳～".$cast->shop->jobs[0]->to_age."歳くらいまで";
                        if (!empty($cast->shop->jobs[0]->qualification_hosoku)) {
                          $qualification = $qualification.="</br>".$cast->shop->jobs[0]->qualification_hosoku;
                        }
                        echo (mb_convert_kana($qualification,'N'));
                      } else { echo ('-'); } ?>
            </td>
          </tr>
          <th align="center">休日</th>
            <td><?php if(!empty($cast->shop->jobs[0]->holiday)) {
                        $holiday = $cast->shop->jobs[0]->holiday;
                        if (!empty($cast->shop->jobs[0]->holiday_hosoku)) {
                          $holiday = $holiday.="</br>".$cast->shop->jobs[0]->holiday_hosoku;
                        }
                        echo ($holiday);
                      } else { echo ('-'); } ?>
            </td>
          </tr>
            <th align="center">待遇</th>
            <td>
              <?php if(!empty($cast->shop->jobs[0]->treatment)) { ?>
                <?php $array =explode(',', $cast->shop->jobs[0]->treatment); ?>
                <?php for ($i = 0; $i < count($array); $i++) { ?>
                <div class="chip" name=""id="<?=$array[$i]?>" value="<?=$array[$i]?>"><?=$array[$i]?></div>
                </div>
                <?php } ?>
              <?php } else {echo ('-');} ?>
            </td>
          </tr>
          <tr>
            <th align="center">PR</th>
            <td><?php if(!empty($cast->shop->jobs[0]->pr)) {
              echo ($cast->shop->jobs[0]->pr);
            } else {echo ('-');}?>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="col s12 m6 l12">
      <table class="tel-table bordered shop-table z-depth-2" border="1">
        <tbody>
          <tr>
            <th  class="table-header" colspan="2" align="center">応募連絡先</th>
          </tr>
          <tr>
            <th align="center">連絡先1</th>
            <td><?php if(!empty($cast->shop->jobs[0]->tel1)): ?>
              <a href="tel:<?= $cast->shop->jobs[0]->tel1?>"><?= $cast->shop->jobs[0]->tel1?></a>
                <?php else: {echo ('-');} endif; ?>
            </td>
          </tr>
          <tr>
            <th align="center">連絡先2</th>
            <td><?php if(!empty($cast->shop->jobs[0]->tel2)): ?>
              <a href="tel:<?= $cast->shop->jobs[0]->tel2?>"><?= $cast->shop->jobs[0]->tel2?></a>
                <?php else: {echo ('-');} endif; ?>
            </td>
          </tr>
          <tr>
            <th align="center">メール</th>
            <td><?php if(!empty($cast->shop->jobs[0]->email)) {
              echo ($cast->shop->jobs[0]->email);
            } else {echo ('-');} ?>
            </td>
          </tr>
          <tr>
            <th align="center">LINE ID</th>
            <td><?php if(!empty($cast->shop->jobs[0]->lineid)) {
              echo ($cast->shop->jobs[0]->lineid);
            } else {echo ('-');} ?>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
</div>
<!-- Modal Structure -->
<div id="coupons-modal" class="modal modal-fixed-footer">
  <div class="modal-content">
  <?php if(count($cast->shop->coupons) > 0) {  ?>
  <ul class="collection with-header">
    <li class="collection-header"><h4>使いたいクーポン番号をお店の人に見せてね☆</h4></li>
  <?php foreach($cast->shop->coupons as  $key => $coupon): ?>
    <li class="collection-item">
      <!-- <i class="material-icons circle">folder</i> -->
      <span><?= "#".($key + 1)."　".$this->Time->format($coupon->from_day, 'Y/M/d') ?> ～ <?= $this->Time->format($coupon->to_day, 'Y/M/d') ?></span><br>
      <span class="title blue-text text-darken-2"><?=$coupon->title ?></span>
      <p><?=$this->Text->autoParagraph($coupon->content) ?></p>
    </li>
  <?php endforeach; ?>
  </ul>
  <?php } else { ?>
    <div class="">
      <p>クーポンの登録はありません。</p>
    </div>
  <?php } ?>
  </div>
  <div class="modal-footer">
    <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">とじる</a>
  </div>
</div>
<?= $this->element('photoSwipe'); ?>
