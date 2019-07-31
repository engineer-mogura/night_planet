<?php
/**
* @var \App\View\AppView $this
* @var \App\Model\Entity\shop[]|\Cake\Collection\CollectionInterface $owners
*/
?>
<div id="cast" class="container">
  <?= $this->Flash->render() ?>
  <nav class="nav-breadcrumb">
      <div class="nav-wrapper nav-wrapper-oki">
        <div class="col s12">
          <?=
            $this->Breadcrumbs->render(
              ['class' => 'breadcrumb'],
              ['separator' => '<i class="material-icons">chevron_right</i>']
            );
          ?>
        </div>
      </div>
    </nav>
  <div class="row">
    <div id="cast-main" class="col s12 m12 l8">
      <img class="responsive-img" width="100%" src=<?php if($cast->image1 != '') {
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
      <ul class="collapsible popout" data-collapsible="accordion">
        <li>
          <div class="collapsible-header orange lighten-4">
            <div class="coupon">
              <i class="material-icons">filter_drama</i>
              クーポン<p class="label">クーポンを表示する</p>
              <p class="arrow nonActive">
                <a class="btn-floating btn-large red">
                  <i class="large material-icons or-material-icons">expand_more</i>
                </a>
              </p>
            </div>
          </div>
            <?php if(count($cast->shop->coupons) > 0) { ?>
            <?php foreach($cast->shop->coupons as $coupon): ?>
              <div class="collapsible-body orange lighten-4">
                <span><?= $this->Time->format($coupon->from_day, 'Y/M/d') ?>～<?= $this->Time->format($coupon->to_day, 'Y/M/d') ?></span><br />
                  <span>★☆★<?=$coupon->title ?>★☆★<br />
                <?=$coupon->content ?><br />
              <?php if($coupon === end($cast->shop->coupons)){echo ('こちらの画面をお店側に見せ、使用するクーポンをお知らせください。');}?></span>
              </div>
            <?php endforeach; ?>
            <?php } else { ?>
              <div class="collapsible-body orange lighten-4">
                <p>クーポンの登録はありません。</p>
              </div>
            <?php } ?>

        </li>
      </ul>
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
                <td><?=!$cast->isEmpty("birthday") ? $this->Time->format($cast->birthday, 'M/d'):"-" ?></td>
              </tr>
              <tr>
                <th align="center">星座</th>
                <td><?=!$cast->isEmpty("constellation") ? CONSTELLATION[$cast->constellation]['label']:"-" ?></td>
              </tr>
              <tr>
                <th align="center">血液型</th>
                <td><?=!$cast->isEmpty("blood_type") ? BLOOD_TYPE[$cast->blood_type]['label']:"-" ?></td>
              </tr>
              <tr>
                <th align="center">メッセージ</th>
                <td class="left-align"><?=!$cast->isEmpty("message") ? $this->Text->autoParagraph($cast->message):"-" ?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="row gallery-list">
        <div class="or-header-wrap card-panel red lighten-3">
          <span class="or-header"><?=$cast->nickname?>さんのギャラリー</span>
        </div>
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
            <div class="card-action like-field">
              <div class="row">
                <div class="col s6 m4 l4"><span class="btn-floating waves-effect waves-green btn-flat blue">
                  <i class="material-icons">thumb_up</i></span><span class="like-field-span like-count"><?=count($cast->diarys[0]->likes)?></span>
                </div>
                <div class="col s6 m4 l4"><a class="btn-floating waves-effect waves-green btn-flat red">
                  <i class="material-icons">thumb_up</i></a><span class="like-field-span">LIKE?</span>
                </div>
                <div class="col s6 m4 l4">
                  <a href="/naha/diary/<?=$cast->id."?area=".$cast->shop->area."&genre=".$cast->shop->genre.
                    "&shop=".$cast->shop->id."&name=".$cast->shop->name."&cast=".$cast->id."&nickname=".$cast->nickname?>"
                    class="waves-effect waves-light btn-large createBtn"><i class="material-icons right">chevron_right</i><?=COMMON_LB['052']?></a>
                </div>
              </div>
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
         <table class="bordered shop-table z-depth-2" border="1">
          <tbody>
            <tr>
              <th class="table-header" colspan="2" align="center"><?= h($cast->shop->name);?></th>
            </tr>
            <tr>
              <th align="center">所在地</th>
              <td><?= h($cast->shop->full_address);?></td>
            </tr>
            <tr>
              <th align="center">連絡先</th>
              <td><?=h($cast->shop->tel);?></td>
            </tr>
            <tr>
              <th align="center">営業時間</th>
              <td><?php if((!$cast->shop->bus_from_time == '')
                            && (!$cast->shop->bus_to_time == '')
                            && (!$cast->shop->bus_hosoku == '')) {
                              $busTime = $this->Time->format($cast->shop->bus_from_time, 'HH:mm')
                              ."～".$this->Time->format($cast->shop->bus_to_time, 'HH:mm')
                              ."</br>".$cast->shop->bus_hosoku;
                              echo ($busTime);
                            } else { echo ('-'); } ?></td>
            </tr>
            <tr>
              <th align="center">スタッフ</th>
              <td><?=h($cast->shop->staff);?></td>
            </tr>
            <tr>
              <th align="center" valign="top">システム</th>
              <td><?=h($cast->shop->system);?></td>
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
                <td><?php if(!$cast->shop->credit == '') { ?>
                      <?php $array =explode(',', $cast->shop->credit); ?>
                      <?php for ($i = 0; $i < count($array); $i++) { ?>
                      <div class="chip" name="" value="">
                        <img src="/img/common/credit/<?=$array[$i]?>.png" id="<?=$array[$i]?>" alt="<?=$array[$i]?>">
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
            <th  class="table-header" colspan="2" align="center"><?php if(!$cast->shop->name == '') {
              echo ($cast->shop->name);
            } else {echo ('-');}?></th>
          </tr>
          <tr>
            <th align="center">業種</th>
            <td>
              <?php if(!$cast->shop->jobs[0]->industry == '') {
                      echo ($this->Text->autoParagraph($cast->shop->jobs[0]->industry));
                    } else {echo ('-');} ?>
            </td>
          </tr>
          <tr>
            <th align="center">職種</th>
            <td>
              <?php if(!$cast->shop->jobs[0]->job_type == '') {
                      echo ($this->Text->autoParagraph($cast->shop->jobs[0]->job_type));
                    } else {echo ('-');} ?>
            </td>
          </tr>
          <th align="center">時間</th>
            <td><?php if((!$cast->shop->jobs[0]->work_from_time == '')
                      && (!$cast->shop->jobs[0]->work_to_time == '')) {
                        $workTime = $this->Time->format($cast->shop->jobs[0]->work_from_time, 'HH:mm')
                        ."～".$this->Time->format($cast->shop->jobs[0]->work_to_time, 'HH:mm');
                        if (!$cast->shop->jobs[0]->work_time_hosoku == '') {
                          $workTime = $workTime.="</br>".$cast->shop->jobs[0]->work_time_hosoku;
                        }
                        echo ($workTime);
                      } else { echo ('-'); } ?>
            </td>
          </tr>
          <th align="center">資格</th>
          <td><?php if((!$cast->shop->jobs[0]->from_age == '')
                      && (!$cast->shop->jobs[0]->to_age == '')) {
                        $qualification = $cast->shop->jobs[0]->from_age."歳～".$cast->shop->jobs[0]->to_age."歳くらいまで";
                        if (!$cast->shop->jobs[0]->qualification_hosoku == '') {
                          $qualification = $qualification.="</br>".$cast->shop->jobs[0]->qualification_hosoku;
                        }
                        echo ($qualification);
                      } else { echo ('-'); } ?>
            </td>
          </tr>
          <th align="center">休日</th>
            <td><?php if(!$cast->shop->jobs[0]->holiday == '') {
                        $holiday = $cast->shop->jobs[0]->holiday;
                        if (!$cast->shop->jobs[0]->holiday_hosoku == '') {
                          $holiday = $holiday.="</br>".$cast->shop->jobs[0]->holiday_hosoku;
                        }
                        echo ($holiday);
                      } else { echo ('-'); } ?>
            </td>
          </tr>
            <th align="center">待遇</th>
            <td>
              <?php if(!$cast->shop->jobs[0]->treatment == '') { ?>
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
            <td><?php if(!$cast->shop->jobs[0]->pr == '') {
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
            <td><?php if(!$cast->shop->jobs[0]->tel1 == '') {
              echo ($cast->shop->jobs[0]->tel1);
            } else {echo ('-');} ?>
            </td>
          </tr>
          <tr>
            <th align="center">連絡先2</th>
            <td><?php if(!$cast->shop->jobs[0]->tel2 == '') {
              echo ($cast->shop->jobs[0]->tel2);
            } else {echo ('-');} ?>
            </td>
          </tr>
          <tr>
            <th align="center">メール</th>
            <td><?php if(!$cast->shop->jobs[0]->email == '') {
              echo ($cast->shop->jobs[0]->email);
            } else {echo ('-');} ?>
            </td>
          </tr>
          <tr>
            <th align="center">LINE ID</th>
            <td><?php if(!$cast->shop->jobs[0]->lineid == '') {
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
<?= $this->element('photoSwipe'); ?>
