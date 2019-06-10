<?php
/**
* @var \App\View\AppView $this
* @var \App\Model\Entity\Owner[]|\Cake\Collection\CollectionInterface $owners
*/
?>
<div class="container">
  <?= $this->Flash->render() ?>
  <h5><?= __('店舗トップページ') ?></h5>
  <?php foreach ($owner as $ownerRow): ?>
  <div class="row">
    <div class="col s12 m12 l8">
      <img class="responsive-img" width="100%" src=<?php if($ownerRow->shop->top_image != '') {
        echo(DS.$infoArray['dir_path'].$ownerRow->shop->top_image);} else {
        echo("/img/common/top/top1.jpg");} ?> />
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
      <h5 class="left-align"><?php if($ownerRow->shop->name != '') {
        echo($ownerRow->shop->name);} else {
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
        <?php if($ownerRow->shop->catch != ''){
          echo ($this->Text->autoParagraph($ownerRow->shop->catch)); } else {
          echo ('キャッチコピーを決めてください。以下はサンプルです。<br />宮古島のキャバクラをお探しならラウンジ美月へ。<br />
      宮古島最大級のキャストと楽しむヒトトキ。時間制・飲み放題で安心のキャバクラです。');} ?>
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
            <?php if(count($ownerRow->shop->coupons) > 0) { ?>
            <?php foreach($ownerRow->shop->coupons as $coupon): ?>
              <div class="collapsible-body orange lighten-4">
                <span><?= $this->Time->format($coupon->from_day, 'Y/M/d') ?>～<?= $this->Time->format($coupon->to_day, 'Y/M/d') ?></span><br />
                  <span>★☆★<?=$coupon->title ?>★☆★<br />
                <?=$coupon->content ?><br />
              <?php if($coupon === end($ownerRow->shop->coupons)){echo ('こちらの画面をお店側に見せ、使用するクーポンをお知らせください。');}?></span>
              </div>
            <?php endforeach; ?>
            <?php } else { ?>
              <div class="collapsible-body orange lighten-4">
                <p>クーポンの登録はありません。</p>
              </div>
            <?php } ?>

        </li>
      </ul>
      <div class="row">
        <div class="or-header-wrap card-panel red lighten-3">
          <span class="or-header">キャスト</span>
        </div>
        <div class="col s12 m4 l3">
          <div class="card">
            <div class="card-image">
              <img src="/img/common/top/top1.jpg">
              <a class="btn-floating halfway-fab waves-effect waves-light red" onclick="Materialize.toast('Name1さんにいいねしました', 4000)"><i class="material-icons">thumb_up</i></a>
            </div>
            <div class="card-content">
              <span>Name1</span>
              <p>キャストからのメッセージ...</p>
            </div>
          </div>
        </div>
        <div class="col s12 m4 l3">
          <div class="card">
            <div class="card-image">
              <img src="/img/common/top/top1.jpg">
              <a class="btn-floating halfway-fab waves-effect waves-light red" onclick="Materialize.toast('Name1さんにいいねしました', 4000)"><i class="material-icons">thumb_up</i></a>
            </div>
            <div class="card-content">
              <span>Name1</span>
              <p>キャストからのメッセージ...</p>
            </div>
          </div>
        </div>
        <div class="col s12 m4 l3">
          <div class="card">
            <div class="card-image">
              <img src="/img/common/top/top1.jpg">
              <a class="btn-floating halfway-fab waves-effect waves-light red" onclick="Materialize.toast('Name1さんにいいねしました', 4000)"><i class="material-icons">thumb_up</i></a>
            </div>
            <div class="card-content">
              <span>Name1</span>
              <p>キャストからのメッセージ...</p>
            </div>
          </div>
        </div>
        <div class="col s12 m4 l3">
          <div class="card">
            <div class="card-image">
              <img src="/img/common/top/top1.jpg">
              <a class="btn-floating halfway-fab waves-effect waves-light red" onclick="Materialize.toast('Name1さんにいいねしました', 4000)"><i class="material-icons">thumb_up</i></a>
            </div>
            <div class="card-content">
              <span>Name1</span>
              <p>キャストからのメッセージ...</p>
            </div>
          </div>
        </div>
        <div class="col s12 m4 l3">
          <div class="card">
            <div class="card-image">
              <img src="/img/common/top/top1.jpg">
              <a class="btn-floating halfway-fab waves-effect waves-light red" onclick="Materialize.toast('Name1さんにいいねしました', 4000)"><i class="material-icons">thumb_up</i></a>
            </div>
            <div class="card-content">
              <span>Name1</span>
              <p>キャストからのメッセージ...</p>
            </div>
          </div>
        </div>
        <div class="col s12 m4 l3">
          <div class="card">
            <div class="card-image">
              <img src="/img/common/top/top1.jpg">
              <a class="btn-floating halfway-fab waves-effect waves-light red" onclick="Materialize.toast('Name1さんにいいねしました', 4000)"><i class="material-icons">thumb_up</i></a>
            </div>
            <div class="card-content">
              <span>Name1</span>
              <p>キャストからのメッセージ...</p>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="or-header-wrap card-panel red lighten-3">
          <span class="or-header">店舗情報</span>
        </div>
        <div class="col s12 m6 l6">
         <table class="bordered shop-table z-depth-2" border="1">
          <tbody>
            <tr>
              <th class="table-header" colspan="2" align="center"><?= h($ownerRow->shop->name);?></th>
            </tr>
            <tr>
              <th align="center">所在地</th>
              <td><?= h($ownerRow->shop->pref21.$ownerRow->shop->addr21.$ownerRow->shop->strt21);?></td>
            </tr>
            <tr>
              <th align="center">連絡先</th>
              <td><?=h($ownerRow->shop->tel);?></td>
            </tr>
            <tr>
              <th align="center">営業時間</th>
              <td><?php if((!$ownerRow->shop->bus_from_time == '')
                            && (!$ownerRow->shop->bus_to_time == '')
                            && (!$ownerRow->shop->bus_hosoku == '')) {
                              $busTime = $this->Time->format($ownerRow->shop->bus_from_time, 'HH:mm')
                              ."～".$this->Time->format($ownerRow->shop->bus_to_time, 'HH:mm')
                              ."</br>".$ownerRow->shop->bus_hosoku;
                              echo ($busTime);
                            } else { echo ('-'); } ?></td>
            </tr>
            <tr>
              <th align="center">スタッフ</th>
              <td><?=h($ownerRow->shop->staff);?></td>
            </tr>
            <tr>
              <th align="center" valign="top">システム</th>
              <td><?=h($ownerRow->shop->system);?></td>
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
                <td><?php if(!$ownerRow->shop->credit == '') { ?>
                      <?php $array =explode(',', $ownerRow->shop->credit); ?>
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
      <div class="or-header-wrap card-panel red lighten-3">
        <span class="or-header">店内</span>
      </div>
      <div class="shop-image col s12 m4 l3">
        <img class="materialboxed" data-caption="店内の様子" width="100%" src="/img/common/top/top1.jpg">
      </div>
      <div class="shop-image col s12 m4 l3">
        <img class="materialboxed" data-caption="店内の様子" width="100%" src="/img/common/top/top1.jpg">
      </div>
      <div class="shop-image col s12 m4 l3">
        <img class="materialboxed" data-caption="店内の様子" width="100%" src="/img/common/top/top1.jpg">
      </div>
      <div class="shop-image col s12 m4 l3">
        <img class="materialboxed" data-caption="店内の様子" width="100%" src="/img/common/top/top1.jpg">
      </div>
      <div class="shop-image col s12 m4 l3">
        <img class="materialboxed" data-caption="店内の様子" width="100%" src="/img/common/top/top1.jpg">
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
  <div class="col s12 m12 l4">
    <div class="card-panel red lighten-3">
      <span class="or-header">求人情報</span>
    </div>
    <div class="col s12 m6 l12">
      <table class="bordered shop-table z-depth-2" border="1">
        <tbody>
          <tr>
          <tr>
            <th  class="table-header" colspan="2" align="center"><?php if(!$ownerRow->shop->name == '') {
              echo ($ownerRow->shop->name);
            } else {echo ('-');}?></th>
          </tr>
          <tr>
            <th align="center">業種</th>
            <td>
              <?php if(!$ownerRow->shop->job->industry == '') {
                      echo ($this->Text->autoParagraph($ownerRow->shop->job->industry));
                    } else {echo ('-');} ?>
            </td>
          </tr>
          <tr>
            <th align="center">職種</th>
            <td>
              <?php if(!$ownerRow->shop->job->job_type == '') {
                      echo ($this->Text->autoParagraph($ownerRow->shop->job->job_type));
                    } else {echo ('-');} ?>
            </td>
          </tr>
          <th align="center">時間</th>
            <td><?php if((!$ownerRow->shop->job->work_from_time == '')
                      && (!$ownerRow->shop->job->work_to_time == '')) {
                        $workTime = $this->Time->format($ownerRow->shop->job->work_from_time, 'HH:mm')
                        ."～".$this->Time->format($ownerRow->shop->job->work_to_time, 'HH:mm');
                        if (!$ownerRow->shop->job->work_time_hosoku == '') {
                          $workTime = $workTime.="</br>".$ownerRow->shop->job->work_time_hosoku;
                        }
                        echo ($workTime);
                      } else { echo ('-'); } ?>
            </td>
          </tr>
          <th align="center">資格</th>
          <td><?php if((!$ownerRow->shop->job->from_age == '')
                      && (!$ownerRow->shop->job->to_age == '')) {
                        $qualification = $ownerRow->shop->job->from_age."歳～".$ownerRow->shop->job->to_age."歳くらいまで";
                        if (!$ownerRow->shop->job->qualification_hosoku == '') {
                          $qualification = $qualification.="</br>".$ownerRow->shop->job->qualification_hosoku;
                        }
                        echo ($qualification);
                      } else { echo ('-'); } ?>
            </td>
          </tr>
          <th align="center">休日</th>
            <td><?php if(!$ownerRow->shop->job->holiday == '') {
                        $holiday = $ownerRow->shop->job->holiday;
                        if (!$ownerRow->shop->job->holiday_hosoku == '') {
                          $holiday = $holiday.="</br>".$ownerRow->shop->job->holiday_hosoku;
                        }
                        echo ($holiday);
                      } else { echo ('-'); } ?>
            </td>
          </tr>
            <th align="center">待遇</th>
            <td>
              <?php if(!$ownerRow->shop->job->treatment == '') { ?>
                <?php $array =explode(',', $ownerRow->shop->job->treatment); ?>
                <?php for ($i = 0; $i < count($array); $i++) { ?>
                <div class="chip" name=""id="<?=$array[$i]?>" value="<?=$array[$i]?>"><?=$array[$i]?></div>
                </div>
                <?php } ?>
              <?php } else {echo ('-');} ?>
            </td>
          </tr>
          <tr>
            <th align="center">PR</th>
            <td><?php if(!$ownerRow->shop->job->pr == '') {
              echo ($ownerRow->shop->job->pr);
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
            <td><?php if(!$ownerRow->shop->job->tel1 == '') {
              echo ($ownerRow->shop->job->tel1);
            } else {echo ('-');} ?>
            </td>
          </tr>
          <tr>
            <th align="center">連絡先2</th>
            <td><?php if(!$ownerRow->shop->job->tel2 == '') {
              echo ($ownerRow->shop->job->tel2);
            } else {echo ('-');} ?>
            </td>
          </tr>
          <tr>
            <th align="center">メール</th>
            <td><?php if(!$ownerRow->shop->job->email == '') {
              echo ($ownerRow->shop->job->email);
            } else {echo ('-');} ?>
            </td>
          </tr>
          <tr>
            <th align="center">LINE ID</th>
            <td><?php if(!$ownerRow->shop->job->lineid == '') {
              echo ($ownerRow->shop->job->lineid);
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
