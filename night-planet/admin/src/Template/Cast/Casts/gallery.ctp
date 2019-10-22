<div id="wrapper">
    <div class="container">
        <div class="row">
            <div id="gallery" class="col s12">
                <span id="dummy" style="display: hidden;"></span>
                <?= $this->Flash->render() ?>
                <h5><?=('ギャラリー') ?></h5>
                <div class="row">
                    <form id="delete-gallery" name="delete_gallery" method="post" style="display:none;" action="/cast/casts/delete_gallery?id=<?=$userInfo['id']?>">
                        <input type="hidden" name="_method" value="POST">
                        <input type="hidden" name="id" value="">
                        <input type="hidden" name="file_path" value="">
                    </form>
                    <input type="hidden" name="file_max" value=<?=PROPERTY['FILE_MAX']?>>
                    <?php foreach ($gallery as $key => $image) : ?>
                        <div class="col s6 m4 l3 card-img">
                            <div class="card">
                                <div class="card-image">
                                    <img class="materialboxed" data-caption="" height="auto" width="auto" src="<?= $image['file_path'] ?>">
                                    <a class="btn-floating halfway-fab waves-effect waves-light red tooltipped deleteBtn" data-delete=<?=$image['file_path']?> data-position="bottom" data-delay="50" data-tooltip="削除"><i class="material-icons">delete</i></a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <form id="save-gallery" name="save_gallery" method="post" accept-charset="utf-8" enctype="multipart/form-data" action="/cast/casts/save_gallery?id=<?=$userInfo['id']?>">
                    <div style="display:none;">
                        <input type="hidden" name="gallery_befor" value='<?=json_encode($gallery); ?>'>
                        <input type="hidden" name="_method" value="POST">
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
                    <div class="card-content" style="text-align:center;">
                        <button type="button" class="waves-effect waves-light btn-large chancelBtn">やめる</button>
                        <button type="button" class="waves-effect waves-light btn-large saveBtn">追加</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>