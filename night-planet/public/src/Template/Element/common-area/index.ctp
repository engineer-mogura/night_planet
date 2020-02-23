<?= $this->fetch('sidebar') ?>
	<div class="nav-wrapper">
		<?= $this->element('top-slider'); ?>
	</div>
<div id="area" class="container">
	<div class="row">
		<div class="col s12 m8 l8">
			<?= $this->element('info-marquee'); ?>
			<div class="row count-section">
				<div id="shop-menu-section" class="shop-menu">
					<div class="white card-panel col s6 center-align">
						<p class="shop-count section-label"><span>　店舗 ( <?=$all_cnt['shops'];?> )</span></p>
					</div>
				</div>
				<div id="casts-section" class="shop-menu">
					<div class="white card-panel col s6 center-align">
						<p class="cast-count section-label"><span>スタッフ ( <?=$all_cnt['casts'];?> )</span></p>
					</div>
				</div>
			</div>
			<div class="row sub-slider-section">
				<?= $this->element('sub-slider'); ?>
			</div>
			<div class="row">
				<?= $this->element('genreCard'); ?>
			</div>
			<div class="row section tabs-section1">
				<ul id="tabs-new-info" class="tabs tabs-fixed-width">
                    <li class="tab-original tab col s6"><a href="#new-info-1-tabs">店舗からのお知らせ</a></li>
                    <li class="tab-original tab col s6"><a class="active" href="#new-info-2-tabs">スタッフ日記</a></li>
                </ul>
                <div id="new-info-1-tabs" class="col s12">
					<?php if(count($notices) > 0): ?>
						<ul id="shop-new-notice" class="collection z-depth-3">
							<?php foreach ($notices as $key => $value): ?>
								<li class="linkbox collection-item avatar">
									<img src="<?= $value->icon ?>" alt="" class="circle">
									<span class="title color-blue"><?= $value->created->nice()?></span>
									<span class="icon-vertical-align color-blue"><i class="small material-icons">camera_alt</i> <?=$value->gallery_count?></span>
									<p><span class="color-blue"><?= AREA[$value->shop['area']]['label'].' '.GENRE[$value->shop['genre']]['label']
										.' '.$value->shop['name']?></span><br>
									<span class="truncate"><?= $value['title'] ?><br><?= $value['content'] ?></span>
									</p>
									<span class="like-count secondary-content icon-vertical-align color-blue"><i class="small material-icons">favorite_border</i><?=count($value->shop_info_likes)?></span>
									<a class="waves-effect hoverable" href="<?=DS.$value->shop['area'].DS.PATH_ROOT['NOTICE'].DS.$value->shop->id."?area=".$value->shop->area."&genre=".$value->shop->genre
										."&name=".$value->shop->name."&shop_infos=".$value->id ?>"></a>
								</li>
							<?php endforeach ?>
						</ul>
					<?php else:?>
						<p class="col">まだお知らせがありません。</p>
					<?php endif ?>
				</div>
                <div id="new-info-2-tabs" class="col s12">
					<?php if (count($diarys) > 0): ?>
						<ul id="cast-new-diary" class="collection z-depth-3">
							<?php foreach ($diarys as $key => $value): ?>
								<li class="linkbox collection-item avatar">
									<img src="<?= $value->icon ?>" alt="" class="circle">
									<span class="title color-blue"><?= $value->created->nice()?></span>
									<span class="icon-vertical-align color-blue"><i class="small material-icons">camera_alt</i> <?=$value->gallery_count?></span>
									<p><span class="color-blue"><?=$value->cast['nickname']?> </span>
										<span class="color-blue"><?= AREA[$value->cast->shop['area']]['label'].' '.GENRE[$value->cast->shop['genre']]['label']
										.' '.$value->cast->shop['name']?></span><br>
										<span class="truncate"><?= $value['title'] ?><br><?= $value['content'] ?></span>
									</p>
									<span class="like-count secondary-content icon-vertical-align color-blue"><i class="small material-icons">favorite_border</i><?=count($value->diary_likes)?></span>
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
			<div class="row section tabs-section2">
				<div class="light-blue accent-2 card-panel col s12 center-align">
					<p class="trending_up-label section-label"><span> ランキング </span></p>
				</div>
				<ul id="tabs-ranking" class="tabs tabs-fixed-width">
					<li class="tab-original tab col s6"><a href="#ranking-1-tabs">スタッフ</a></li>
					<li class="tab-original tab col s6"><a class="active" href="#ranking-2-tabs">店舗</a></li>
				</ul>
				<!-- スタッフランキング START -->
				<div id="ranking-1-tabs" class="col s12">
					<p class="col">まだランキングがありません。</p>
				</div>
				<!-- スタッフランキング END -->
				<!-- 店舗ランキング START -->
				<div id="ranking-2-tabs" class="col s12">
					<div class="my-ranking" style="display:inline-block;">
						<?php foreach ($shop_ranking as $key => $value): ?>
							<figure>
								<span class="rank rank-header">No <?=$key + 1?></span>
								<a href="<?=$value->shopInfo['shop_url']?>" data-size="800x1000">
									<img width="100%" src="<?=$value['top_image']?>" alt="<?=$value->shopInfo['area']['label'] . $value->name?>" />
								</a>
								<span class="rank rank-footer truncate"><?=$value->shopInfo['area']['label']?>
									<br><?=$value->name?></span>
							</figure>
						<?php endforeach ?>
					</div>
				</div>
				<!-- 店舗ランキング END -->
			</div>
			<!-- Photos START -->
			<div id="instagram-section" class="row shop-menu section scrollspy">
				<div class="light-blue accent-2 card-panel col s12 center-align">
					<p class="instagram-label section-label"><span> Photos </span></p>
				</div>
				<!-- photoSwipe START -->
				<?= $this->element('newPhotos'); ?>
				<!-- photoSwipe END -->
			</div>
			<!-- Photos END -->
		</div>
		<div class="sidebar col s12 m4 l4">
			<!-- 広告枠 START -->
			<?= $this->element('banner'); ?>
			<!-- 広告枠 END -->
		</div>
	</div>
</div>
<!-- 共通ボトムナビゲーション START -->
<?= $this->element('bottom-navigation'); ?>
<!-- 共通ボトムナビゲーション END -->