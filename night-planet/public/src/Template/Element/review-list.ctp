<?php foreach ($shop->reviews as $key => $value) : ?>
	<li class="collection-item other-review-section__ul__li avatar">
		<img src="<?=$this->User->get_icon_path($value->userInfo)?>" alt="" class="circle">
		<span class="title"><?=$value->user->name?></span>
		<h6><?=$value->user->created?> に参加</h6>
		<span class="truncate"><?=$value->comment?></span>
		<a href="#!" class="secondary-content">
			<div class="rateit"
					data-rateit-readonly=true
					data-rateit-value=<?=$value->review_average?>
					data-rateit-max="5">
			</div>
		</a>
	</li>
<?php endforeach ; ?>