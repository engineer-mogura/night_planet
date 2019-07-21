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
            <div class="row">
                <form id="view-archive-diary" name="view_archive_diary" method="get" style="display:none;" action="/naha/view_diary/">
                    <input type="hidden" name="_method" value="POST">
                    <input type="hidden" name="id" value="">
                </form>
                <div class="col s12 m12 l12 xl12">
                <?php if(count($diarys) > 0) { ?>
                    <ul class="collection card-panel archive-panel">
                        <?php $count = 0; ?>
                        <?php foreach ($diarys as $key => $rows): ?>
                        <?php foreach ($rows as $key => $row): ?>
                        <li class="collection-item avatar waves-effect archiveLink">
                            <input type="hidden" name="id" value=<?=$row->id?>>
                        <?php !empty($row->image1)? $imgPath = $dir.$row->dir.DS.$row->image1 : $imgPath = PATH_ROOT['NO_IMAGE01']; ?>
                            <img src="<?= $imgPath ?>" alt="" class="circle">
                            <span class="title"><?=$this->Text->excerpt($row->title, 'method', CAST_CONFIG['TITLE_EXCERPT'], CAST_CONFIG['ELLIPSIS']); ?></span><span class="badge"><?= $row->md_created ?></span>
                            <p><?= $this->Text->excerpt($row->content, 'method', CAST_CONFIG['CONTENT_EXCERPT'], CAST_CONFIG['ELLIPSIS']);?></p>
                            <span class="like-count secondary-content center-align"><i class="tiny material-icons">thumb_up</i><?=count($row->likes)?></span>
                        </li>
                        <?php $count = $count + 1;?>
                        <?php if ($count == 5) {break;} ?>
                        <?php endforeach; ?>
                        <?php if ($count == 5) {break;} ?>
                        <?php endforeach; ?>
                    </ul>
                <?php } ?>
                <?php if(count($diarys) > 0) { ?>
                    <ul class="collapsible popout archive-panel" data-collapsible="accordion">
                        <?php foreach ($diarys as $rows): ?>
                        <li>
                            <div class="collapsible-header waves-effect"><?= $rows["0"]["ym_created"] ?><span class="badge">投稿：<?= count($rows) ?></span></div>
                            <?php foreach ($rows as $row): ?>
                            <?php !empty($row['image1'])? $imgPath = $dir.$row['dir'].DS.$row['image1'] : $imgPath = PATH_ROOT['NO_IMAGE01']; ?>
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



