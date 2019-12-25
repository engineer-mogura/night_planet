<ul class="collapsible" data-collapsible="accordion">
	<li>
		<div class="collapsible-header">
			<i class="material-icons">zoom_in</i><?=REGION['south']['label']?>
		</div>
		<div class="collapsible-body">
			<ul class="collection with-header">
				<?php foreach ($area as $key => $value): ?>
					<?php if (REGION['south']['path'] == $value['region']): ?>
						<li class="collection-item linkbox">
							<div><?=REGION['okinawa']['label'].'　'.$value['label'].'エリア ( '.$value['count'].' )'?><a href="<?=$value['path']?>" class="secondary-content">
								<i class="material-icons" style="margin-top: 8px;">location_on</i></a>
							</div>
						</li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		</div>
	</li>
	<li>
		<div class="collapsible-header">
			<i class="material-icons">zoom_in</i><?=REGION['center']['label']?>
		</div>
		<div class="collapsible-body">
			<ul class="collection with-header">
				<?php foreach ($area as $key => $value): ?>
					<?php if (REGION['center']['path'] == $value['region']): ?>
						<li class="collection-item linkbox">
							<div><?=REGION['okinawa']['label'].'　'.$value['label'].'エリア ( '.$value['count'].' )'?><a href="<?=$value['path']?>" class="secondary-content">
								<i class="material-icons" style="margin-top: 8px;">location_on</i></a>
							</div>
						</li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		</div>
	</li>
	<li>
		<div class="collapsible-header">
			<i class="material-icons">zoom_in</i><?=REGION['north']['label']?>
		</div>
		<div class="collapsible-body">
			<ul class="collection with-header">
				<?php foreach ($area as $key => $value): ?>
					<?php if (REGION['north']['path'] == $value['region']): ?>
						<li class="collection-item linkbox">
							<div><?=REGION['okinawa']['label'].'　'.$value['label'].'エリア ( '.$value['count'].' )'?><a href="<?=$value['path']?>" class="secondary-content">
								<i class="material-icons" style="margin-top: 8px;">location_on</i></a>
							</div>
						</li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		</div>
	</li>
	<li>
		<div class="collapsible-header">
			<i class="material-icons">zoom_in</i><?=REGION['miyakojima']['label']?>
		</div>
		<div class="collapsible-body">
			<ul class="collection with-header">
				<?php foreach ($area as $key => $value): ?>
					<?php if (REGION['miyakojima']['path'] == $value['region']): ?>
						<li class="collection-item linkbox">
							<div><?=$value['label'].'エリア ( '.$value['count'].' )'?><a href="<?=$value['path']?>" class="secondary-content">
								<i class="material-icons" style="margin-top: 8px;">location_on</i></a>
							</div>
						</li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		</div>
	</li>
	<li>
		<div class="collapsible-header">
			<i class="material-icons">zoom_in</i><?=REGION['ishigakijima']['label']?>
		</div>
		<div class="collapsible-body">
			<ul class="collection with-header">
				<?php foreach ($area as $key => $value): ?>
					<?php if (REGION['ishigakijima']['path'] == $value['region']): ?>
						<li class="collection-item linkbox">
							<div><?=$value['label'].'エリア ( '.$value['count'].' )'?><a href="<?=$value['path']?>" class="secondary-content">
								<i class="material-icons" style="margin-top: 8px;">location_on</i></a>
							</div>
						</li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		</div>
	</li>
</ul>