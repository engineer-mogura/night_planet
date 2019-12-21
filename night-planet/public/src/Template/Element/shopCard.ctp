<div id="search-result">
	<div class="col s12">
		<?php if(isset($search)):?>
			<?php $shops = $search; ?>
			<span class="title"><?=h("店舗の検索結果")?></span>
			<span class="header"><?=h("店舗の検索結果 ".count($search)."件"."　キーワード「".$this->request->query('key_word')."」")?></span>
			<p class="message"><?= count($search) == 0 ? h("検索結果が０件でした。条件を変更し、もう一度検索してみてください。"):""?></p>
		<?php elseif(isset($shops)): ?>
			<span class="title"><?=h($area_genre['area']['label'].'の'.$area_genre['genre']['label'].'　'.count($shops).'件')?></span>
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
						<h1>
						<?php if (!empty($rows->snss[0]->instagram)): ?>
								<a style="margin-left: 80%;">
									<span class="Instagram_logo1"></span>
								</a>
							<?php endif; ?>
							<?php if (!empty($rows->snss[0]->facebook)): ?>
								<a style="margin-left: 90%;">
									<span class="facebook_logo1"></span>
								</a>
							<?php endif; ?>
							<?php if (!empty($rows->snss[0]->twitter)): ?>
								<a style="margin-left: 100%;">
									<span class="twitter_logo1"></span>
								</a>
							<?php endif; ?>
						</h1>
						<div class="card-content">
							<h5 class="blue-text text-darken-2"><?= $rows['name']?></h5>
							<p class="blue-text text-darken-2"><?= $rows['catch']
							.'<br>'.GENRE[$rows['genre']]['label'].'|'.$rows['addr21'].$rows['strt21']	?></p>
						</div>
					</div>
					<a href="<?= DS.$rows['area'].DS.$rows['genre'].DS.$rows['id'] ?>"></a>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</div>