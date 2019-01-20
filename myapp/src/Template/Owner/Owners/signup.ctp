<?php
/**
* @var \App\View\AppView $this
* @var \App\Model\Entity\Owner[]|\Cake\Collection\CollectionInterface $owners
*/
?>

<div class="container">
  <?= $this->Flash->render() ?>
    <div class="card or-card">
        <div class="card-image waves-effect waves-block waves-light">
            <div class="or-form-wrap">
            <h3><?= __('おきよるGo') ?></h3>

            <form method="post" accept-charset="utf-8" action="/owner/owners/signup">
                <div style="display:none;">
                    <input type="hidden" name="_method" value="POST">
                </div>
                <div class="input email required">
                    <div class="input-field col ">
                        <input type="email" name="email" required="required" maxlength="255" id="email">
                        <label for="email">Email</label>
                    </div>
                </div>
                <div class="input password required">
                    <div class="input-field col ">
                        <input type="password" name="password" required="required" id="password">
                        <label for="password" class="">Password</label>
                    </div>
                </div>
                <div class="or-button">
                <button type="submit" class="waves-effect waves-light btn-large">新規登録
                </button>
                </div>
            </form>
        </div>
        </div>
    </div>
</div>
<script>
  $(document).ready(function(){
    $("nav").hide();
    $('.page-footer').hide();
});
</script>


