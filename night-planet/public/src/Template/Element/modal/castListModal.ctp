<div id="modal-cast-list" class="modal">
    <form id="save-work-schedule" name="save_work_schedule" method="post" action="/owner/shops/save_work_schedule/">
        <input type="hidden" name="_method" value="POST">
        <input type="hidden" name="id" value="<?=$workSchedule['id']?>">
        <input type="hidden" name="cast_ids" value="">
        <div class="modal-content">
            <h5>スタッフ一覧</h5>
            <div class="chip-box">
                <p>出勤するスタッフを選択してください。</br>
                    <span class="color:red;">前回の出勤メンバーは選択済みです。</span>
                </p>
                <?php $castIds = explode(',', $workSchedule['cast_ids']); ?>
                <?php foreach ($casts as $key => $cast) : ?>
                    <?php $selected = in_array(strval($cast['id']), $castIds, true) ? true : false; ?>
                    <div class="chip-dummy chip-cast<?=$selected ? " back-color" : "" ?>" data-select="<?=$selected ? $cast['id'] : "" ?>" data-cast_id="<?=$cast['id']?>">
                    <img src="<?=isset($cast->schedule_info['castInfo']['icon_name']) ? $cast->schedule_info['castInfo']['profile_path'].DS.$cast->schedule_info['castInfo']['icon_name'] : PATH_ROOT['NO_IMAGE02'] ?>" alt="<?=$cast['name']?>">
                    <?=$cast['nickname']?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="modal-footer">
            <a class="waves-effect waves-light btn saveBtn">登録</a>
            <a href="#!" class="modal-close waves-effect waves-green btn-flat">閉じる</a>
        </div>
    </form>
</div>