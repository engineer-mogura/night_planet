<div id="search-result">
	<div class="col s12">
		<?php if(isset($search)):?>
			<?php $shops = $search; ?>
			<h5 class="title"><?=h("店舗の検索結果")?></h5>
			<h6 class="header"><?=h("店舗の検索結果 ".count($search)."件"."　キーワード「".$this->request->query('key_word')."」")?></h6>
			<p class="message"><?= count($search) == 0 ? h("検索結果が０件でした。条件を変更し、もう一度検索してみてください。"):""?></p>
		<?php elseif(isset($shops)): ?>
			<h5 class="title"><?=h($area_genre['area'].'の'.$area_genre['genre'].'一覧　'.count($shops).'件')?></h5>
		<?php endif; ?>
	</div>
	<?php if(count($shops) > 0): ?>
		<ul>
			<?php foreach ($shops as $key => $rows): ?>
				<li class="linkbox col card horizontal waves-effect hoverable search-result-card">
					<div class="card-image">
						<img src="<?= $rows->top_image ?>" class="card-image-img" height="200">
					</div>
					<div class="card-stacked">
						<div class="card-content">
							<h5 class="blue-text text-darken-2"><?= $rows['name']?></h5>
							<p class="blue-text text-darken-2"><?= $rows['catch']
							.'<br>'.GENRE[$rows['genre']]['label'].'|'.$rows['addr21'].$rows['strt21']	?></p>
						</div>
					</div>
					<a href="<?=DS.$rows['area'].DS.'shop'.DS.$rows['id']
						.'?area='.$rows['area'].'&genre='.$rows['genre'].'&name='.$rows['name']?>"></a>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</div>