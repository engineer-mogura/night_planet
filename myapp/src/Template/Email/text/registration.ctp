<?php
use Cake\Error\Debugger;
use Cake\Routing\Router;

$this->log('registration.ctp',"debug");
$this->log($owner,"debug");
$this->log($this,"debug");
$url = Router::url(['controller' => 'Registe', 'action' => 'verify', $owner->tokenGenerate()], true);
?>
こんにちは、<?= $owner->name ?>さん。
メールアドレスを認証をするために以下のURLにアクセスしてください。
<?= $url ?>