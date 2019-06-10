<div id="wrapper">
  <div id="genre" class="container">
    <span id="dummy" style="display: hidden;"></span>
    <?= $this->Flash->render() ?>
    <nav class="nav-breadcrumb">
      <div class="nav-wrapper nav-wrapper-oki">
        <div class="col s12">
          <?=
            $this->Breadcrumbs->render(
              ['class' => 'breadcrumb'],
              ['separator' => '<i class="material-icons">chevron_right</i>']
            );
          ?>
        </div>
      </div>
    </nav>
    <?= $this->element('elmSearch'); ?>
    <input type="hidden" name="area_define" value=<?=json_encode(AREA)?>>
    <input type="hidden" name="genre_define" value=<?=json_encode(GENRE)?>>
    <div class="resultSearch">
      <div class="col s12">
        <h5 class="title"><?=h($title)?></h5>
        <h6 class="header"><?=h(count($shops)."件")?></h6>
        <p class="message"><?= count($shops) == 0 ? h("検索結果が０件でした。条件を変更し、もう一度検索してみてください。"):""?></p>
      </div>
      <?php if(count($shops) > 0) { ?>
        <?php foreach ($shops as $key => $rows): ?>
          <div class="col card horizontal waves-effect hoverable search-result-card">
            <div class="card-image">
              <img src="/img/common/top/top1.jpg" height="200">
            </div>
            <div class="card-stacked">
              <div class="card-content">
                <h5 class="blue-text text-darken-2"><?= $rows['name'] .DS.AREA[$rows['area']]['label'] .DS. GENRE[$rows['genre']]['label']?></h5>
                <p class="blue-text text-darken-2"><?= $rows['catch'] ?></p>
              </div>
              <div class="card-action">
              <?= $this->Html->link(COMMON_LB['053'], ['controller' => 'Naha', 'action' => 'shop/'.$rows['id'] ,'?'
                => ['genre'=>$rows['genre'],'name'=>$rows['name']]]) ?>
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
