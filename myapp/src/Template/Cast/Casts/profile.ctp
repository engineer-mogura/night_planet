<?php
/**
* @var \App\View\AppView $this
* @var \App\Model\Entity\cast[]|\Cake\Collection\CollectionInterface $casts
*/
?>
<div id="wrapper">
    <div class="container">
        <span id="dummy" style="display: hidden;"></span>
        <?= $this->Flash->render() ?>
        <h5><?=('プロフィール') ?></h5>
            <?php foreach ($cast as $row): ?>
            <div id="cast-profile" class="row">
                <div class="col s12 m12 l12 xl8">
                    <div class="card-panel grey lighten-5">
                        <form id="edit-profile" name="edit_profile" method="post" action="/cast/casts/profile/<?= $row->id ?>">
                            <div style="display:none;">
                                <input type="hidden" name="profile_copy" value='<?=$row ?>'>
                                <input type="hidden" name="_method" value="POST">
                                <input type="hidden" name="profile_edit" value="">
                                <input type="hidden" name="profile_edit_id" value="">
                                <input type="hidden" name="credit" value="">
                            </div>
                            <div class="row">
                                <div class="input-field col s12 m6 l6">
                                    <input type="text" id="name" class="validate" name="name" value="<?=$row->name ?>" data-length="30">
                                    <label for="name">名前</label>
                                </div>
                                <div class="input-field col s12 m6 l6">
                                    <input type="text" id="nickname" class="validate" name="nickname" value="<?=$row->nickname ?>" data-length="30">
                                    <label for="nickname">ニックネーム</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s12 m6 l6">
                                    <input type="text" id="email" class="validate" name="email" value="<?=$row->email ?>">
                                    <label for="email">Ｅメール</label>
                                </div>
                                <div class="input-field col s12 m6 l6">
                                    <input type="password" id="password" class="validate" name="password" value="<?=$row->password ?>" disabled>
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
                                        <option value="">年齢を選択してください</option>
                                        <?php foreach ($selectList['age'] as $key => $value) {
                                            $row->age == $key ? $option = '<option value="'.$key.'" selected>'.$value.'</option>':$option = '<option value="'.$key.'">'.$value.'</option>';
                                            echo($option);
                                        }?>
                                    </select>
                                    <label>年齢</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s12 m6 l6">
                                    <select name="constellation">
                                        <option value="">星座を選択してください</option>
                                        <?php foreach ($selectList['constellation'] as $key => $value) {
                                            $row->constellation == $key ? $option = '<option value="'.$key.'" selected>'.$value.'</option>':$option = '<option value="'.$key.'">'.$value.'</option>';
                                            echo($option);
                                        }?>
                                    </select>
                                    <label>星座</label>
                                </div>
                                <div class="input-field col s12 m6 l6">
                                    <select name="blood_type">
                                        <option value="">血液型を選択してください</option>
                                        <?php foreach ($selectList['blood_type'] as $key => $value) {
                                            $row->blood_type == $key ? $option = '<option value="'.$key.'" selected>'.$value.'</option>':$option = '<option value="'.$key.'">'.$value.'</option>';
                                            echo($option);
                                        }?>
                                    </select>
                                    <label>血液型</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s12 m12 l12">
                                    <textarea id="message" class="validate materialize-textarea" name="message" data-length="50"><?=$row->message ?></textarea>
                                    <label for="message">メッセージ</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s12 m12 l12">
                                    <button type="submit" class="waves-effect waves-light btn-large disabled saveBtn right">登録</button>
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
</div>

