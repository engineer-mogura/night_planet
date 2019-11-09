<?php
use Cake\Routing\Router;

//$url = Router::url(['controller' => 'Owners', 'action' => 'verify', $owner->tokenGenerate()], true);
$url = ADMIN_DOMAIN.'/cast/casts/verify/'.$cast->tokenGenerate();
?>
<?= $cast->name ?>さん。
ご登録ありがとうございます。<br>
<?= MAIL['FROM_NAME'] ."です。"?>
メールアドレスを認証をするために以下のURLにアクセスしてください。<br>
<?= $url ?>