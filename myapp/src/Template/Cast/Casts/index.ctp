<div id="wrapper">
    <?= $this->element('modal/calendarModal'); ?>
    <div class="container">
        <div class="row">
            <div id="dashboard" class="col">
                <span id="dummy" style="display: hidden;"></span>
                <?= $this->Flash->render() ?>
                <h5><?= $cast->name.'　所属：'.$cast->shop->name ?></h5>
                <div id="cast" class="row">
                    <div class="col s12 m4 l4">
                        <div class="card">
                            <div class="card-image">
                                <img src="/img/common/top/top1.jpg">
                                <span class="card-title">日記の投稿数</span>
                                <a class="btn-floating halfway-fab waves-effect waves-light red"><i class="material-icons">mode_edit</i></a>
                            </div>
                            <div class="card-content">
                                <p>日記の投稿数：<?=count($cast->diarys)?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col s12 m4 l4">
                        <div class="card">
                            <div class="card-image">
                                <img src="/img/common/top/top1.jpg">
                                <span class="card-title">出勤率</span>
                                <a class="btn-floating halfway-fab waves-effect waves-light red"><i class="material-icons">directions_run</i></a>
                            </div>
                            <div class="card-content">
                                <p>出勤率</p>
                            </div>
                        </div>
                    </div>
                    <div class="col s12 m4 l4">
                        <div class="card">
                            <div class="card-image">
                                <img src="/img/common/top/top1.jpg">
                                <span class="card-title">いいねの数</span>
                                <a class="btn-floating halfway-fab waves-effect waves-light red"><i class="material-icons">favorite_border</i></a>
                            </div>
                            <div class="card-content">
                                <p>いいねの数：<?=$likeTotal?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 l6">
                        <div class="card">
                            <div class="card-image">
                                <img src="/img/common/top/top1.jpg">
                                <span class="card-title">Card Title</span>
                                <a class="btn-floating halfway-fab waves-effect waves-light red"><i class="material-icons">mode_edit</i></a>
                            </div>
                            <div class="card-content">
                                <div id="show-job">
                                    <div style="display:none;">
                                        <input type="hidden" name="job_copy" value='<?=$cast->job ?>'>
                                        <input type="hidden" name="treatment_hidden" value=''>
                                    </div>
                                    <div class="progress">
                                        <div class="determinate" style="width: 70%"></div>
                                    </div>
                                    <span class="right">プロフィールの入力率：70%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col s12 m6 l6">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
