<?php
use Cake\Error\Debugger;
use Cake\Routing\Router;

$url = Router::url(['controller' => 'Owners', 'action' => 'verify', $owner->tokenGenerate()], true);
?>
こんにちは、<?= $owner->name ?>さん。
メールアドレスを認証をするために以下のURLにアクセスしてください。test
<?= $url ?>