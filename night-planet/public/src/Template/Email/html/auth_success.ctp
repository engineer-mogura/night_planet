<?php
    if (!empty($user)) :
        $url = ADMIN_DOMAIN.'/user/users/login/';
        echo($user->name . '様。' . MAIL['FROM_NAME'] .'です。認証が完了しました。<br>
        ログインして、お気に入りのお店やスタッフを登録して新着情報やスタッフブログを見逃さないようにしよう‼<br>');
        echo('ログインは以下のURLから<br><br>');
    endif;
    echo('<span style="color:red;font-weight: bold;">※ URLがリンクになっていない場合、お手数ですが、リンクをコピーし、ブラウザのURL欄に張り付けてください。</span><br><br>');

    echo($url);