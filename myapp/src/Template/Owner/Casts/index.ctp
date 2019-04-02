<?php
/**
* @var \App\View\AppView $this
* @var \App\Model\Entity\Owner[]|\Cake\Collection\CollectionInterface $owners
*/
?>
<div class="container">
  <?= $this->Flash->render() ?>
  <h5><?= __('キャストトップページ') ?></h5>
  <?php foreach ($cast as $castRow): ?>
  <!-- <div class="row">
    <div class="col s12 m4 l4">
      <div class="card horizontal">
        <div class="card-image">
          <img src="/img/common/top/top1.jpg">
        </div>
        <div class="card-stacked">
          <div class="card-content">
            <p>I am a very simple card. I am good at containing small bits of information.</p>
          </div>
          <div class="card-action">
            <a href="#">This is a link</a>
          </div>
        </div>
      </div>
    </div>
    <div class="col s12 m4 l4">
      <div class="card horizontal">
        <div class="card-image">
          <img src="/img/common/top/top1.jpg">
        </div>
        <div class="card-stacked">
          <div class="card-content">
            <p>I am a very simple card. I am good at containing small bits of information.</p>
          </div>
          <div class="card-action">
            <a href="#">This is a link</a>
          </div>
        </div>
      </div>
    </div>
    <div class="col s12 m4 l4">
      <div class="card horizontal">
        <div class="card-image">
          <img src="/img/common/top/top1.jpg">
        </div>
        <div class="card-stacked">
          <div class="card-content">
            <p>I am a very simple card. I am good at containing small bits of information.</p>
          </div>
          <div class="card-action">
            <a href="#">This is a link</a>
          </div>
        </div>
      </div>
    </div>
  </div> -->

  <div id="cast" class="row">
    <div class="col s12 m4 l4">
      <div class="card">
        <div class="card-image">
          <img src="/img/common/top/top1.jpg">
          <span class="card-title">日記の投稿数</span>
          <a class="btn-floating halfway-fab waves-effect waves-light red"><i class="material-icons">mode_edit</i></a>
        </div>
        <div class="card-content">
          <p>日記の投稿数</p>
        </div>
      </div>
    </div>
    <div class="col s12 m4 l4">
      <div class="card">
        <div class="card-image">
          <img src="/img/common/top/top1.jpg">
          <span class="card-title">出勤率</span>
          <a class="btn-floating halfway-fab waves-effect waves-light red"><i class="material-icons">directions_run</i></a>
        </div>
        <div class="card-content">
          <p>出勤率</p>
        </div>
      </div>
    </div>
    <div class="col s12 m4 l4">
      <div class="card">
        <div class="card-image">
          <img src="/img/common/top/top1.jpg">
          <span class="card-title">いいねの数</span>
          <a class="btn-floating halfway-fab waves-effect waves-light red"><i class="material-icons">favorite_border</i></a>
        </div>
        <div class="card-content">  
          <p>いいねの数</p>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col s12 m6 l6">
      <div class="card">
        <div class="card-image">
          <img src="/img/common/top/top1.jpg">
          <span class="card-title">Card Title</span>
          <a class="btn-floating halfway-fab waves-effect waves-light red"><i class="material-icons">mode_edit</i></a>
        </div>
        <div class="card-content">
          <div id="show-job">
              <div style="display:none;">
                <input type="hidden" name="job_copy" value='<?=$castRow->job ?>'>
                <input type="hidden" name="treatment_hidden" value=''>
              </div>
              <table class="bordered shop-table z-depth-2" border="1">
              <div class="progress">
                  <div class="determinate" style="width: 70%"></div>
              </div>                  <span class="right">プロフィールの入力率：70%</span>

              <tr>
                <th align="center">店舗名</th>
                <td class="show-job-name"><?php if(!$castRow->name == '') {
                  echo ($castRow->name);
                } else {echo ('登録されていません。');}?>
                </td>
              </tr>
              <tr>
                <th align="center">業種</th>
                <td>
                  <?php if(!$castRow->name == '') {
                          echo ($this->Text->autoParagraph($castRow->name));
                        } else {echo ('登録されていません。');} ?>
                </td>
              </tr>
              <tr>
                <th align="center">職種</th>
                <td>
                  <?php if(!$castRow->name == '') {
                          echo ($this->Text->autoParagraph($castRow->name));
                        } else {echo ('登録されていません。');} ?>
                </td>
              </tr>
              <th align="center">休日</th>
                <td><?php if(!$castRow->name == '') {
                            $holiday = $castRow->name;
                            if (!$castRow->name == '') {
                              $holiday = $holiday.="</br>".$castRow->name;
                            }
                            echo ($holiday);
                          } else { echo ('登録されていません。'); } ?>
                </td>
              </tr>
                <th align="center">待遇</th>
                <td>
                  <?php if(!$castRow->joname == '') { ?>
                    <?php $array =explode(',', $castRow->name); ?>
                    <?php for ($i = 0; $i < count($array); $i++) { ?>
                    <div class="chip" name=""id="<?=$array[$i]?>" value="<?=$array[$i]?>"><?=$array[$i]?></div>
                    </div>
                    <?php } ?>
                  <?php } else {echo ('登録されていません。');} ?>
                </td>
              </tr>
              <tr>
                <th align="center">連絡先1</th>
                <td><?php if(!$castRow->name == '') {
                  echo ($castRow->name);
                } else {echo ('登録されていません。');} ?>
                </td>
              </tr>
              <tr>
                <th align="center">連絡先2</th>
                <td><?php if(!$castRow->name == '') {
                  echo ($castRow->name);
                } else {echo ('登録されていません。');} ?>
                </td>
              </tr>
              <tr>
                <th align="center">メール</th>
                <td><?php if(!$castRow->email == '') {
                  echo ($castRow->email);
                } else {echo ('登録されていません。');} ?>
                </td>
              </tr>
              <tr>
                <th align="center">LINEID</th>
                <td><?php if(!$castRow->name == '') {
                  echo ($castRow->name);
                } else {echo ('登録されていません。');} ?>
                </td>
              </tr>
              <tr>
                <th align="center">PR</th>
                <td><?php if(!$castRow->name == '') {
                  echo ($castRow->name);
                } else {echo ('登録されていません。');}?>
                </td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="col s12 m6 l6">
      <div id="calendar"></div>
    </div>
    <!-- <div class="row">
            <div class="col s5 m4 l3"><input id="from-time" class="timepicker" name="from_time"></div>
            <div class="col s2 m1 l1 center-align">～</div>
            <div class="col s5 m4 l3"><input id="to-time" class="timepicker" name="to_time"></div>
          </div>
  </div> -->
</div>
<?php endforeach; ?>
</div>
