<div id="search-result">
	<div class="col s12">
		<h5 class="title"><?=h("キャストの検索結果")?></h5>
		<h6 class="header"><?=h("キャストの検索結果 ".count($search)."件")?></h6>
		<p class="message"><?= count($search) == 0 ? h("検索結果が０件でした。条件を変更し、もう一度検索してみてください。"):""?></p>
	</div>
	<?php if(count($search) > 0) { ?>
	<ul>
		<?php foreach ($search as $key => $rows): ?>
			<li class="linkbox col card horizontal waves-effect hoverable search-result-card">
				<div class="card-image">
					<img src="<?= $rows->icon ?>" class="card-image-img" height="200">
				</div>
				<div class="card-stacked">
					<div class="card-content">
						<h5 class="blue-text text-darken-2"><?= $rows['name']?><span style="font-size: small;"class="blue-text text-darken-2">　所属：<?=$rows->shop['name']?></span></h5>
						<p class="blue-text text-darken-2"><?= $rows['message']
						.'<br>'.GENRE[$rows->shop['genre']]['label'].'|'.$rows->shop['addr21'].$rows->shop['strt21']?></p>
					</div>
				</div>
				<a href="<?=DS.$rows->shop['area'].DS.'cast'.DS.$rows['id']
					.'?genre='.$rows->shop['genre'].'&name='.$rows->shop['name']
					.'&shop='.$rows->shop['id'].'&nickname='.$rows['nickname']?>"></a>
			</li>
		<?php endforeach; ?>
	</ul>
	<?php }  ?>
</div>