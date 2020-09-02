<?php if ($favos[0]->registry_alias == 'shop_likes') { ?>
	<?php foreach ($favos as $key => $favo) : ?>
		<?php $shop = $favo->shop; ?>
		<li class="collection-item favo-list-section__ul__li avatar favorite">
			<a href="<?= DS.$shop['area'].DS.$shop['genre'].DS.$shop['id'] ?>">
				<img src="<?=$shop->top_image?>" alt="" class="circle">
			</a>
			<span class="card-tag white-text red"><?= $shop['name']?></span>
			<span class="card-tag white-text orange darken-1">
				<?=GENRE[$shop['genre']]['label']?>
			</span><br>
			<span class="card-tag white-text blue darken-1">
				<?=$shop['addr21']?></span>
			<span class="favo-list-section__ul__li__address"><?=$shop['strt21']?></span>
			<h6><?=$favo->created->format('Y/m/d')?> にお気に入り</h6>
			<a href="#!" class="secondary-content">
				<?=$this->User->get_favo_html('my_favo', $favo)?>
			</a>
		</li>
	<?php endforeach ; ?>
<?php } ?>
<?php if ($favos[0]->registry_alias == 'cast_likes') { ?>
	<?php foreach ($favos as $key => $favo) : ?>
		<?php $cast = $favo->cast; ?>
		<li class="collection-item favo-list-section__ul__li avatar favorite">
			<a href="<?= DS.$cast->shop['area'].DS.PATH_ROOT['CAST'].DS.$cast['id'] ?>">
				<img src="<?=$cast->icon?>" alt="<?=$cast->nickname?>" class="circle">
			</a>
			<span class="card-tag white-text red"><?= $cast['name']?></span>
			<span class="card-tag white-text red"><?= $cast->shop['name']?></span><br>
			<span class="card-tag white-text orange darken-1">
				<?=GENRE[$cast->shop['genre']]['label']?>
			</span><br>
			<span class="card-tag white-text blue darken-1">
				<?=$cast->shop['addr21']?></span>
			<span class="favo-list-section__ul__li__address"><?=$cast->shop['strt21']?></span>
			<h6><?=$favo->created->format('Y/m/d')?> にお気に入り</h6>
			<a href="#!" class="secondary-content">
				<?=$this->User->get_favo_html('my_favo', $favo)?>
			</a>
		</li>
	<?php endforeach ; ?>
<?php } ?>
