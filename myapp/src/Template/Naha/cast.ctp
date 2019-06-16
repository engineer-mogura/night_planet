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
  <?php foreach ($cast as $row): ?>
  <div class="row">
    <div id="cast-main" class="col s12 m12 l8">
      <img class="responsive-img" width="100%" src=<?php if($row->image1 != '') {
        echo($infoArray['dir_path'].PATH_ROOT['CAST'].DS.$row->dir.DS.PATH_ROOT['IMAGE'].DS.$row->image1);} else {
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
      <h5 class="left-align"><?php if($row->nickname != '') {
        echo($row->nickname);} else {
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
            <?php if(count($row->shop->coupons) > 0) { ?>
            <?php foreach($row->shop->coupons as $coupon): ?>
              <div class="collapsible-body orange lighten-4">
                <span><?= $this->Time->format($coupon->from_day, 'Y/M/d') ?>～<?= $this->Time->format($coupon->to_day, 'Y/M/d') ?></span><br />
                  <span>★☆★<?=$coupon->title ?>★☆★<br />
                <?=$coupon->content ?><br />
              <?php if($coupon === end($row->shop->coupons)){echo ('こちらの画面をお店側に見せ、使用するクーポンをお知らせください。');}?></span>
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
                <th class="table-header" colspan="2" align="center"><?= h($row->nickname);?></th>
              </tr>
              <tr>
                <th align="center">誕生日</th>
                <td><?=!$row->isEmpty("birthday") ? $this->Time->format($row->birthday, 'M/d'):"-" ?></td>
              </tr>
              <tr>
                <th align="center">星座</th>
                <td><?=!$row->isEmpty("constellation") ? CONSTELLATION[$row->constellation]['label']:"-" ?></td>
              </tr>
              <tr>
                <th align="center">血液型</th>
                <td><?=!$row->isEmpty("blood_type") ? BLOOD_TYPE[$row->blood_type]['label']:"-" ?></td>
              </tr>
              <tr>
                <th align="center">メッセージ</th>
                <td class="left-align"><?=!$row->isEmpty("message") ? $this->Text->autoParagraph($row->message):"-" ?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="row gallery-list">
        <div class="or-header-wrap card-panel red lighten-3">
          <span class="or-header"><?=$row->nickname?>さんのギャラリー</span>
        </div>
        <?php
        $isGalleryExists = false;
        foreach ($imageCol as $key => $value) {
          if(!$row->isEmpty($value)) { ?>
            <div class="col s4 m4 l3">
              <img class="materialboxed" data-caption="店内の様子" width="100%" src="<?=$infoArray['dir_path'].PATH_ROOT['CAST'].DS.$row->dir.DS.PATH_ROOT['IMAGE'].DS.$row->get($value)?>">
            </div>
            <?php $isGalleryExists = true;
          }
        }?>
        <?= $isGalleryExists ? "" : '<p class="col">ギャラリーの登録はありません。</p>';?>
      </div>
      <div class="row diary-list">
        <div class="or-header-wrap card-panel red lighten-3">
          <span class="or-header"><?=$row->nickname?>さんの日記</span>
        </div>
        <?php if (count($row->diarys) > 0) { ?>
          <div class="card diary-card">
        <?php foreach ($dImgCol as $key => $value) { ?>
        <?= $value == reset($dImgCol) ?'<div class="diary-image">':""?>
            <div class="col <?=(count($dImgCol)==1?'s12 m12 l12':(count($dImgCol)==2?'s6 m6 l6':'s4 m4 l4'))?>">
              <img class="materialboxed" data-caption="店内の様子" width="100%" src="<?=$infoArray['dir_path'].PATH_ROOT['CAST'].DS.$row->dir.DS.PATH_ROOT['DIARY'].DS.$row->diarys[0]->dir.DS.$row->diarys[0]->get($value)?>">
            </div>
        <?= $value == end($dImgCol) ?'</div>':""?>
        <?php } ?>
            <div class="card-content">
            <p class="right-align"><?=$row->diarys[0]->ymd_created?></p>
            <p class="title">
              <?=$row->diarys[0]->title?>
            </p>
              <p class="content"><?=$this->Text->autoParagraph($row->diarys[0]->content)?></p>
            </div>
            <div class="card-action like-field">
              <div class="row">
                <div class="col s6 m4 l4"><span class="btn-floating waves-effect waves-green btn-flat blue">
                  <i class="material-icons">thumb_up</i></span><span class="like-field-span like-count"><?=count($row->diarys[0]->likes)?></span>
                </div>
                <div class="col s6 m4 l4"><a class="btn-floating waves-effect waves-green btn-flat red">
                  <i class="material-icons">thumb_up</i></a><span class="like-field-span">LIKE?</span>
                </div>
                <div class="col s6 m4 l4">
                  <a href="/naha/diary/<?=$row->id."?area=".$row->shop->area."&genre=".$row->shop->genre.
                    "&shop=".$row->shop->id."&name=".$row->shop->name."&cast=".$row->id."&nickname=".$row->nickname?>"
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
              <th class="table-header" colspan="2" align="center"><?= h($row->shop->name);?></th>
            </tr>
            <tr>
              <th align="center">所在地</th>
              <td><?= h($row->shop->pref21.$row->shop->addr21.$row->shop->strt21);?></td>
            </tr>
            <tr>
              <th align="center">連絡先</th>
              <td><?=h($row->shop->tel);?></td>
            </tr>
            <tr>
              <th align="center">営業時間</th>
              <td><?php if((!$row->shop->bus_from_time == '')
                            && (!$row->shop->bus_to_time == '')
                            && (!$row->shop->bus_hosoku == '')) {
                              $busTime = $this->Time->format($row->shop->bus_from_time, 'HH:mm')
                              ."～".$this->Time->format($row->shop->bus_to_time, 'HH:mm')
                              ."</br>".$row->shop->bus_hosoku;
                              echo ($busTime);
                            } else { echo ('-'); } ?></td>
            </tr>
            <tr>
              <th align="center">スタッフ</th>
              <td><?=h($row->shop->staff);?></td>
            </tr>
            <tr>
              <th align="center" valign="top">システム</th>
              <td><?=h($row->shop->system);?></td>
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
                <td><?php if(!$row->shop->credit == '') { ?>
                      <?php $array =explode(',', $row->shop->credit); ?>
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
            <th  class="table-header" colspan="2" align="center"><?php if(!$row->shop->name == '') {
              echo ($row->shop->name);
            } else {echo ('-');}?></th>
          </tr>
          <tr>
            <th align="center">業種</th>
            <td>
              <?php if(!$row->shop->job->industry == '') {
                      echo ($this->Text->autoParagraph($row->shop->job->industry));
                    } else {echo ('-');} ?>
            </td>
          </tr>
          <tr>
            <th align="center">職種</th>
            <td>
              <?php if(!$row->shop->job->job_type == '') {
                      echo ($this->Text->autoParagraph($row->shop->job->job_type));
                    } else {echo ('-');} ?>
            </td>
          </tr>
          <th align="center">時間</th>
            <td><?php if((!$row->shop->job->work_from_time == '')
                      && (!$row->shop->job->work_to_time == '')) {
                        $workTime = $this->Time->format($row->shop->job->work_from_time, 'HH:mm')
                        ."～".$this->Time->format($row->shop->job->work_to_time, 'HH:mm');
                        if (!$row->shop->job->work_time_hosoku == '') {
                          $workTime = $workTime.="</br>".$row->shop->job->work_time_hosoku;
                        }
                        echo ($workTime);
                      } else { echo ('-'); } ?>
            </td>
          </tr>
          <th align="center">資格</th>
          <td><?php if((!$row->shop->job->from_age == '')
                      && (!$row->shop->job->to_age == '')) {
                        $qualification = $row->shop->job->from_age."歳～".$row->shop->job->to_age."歳くらいまで";
                        if (!$row->shop->job->qualification_hosoku == '') {
                          $qualification = $qualification.="</br>".$row->shop->job->qualification_hosoku;
                        }
                        echo ($qualification);
                      } else { echo ('-'); } ?>
            </td>
          </tr>
          <th align="center">休日</th>
            <td><?php if(!$row->shop->job->holiday == '') {
                        $holiday = $row->shop->job->holiday;
                        if (!$row->shop->job->holiday_hosoku == '') {
                          $holiday = $holiday.="</br>".$row->shop->job->holiday_hosoku;
                        }
                        echo ($holiday);
                      } else { echo ('-'); } ?>
            </td>
          </tr>
            <th align="center">待遇</th>
            <td>
              <?php if(!$row->shop->job->treatment == '') { ?>
                <?php $array =explode(',', $row->shop->job->treatment); ?>
                <?php for ($i = 0; $i < count($array); $i++) { ?>
                <div class="chip" name=""id="<?=$array[$i]?>" value="<?=$array[$i]?>"><?=$array[$i]?></div>
                </div>
                <?php } ?>
              <?php } else {echo ('-');} ?>
            </td>
          </tr>
          <tr>
            <th align="center">PR</th>
            <td><?php if(!$row->shop->job->pr == '') {
              echo ($row->shop->job->pr);
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
            <td><?php if(!$row->shop->job->tel1 == '') {
              echo ($row->shop->job->tel1);
            } else {echo ('-');} ?>
            </td>
          </tr>
          <tr>
            <th align="center">連絡先2</th>
            <td><?php if(!$row->shop->job->tel2 == '') {
              echo ($row->shop->job->tel2);
            } else {echo ('-');} ?>
            </td>
          </tr>
          <tr>
            <th align="center">メール</th>
            <td><?php if(!$row->shop->job->email == '') {
              echo ($row->shop->job->email);
            } else {echo ('-');} ?>
            </td>
          </tr>
          <tr>
            <th align="center">LINE ID</th>
            <td><?php if(!$row->shop->job->lineid == '') {
              echo ($row->shop->job->lineid);
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
