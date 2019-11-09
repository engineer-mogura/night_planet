<?php
use Cake\Routing\Router;

//$url = Router::url(['controller' => 'Owners', 'action' => 'verify', $owner->tokenGenerate()], true);
$url = ADMIN_DOMAIN.'/owner/owners/verify/'.$owner->tokenGenerate();
?>
<?= $owner->name ?>さん。
ご登録ありがとうございます。<br>
<?= MAIL['FROM_NAME'] ."です。"?>
メールアドレスを認証をするために以下のURLにアクセスしてください。<br>
<?= $url ?>