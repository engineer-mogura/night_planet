<?php
/**
* @var \App\View\AppView $this
* @var \App\Model\Entity\Cast[]|\Cake\Collection\CollectionInterface $shops
*/
?>
<div id="wrapper">
<?= $this->element('modal/castListModal'); ?>
    <div class="container">
        <span id="dummy" style="display: hidden;"></span>
        <?= $this->Flash->render() ?>
        <h5><?=h('出勤管理') ?></h5>
            <div id="work-schedule-management" class="row">
                <!-- 出勤希望リスト START -->
                <?php if(!empty($castList)) : ?>
                <div class="col s12 table-wrap">
                    <table class="striped centered" border="1">
                        <thead>
                            <tr class="tr-header">
                                <th align="center"><?=date('Y')?></th>
                                <?php 
                                    foreach ($dateList as $key => $date):
                                ?>
                                <th align="center"><?= $date ?></th>
                                <?php
                                    endforeach;
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <?php 
                                    for($x = 0; $x < 5; $x++):
                                    foreach ($castList as $key => $cast):
                                ?>
                                <th align="center">
                                    <img src="<?=isset($cast['icon_name']) ? $cast['profile_path'].DS.$cast['icon_name'] : PATH_ROOT['NO_IMAGE02'] ?>" alt="" class="circle" width="50" height="50">
                                    </br><span class="center-align"><?=$cast['0']['name']?></span>
                                </th>
                                <?php 
                                    for ($i = 0; $i < count($dateList); $i++):
                                ?>
                                <td align="center">〇</td>
                                <?php
                                    endfor;
                                ?>
                            </tr>
                                <?php
                                    endforeach;
                                    endfor;
                                ?>
                        </tbody>
                    </table>
                </div>
                <!-- 出勤希望リスト END -->
                <div class="col s12 center-align" style="margin-top: 20px;">
                    <a data-target="modal-cast-list" class="waves-effect waves-light btn modal-trigger jobModal-callBtn">本日のメンバーを選択する</a>
                </div>
                <?php 
                    else :
                        echo ('キャストが登録されていないか、表示ボタンがONになっていません。');
                    endif;?>
            </div>
            <div class="row">
                <div class="col s12">
                    <div id='calendar'></div>
                </div>
            </div>
        </div>
    </div>
</div>
