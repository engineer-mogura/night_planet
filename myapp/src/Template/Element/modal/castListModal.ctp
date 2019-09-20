<div id="modal-cast-list" class="modal">
    <form id="save-work-schedule" name="save_work_schedule" method="post" action="/owner/shops/save_work_schedule/">
        <input type="hidden" name="_method" value="POST">
        <input type="hidden" name="id" value="<?=$WorkSchedule['id']?>">
        <input type="hidden" name="cast_ids" value="">
        <div class="modal-content">
            <h5>キャスト一覧</h5>
            <div class="chip-box">
                <p>出勤するキャストを選択してください。</br>
                    <span class="color:red;">前回の出勤メンバーは選択済みです。</span>
                </p>
                <?php $castIds = explode(',', $WorkSchedule['cast_ids']); ?>
                <?php foreach ($castList as $key => $cast) : ?>
                    <?php $selected = in_array(strval($cast['id']), $castIds, true) ? true : false; ?>
                    <div class="chip-dummy chip-cast<?=$selected ? " back-color" : "" ?>" data-select="<?=$selected ? $cast['id'] : "" ?>" data-cast_id="<?=$cast['id']?>">
                    <img src="<?=isset($cast['icon_name']) ? $cast['profile_path'].DS.$cast['icon_name'] : PATH_ROOT['NO_IMAGE02'] ?>" alt="<?=$cast['name']?>">
                    <?=$cast['0']['nickname']?>
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