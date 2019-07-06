<div id="wrapper">
  <div class="container">
    <span id="dummy" style="display: hidden;"></span>
    <div class="row">
      <div class="col s12 m12 l12 edit-menu">
      <!-- <?= $this->Form->hidden('activeTab',['id'=>'activeTab','value' => $activeTab]); ?> -->
        <ul class="tabs">
          <li class="tab"><a class="active" href="#top-image">トップ画像</a></li>
          <li class="tab"><a href="#catch">キャッチコピー</a></li>
          <li class="tab"><a href="#coupon">クーポン</a></li>
          <li class="tab"><a href="#cast">キャスト</a></li>
          <li class="tab"><a href="#tenpo">店舗情報</a></li>
          <li class="tab"><a href="#tenpo-gallery">店舗ギャラリー</a></li>
          <li class="tab"><a href="#map">マップ</a></li>
          <li class="tab"><a href="#job">求人情報</a></li>
        </ul>
      </div>
      <!-- トップ画像タブ -->
      <?= $this->element('shopEdit/top-image'); ?>
      <!-- キャッチコピータブ -->
      <?= $this->element('shopEdit/catch'); ?>
      <!-- クーポンタブ -->
      <?= $this->element('shopEdit/coupon'); ?>
      <!-- キャストタブ -->
      <?= $this->element('shopEdit/cast'); ?>
      <!-- 店舗情報タブ -->
      <?= $this->element('shopEdit/tenpo'); ?>
      <div id="tenpo-gallery" class="col s12">
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
        <h5>求人情報</h5>
        <div id="show-job">
          <div class="row">
            <div class="col s12 m6 l6">
              <?php foreach ($shop as $shopRow): ?>
                <div style="display:none;">
                  <input type="hidden" name="job_copy" value='<?=$shopRow->job ?>'>
                  <input type="hidden" name="treatment_hidden" value='<?=$masterCodeHidden['treatment']?>'>
                </div>
                <table class="bordered shop-table z-depth-2" border="1">
                <tr>
                  <th align="center">店舗名</th>
                  <td class="show-job-name"><?php if(!$shopRow->name == '') {
                    echo ($shopRow->name);
                  } else {echo ('登録されていません。');}?>
                  </td>
                </tr>
                <tr>
                  <th align="center">業種</th>
                  <td>
                    <?php if(!$shopRow->job->industry == '') {
                            echo ($this->Text->autoParagraph($shopRow->job->industry));
                          } else {echo ('登録されていません。');} ?>
                  </td>
                </tr>
                <tr>
                  <th align="center">職種</th>
                  <td>
                    <?php if(!$shopRow->job->job_type == '') {
                            echo ($this->Text->autoParagraph($shopRow->job->job_type));
                          } else {echo ('登録されていません。');} ?>
                  </td>
                </tr>
                <th align="center">時間</th>
                  <td><?php if((!$shopRow->job->work_from_time == '')
                            && (!$shopRow->job->work_to_time == '')) {
                              $workTime = $this->Time->format($shopRow->job->work_from_time, 'HH:mm')
                              ."～".$this->Time->format($shopRow->job->work_to_time, 'HH:mm');
                              if (!$shopRow->job->work_time_hosoku == '') {
                                $workTime = $workTime.="</br>".$shopRow->job->work_time_hosoku;
                              }
                              echo ($workTime);
                            } else { echo ('登録されていません。'); } ?>
                  </td>
                </tr>
                <th align="center">資格</th>
                <td><?php if((!$shopRow->job->from_age == '')
                            && (!$shopRow->job->to_age == '')) {
                              $qualification = $shopRow->job->from_age."歳～".$shopRow->job->to_age."歳くらいまで";
                              if (!$shopRow->job->qualification_hosoku == '') {
                                $qualification = $qualification.="</br>".$shopRow->job->qualification_hosoku;
                              }
                              echo ($qualification);
                            } else { echo ('登録されていません。'); } ?>
                  </td>
                </tr>
                <th align="center">休日</th>
                  <td><?php if(!$shopRow->job->holiday == '') {
                              $holiday = $shopRow->job->holiday;
                              if (!$shopRow->job->holiday_hosoku == '') {
                                $holiday = $holiday.="</br>".$shopRow->job->holiday_hosoku;
                              }
                              echo ($holiday);
                            } else { echo ('登録されていません。'); } ?>
                  </td>
                </tr>
                  <th align="center">待遇</th>
                  <td>
                    <?php if(!$shopRow->job->treatment == '') { ?>
                      <?php $array =explode(',', $shopRow->job->treatment); ?>
                      <?php for ($i = 0; $i < count($array); $i++) { ?>
                      <div class="chip" name=""id="<?=$array[$i]?>" value="<?=$array[$i]?>"><?=$array[$i]?></div>
                      </div>
                      <?php } ?>
                    <?php } else {echo ('登録されていません。');} ?>
                  </td>
                </tr>
                <tr>
                  <th align="center">連絡先1</th>
                  <td><?php if(!$shopRow->job->tel1 == '') {
                    echo ($shopRow->job->tel1);
                  } else {echo ('登録されていません。');} ?>
                  </td>
                </tr>
                <tr>
                  <th align="center">連絡先2</th>
                  <td><?php if(!$shopRow->job->tel2 == '') {
                    echo ($shopRow->job->tel2);
                  } else {echo ('登録されていません。');} ?>
                  </td>
                </tr>
                <tr>
                  <th align="center">メール</th>
                  <td><?php if(!$shopRow->job->email == '') {
                    echo ($shopRow->job->email);
                  } else {echo ('登録されていません。');} ?>
                  </td>
                </tr>
                <tr>
                  <th align="center">LINEID</th>
                  <td><?php if(!$shopRow->job->lineid == '') {
                    echo ($shopRow->job->lineid);
                  } else {echo ('登録されていません。');} ?>
                  </td>
                </tr>
                <tr>
                  <th align="center">PR</th>
                  <td><?php if(!$shopRow->job->pr == '') {
                    echo ($shopRow->job->pr);
                  } else {echo ('登録されていません。');}?>
                  </td>
                </tr>
              </table>
              <?php endforeach; ?>
              <p style="text-align:center;">
                <button type="button" class="waves-effect waves-light btn-large changeBtn" onclick="jobChangeBtn($('#job'));return false;">編集</button>
              </p>
            </div>
          </div>
        </div>
        <form id="edit-job" name="edit_job" method="post" action="/owner/shops/edit_job/<?= $shopRow->job->id ?>" style="display:none;">
          <div style="display:none;">
            <input type="hidden" name="_method" value="POST">
            <input type="hidden" name="job_edit" value="">
            <input type="hidden" name="job_edit_id" value="">
            <input type="hidden" name="treatment" value="">
          </div>
          <div class="row">
            <div class="input-field col s12 m10 l6">
              <p name="name"></p>
            </div>
          </div>
          <div class="row">
            <div class="input-field col s12 m10 l6">
            <select name="industry">
                <option value="" disabled selected>業種を選択してください。</option>
                <?php foreach ($selectList['industry'] as $key => $value) {
                  echo('<option value="' .$value.'">'.$value.'</option>');
                  }?>
              </select>
              <label>業種</label>
            </div>
          </div>
          <div class="row">
            <div class="input-field col s12 m10 l6">
            <select name="job_type">
                <option value="" disabled selected>職種を選択してください。</option>
                <?php foreach ($selectList['job_type'] as $key => $value) {
                  echo('<option value="' .$value.'">'.$value.'</option>');
                  }?>
              </select>
              <label>職種</label>
            </div>
          </div>
          <div class="row">
            <div class="col s12 m12 l12"><label style="font-size: 1rem;">時間</label></div>
            <div class="col s5 m2 l2"><input id="work-from-time" class="timepicker" name="work_from_time"></div>
            <div class="col s2 m2 l2 center-align">～</div>
            <div class="col s5 m2 l2"><input id="work-to-time" class="timepicker" name="work_to_time"></div>
          </div>
          <div class="row">
            <div class="input-field col s12 m10 l6">
              <input type="text" id="work-time-hosoku" class="validate" name="work_time_hosoku" data-length="50">
              <label for="work-time-hosoku">時間についての補足</label>
            </div>
          </div>
          <div class="row">
          <div class="input-field col s12 m4 l2">
            <select name="from_age">
                <option value="" selected>年齢を選択してください</option>
                <?php foreach ($selectList['age'] as $key => $value) {
                  echo('<option value="' .$key.'">'.$value.'</option>');
                  }?>
              </select>
              <label>資格</label>
            </div>
            <label class="col s12 m1 l1">歳から</label>
            <div class="input-field col s12 m4 l2">
              <select name="to_age">
                <option value="" selected>年齢を選択してください</option>
                <?php foreach ($selectList['age'] as $key => $value) {
                  echo('<option value="' .$key.'">'.$value.'</option>');
                  }?>
              </select>
            </div>
            <label class="col s12 m2 l1">歳くらいまで</label>
          </div>
          <div class="row">
            <div class="input-field col s12 m10 l6">
              <input type="text" id="qualification-hosoku" class="validate" name="qualification_hosoku" data-length="50">
              <label for="qualification-hosoku">資格についての補足</label>
            </div>
          </div>
          <div class="row">
            <div class="col s12 m10 l6">
              <label>休日</label>
              <ul>
                <?php foreach ($selectList['day'] as $key => $value) {
                        echo('<li class="daylist"><input type="checkbox" name="holiday[]" id="'.$key.'" value="'.$value.'" /><label for="'.$key.'">'.$value.'</label></li>');
                      }
                ?>
              </ul>
            </div>
          </div>
          <div class="row">
            <div class="input-field col s12 m10 l6">
              <input type="text" id="holiday_hosoku" class="validate" name="holiday_hosoku">
              <label for="holiday_hosoku">休日についての補足</label>
            </div>
          </div>
          <div class="row">
            <div class="input-field col s12 m10 l6">
            <div class="chips chips-initial disabled" name="credit"></div>
              <label for="chips-autocomplete">待遇</label>
              <a data-target="modal-job" onClick="modalJobTriggerBtn($('#job'));" class="waves-effect waves-light btn-large modal-trigger">リストから選ぶ</a>
            </div>
          </div>
          <div class="row">
            <div class="input-field col s12 m10 l6">
              <textarea id="pr" class="materialize-textarea" name="pr" data-length="120"></textarea>
              <label for="pr">PR文</label>
            </div>
          </div>
          <div class="row">
            <div class="input-field col s12 m10 l6">
              <input type="tel" id="tel1" class="validate" name="tel1">
              <label for="tel1">連絡先１</label>
            </div>
          </div>
          <div class="row">
            <div class="input-field col s12 m10 l6">
              <input type="tel" id="tel2" class="validate" name="tel2">
              <label for="tel2">連絡先２</label>
            </div>
          </div>
          <div class="row">
            <div class="input-field col s12 m10 l6">
              <input type="text" id="email" class="validate" name="email">
              <label for="email">email</label>
            </div>
          </div>
          <div class="row">
            <div class="input-field col s12 m10 l6">
              <input type="text" id="lineid" class="validate" name="lineid">
              <label for="lineid">LINEID</label>
            </div>
          </div>
          <div class="card-content" style="text-align:center;">
            <button type="button" href="#" class="waves-effect waves-light btn-large changeBtn" onclick="jobChangeBtn($('#job'));return false;">やめる</button>
            <button type="button" class="waves-effect waves-light btn-large saveBtn" onclick="jobSaveBtn();return false;">確定</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
