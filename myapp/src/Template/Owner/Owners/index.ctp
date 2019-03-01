<?php
/**
* @var \App\View\AppView $this
* @var \App\Model\Entity\Owner[]|\Cake\Collection\CollectionInterface $owners
*/
?>

<div class="container">
  <?= $this->Flash->render() ?>
  <h5><?= __('店舗トップページ') ?></h5>
  <div class="row">
    <div class="col s12 m12 l8">
      <img class="responsive-img" width="100%" src=<?php if($shop->shop->top_image != '') {
        echo('/'.$infoArray['dir_path'].$shop->shop->top_image);} else {
        echo("/img/common/top/top1.jpg");} ?> />
      <div class="fixed-action-btn horizontal click-to-toggle">
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
      <h5 class="left-align"><?php if($shop->shop->name != '') {
        echo($shop->shop->name);} else {
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
      <div class="description">
        <?php if($shop->shop->catch != ''){
          echo ($this->Text->autoParagraph($shop->shop->catch)); } else {
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
            <?php if(count($shop->shop->coupons) > 0) { ?>
            <?php foreach($shop->shop->coupons as $coupon): ?>
              <div class="collapsible-body orange lighten-4">
                <span><?= $this->Time->format($coupon->from_day, 'Y/M/d') ?>～<?= $this->Time->format($coupon->to_day, 'Y/M/d') ?></span><br />
                  <span>★☆★<?=$coupon->title ?>★☆★<br />
                <?=$coupon->content ?><br />
              <?php if($coupon === end($shop->shop->coupons)){echo ('こちらの画面をお店側に見せ、使用するクーポンをお知らせください。');}?></span>
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
              <th class="table-header" colspan="2" align="center">ラウンジ美月 宮古島店</th>
            </tr>
            <tr>
              <th align="center">所在地</th>
              <td>〒906-0012 沖縄県宮古島市平良字西里171</td>
            </tr>
            <tr>
              <th align="center">連絡先</th>
              <td>TEL. 0980-79-0257</td>
            </tr>
            <tr>
              <th align="center">営業時間</th>
              <td>20：00 ～ LAST ※日曜日も営業しております。</td>
            </tr>
            <tr>
              <th align="center">スタッフ</th>
              <td>全国各地から集まった20歳～30歳の明るい女のコ多数</td>
            </tr>
            <tr>
              <th align="center" valign="top">システム</th>
              <td>時間制 1時間飲み放題<br>
                お一人様（税・サービス料込）<br>
                ￥3,000（3名様より）￥4,000（2名様）￥6,000（1名様）<br>
              ★ＶＩＰルーム、カラオケ完備</td>
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
                <td>MasterCard<br>
                  VISA<br>
                JCB</td>
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
            <th  class="table-header" colspan="2" align="center">ラウンジ美月 宮古島店</th>
          </tr>
          <tr>
            <th align="center">職種</th>
            <td>スナック・パブ・ラウンジ 【アルバイト・パート】フロアレディ・カウンターレディ(ナイトワーク系)</td>
          </tr>
          <tr>
            <th align="center">給与</th>
            <td>日払い週払い高収入給与手渡し昇給あり 【アルバイト・パート】時給3,000円～<br>
              ★経験者優遇します！<br>
              ★日払いOK！<br>
            ★昇給あり</td>
          </tr>
          <tr>
            <th align="center">勤務時間</th>
            <td>シフト相談 週/シフト～4h/日9時～OK10時～OK残業なし週1縲廾K週2・3縲廾K週4縲廾K夏(冬)休み限【アルバイト・パート】19:30～00:00<br>
              週１日・１日3h～OK！<br>
            時間・曜日はお気軽にご相談ください♪</td>
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
            <th align="center">TEL1</th>
            <td>0980-72-XXXX</td>
          </tr>
          <tr>
            <th align="center">TEL2</th>
            <td>090-XXXX-XXXX</td>
          </tr>
          <tr>
            <th align="center">MAIL</th>
            <td>XXXXX@gmail.com</td>
          </tr>
          <tr>
            <th align="center">LINE</th>
            <td>LINEIDXXXXXXXX</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
</div>
