<?= $this->fetch('sidebar') ?>
	<div class="nav-wrapper">
		<?= $this->element('top-slider'); ?>
	</div>
<div id="area" class="container">
	<?= $this->element('info-marquee'); ?>
	<div class="row">
		<?= $this->element('genreCard'); ?>
	</div>
	<div class="row">
		<div class="col s12 m6 l6">
			<div class="card-panel teal lighten-2 oki-card-panel">
				<h6 class="white-text">店舗からのお知らせ</h6>
			</div>
			<?php if(count($notices) > 0): ?>
				<ul class="collection z-depth-3">
					<?php foreach ($notices as $key => $value): ?>
						<?php $path = DS.PATH_ROOT['IMG'].DS.$value->shop['area']
							.DS.$value->shop['genre'].DS.$value->shop['dir'].DS.PATH_ROOT['NOTICE']
							.$value['dir'];
						?>
						<?php !empty($value->icon)? $imgPath = $path.DS.$value['icon'] : $imgPath = PATH_ROOT['NO_IMAGE01']; ?>
					<li class="linkbox collection-item avatar">
						<img src="<?= $imgPath ?>" alt="" class="circle">
						<span class="title color-blue"><?= $value->created->nice()?></span>
						<p><span class="color-blue"><?=$value['nickname']?></span><br>
							<span class="color-blue"><?= AREA[$value->shop['area']]['label'].' '.GENRE[$value->shop['genre']]['label']
							.' '.$value->shop['name']?></span><br>
						<span class="truncate"><?= $value['title'] ?><br><?= $value['content'] ?></span>
						</p>
						<span class="like-count secondary-content center-align"><i class="small material-icons">favorite_border</i><?=count($value->likes)?></span>
						<a class="waves-effect hoverable" href="<?=DS.$value->shop['area'].DS.PATH_ROOT['NOTICE'].DS.$value->id."?area=".$value->shop->area."&genre=".$value->shop->genre.
						"&shop=".$value->shop->id."&name=".$value->shop->name."&shop_infos=".$value->id ?>"></a>
					</li>
					<?php endforeach ?>
				</ul>
			<?php else:?>
				<p class="col">まだお知らせがありません。</p>
			<?php endif ?>
		</div>
		<div class="col s12 m6 l6">
			<div class="card-panel teal lighten-2 oki-card-panel">
				<h6 class="white-text">キャスト日記</h6>
			</div>
			<?php if (count($diarys) > 0): ?>
				<ul class="collection z-depth-3">
					<?php foreach ($diarys as $key => $value): ?>
					<?php $path = DS.PATH_ROOT['IMG'].DS.$value->cast->shop['area']
						.DS.$value->cast->shop['genre'].DS.$value->cast->shop['dir'].DS.PATH_ROOT['CAST']
						.DS.$value->cast['dir'].DS.PATH_ROOT['PROFILE'];
					?>
					<?php !empty($value->cast->image1)? $imgPath = $path.DS.$value->cast['image1'] : $imgPath = PATH_ROOT['NO_IMAGE01']; ?>
						<li class="linkbox collection-item avatar">
							<img src="<?= $imgPath ?>" alt="" class="circle">
							<span class="title color-blue"><?= $value->created->nice()?></span>
							<p><span class="color-blue"><?=$value->cast['nickname']?></span><br>
								<span class="color-blue"><?= AREA[$value->cast->shop['area']]['label'].' '.GENRE[$value->cast->shop['genre']]['label']
								.' '.$value->cast->shop['name']?></span><br>
								<span class="truncate"><?= $value['title'] ?><br><?= $value['content'] ?></span>
							</p>
							<span class="like-count secondary-content center-align"><i class="small material-icons">favorite_border</i><?=count($value->likes)?></span>
							<a class="waves-effect hoverable" href="<?=DS.$value->cast->shop['area'].DS.PATH_ROOT['DIARY'].DS.$value->cast->id."?area=".$value->cast->shop->area."&genre=".$value->cast->shop->genre.
							"&shop=".$value->cast->shop->id."&name=".$value->cast->shop->name."&cast=".$value->cast->id."&nickname=".$value->cast->nickname?>"></a>
						</li>
					<?php endforeach ?>
				</ul>
			<?php else:?>
				<p class="col">まだ日記がありません。</p>
			<?php endif ?>
		</div>
	</div>
</div>
<!-- ショップ用ボトムナビゲーション START -->
<?= $this->element('bottom-navigation'); ?>
<!-- ショップ用ボトムナビゲーション END -->