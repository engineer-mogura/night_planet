<?php
use Cake\Routing\Router;

//$url = Router::url(['controller' => 'Owners', 'action' => 'verify', $owner->tokenGenerate()], true);
$url = ADMIN_DOMAIN.'/cast/casts/verify/'.$cast->tokenGenerate();
?>
<?= $cast->name ?>様。初めまして、<?= MAIL['FROM_NAME'] ?>です。<br>
【<?=$shop_name?>】様より、スタッフ登録のご案内があります。<br>
メールアドレスの認証をするために以下のURLにアクセスしてください。<br>
認証後のログインは、【Email】入力欄に<?= $cast->name ?>様のメールアドレス。<br>
【Password】入力欄には、初回ログイン用で【<span style="color:red;font-weight: bold;">pass1234</span>】でログインください。<br>
<span style="color:red;font-weight: bold;">ログイン後は、パスワードの変更を強くお勧めします。</span><br><br>
<?= $url ?>