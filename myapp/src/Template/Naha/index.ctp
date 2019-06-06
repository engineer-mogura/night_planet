<?= $this->fetch('sidebar') ?>
<div class="nav-wrapper">
<div class="slider">
  <ul class="slides">
    <li>
      <img src="/img/common/top/top1.jpg"> <!-- random image -->
      <div class="caption center-align">
        <h3>沖縄の夜遊び探しは【おきよる】!</h3>
        <h5 class="light grey-text text-lighten-3">キーワード、エリア、ジャンルですぐに見つかる!</h5>
      </div>
    </li>
    <li>
      <img src="/img/common/top/top2.jpg"> <!-- random image -->
      <div class="caption left-align">
        <h3>沖縄の夜遊び探しは【おきよる】!</h3>
        <h5 class="light grey-text text-lighten-3">キーワード、エリア、ジャンルですぐに見つかる!</h5>
      </div>
    </li>
    <li>
      <img src="/img/common/top/top3.jpg"> <!-- random image -->
      <div class="caption right-align">
        <h3>沖縄の夜遊び探しは【おきよる】!</h3>
        <h5 class="light grey-text text-lighten-3">キーワード、エリア、ジャンルですぐに見つかる!</h5>
      </div>
    </li>
  </ul>
  </div>
  </div>
<div id="top" class="container">
  <ul class="collection">
    <li class="collection-item dismissable">
      <div>【おきよる】那覇の運営を開始しました！<a href="#!" class="secondary-content"><span class="notice">お知らせ一覧</span><i class="material-icons">chevron_right</i></a>
      </div>
    </li>
  </ul>
    <?= $this->element('elmSearch'); ?>
  <div class="row">
    <?php foreach ($genreCounts as $key => $row): ?>
      <div class="col s12 m4 l3">
        <div class="card small hoverable">
          <div class="card-image">
            <img src="/img/common/top/top1.jpg">
          </div>
          <div class="card-content">
            <p><?=$row['label'].'（'.$row['count'].'）'?></p>
          </div>
          <div class="card-action">
          <a href="<?=$row['area']."/genre/?genre=".$row['path']?>"><?=$row['label']."一覧へ"?></a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <div class="row">
    <div class="col s12 m6 l6">
      <div class="card-panel teal lighten-2 oki-card-panel"><h6 class="white-text">新着情報</h6></div>
      <ul class="collection">
        <li class="collection-item avatar">
          <img src="/img/common/top/top1.jpg" alt="" class="circle">
          <span class="title">Title</span>
          <p>First Line <br>
            Second Line
          </p>
          <a href="#!" class="secondary-content"><i class="material-icons">grade</i></a>
        </li>
        <li class="collection-item avatar">
          <i class="material-icons circle">folder</i>
          <span class="title">Title</span>
          <p>First Line <br>
            Second Line
          </p>
          <a href="#!" class="secondary-content"><i class="material-icons">grade</i></a>
        </li>
        <li class="collection-item avatar">
          <i class="material-icons circle green">insert_chart</i>
          <span class="title">Title</span>
          <p>First Line <br>
            Second Line
          </p>
          <a href="#!" class="secondary-content"><i class="material-icons">grade</i></a>
        </li>
        <li class="collection-item avatar">
          <i class="material-icons circle red">play_arrow</i>
          <span class="title">Title</span>
          <p>First Line <br>
            Second Line
          </p>
          <a href="#!" class="secondary-content"><i class="material-icons">grade</i></a>
        </li>
      </ul>
    </div>
    <div class="col s12 m6 l6">
      <div class="card-panel teal lighten-2 oki-card-panel"><h6 class="white-text">店舗からのお知らせ</h6></div>
      <ul class="collection">
        <li class="collection-item avatar">
          <img src="/img/common/top/top1.jpg" alt="" class="circle">
          <span class="title">Title</span>
          <p>First Line <br>
            Second Line
          </p>
          <a href="#!" class="secondary-content"><i class="material-icons">grade</i></a>
        </li>
        <li class="collection-item avatar">
          <i class="material-icons circle">folder</i>
          <span class="title">Title</span>
          <p>First Line <br>
            Second Line
          </p>
          <a href="#!" class="secondary-content"><i class="material-icons">grade</i></a>
        </li>
        <li class="collection-item avatar">
          <i class="material-icons circle green">insert_chart</i>
          <span class="title">Title</span>
          <p>First Line <br>
            Second Line
          </p>
          <a href="#!" class="secondary-content"><i class="material-icons">grade</i></a>
        </li>
        <li class="collection-item avatar">
          <i class="material-icons circle red">play_arrow</i>
          <span class="title">Title</span>
          <p>First Line <br>
            Second Line
          </p>
          <a href="#!" class="secondary-content"><i class="material-icons">grade</i></a>
        </li>
      </ul>
    </div>
    <div class="col s12 m6 l6">
      <div class="card-panel teal lighten-2 oki-card-panel"><h6 class="white-text">キャスト日記</h6></div>
      <ul class="collection">
        <li class="collection-item avatar">
          <img src="/img/common/top/top1.jpg" alt="" class="circle">
          <span class="title">Title</span>
          <p>First Line <br>
            Second Line
          </p>
          <a href="#!" class="secondary-content"><i class="material-icons">grade</i></a>
        </li>
        <li class="collection-item avatar">
          <i class="material-icons circle">folder</i>
          <span class="title">Title</span>
          <p>First Line <br>
            Second Line
          </p>
          <a href="#!" class="secondary-content"><i class="material-icons">grade</i></a>
        </li>
        <li class="collection-item avatar">
          <i class="material-icons circle green">insert_chart</i>
          <span class="title">Title</span>
          <p>First Line <br>
            Second Line
          </p>
          <a href="#!" class="secondary-content"><i class="material-icons">grade</i></a>
        </li>
        <li class="collection-item avatar">
          <i class="material-icons circle red">play_arrow</i>
          <span class="title">Title</span>
          <p>First Line <br>
            Second Line
          </p>
          <a href="#!" class="secondary-content"><i class="material-icons">grade</i></a>
        </li>
      </ul>
    </div>
  </div>
</div>
<?= $this->Html->scriptstart() ?>
$(document).ready(function(){
$('.slider').slider();
$('select').material_select();
});
<?= $this->Html->scriptend() ?>
