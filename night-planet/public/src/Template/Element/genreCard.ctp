<?php foreach ($genreCounts as $key => $row): ?>
	<div class="col s12 m6 l6">
		<div class="linkbox card waves-effect hoverable">
			<div class="card-image">
				<img src="<?=$row['image']?>" class="teal lighten-5" style="width: 100%;height: 200px;object-fit: cover;">
			</div>
			<div class="card-content">
				<p class="blue-text text-darken-2"><?=$row['label'].'（ '.$row['count'].' ）'?></p>
			</div>
			<a href="<?=$row['area']."/genre/?genre=".$row['path']?>"></a>
		</div>
	</div>
<?php endforeach; ?>