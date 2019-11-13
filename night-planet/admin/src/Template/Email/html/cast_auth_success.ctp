<?php
use Cake\Routing\Router;

//$url = Router::url(['controller' => 'Owners', 'action' => 'verify', $owner->tokenGenerate()], true);
$url = ADMIN_DOMAIN.'/cast/casts/login/';
?>
<?= $cast->name ?>様。<?= MAIL['FROM_NAME'] ?>です。認証が完了しました。<br>
すぐにログインして、プロフィール等、情報を入力していきましょう！<br>
ログインは以下のURLから<br>
<?= $url ?>