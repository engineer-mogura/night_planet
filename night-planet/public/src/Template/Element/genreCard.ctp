<?php foreach ($genreCounts as $key => $row): ?>
	<div class="col s12 m6 l6">
		<div class="linkbox card waves-effect hoverable">
			<div class="card-image">
				<img src="<?=$row['image']?>" class="" style="background-color: #ffffff;width: 100%;height: 200px;object-fit: cover;">
			</div>
			<div class="card-content center-align deep-orange darken-1">
				<p class="white-text"><?=$row['label'].' ( '.$row['count'].' )'?></p>
			</div>
			<a href="<?=$row['area'].DS.$row['path'].DS ?>"></a>
		</div>
	</div>
<?php endforeach; ?>