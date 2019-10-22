<div>
  <?= $this->Flash->render() ?>
  <div class="card or-card">
    <div class="card-image waves-block">
        <div class="or-form-wrap">
            <h3>開発者モード</h3>
            <?= $this->Form->create() ?>
            <?= $this->Form->control('email', array('required' => false)) ?>
            <?= $this->Form->control('password', array('required' => false)) ?>
            <div class="or-button">
                <?= $this->Form->button('ログイン',array('class'=>'waves-effect waves-light btn-large'));?>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<script>
  $(document).ready(function(){
    $("nav").hide();
    $('.page-footer').hide();
});
</script>