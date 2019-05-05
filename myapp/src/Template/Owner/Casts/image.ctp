<?php
/**
* @var \App\View\AppView $this
* @var \App\Model\Entity\Owner[]|\Cake\Collection\CollectionInterface $owners
*/
?>
<div id="wrapper">
    <div class="container">
        <span id="dummy" style="display: hidden;"></span>
        <?= $this->Flash->render() ?>
        <h5><?=('画像アップロード') ?></h5>
            <div id="cast-image" class="row">
                <input type="hidden" name="file_max" value=<?=CAST_CONFIG['FILE_MAX']?>>
                    <?php foreach ($cast as $key => $value) {
                        if(preg_match('/image[0-9]/',$key)) {
                            if (!empty($value)) {
                                $json_array = [
                                    'col_name' => $key,
                                    'file_name' => $value,
                                ];
                                $json = json_encode( $json_array, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
                                ?>
                                <div class="col s6 m4 l3 card-img <?=$key?>">
                                    <div class="card">
                                        <div class="card-image">
                                            <img class="materialboxed" data-caption="" height="120" width="100%" src="<?= DS.$infoArray['dir_path'].PATH_ROOT['CAST'].DS.$cast['dir'].DS.PATH_ROOT['IMAGE'].DS.$value ?>">
                                            <a class="btn-floating halfway-fab waves-effect waves-light red tooltipped deleteBtn" data-delete=<?=$json?> data-position="bottom" data-delay="50" data-tooltip="削除"><i class="material-icons">delete</i></a>
                                        </div>
                                    </div>
                                </div>
                        <?php
                            }
                        }
                    }
                    ?>
            </div>
            <form id="delete-image" name="delete_image" method="post" action="/owner/casts/image/<?= $cast['id'] ?>">
                                <div style="display:none;">
                                    <input type="hidden" name="_method" value="POST">
                                    <input type="hidden" name="crud_type" value="delete">
                                    <input type="hidden" name="col_name" value="">
                                    <input type="hidden" name="file_name" value="">
                                </div>
                            </form>
            <div class="row">
                <form id="edit-image" name="edit_image" method="post" accept-charset="utf-8" enctype="multipart/form-data" action="/owner/casts/image/<?= $cast->id ?>">
                    <div style="display:none;">
                        <input type="hidden" name="image_json" value='<?=json_encode($imageList); ?>'>
                        <input type="hidden" name="_method" value="POST">
                        <input type="hidden" name="crud_type" value="update">
                    </div>
                    <div class="file-field input-field col s12 m6 l6">
                        <div class="btn">
                            <span>File</span>
                            <input type="file" id="image-file" name="image[]" multiple>
                        </div>
                        <div class="file-path-wrapper">
                            <input class="file-path validate" name="file_path" type="text">
                        </div>
                        <canvas id="image-canvas" style="display:none;"></canvas>
                    </div>
                    <div class="input-field col s12 m12 l12">
                        <button type="button" class="waves-effect waves-light btn-large disabled cancelBtn">やめる</button>
                        <button type="button" class="waves-effect waves-light btn-large disabled createBtn">確定</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

