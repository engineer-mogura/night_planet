<?php
/**
* @var \App\View\AppView $this
* @var \App\Model\Entity\Cast[]|\Cake\Collection\CollectionInterface $casts
*/
?>
<div id="wrapper">
<?= $this->element('modal/diaryModal'); ?>
    <div class="container">
        <span id="dummy" style="display: hidden;"></span>
        <?= $this->Flash->render() ?>
        <h5><?=('日記') ?></h5>
            <div id="diary" class="row">
            <input type="hidden" name="file_max" value=<?=CAST_CONFIG['FILE_MAX']?>>
            <input type="hidden" name="cast_dir" value=<?=$dir?>>
                <div class="col s12 m12 l12 xl8">
                    <div class="card-panel grey lighten-5">
                        <form id="edit-diary" name="edit_diary" method="post" action="/cast/casts/save_diary/">
                            <div style="display:none;">
                                <input type="hidden" name="_method" value="POST">
                                <input type="hidden" name="cast_id" value=<?= $cast->id ?>>
                            </div>
                            <div class="row scrollspy">
                                <div class="input-field col s12 m12 l12">
                                    <input type="text" id="title" class="validate" name="title" value="" data-length="50">
                                    <label for="title">タイトル</label>
                                </div>
                                <div class="input-field col s12 m12 l12">
                                    <textarea id="content" class="validate materialize-textarea" name="content" data-length="250"></textarea>
                                    <label for="content">内容</label>
                                </div>
                            </div>
                            <div class="file-field input-field col s12 m12 l12">
                                <div class="btn">
                                    <span>File</span>
                                    <input type="file" id="image-file" class="image-file" name="image[]" multiple>
                                </div>
                                <div class="file-path-wrapper">
                                    <input id="file-path" class="file-path validate" name="file_path" type="text">
                                </div>
                                <canvas id="image-canvas" style="display:none;"></canvas>
                            </div>
                            <div class="row">
                                <div class="input-field col s12 m12 l12">
                                    <a class="waves-effect waves-light btn-large createBtn disabled"><i class="material-icons right">search</i>登録</a>
                                    <a class="waves-effect waves-light btn-large cancelBtn disabled"><i class="material-icons right">search</i>やめる</a>
                                </div>
                            </div>
                        </form>
                        <form id="view-archive-diary" name="view_archive_diary" method="get" style="display:none;" action="/cast/casts/view_diary/">
                            <input type="hidden" name="_method" value="POST">
                            <input type="hidden" name="id" value="">
                        </form>
                    </div>
                </div>
                <div class="col s12 m12 l12 xl4">
                <?php if(count($diarys) > 0) { ?>
                    <ul class="collection card-panel archive-panel">
                        <?php $count = 0; ?>
                        <?php foreach ($diarys as $key => $rows): ?>
                        <?php foreach ($rows as $key => $row): ?>
                        <li class="collection-item avatar waves-effect archiveLink">
                            <input type="hidden" name="id" value=<?=$row['id']?>>
                        <?php !empty($row['image1'])? $imgPath = $dir.$row['dir'].DS.$row['image1'] : $imgPath = PATH_ROOT['NO_IMAGE01']; ?>
                            <img src="<?= $imgPath ?>" alt="" class="circle">
                            <span class="title"><?=$this->Text->excerpt($row->title, 'method', CAST_CONFIG['TITLE_EXCERPT'], CAST_CONFIG['ELLIPSIS']); ?></span><span class="badge"><?= $row['md_created'] ?></span>
                            <p><?= $this->Text->excerpt($row['content'], 'method', CAST_CONFIG['CONTENT_EXCERPT'], CAST_CONFIG['ELLIPSIS']);?></p>
                            <span class="like-count secondary-content center-align"><i class="material-icons">thumb_up</i><?=count($row['likes'])?></span>
                        </li>
                        <?php $count = $count + 1;?>
                        <?php if ($count == 5) {break;} ?>
                        <?php endforeach; ?>
                        <?php if ($count == 5) {break;} ?>
                        <?php endforeach; ?>
                    </ul>
                <?php } else { ?>
                    <p>最近の投稿はありません。</p>
                <?php } ?>
                <?php if(count($diarys) > 0) { ?>
                    <ul class="collapsible popout archive-panel" data-collapsible="accordion">
                        <?php foreach ($diarys as $rows): ?>
                        <li>
                            <div class="collapsible-header waves-effect"><?= $rows["0"]["ym_created"] ?><span class="badge">投稿：<?= count($rows) ?></span></div>
                            <?php foreach ($rows as $row): ?>
                            <?php !empty($row['image1'])? $imgPath = $dir . $row['dir'] . $row['image1'] : $imgPath = PATH_ROOT['NO_IMAGE01']; ?>
                            <div class="collapsible-body waves-effect archiveLink">
                            <input type="hidden" name="id" value=<?=$row->id?>>
                            <p><?= $this->Text->excerpt($row['title'], 'method', CAST_CONFIG['TITLE_EXCERPT'], CAST_CONFIG['ELLIPSIS']); ?><span class="badge"><?=$row['md_created']?></span></p>
                            <p><?= $this->Text->excerpt($row['content'], 'method', CAST_CONFIG['CONTENT_EXCERPT'], CAST_CONFIG['ELLIPSIS']);?><span class="like-count secondary-content center-align"><i class="material-icons">thumb_up</i><?=count($row->likes)?></span></p>
                            </div>
                            <?php endforeach; ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                <?php } else { ?>
                    <p>過去の投稿はありません。</p>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<?= $this->element('photoSwipe'); ?>
