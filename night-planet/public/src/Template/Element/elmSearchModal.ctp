<div class="row">
	<form method="GET" class="search-form" name="search_form" action="/search/">
		<div class="col s6 m6 l6">
			<p>
				<input type="radio" name="search-choice" id="modal-shop-search" value="shop" <?=($selected['search-choice'] == 'shop')
					 || (empty($selected['search-choice'])) ? 'checked':''?> />
				<label for="modal-shop-search">店舗</label>
			</p>
		</div>
		<div class="col s6 m6 l6">
			<p>
				<input type="radio" name="search-choice" id="modal-cast-search" value="cast" <?=$selected['search-choice'] == 'cast' ? 'checked':''?> />
				<label for="modal-cast-search">キャスト</label>
			</p>
		</div>
		<li class="search col s12 m12 l12">
			<div class="input-field oki-input-field">
				<input placeholder="店舗名、キャスト名を入力" value="<?= $selected['key_word']?>" name="key_word" type="text"
					class="validate input-search">
			</div>
		</li>
		<li class="search col s12 m6 l6">
			<div class="input-field oki-input-field">
				<select name="area">
					<option value="" selected>エリアを選択してください。</option>
					<?php foreach ($selectList['area'] as $key => $value): ?>
					<option value="<?=$key?>" <?= $selected['area'] == $key? "selected":"" ?>><?=$value?></option>
					<?php endforeach ?>
				</select>
			</div>
		</li>
		<li class="search col s12 m6 l6">
			<div class="input-field oki-input-field">
				<select name="genre">
					<option value="" selected>ジャンルを選択してください。</option>
					<?php foreach ($selectList['genre'] as $key => $value): ?>
					<option value="<?=$key?>" <?= $selected['genre'] == $key? "selected":"" ?>><?=$value?></option>
					<?php endforeach ?>
				</select>
			</div>
		</li>
		<div class="modal-footer">
			<a class="col s8 m8 l8 modal-action waves-effect waves-light btn searchBtn"><i class="material-icons right">search</i>検索</a>
			<a href="#!" class="col s3 m3 l3 modal-action modal-close waves-effect waves-green btn">閉じる</a>
		</div>

	</form>
</div>