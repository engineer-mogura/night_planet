<?php
/**
* @var \App\View\AppView $this
* @var \App\Model\Entity\Owner[]|\Cake\Collection\CollectionInterface $owners
*/
?>
<div class="container">
    <span id="dummy" style="display: hidden;"></span>
    <?= $this->Flash->render() ?>
    <h5><?=('プロフィール') ?></h5>
        <?php foreach ($cast as $castRow): ?>
        <div id="cast-profile" class="row">
            <div class="col s12 m8 l8">
                <div class="card-panel grey lighten-5">
                    <form id="edit-profile" name="edit_profile" method="post" action="/owner/casts/profile/<?= $castRow->id ?>">
                        <div style="display:none;">
                            <input type="hidden" name="profile_copy" value='<?=$castRow ?>'>
                            <input type="hidden" name="_method" value="POST">
                            <input type="hidden" name="profile_edit" value="">
                            <input type="hidden" name="profile_edit_id" value="">
                            <input type="hidden" name="credit" value="">
                        </div>
                        <div class="row">
                            <div class="input-field col s12 m6 l6">
                                <input type="text" id="name" class="validate" name="name" value="<?=$castRow->name ?>" data-length="30">
                                <label for="name">名前</label>
                            </div>
                            <div class="input-field col s12 m6 l6">
                                <input type="text" id="nickname" class="validate" name="nickname" value="<?=$castRow->nickname ?>" data-length="30">
                                <label for="nickname">ニックネーム</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12 m6 l6">
                                <input type="text" id="email" class="validate" name="email" value="<?=$castRow->email ?>">
                                <label for="email">Ｅメール</label>
                            </div>
                            <div class="input-field col s12 m6 l6">
                                <input type="password" id="password" class="validate" name="password" value="<?=$castRow->password ?>" disabled>
                                <label for="password">パスワード</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12 m6 l6">
                                <input type="text" id="birthday" class="birthday-picker" name="birthday">
                                <label>誕生日</label>
                            </div>
                            <div class="input-field col s12 m6 l6">
                                <select name="age">
                                    <option value="" selected>年齢を選択してください</option>
                                    <?php foreach ($selectList['age'] as $key => $value) {
                                        echo('<option value="' .$key.'">'.$value.'</option>');
                                    }?>
                                </select>
                                <label>年齢</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12 m6 l6">
                                <select name="constellation">
                                    <option value="" selected>星座を選択してください</option>
                                    <?php foreach ($selectList['constellation'] as $key => $value) {
                                        echo('<option value="' .$key.'">'.$value.'</option>');
                                    }?>
                                </select>
                                <label>星座</label>
                            </div>
                            <div class="input-field col s12 m6 l6">
                                <select name="blood_type">
                                    <option value="" selected>血液型を選択してください</option>
                                    <?php foreach ($selectList['blood_type'] as $key => $value) {
                                        echo('<option value="' .$key.'">'.$value.'</option>');
                                    }?>
                                </select>
                                <label>血液型</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12 m12 l12">
                            <textarea id="message" class="validate materialize-textarea" name="message" data-length="50"><?=$castRow->message ?></textarea>
                            <label for="message">メッセージ</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12 m12 l12">
                                <a class="waves-effect waves-light btn-large disabled saveBtn right" onclick="profileSaveBtn($('#edit-profile'));return false;">確定</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col s12 m4 l4">
                <div class="card-panel grey lighten-5">
                    <img src="/img/common/noimage.jpg" alt="" class="circle left" width="80" height="80">
                <p>test<br>test<br>test<br>test<br>test<br>test<br>test<br></p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

