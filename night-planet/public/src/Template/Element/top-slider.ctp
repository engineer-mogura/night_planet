<div class="slider">
    <ul class="slides">
		<?php
			foreach ($adsenses['main_adsenses'] as $key => $value) :
		?>
			<li>
				<a href="<?= $value->shop_url ?>">
					<img src="<?= $value->image ?>">
					<div class="caption center-align">
						<h6 class="light grey-text text-lighten-3"><?= $value->catch ?></h6>
					</div>
				</a>
			</li>
		<?php
			endforeach;
		?>
    </ul>
</div>