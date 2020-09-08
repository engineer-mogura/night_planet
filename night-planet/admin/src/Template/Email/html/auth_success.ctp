<?php
    if (!empty($owner)) :
?>
    <?php
        $url = ADMIN_DOMAIN.'/owner/owners/login/';
    ?>
    <?= $owner->name ?>様。<?= MAIL['FROM_NAME'] ?>です。認証が完了しました。<br>
        すぐにログインして、店舗情報を入力していきましょう！<br>
        ログインは以下のURLから<br><br>
<?php
    elseif (!empty($cast)) :
?>
    <?php
        $url = ADMIN_DOMAIN.'/cast/casts/login/';
    ?>
    <?= $cast->name ?>様。<?= MAIL['FROM_NAME'] ?>です。認証が完了しました。<br>
        すぐにログインして、プロフィール等、情報を入力していきましょう！<br>
        ログインは以下のURLから<br><br>
<?php
    endif;
?>
<span style="color:red;font-weight: bold;">※ URLがリンクになっていない場合、お手数ですが、リンクをコピーし、ブラウザのURL欄に張り付けてください。</span><br><br>

<?= $url ?>