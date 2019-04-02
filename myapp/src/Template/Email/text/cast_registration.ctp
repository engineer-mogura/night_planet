<?php
use Cake\Error\Debugger;
use Cake\Routing\Router;

$url = Router::url(['controller' => 'Casts', 'action' => 'verify', $cast->tokenGenerate()], true);
?>
こんにちは、<?= $cast->name ?>さん。
メールアドレスを認証をするために以下のURLにアクセスしてください。
<?= $url ?>