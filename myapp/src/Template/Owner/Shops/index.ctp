<div id="wrapper">
    <div id="shop-edit" class="container">
        <span id="dummy" style="display: hidden;"></span>
        <div class="row">
            <div class="col s12 m12 l12 edit-menu">
                <!-- <?= $this->Form->hidden('activeTab',['id'=>'activeTab','value' => $activeTab]); ?> -->
                <ul class="tabs">
                    <li class="tab"><a class="active" href="#top-image">トップ画像</a></li>
                    <li class="tab"><a href="#catch">キャッチコピー</a></li>
                    <li class="tab"><a href="#coupon">クーポン</a></li>
                    <li class="tab"><a href="#cast">キャスト</a></li>
                    <li class="tab"><a href="#tenpo">店舗情報</a></li>
                    <li class="tab"><a href="#gallery">店舗ギャラリー</a></li>
                    <li class="tab"><a href="#map">マップ</a></li>
                    <li class="tab"><a href="#job">求人情報</a></li>
                </ul>
            </div>
            <!-- トップ画像タブ -->
            <?= $this->element('shopEdit/top-image'); ?>
            <!-- キャッチコピータブ -->
            <?= $this->element('shopEdit/catch'); ?>
            <!-- クーポンタブ -->
            <?= $this->element('shopEdit/coupon'); ?>
            <!-- キャストタブ -->
            <?= $this->element('shopEdit/cast'); ?>
            <!-- 店舗情報タブ -->
            <?= $this->element('shopEdit/tenpo'); ?>
            <!-- ギャラリータブ -->
            <?= $this->element('shopEdit/gallery'); ?>
            <div id="map" class="col s12">
            <h5>マップ</h5>
            </div>
            <!-- 求人情報タブ -->
            <?= $this->element('shopEdit/job'); ?>
        </div>
    </div>
</div>
<?= $this->element('photoSwipe'); ?>
