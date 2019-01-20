<?php
use Cake\Error\Debugger;
use Cake\Routing\Router;

$url = Router::url(['controller' => 'Users', 'action' => 'verify', $user->tokenGenerate()], true);
?>
こんにちは、<?= $user->name ?>さん。
メールアドレスを認証をするために以下のURLにアクセスしてください。
<?= $url ?>