<div id="wrapper">
  <div id="search" class="container">
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
    <?= $this->element('shopCard'); ?>
  </div>
</div>

<?= $this->Html->scriptstart() ?>
$(document).ready(function(){
$('.slider').slider();
$('select').material_select();
});
<?= $this->Html->scriptend() ?>
