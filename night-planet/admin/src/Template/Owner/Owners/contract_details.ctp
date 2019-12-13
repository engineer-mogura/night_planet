<div id="wrapper">
    <div class="container">
        <div class="row">
            <?= $this->Flash->render() ?>
            <div id="contract-details" class="col s12 m12 l12">
                <h6><?=('契約内容・プラン変更・広告のお申し込み') ?></h6>
                <span id="dummy" style="display: hidden;"></span>
                <div class="col s12 m12 l12">
                    <table class="bordered shop-table z-depth-2" border="1">
                        <tbody>
                        <tr>
                            <th align="center">あなたの会員ステータス</th>
                            <td>
<?php
                            $start = $this->Time->format($owner[0]->servece_plan->from_start, 'Y/M/d');
                            $end   = $this->Time->format($owner[0]->servece_plan->to_end, 'Y/M/d');
                            $course = $owner[0]->servece_plan->course;
?>
                                <?= SERVECE_PLAN[$owner[0]->servece_plan->current_plan]['name']
                                    .'　'.$course .'ヵ月コース'?>
                            </td>
                        </tr>
                        <tr>
                            <th align="center">期間</th>
                            <td><?php echo($start . ' ～ ' .$end); ?></td>
                        </tr>
<?php 
                            foreach ($owner[0]->shops as $key => $shop) {
                                $is_brank = null;
?>
                                <tr>
                                    <th align="center" colspan="2"><?= $shop->name?></th>
                                </tr>
<?php
                                if (count($shop->adsenses) == 0) {
?>
                                    <tr>
                                        <th align="center">メイン広告</th>
                                        <td><?php echo('-'); ?></td>
                                    </tr>
                                    <tr>
                                        <th align="center">サブ広告</th>
                                        <td><?php echo('-'); ?></td>
                                    </tr>
<?php
                                }

?>
<?php
                                foreach ($shop->adsenses as $key => $adsense) {
                                    if (count($shop->adsenses) == 1)  {
                                        $is_brank = $adsense->type == 'main' ? 'main' : 'sub';
                                    }
                                    $start = $this->Time->format($adsense->valid_start, 'Y/M/d');
                                    $end   = $this->Time->format($adsense->valid_end, 'Y/M/d');
?>
<?php
                                    if (!empty($is_brank) && $is_brank == 'sub') {
?>
                                        <tr>
                                            <th align="center"><?= "メイン広告" ?></th>
                                            <td><?php echo('-'); ?></td>
                                        </tr>
<?php
                                    }
?>
                                    <tr>
                                        <th align="center"><?=$adsense->type == 'main' ? "メイン広告" : "サブ広告" ?></th>
                                        <td><?php echo('掲載期間</br>'. $start . ' ～ ' .$end); ?></td>
                                    </tr>
<?php
                                    if (!empty($is_brank) && $is_brank == 'main') {
?>
                                        <tr>
                                            <th align="center"><?= "サブ広告" ?></th>
                                            <td><?php echo('-'); ?></td>
                                        </tr>
<?php
                                    }
                                }
                            }
?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="or-button">
			<a href="/owner/owners/change_plan" class="waves-effect waves-light btn-large">プランを変更する</a>
		</div>
        <div class="or-button">
			<a class="waves-effect waves-light btn-large disabled">広告のお申し込み</a>
		</div>
    </div>
</div>
