<?php foreach ($genreCounts as $key => $row): ?>
	<div class="col s12 m4 l4">
		<div class="linkbox card horizontal waves-effect hoverable">
			<div class="card-image">
				<img src="<?=$row['image']?>" style="width: 100%;height: 200px;object-fit: cover; background-color: lightsalmon;">
			</div>
			<div class="card-content">
				<p class="blue-text text-darken-2"><?=$row['label'].'（'.$row['count'].'）'?></p>
			</div>
			<a href="<?=$row['area']."/genre/?genre=".$row['path']?>"></a>
		</div>
	</div>
<?php endforeach; ?>