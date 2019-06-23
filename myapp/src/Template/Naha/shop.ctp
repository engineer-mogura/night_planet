<?php
/**
* @var \App\View\AppView $this
* @var \App\Model\Entity\shop[]|\Cake\Collection\CollectionInterface $owners
*/
?>
<div id="shop" class="container">
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
  <?php foreach ($shop as $row): ?>
  <div class="row">
    <div id="shop-main" class="col s12 m12 l8">
      <img class="responsive-img" width="100%" src=<?php if($row->top_image != '') {
        echo($infoArray['dir_path'].$row->top_image);} else {
        echo("/img/common/top/top1.jpg");} ?> />
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
      <h5 class="left-align"><?php if($row->name != '') {
        echo($row->name);} else {
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
      <div class="card-panel light-blue accent-1">
        <?php if($row->catch != ''){
          echo ($this->Text->autoParagraph($row->catch)); } else {
          echo ('キャッチコピーがありません。');} ?>
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
            <?php if(count($row->coupons) > 0) { ?>
            <?php foreach($row->coupons as $coupon): ?>
              <div class="collapsible-body orange lighten-4">
                <span><?= $this->Time->format($coupon->from_day, 'Y/M/d') ?>～<?= $this->Time->format($coupon->to_day, 'Y/M/d') ?></span><br />
                  <span>★☆★<?=$coupon->title ?>★☆★<br />
                <?=$coupon->content ?><br />
              <?php if($coupon === end($row->coupons)){echo ('こちらの画面をお店側に見せ、使用するクーポンをお知らせください。');}?></span>
              </div>
            <?php endforeach; ?>
            <?php } else { ?>
              <div class="collapsible-body orange lighten-4">
                <p>クーポンの登録はありません。</p>
              </div>
            <?php } ?>

        </li>
      </ul>
      <div class="row cast-list">
        <div class="or-header-wrap card-panel red lighten-3">
          <span class="or-header">キャスト</span>
        </div>
        <?php if(count($row->casts) > 0) { ?>
            <?php foreach($row->casts as $cast): ?>
            <div class="col s4 m3 l3">
              <div>
                <a href="<?=DS.$row['area'].DS.PATH_ROOT['CAST'].DS.$cast['id']."?genre=".$row['genre']."&name=".$row['name']."&shop=".$row['id']."&nickname=".$cast['nickname']?>">
                  <img src="<?=isset($cast->image1) ? $infoArray['dir_path'].PATH_ROOT['CAST'].DS.$cast->dir.DS.PATH_ROOT['IMAGE'].DS.$cast->image1:PATH_ROOT['NO_IMAGE02'] ?>" alt="" class="circle" width="80" height="80">
                </a>
                </div>
              <h6><?=$cast->nickname?></h6>
            </div>
            <?php endforeach; ?>
        <?php } else { ?>
            <p>キャストの登録はありません。</p>
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
              <th class="table-header" colspan="2" align="center"><?= h($row->name);?></th>
            </tr>
            <tr>
              <th align="center">所在地</th>
              <td><?= h($row->pref21.$row->addr21.$row->strt21);?></td>
            </tr>
            <tr>
              <th align="center">連絡先</th>
              <td><?=h($row->tel);?></td>
            </tr>
            <tr>
              <th align="center">営業時間</th>
              <td><?php if((!$row->bus_from_time == '')
                            && (!$row->bus_to_time == '')
                            && (!$row->bus_hosoku == '')) {
                              $busTime = $this->Time->format($row->bus_from_time, 'HH:mm')
                              ."～".$this->Time->format($row->bus_to_time, 'HH:mm')
                              ."</br>".$row->bus_hosoku;
                              echo ($busTime);
                            } else { echo ('-'); } ?></td>
            </tr>
            <tr>
              <th align="center">スタッフ</th>
              <td><?=h($row->staff);?></td>
            </tr>
            <tr>
              <th align="center" valign="top">システム</th>
              <td><?=h($row->system);?></td>
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
                <td><?php if(!$row->credit == '') { ?>
                      <?php $array =explode(',', $row->credit); ?>
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
      <div class="my-gallery">
        <figure class="col s4 m4 l3">
          <a href="/img/common/top/top1.jpg" data-size="800x600"><img width="100%" src="/img/common/top/top1.jpg" alt="写真の説明でーす。" /></a>
        </figure>
        <figure class="col s4 m4 l3">
          <a href="/img/common/top/top2.jpg" data-size="800x600"><img width="100%" src="/img/common/top/top2.jpg" alt="写真の説明でーす。" /></a>
        </figure>
        <figure class="col s4 m4 l3">
          <a href="/img/common/top/top3.jpg" data-size="800x600"><img width="100%" src="/img/common/top/top3.jpg" alt="写真の説明でーす。" /></a>
        </figure>
        <figure class="col s4 m4 l3">
          <a href="/img/common/top/top1.jpg" data-size="800x600"><img width="100%" src="/img/common/top/top1.jpg" alt="写真の説明でーす。" /></a>
        </figure>
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
            <th  class="table-header" colspan="2" align="center"><?php if(!$row->name == '') {
              echo ($row->name);
            } else {echo ('-');}?></th>
          </tr>
          <tr>
            <th align="center">業種</th>
            <td>
              <?php if(!$row->job->industry == '') {
                      echo ($this->Text->autoParagraph($row->job->industry));
                    } else {echo ('-');} ?>
            </td>
          </tr>
          <tr>
            <th align="center">職種</th>
            <td>
              <?php if(!$row->job->job_type == '') {
                      echo ($this->Text->autoParagraph($row->job->job_type));
                    } else {echo ('-');} ?>
            </td>
          </tr>
          <th align="center">時間</th>
            <td><?php if((!$row->job->work_from_time == '')
                      && (!$row->job->work_to_time == '')) {
                        $workTime = $this->Time->format($row->job->work_from_time, 'HH:mm')
                        ."～".$this->Time->format($row->job->work_to_time, 'HH:mm');
                        if (!$row->job->work_time_hosoku == '') {
                          $workTime = $workTime.="</br>".$row->job->work_time_hosoku;
                        }
                        echo ($workTime);
                      } else { echo ('-'); } ?>
            </td>
          </tr>
          <th align="center">資格</th>
          <td><?php if((!$row->job->from_age == '')
                      && (!$row->job->to_age == '')) {
                        $qualification = $row->job->from_age."歳～".$row->job->to_age."歳くらいまで";
                        if (!$row->job->qualification_hosoku == '') {
                          $qualification = $qualification.="</br>".$row->job->qualification_hosoku;
                        }
                        echo ($qualification);
                      } else { echo ('-'); } ?>
            </td>
          </tr>
          <th align="center">休日</th>
            <td><?php if(!$row->job->holiday == '') {
                        $holiday = $row->job->holiday;
                        if (!$row->job->holiday_hosoku == '') {
                          $holiday = $holiday.="</br>".$row->job->holiday_hosoku;
                        }
                        echo ($holiday);
                      } else { echo ('-'); } ?>
            </td>
          </tr>
            <th align="center">待遇</th>
            <td>
              <?php if(!$row->job->treatment == '') { ?>
                <?php $array =explode(',', $row->job->treatment); ?>
                <?php for ($i = 0; $i < count($array); $i++) { ?>
                <div class="chip" name=""id="<?=$array[$i]?>" value="<?=$array[$i]?>"><?=$array[$i]?></div>
                </div>
                <?php } ?>
              <?php } else {echo ('-');} ?>
            </td>
          </tr>
          <tr>
            <th align="center">PR</th>
            <td><?php if(!$row->job->pr == '') {
              echo ($row->job->pr);
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
            <td><?php if(!$row->job->tel1 == '') {
              echo ($row->job->tel1);
            } else {echo ('-');} ?>
            </td>
          </tr>
          <tr>
            <th align="center">連絡先2</th>
            <td><?php if(!$row->job->tel2 == '') {
              echo ($row->job->tel2);
            } else {echo ('-');} ?>
            </td>
          </tr>
          <tr>
            <th align="center">メール</th>
            <td><?php if(!$row->job->email == '') {
              echo ($row->job->email);
            } else {echo ('-');} ?>
            </td>
          </tr>
          <tr>
            <th align="center">LINE ID</th>
            <td><?php if(!$row->job->lineid == '') {
              echo ($row->job->lineid);
            } else {echo ('-');} ?>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php endforeach; ?>
</div>
<?= $this->element('photoSwipe'); ?>
