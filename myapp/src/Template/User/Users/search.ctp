<div id="wrapper">
  <div id="search" class="container">
    <span id="dummy" style="display: hidden;"></span>
    <?= $this->Flash->render() ?>
    <ul class="collection">
      <li class="collection-item dismissable">
        <div>2018.08.search画面<a href="#!" class="secondary-content"><span class="notice">お知らせ一覧</span><i class="material-icons">chevron_right</i></a>
        </div>  
      </li>
    </ul>
    <?= $this->element('elmSearch'); ?>
    <input type="hidden" name="area_define" value=<?=json_encode(AREA)?>>
    <input type="hidden" name="genre_define" value=<?=json_encode(GENRE)?>>
    <div class="resultSearch row">
      <h2 class="header"><?=h("検索結果 ".count($shops)."件")?></h2>
      <p class="message"><?= count($shops) == 0 ? h("検索結果が０件でした。条件を変更し、もう一度検索してみてください。"):""?></p>
      <?php if(count($shops) > 0) { ?>
          <?php foreach ($shops as $key => $rows): ?>
      <div class="card horizontal waves-effect hoverable search-result-card">
        <div class="card-image">
          <img src="/img/common/top/top1.jpg" height="200">
        </div>
        <div class="card-stacked">
          <div class="card-content">
          <h5 class="blue-text text-darken-2"><?= $rows['name'] .'/'.AREA[$rows['area']]['label'] .'/'. GENRE[$rows['genre']]['label']?></h5>
          <p class="blue-text text-darken-2"><?= $rows['catch'] ?></p>
          </div>
          <div class="card-action">
            <a href="#">店舗詳細</a>
          </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php }  ?>

    </div>
  </div>
</div>

<?= $this->Html->scriptstart() ?>
$(document).ready(function(){
$('.slider').slider();
$('select').material_select();
});
<?= $this->Html->scriptend() ?>
