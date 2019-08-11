<?php
/**
* @var \App\View\AppView $this
* @var \App\Model\Entity\Owner[]|\Cake\Collection\CollectionInterface $owners
*/
?>
<?= $this->element('modal/viewDiaryModal'); ?>
<div id="wrapper">
    <div id="diary" class="container">
        <div style="display:none;">
            <input type="hidden" name="cast_dir" value="<?=$dir?>">
            <input type="hidden" name="diary_id" value=''>
        </div>
        <span id="dummy" style="display: hidden;"></span>
        <?= $this->Flash->render() ?>
        <?= $this->element('nav-breadcrumb'); ?>
            <div class="row">
                <form id="view-archive-diary" name="view_archive_diary" method="get" style="display:none;" action="<?=DS.$shopInfo['area']['path'].DS.'view_diary'.DS?>">
                    <input type="hidden" name="_method" value="POST">
                    <input type="hidden" name="id" value="">
                </form>
                <div class="col s12 m12 l12 xl12">
                <?php if(count($diarys) > 0) { ?>
                    <ul class="collection z-depth-3">
                        <?php $count = 0; ?>
                        <?php foreach ($diarys as $key => $rows): ?>
                        <?php foreach ($rows as $key => $row): ?>
                        <li class="linkbox collection-item avatar archiveLink">
                            <input type="hidden" name="id" value=<?=$row->id?>>
                        <?php !empty($row->image1)? $imgPath = $dir.$row->dir.DS.$row->image1 : $imgPath = PATH_ROOT['NO_IMAGE01']; ?>
                            <img src="<?= $imgPath ?>" alt="" class="circle">
                            <span class="title color-blue"><?= $row['md_created'] ?></span>
                            <p><span class="truncate"><?= $row->title ?><br>
                                <?= $row['content'] ?></span>
                            </p>
                            <a class="waves-effect hoverable" href="#">
                                <span class="like-count secondary-content center-align"><i class="tiny material-icons">thumb_up</i><?= count($row['likes']) ?></span>
                            </a>
                        </li>
                        <?php $count = $count + 1;?>
                        <?php if ($count == 5) {break;} ?>
                        <?php endforeach; ?>
                        <?php if ($count == 5) {break;} ?>
                        <?php endforeach; ?>
                    </ul>
                <?php } ?>
                <?php if(count($diarys) > 0) { ?>
                    <ul class="collapsible popout" data-collapsible="accordion">
                        <?php foreach ($diarys as $rows): ?>
                        <li class="collection-item">
                            <div class="collapsible-header waves-effect"><?= $rows["0"]["ym_created"] ?><span class="badge">投稿：<?= count($rows) ?></span></div>
                            <?php foreach ($rows as $row): ?>
                            <?php !empty($row['image1'])? $imgPath = $dir.$row['dir'].DS.$row['image1'] : $imgPath = PATH_ROOT['NO_IMAGE01']; ?>
                            <div class="linkbox collapsible-body archiveLink">
                            <input type="hidden" name="id" value=<?=$row->id?>>
                            <span class="title color-blue"><?= $row['md_created'] ?></span>
                            <span class="like-count secondary-content center-align"><i class="tiny material-icons">thumb_up</i><?= count($row['likes']) ?></span>
                            <p><span class="truncate"><?= $row->title ?><br>
                                <?= $row['content'] ?></span>
                            </p>
                            <a class="waves-effect hoverable" href="#"></a>
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



