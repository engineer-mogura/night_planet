<?php

$url = ADMIN_DOMAIN.'/owner/owners/verify/'.$owner->tokenGenerate();
?>
<?= $owner->name ?>様。初めまして、<?= MAIL['FROM_NAME'] ?>です。<br>
メールアドレスの認証をするために以下のURLにアクセスしてください。<br><br>
<?= $url ?>