<?php
use Cake\Error\Debugger;
use Cake\Routing\Router;

//$url = Router::url(['controller' => 'Owners', 'action' => 'verify', $owner->tokenGenerate()], true);
$url = Router::url('/', true).'owner/verify/'.$owner->tokenGenerate();
?>
こんにちは、<?= $owner->email ?>さん。
メールアドレスを認証をするために以下のURLにアクセスしてください。
<?= $url ?>