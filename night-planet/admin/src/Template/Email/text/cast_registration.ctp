<?php
use Cake\Error\Debugger;
use Cake\Routing\Router;

//$url = Router::url(['controller' => 'Shops', 'action' => 'verify', $cast->tokenGenerate()], true);
$url = Router::url('/', true).'cast/casts/verify/'.$cast->tokenGenerate();
?>
こんにちは、<?= $cast->name ?>さん。
メールアドレスを認証をするために以下のURLにアクセスしてください。
<?= $url ?>