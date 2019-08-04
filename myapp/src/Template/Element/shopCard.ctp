<div id="search-result">
	<div class="col s12">
	<h5 class="title"><?=h($title)?></h5>
	<h6 class="header"><?=h("検索結果 ".count($shops)."件")?></h6>
	<p class="message"><?= count($shops) == 0 ? h("検索結果が０件でした。条件を変更し、もう一度検索してみてください。"):""?></p>
	</div>
	<?php if(count($shops) > 0) { ?>
	<?php foreach ($shops as $key => $rows): ?>
		<?php !empty($rows['top_image']) ? $main_image = DS.PATH_ROOT['IMG']
			.DS.AREA[$rows['area']]['path'].DS.GENRE[$rows['genre']]['path']
			.DS.$rows['dir'].DS.$rows['top_image']: $main_image = GENRE[$rows['genre']]['image']?>
		<div class="col card horizontal waves-effect hoverable search-result-card">
		<div class="card-image">
			<img src="<?= $main_image ?>" height="200">
		</div>
		<div class="card-stacked">
			<div class="card-content">
			<h5 class="blue-text text-darken-2"><?= $rows['name']?></h5>
			<p class="blue-text text-darken-2"><?= $rows['catch']
				.'<br>'.GENRE[$rows['genre']]['label'].'|'.$rows['addr21'].$rows['strt21']	?></p>
			</div>
			<div class="card-action">
			<a href="<?=DS.$rows['area'].DS.'shop'.DS.$rows['id']
				.'?area='.$rows['area'].'&genre='.$rows['genre'].'&name='.$rows['name']?>">店舗詳細</a>
			</div>
		</div>
		</div>
	<?php endforeach; ?>
	<?php }  ?>
</div>