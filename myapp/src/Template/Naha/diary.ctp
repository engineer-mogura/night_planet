<?php
/**
* @var \App\View\AppView $this
* @var \App\Model\Entity\Owner[]|\Cake\Collection\CollectionInterface $owners
*/
?>
<?= $this->element('modal/diaryModal'); ?>
<div id="wrapper">
    <div class="container">
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
            <div id="cast-diary" class="row">
                </div>
                <div class="col s12 m12 l12 xl4">
                <?php if(count($archive) > 0) { ?>
                    <ul class="collection card-panel archive-panel">
                        <?php $count = 0; ?>
                        <?php foreach ($archive as $key => $rows): ?>
                        <?php foreach ($rows as $key => $row): ?>
                        <li class="collection-item avatar waves-effect archiveLink">
                            <input type="hidden" name="id" value=<?=$row->id?>>
                        <?php !empty($row->image1)? $imgPath = "/".$infoArray['dir_path']."cast/".$cast->dir."/diary/".$row->dir."/".$row->image1 : $imgPath = PATH_ROOT['NO_IMAGE01']; ?>
                            <img src="<?= $imgPath ?>" alt="" class="circle">
                            <span class="title"><?=$this->Text->excerpt($row->title, 'method', CAST_CONFIG['TITLE_EXCERPT'], CAST_CONFIG['ELLIPSIS']); ?></span><span class="badge"><?= $row->mdCreated ?></span>
                            <p><?= $this->Text->excerpt($row->content, 'method', CAST_CONFIG['CONTENT_EXCERPT'], CAST_CONFIG['ELLIPSIS']);?></p>
                            <span class="secondary-content center-align"><i class="material-icons">thumb_up</i>1234</span>
                        </li>
                        <?php $count = $count + 1;?>
                        <?php if ($count == 5) {break;} ?>
                        <?php endforeach; ?>
                        <?php if ($count == 5) {break;} ?>
                        <?php endforeach; ?>
                    </ul>
                <?php } ?>
                <?php if(count($archive) > 0) { ?>
                    <ul class="collapsible popout archive-panel" data-collapsible="accordion">
                        <?php foreach ($archive as $rows): ?>
                        <li>
                            <div class="collapsible-header waves-effect"><?= $rows["0"]["ymCreated"] ?><span class="badge">投稿：<?= count($rows) ?></span></div>
                            <?php foreach ($rows as $row): ?>
                            <?php !empty($row['image1'])? $imgPath = "/".$infoArray['dir_path']."cast/".$cast->dir."/diary/".$row['dir']."/".$row['image1'] : $imgPath = PATH_ROOT['NO_IMAGE01']; ?>
                            <div class="collapsible-body waves-effect archiveLink">
                            <input type="hidden" name="id" value=<?=$row->id?>>
                            <p><?= $this->Text->excerpt($row['title'], 'method', CAST_CONFIG['TITLE_EXCERPT'], CAST_CONFIG['ELLIPSIS']); ?><span class="badge"><?=$row['mdCreated']?></span></p>
                            <p><?= $this->Text->excerpt($row['content'], 'method', CAST_CONFIG['CONTENT_EXCERPT'], CAST_CONFIG['ELLIPSIS']);?><span class="secondary-content center-align"><i class="material-icons">thumb_up</i>1234</span></p>
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



