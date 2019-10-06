<div id="today-member-modal" class="modal modal-fixed-footer">
	<div class="modal-content">
		<div class="row shop-menu section scrollspy">
			<p>本日の出勤メンバー(※予定)</p>
				<?php if((count($shop->casts) > 0) && (count($shop->work_schedules) > 0)): ?>
					<?php $idsArr = explode(',', $shop->work_schedules[0]['cast_ids']);?>
					<?php foreach($shop->casts as $cast): ?>
						<?php if(!in_array($cast->id, $idsArr)) { continue; } ?>
						<div class="cast-icon-list center-align col s3 m3 l3">
							<a href="<?=DS.$shop['area'].DS.PATH_ROOT['CAST'].DS.$cast['id']."?genre=".$shop['genre']."&name=".$shop['name']."&shop=".$shop['id']."&nickname=".$cast['nickname']?>">
								<img src="<?=$cast->icon?>" alt="" class="circle" width="100%" height="80">
							</a>
							<h6><?=$cast->nickname?></h6>
						</div>
					<?php endforeach; ?>
				<?php else: ?>
			<div class="">
				<p>本日の出勤メンバーが登録されていません。</p>
			</div>
			<?php endif; ?>
		</div>
	</div>
	<div class="modal-footer">
		<a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">とじる</a>
	</div>
</div>