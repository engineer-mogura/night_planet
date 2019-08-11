<?php
/**
* @var \App\View\AppView $this
* @var \App\Model\Entity\shop[]|\Cake\Collection\CollectionInterface $owners
*/
?>
<div id="shop" class="container">
  <?= $this->Flash->render() ?>
  <?= $this->element('nav-breadcrumb'); ?>
  <div class="row">
    <div id="shop-main" class="col s12 m12 l8">
      <img class="responsive-img" width="100%" src=<?php if($shop->top_image != '') {
        echo($shopInfo['shop_path'].DS.$shop->top_image);} else {
        echo("/img/common/area/top1.jpg");} ?> />
      <div class="fixed-action-btn share horizontal click-to-toggle">
        <a class="btn-floating btn-large red">
          <i class="material-icons">share</i>
        </a>
        <ul>
          <li>
            <a href="<?=SHARER['FACEBOOK'].$sharer?>" class="btn-floating blue"><i class="icon-facebook-rect">icon-facebook-rect</i></a>
          </li>
          <li>
            <a href="<?=SHARER['TWITTER'].$sharer?>" class="btn-floating blue darken-1"><i class="icon-twitter">icon-twitter</i></a>
          </li>
          <li>
            <a class="btn-floating chocolate"><i class="icon-instagram">icon-instagram</i></a>
          </li>
          <li>
            <a href="<?=SHARER['LINE'].$sharer?>" class="btn-floating green"><i class="icon-comment-alt">icon-comment-alt</i></a>
          </li>
        </ul>
      </div>
      <?= $this->element('shop-edit-form') ?>
      <h5 class="left-align">
        <?= !empty($shop->name) ? h($shop->name) : h('-') ?>
      </h5>
      <div class="header-area">
        <div class="share right-align">
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
      <div class="card-panel light-blue accent-1">
        <?php if($shop->catch != ''){
          echo ($this->Text->autoParagraph($shop->catch)); } else {
          echo ('キャッチコピーがありません。');} ?>
      </div>
      <div class="row">
        <div class="col s12">
          <a class="waves-effect waves-light btn-large modal-trigger" style="width:100%" href="#coupons-modal">クーポン一覧を見る<i class="material-icons right">keyboard_arrow_right</i></a>
        </div>
      </div>
      <div class="row cast-list">
        <div class="or-header-wrap card-panel red lighten-3">
          <span class="or-header">キャスト</span>
        </div>
        <?php if(count($shop->casts) > 0) { ?>
            <?php foreach($shop->casts as $cast): ?>
            <div class="col s4 m3 l3">
              <div>
                <a href="<?=DS.$shop['area'].DS.PATH_ROOT['CAST'].DS.$cast['id']."?genre=".$shop['genre']."&name=".$shop['name']."&shop=".$shop['id']."&nickname=".$cast['nickname']?>">
                  <img src="<?=isset($cast->image1) ? $shopInfo['shop_path'].DS.PATH_ROOT['CAST'].DS.$cast->dir.DS.PATH_ROOT['IMAGE'].DS.$cast->image1 : PATH_ROOT['NO_IMAGE02'] ?>" alt="" class="circle" width="80" height="80">
                </a>
                </div>
              <h6><?=$cast->nickname?></h6>
            </div>
            <?php endforeach; ?>
        <?php } else { ?>
            <p class="col">キャストの登録はありません。</p>
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
              <td name="address"><?= !empty($shop->full_address) ? h($shop->full_address) : h('-') ?></td>
            </tr>
            <tr>
              <th align="center">連絡先</th>
              <td><?= !empty($shop->tel) ? h($shop->tel) : h('-') ?></td>
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
              <td><?= !empty($shop->staff) ? h($shop->staff) : h('-') ?></td>
            </tr>
            <tr>
              <th align="center" valign="top">システム</th>
              <td><?= !empty($shop->system) ? $this->Text->autoParagraph($shop->system) : h('-') ?></td>
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
              <tr>
                <th align="center">ご利用できるクレジットカード</th>
                <td><?php if(!empty($shop->credit)) { ?>
                      <?php $array =explode(',', $shop->credit); ?>
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
        <div class="post_col post_col-2">
          <table class="new-info-table bordered shop-table z-depth-2" border="1">
            <tbody>
              <tr>
                <th class="table-header" colspan="2" align="center">新着情報</th>
              </tr>
              <tr>
                <td>新着情報はありません。</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="or-header-wrap card-panel red lighten-3">
        <span class="or-header">ギャラリー</span>
      </div>
      <?php $isGalleryExists = false; ?>
        <?php foreach ($imageList as $key => $value): ?>
          <?= $value == reset($imageList) ?'<div class="gallery-box"><div class="my-gallery">':""?>
            <figure class="col <?=(count($imageList)==1?'s12 m12 l12':(count($imageList)==2?'s6 m6 l6':'s4 m4 l4'))?>">
              <a href="<?=$shopInfo['image_path'].DS.$value['name']?>" data-size="800x600"><img width="100%" src="<?=$shopInfo['image_path'].DS.$value['name']?>" alt="写真の説明でーす。" /></a>
            </figure>
          <?= $value == end($imageList) ?'</div></div>':""?>
          <?php $isGalleryExists = true; ?>
        <?php endforeach; ?>
        <?= $isGalleryExists ? "" : '<p class="col">ギャラリーの登録はありません。</p>';?>
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
            <th  class="table-header" colspan="2" align="center"><?php if(!empty($shop->name)) {
              echo ($shop->name);
            } else {echo ('-');}?></th>
          </tr>
          <tr>
            <th align="center">業種</th>
            <td>
              <?= !empty($shop->jobs[0]->industry) ? 
                $this->Text->autoParagraph($shop->jobs[0]->industry): h('-') ?>
            </td>
          </tr>
          <tr>
            <th align="center">職種</th>
            <td>
              <?= !empty($shop->jobs[0]->job_type) ? 
                $this->Text->autoParagraph($shop->jobs[0]->job_type): h('-') ?>
            </td>
          </tr>
          <th align="center">時間</th>
            <td><?php if((!empty($shop->jobs[0]->work_from_time))) {
                        $workTime = $this->Time->format($shop->jobs[0]->work_from_time, 'HH:mm')
                        ." ～ ".(empty($shop->jobs[0]->work_to_time) ? 'ラスト' : $this->Time->format($shop->jobs[0]->work_to_time, 'HH:mm'));
                        if (!empty($shop->jobs[0]->work_time_hosoku)) {
                          $workTime = $workTime.="</br>".$shop->jobs[0]->work_time_hosoku;
                        }
                        echo (mb_convert_kana($workTime,'N'));
                      } else { echo ('-'); } ?>
            </td>
          </tr>
          <th align="center">資格</th>
          <td><?php if((!empty($shop->jobs[0]->from_age))
                      && (!empty($shop->jobs[0]->to_age))) {
                        $qualification = $shop->jobs[0]->from_age."歳～".$shop->jobs[0]->to_age."歳くらいまで";
                        if (!empty($shop->jobs[0]->qualification_hosoku)) {
                          $qualification = $qualification.="</br>".$shop->jobs[0]->qualification_hosoku;
                        }
                        echo (mb_convert_kana($qualification,'N'));
                      } else { echo ('-'); } ?>
            </td>
          </tr>
          <th align="center">休日</th>
            <td><?php if(!empty($shop->jobs[0]->holiday)) {
                        $holiday = $shop->jobs[0]->holiday;
                        if (!empty($shop->jobs[0]->holiday_hosoku)) {
                          $holiday = $holiday.="</br>".$shop->jobs[0]->holiday_hosoku;
                        }
                        echo ($holiday);
                      } else { echo ('-'); } ?>
            </td>
          </tr>
            <th align="center">待遇</th>
            <td>
              <?php if(!empty($shop->jobs[0]->treatment)) { ?>
                <?php $array =explode(',', $shop->jobs[0]->treatment); ?>
                <?php for ($i = 0; $i < count($array); $i++) { ?>
                <div class="chip" name=""id="<?=$array[$i]?>" value="<?=$array[$i]?>"><?=$array[$i]?></div>
                </div>
                <?php } ?>
              <?php } else {echo ('-');} ?>
            </td>
          </tr>
          <tr>
            <th align="center">PR</th>
            <td><?php if(!empty($shop->jobs[0]->pr)) {
              echo ($shop->jobs[0]->pr);
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
            <td><?php if(!empty($shop->jobs[0]->tel1)) {
              echo ($shop->jobs[0]->tel1);
            } else {echo ('-');} ?>
            </td>
          </tr>
          <tr>
            <th align="center">連絡先2</th>
            <td><?php if(!empty($shop->jobs[0]->tel2)) {
              echo ($shop->jobs[0]->tel2);
            } else {echo ('-');} ?>
            </td>
          </tr>
          <tr>
            <th align="center">メール</th>
            <td><?php if(!empty($shop->jobs[0]->email)) {
              echo ($shop->jobs[0]->email);
            } else {echo ('-');} ?>
            </td>
          </tr>
          <tr>
            <th align="center">LINE ID</th>
            <td><?php if(!empty($shop->jobs[0]->lineid)) {
              echo ($shop->jobs[0]->lineid);
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
    <?php if(count($shop->coupons) > 0) { ?>
    <ul class="collection with-header">
      <li class="collection-header"><h4>使いたいクーポン番号をお店の人に見せてね☆</h4></li>
    <?php foreach($shop->coupons as  $key => $coupon): ?>
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
