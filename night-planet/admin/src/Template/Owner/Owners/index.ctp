<div id="wrapper">
	<div class="container">
		<?= $this->Flash->render() ?>
		<h5><?= __('オーナー画面') ?></h5>
		<div class="row">
			<?php foreach ($shops  as $key => $shop): ?>
			<?php $shopPath = DS.PATH_ROOT['IMG'].DS.$shop->area.DS.$shop->genre.DS.$shop->dir; ?>
				<div class="col s12 m6 l6">
					<div class="card <?php if(count($shops) == $key + 1) { echo('targetScroll');}?>">
						<div class="card-image">
							<a href="/owner/shops/index?shop_id=<?=$shop->id?>">
								<img src="<?=$shop->top_image?>" height="300px;" width="100%" alt="">
							</a>
						</div>
						<div class="card-content">
							<table class="highlight">
								<thead>
									<tr>
										<td colspan="2">
											<span><?=$shop->name ?></span>
											<a href="#!" class="secondary-content">
												<div class="switch">
													<label>OFF<input type="checkbox" value="<?=$shop->status ?>" name="shop_switch<?=$shop->id ?>" class="shop-switchBtn" <?php if ($shop->status == 1) { echo 'checked'; }?>><span class="lever"></span>ON</label>
												</div>
											</a>
										</td>
									</tr>
								</thead>
								<tbody class="tbody-shop-group">
									<tr>
										<th>登録日</th>
										<td><?=$this->Time->format($shop->created, 'Y年M月d日')?></td>
									</tr>
									<tr style="height: 70px;">
										<th>住所</th>
										<td><?=$shop->full_address ?></td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="card-action">
							<a href="/owner/shops/index?shop_id=<?=$shop->id?>">店舗詳細</a>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<div class="or-button">
			<a href="/owner/owners/shop_add" class="waves-effect waves-light btn-large<?= !$is_add ? " disabled":""?>">店舗を追加する</a>
		</div>
	</div>
</div>
