<div class="row">
  <form method="GET" class="search-form" name="search_form" action="/user/users/search/">
    <!-- <input type="hidden" name="_method" value="GET"> -->
    <li class="search col s12 l3">
      <div class="input-field oki-input-field">
        <input placeholder="キーワード" value="<?= $conditionSelected['key_word']?>" name="key_word" type="text" class="validate input-search">
      </div>
    </li>
    <li class="search col s12 m6 l3">
      <div class="input-field oki-input-field">
        <select name="area">
          <option value="" selected>エリアを選択してください。</option>
          <?php foreach ($selectList['area'] as $key => $value) {?>
            <option value="<?=$key?>" <?= $conditionSelected['area'] == $key? "selected":"" ?>><?=$value?></option>
          <?php } ?>
        </select>
      </div>
    </li>
    <li class="search col s12 m6 l3">
      <div class="input-field oki-input-field">
        <select name="genre">
          <option value="" selected>ジャンルを選択してください。</option>
          <?php foreach ($selectList['genre'] as $key => $value) {?>
            <option value="<?=$key?>" <?= $conditionSelected['genre'] == $key? "selected":"" ?>><?=$value?></option>
          <?php } ?>
        </select>
      </div>
    </li>
    <li class="search col s12 m12 l3">
      <a class="waves-effect waves-light btn-large searchBtn"><i class="material-icons right">search</i>検索</a>
    </li>
  </form>
</div>