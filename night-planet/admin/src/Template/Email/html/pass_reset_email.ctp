<?php
    if (!empty($owner)) :
?>
    <?php
        $url = ADMIN_DOMAIN.'/owner/owners/reset_verify/'.$owner->tokenGenerate();
    ?>
    <?= $owner->name ?>様。<?= MAIL['FROM_NAME'] ?>です。<br>
        パスワードの再設定は以下のURLページより、２４時間以内に行ってください。<br><br>
<?php
    elseif (!empty($cast)) :
?>
    <?php
        $url = ADMIN_DOMAIN.'/cast/casts/reset_verify/'.$cast->tokenGenerate();
    ?>
    <?= $cast->name ?>様。<?= MAIL['FROM_NAME'] ?>です。<br>
        パスワードの再設定は以下のURLページより、２４時間以内に行ってください。<br><br>
<?php
    endif;
?>
<?= $url ?>