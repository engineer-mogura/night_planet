<div id="info-marquee" class="row card">
  <div class="col s12">
    <?php if(count($updateInfo) > 0): ?>
      <ul id="marquee" class="marquee collection">
        <?php foreach ($updateInfo as $key => $value):?>
          <li class="dismissable">
            <a href="#!" class="">
              <?='【'.$value->created->nice().'】'.$value['content']?>
            </a>
          </li>
        <?php endforeach ?>
      </ul>
    <?php else: ?>
      <ul id="marquee" class="marquee collection">
        <li class="dismissable">
          <a href="#!" class="">
            最近の更新はありません…。
          </a>
        </li>
      </ul>
    <?php endif; ?>
  </div>
</div>