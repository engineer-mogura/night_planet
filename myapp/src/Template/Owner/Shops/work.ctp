<?php
/**
* @var \App\View\AppView $this
* @var \App\Model\Entity\Cast[]|\Cake\Collection\CollectionInterface $shops
*/
?>
<div id="wrapper">
<?= $this->element('modal/noticeModal'); ?>
    <div class="container">
        <span id="dummy" style="display: hidden;"></span>
        <?= $this->Flash->render() ?>
        <h5><?=h('店舗お知らせ') ?></h5>
            <div id="notice" class="row">
            <input type="hidden" name="file_max" value=<?=PROPERTY['FILE_MAX']?>>
            <input type="hidden" name="notice_dir" value=<?=$shopInfo['notice_path'] ?>>
                <div class="col s12 m12 l12 xl8">
                    <div class="card-panel grey lighten-5">
                        <form id="edit-notice" name="edit_notice" method="post" action="/owner/shops/save_notice/">
                            <div style="display:none;">
                                <input type="hidden" name="_method" value="POST">
                                <input type="hidden" name="shop_id" value=<?= $shopInfo['id'] ?>>
                            </div>
                            <div class="row scrollspy">
                                <div class="input-field col s12 m12 l12">
                                    <input type="text" id="title" class="validate" name="title" value="" data-length="50">
                                    <label for="title">タイトル</label>
                                </div>
                                <div class="input-field col s12 m12 l12">
                                    <textarea id="content" class="validate materialize-textarea" name="content" data-length="600"></textarea>
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
                        <form id="view-archive-notice" name="view_archive_notice" method="get" style="display:none;" action="/owner/shops/view_notice/">
                            <input type="hidden" name="_method" value="POST">
                            <input type="hidden" name="id" value="">
                        </form>
                    </div>
                </div>
                <div class="col s12 m12 l12 xl4">
                <?php if(count($notices) > 0) { ?>
                    <ul class="collection z-depth-3">
                        <?php $count = 0; ?>
                        <?php foreach ($notices as $key => $rows): ?>
                        <?php foreach ($rows as $key => $row): ?>
                        <li class="linkbox collection-item avatar archiveLink">
                            <input type="hidden" name="id" value=<?=$row['id']?>>
                        <?php !empty($row['image1'])? $imgPath = $shopInfo['notice_path'].$row['dir'].DS.$row['image1'] : $imgPath = PATH_ROOT['NO_IMAGE01']; ?>
                            <img src="<?= $imgPath ?>" alt="" class="circle">
                            <span class="title color-blue"><?= $row['md_created'] ?></span>
                            <p><span class="truncate"><?= $row->title ?><br>
                                <?= $row['content'] ?></span>
                            </p>
                            <a class="waves-effect hoverable" href="#">
                                <span class="like-count secondary-content center-align"><i class="small material-icons">favorite_border</i><?= count($row['likes']) ?></span>
                            </a>
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
                <?php if(count($notices) > 0) { ?>
                    <ul class="collapsible popout" data-collapsible="accordion">
                        <?php foreach ($notices as $rows): ?>
                        <li class="collection-item">
                            <div class="collapsible-header waves-effect"><?= $rows["0"]["ym_created"] ?><span class="badge">投稿：<?= count($rows) ?></span></div>
                            <?php foreach ($rows as $row): ?>
                            <?php !empty($row['image1'])? $imgPath = $shopInfo['notice_path'].$row['dir'].DS.$row['image1'] : $imgPath = PATH_ROOT['NO_IMAGE01']; ?>
                            <div class="linkbox collapsible-body archiveLink">
                                <input type="hidden" name="id" value=<?=$row->id?>>
                                <span class="title color-blue"><?= $row['md_created'] ?></span>
                                <span class="like-count secondary-content center-align"><i class="small material-icons">favorite_border</i><?= count($row['likes']) ?></span>
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
        <script>
document.addEventListener ( 'DOMContentLoaded' , function () {
    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();
    var array = [{
      title: 'All Day Event',
      start: new Date(y, m, 1),
      time_start: '13:00',
      time_end: '14:00',
      active: '1'
    }, {
      id: 1,
      cast_id: '0',
      title: 'Long Event',
      start: new Date(y, m, d - 5),
      end: new Date(y, m, d - 2),
      time_start: '13:00',
      time_end: '14:00',
      active: '1'
    }, {
        id: 2,
        cast_id: '0',
        title: 'Repeating Event',
        start: new Date(y, m, d - 3, 16, 0),
        time_start: '13:00',
        time_end: '14:00',
        allDay: false,
        active: '1'
    }, {
        id: 3,
        cast_id: '0',
        title: 'Repeating Event',
        start: new Date(y, m, d + 4, 16, 0),
        time_start: '13:00',
        time_end: '14:00',
        allDay: false,
        active: '1'
    }, {
        id: 4,
        cast_id: '0',
        title: 'travel',
        start: new Date(y, m, d + 1, 19, 0),
        end: new Date(y, m, d + 1, 22, 30),
        time_start: '13:00',
        time_end: '14:00',
        allDay: false,
        active: '1'
    }, {
        id: 5,
        cast_id: '0',
        title: 'Click for Google',
        start: new Date(y, m, 28),
        end: new Date(y, m, 29),
        time_start: '13:00',
        time_end: '14:00',
        active: '1'
    }];
        var calendarEl = document . getElementById ( 'calendar' );
        var calendar = new FullCalendar . Calendar ( calendarEl
            , {
                header: {
                    left: 'today month,basicWeek',
                    center: 'title,dayGridMonth,timeGridWeek',
                    right: 'prev next'
                },
                plugins : [ 'dayGrid' , 'timeGrid' , 'list' ] 
            ,   defaultView : 'timeGridWeek' 
            ,   events:array
            });
                calendar . render ();
            });
        </script>
        <div id='calendar'></div>
    </div>
</div>
