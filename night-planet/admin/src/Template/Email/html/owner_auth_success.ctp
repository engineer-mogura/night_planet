<?php
use Cake\Routing\Router;

//$url = Router::url(['controller' => 'Owners', 'action' => 'verify', $owner->tokenGenerate()], true);
$url = ADMIN_DOMAIN.'/owner/owners/login/';
?>
<?= $owner->name ?>様。<?= MAIL['FROM_NAME'] ?>です。認証が完了しました。<br>
すぐにログインして、店舗情報を入力していきましょう！<br>
ログインは以下のURLから<br>
<?= $url ?>