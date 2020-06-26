<div id="search-result">
	<div class="col s12">
		<?php $key_word = empty($this->request->query('key_word'))? "キーワード指定なし": $this->request->query('key_word'); ?>
		<?php $area = empty($this->request->query('area'))? "エリア指定なし": AREA[$this->request->query('area')]['label']; ?>
		<?php $genre = empty($this->request->query('genre'))? "ジャンル指定なし": GENRE[$this->request->query('genre')]['label']; ?>
		<!-- パンくずからのジャンルの場合 -->
		<?php if(isset($shops)): ?>
			<span class="header"><?=h($area_genre['area']['label'].'の'.$area_genre['genre']['label'].'　'.count($shops).'件')?></span>
		<?php $search = $shops; ?>
		<!-- UNKNOWからの場合 -->
		<?php elseif(isset($unknow)): ?>
			<span class="header"><?=h($area.'の'.$genre.'　'.count($search).'件')?></span>
		<?php else: ?>
			<span class="header"><?=h("店舗の検索結果 ".count($search)." 件")?></span><br>
			<span class="header"><?=h("キーワード「".$key_word."」")?></span><br>
			<span class="header"><?=h("エリア「".$area."」")?></span><br>
			<span class="header"><?=h("ジャンル「".$genre."」")?></span>
			<p class="message"><?= count($search) == 0 ? h("検索結果が０件でした。条件を変更し、もう一度検索してみてください。"):""?></p>
		<?php endif; ?>
	</div>
	<?php if(count($search) > 0): ?>
		<ul>
			<?php foreach ($search as $key => $rows): ?>
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
							<h6 class="blue-text text-darken-2"><?= $rows['name']?></h6>
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