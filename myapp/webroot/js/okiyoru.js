
document.write("<script type='text/javascript' src='/js/util.js'></script>");
        /** */
$(document).ready(function(){
    // 初期化
    initialize();
});

/* オーナーデフォルト start */
/* トップ画像 関連処理 start */

/**
 * トップ画像 通常表示、変更表示切替え処理
 * @param  {} obj
 */
function topImageChangeBtn(obj){
        $(obj).find('#top-image-show').attr({width:'',height:'',src:''});
        $(obj).find('[name="top_image"]').val('');
    if ($('#edit-top-image').css('display') == 'block') {

        $(obj).find("#edit-top-image").hide();
        $(obj).find("#show-top-image").show();
    } else {
        $(obj).find('#edit-top-image').show();
        $(obj).find("#show-top-image").hide();
    }
}

function imgDisp() {
    var file = $("#top-image-file").prop("files")[0];

    //画像ファイルかチェック
    if (file["type"] != "image/jpeg" && file["type"] != "image/png" && file["type"] != "image/gif") {
        alert("jpgかpngかgifファイルを選択してください");
        $("#top-image-file").val('');
        return false;
    }

    var fileReader = new FileReader();
    fileReader.onloadend = function() {
        //選択した画像をimg要素に表示
        $('#top-image-show').attr({width:'100%',height:'300',src:fileReader.result})
                                .css({'background-color':'gray','object-fit':'contain'});
        $('#top-image-preview').attr('src',fileReader.result);
    }
    fileReader.readAsDataURL(file);
}

/**
 * トップ画像 登録ボタン処理
 */
function topImageSaveBtn(){

    //加工後の横幅を800pxに設定
    var processingWidth = 800;

    //加工後の容量を100KB以下に設定
    var processingCapacity = 100000;

    //ファイル選択済みかチェック
    var fileCheck = $("#top-image-file").val().length;
    if (fileCheck === 0) {
        alert("画像ファイルを選択してください");
        return false;
    }
    if(!confirm('こちらの画像に変更でよろしいですか？')) {
        return false;
    }
    //imgタグに表示した画像をimageオブジェクトとして取得
    var image = new Image();
    image.src = $("#top-image-preview").attr("src");

    var h;
    var w;

    //原寸横幅が加工後横幅より大きければ、縦横比を維持した縮小サイズを取得
    if(processingWidth < image.width) {
        w = processingWidth;
        h = image.height * (processingWidth / image.width);

    //原寸横幅が加工後横幅以下なら、原寸サイズのまま
    } else {
        w = image.width;
        h = image.height;
    }

    //取得したサイズでcanvasに描画
    var canvas = $("#top-image-canvas");
    var ctx = canvas[0].getContext("2d");
    $("#top-image-canvas").attr("width", w);
    $("#top-image-canvas").attr("height", h);
    ctx.drawImage(image, 0, 0, w, h);

    //canvasに描画したデータを取得
    var canvasImage = $("#top-image-canvas").get(0);

    //オリジナル容量(画質落としてない場合の容量)を取得
    var originalBinary = canvasImage.toDataURL("image/jpeg"); //画質落とさずバイナリ化
    var originalBlob = base64ToBlob(originalBinary); //画質落としてないblobデータをアップロード用blobに設定
    console.log(originalBlob["size"]);

    //オリジナル容量blobデータをアップロード用blobに設定
    var uploadBlob = originalBlob;

    //オリジナル容量が加工後容量以上かチェック
    if(processingCapacity <= originalBlob["size"]) {
        //加工後容量以下に落とす
        var capacityRatio = processingCapacity / originalBlob["size"];
        var processingBinary = canvasImage.toDataURL("image/jpeg", capacityRatio); //画質落としてバイナリ化
        uploadBlob = base64ToBlob(processingBinary); //画質落としたblobデータをアップロード用blobに設定
        console.log(capacityRatio);
        console.log(uploadBlob["size"]);
    }

    //アップロード用blobをformDataに設定
    var $form = $('#edit-top-image');
    var formData = new FormData($form.get()[0]);
    formData.append("top_image_file", uploadBlob);


    //通常のアクションをキャンセルする
    event.preventDefault();

    $.ajax({
        url : $form.attr('action'), //Formのアクションを取得して指定する
        type: $form.attr('method'),//Formのメソッドを取得して指定する
        data: formData, //データにFormがserialzeした結果を入れる
        dataType: 'json', //データにFormがserialzeした結果を入れる
        processData: false,
        contentType: false,
        timeout: 1000000,
        beforeSend : function(xhr, settings){
            //Buttonを無効にする
            $($form).find('.saveBtn').attr('disabled' , true);
            //処理中のを通知するアイコンを表示する
            $("#dummy").load("/module/Preloader.ctp");
        },
        complete: function(xhr, textStatus){
            //処理中アイコン削除
            $('.preloader-wrapper').remove();
            $($form).find('.saveBtn').attr('disabled' , false);
        },
        success: function (response, textStatus, xhr) {

            // OKの場合
            if(response.success){
                var $objWrapper = $("#wrapper");
                $($objWrapper).replaceWith(response.html);
                    $.notifyBar({
                    cssClass: 'success',
                    html: response.message
                });
                initialize();
            }else{
            // NGの場合
                $.notifyBar({
                    cssClass: 'error',
                    html: response.error
                });
            }
        },
        error : function(response, textStatus, xhr){
            $($form).find('.saveBtn').attr('disabled' , false);
            $.notifyBar({
                cssClass: 'error',
                html: "エラーが発生しました。ステータス：" + textStatus
            });
            console.log(response);
            console.log(textStatus);
            console.log(xhr);
        }
    });
}

/**
 * トップ画像 削除ボタン処理
 */
function topImageDeleteBtn(){

    var $form = $('#delete-top-image');

    if (!confirm('トップ画像を削除してもよろしいですか？')) {
        return false;
    }
    //通常のアクションをキャンセルする
    event.preventDefault();

    $.ajax({
        url : $form.attr('action'), //Formのアクションを取得して指定する
        type: $form.attr('method'),//Formのメソッドを取得して指定する
        data: $form.serialize(), //データにFormがserialzeした結果を入れる
        dataType: 'json', //データにFormがserialzeした結果を入れる
        timeout: 100000,
        beforeSend : function(xhr, settings){
            //Buttonを無効にする
            $($form).find('.deleteBtn').attr('disabled' , true);
            //処理中のを通知するアイコンを表示する
            $("#dummy").load("/module/Preloader.ctp");
        },
        complete: function(xhr, textStatus){
            //処理中アイコン削除
            $('.preloader-wrapper').remove();
            $($form).find('.deleteBtn').attr('disabled' , false);
        },
        success: function (response, textStatus, xhr) {

            // OKの場合
            if(response.success){
                var $objWrapper = $("#wrapper");
                $($objWrapper).replaceWith(response.html);
                    $.notifyBar({
                    cssClass: 'success',
                    html: response.message
                });
                initialize();
            }else{
            // NGの場合
                $.notifyBar({
                    cssClass: 'error',
                    html: response.error
                });
            }
        },
        error : function(response, textStatus, xhr){
            $($form).find('.deleteBtn').attr('disabled' , false);
            $.notifyBar({
                cssClass: 'error',
                html: "エラーが発生しました。ステータス：" + textStatus
            });
        }
    });
}
/* トップ画像 関連処理 end */

/* キャッチコピー 関連処理 start */

/**
 * キャッチコピー 通常表示、変更表示切替え処理
 * @param  {} obj
 */
function catchChangeBtn(obj){
    if ($('#edit-catch').css('display') == 'block') {
        // $(obj).find('[name="catch"]').val(""); //初期値を削除しない
        $(obj).find("#edit-catch").hide();
        $(obj).find("#show-catch").show();
    } else {
        // catchという変数にしたかったがcatchはjsで予約語になる
        var catchCopy = $(obj).find('input[name="catch_copy"]').val();
        $('textarea[name="catch"]').val(catchCopy);
        $(obj).find('#edit-catch').show();
        $(obj).find("#show-catch").hide();
    }
}

/**
 * キャッチコピー 登録ボタン処理
 */
function catchSaveBtn(){

    var $form = $('#edit-catch');
    if($form.find('textarea[name="catch"]').val() == '') {
        alert('キャッチコピーを入力してください。');
        return false;
    }
    if(!confirm('キャッチコピーを変更してもよろしいですか？')) {
        return false;
    }

    //通常のアクションをキャンセルする
    event.preventDefault();

    $.ajax({
        url : $form.attr('action'), //Formのアクションを取得して指定する
        type: $form.attr('method'),//Formのメソッドを取得して指定する
        data: $form.serialize(), //データにFormがserialzeした結果を入れる
        dataType: 'json', //データにFormがserialzeした結果を入れる
        timeout: 100000,
        beforeSend : function(xhr, settings){
            //Buttonを無効にする
            $($form).find('.saveBtn').attr('disabled' , true);
            //処理中のを通知するアイコンを表示する
            $("#dummy").load("/module/Preloader.ctp");
        },
        complete: function(xhr, textStatus){
            //処理中アイコン削除
            $('.preloader-wrapper').remove();
            $($form).find('.saveBtn').attr('disabled' , false);
        },
        success: function (response, textStatus, xhr) {

            // OKの場合
            if(response.success){
                var $objWrapper = $("#wrapper");
                $($objWrapper).replaceWith(response.html);
                    $.notifyBar({
                    cssClass: 'success',
                    html: response.message
                });
                initialize();
            }else{
            // NGの場合
                $.notifyBar({
                    cssClass: 'error',
                    html: response.error
                });
            }
        },
        error : function(response, textStatus, xhr){
            $($form).find('.saveBtn').attr('disabled' , false);
            $.notifyBar({
                cssClass: 'error',
                html: "エラーが発生しました。ステータス：" + textStatus
            });
        }
    });
}

/**
 * キャッチコピー 削除ボタン処理
 */
function catchDeleteBtn(){

    if(!confirm('キャッチコピーを削除してもよろしいですか？')) {
        return false;
    }

    var $form = $('#delete-catch');

    //通常のアクションをキャンセルする
    event.preventDefault();

    $.ajax({
        url : $form.attr('action'), //Formのアクションを取得して指定する
        type: $form.attr('method'),//Formのメソッドを取得して指定する
        data: $form.serialize(), //データにFormがserialzeした結果を入れる
        dataType: 'json', //データにFormがserialzeした結果を入れる
        timeout: 100000,
        beforeSend : function(xhr, settings){
            //Buttonを無効にする
            $($form).find('.deleteBtn').attr('disabled' , true);
            //処理中のを通知するアイコンを表示する
            $("#dummy").load("/module/Preloader.ctp");
        },
        complete: function(xhr, textStatus){
            //処理中アイコン削除
            $('.preloader-wrapper').remove();
            $($form).find('.deleteBtn').attr('disabled' , false);
        },
        success: function (response, textStatus, xhr) {

            // OKの場合
            if(response.success){
                var $objWrapper = $("#wrapper");
                $($objWrapper).replaceWith(response.html);
                    $.notifyBar({
                    cssClass: 'success',
                    html: response.message
                });
                initialize();
            }else{
            // NGの場合
                $.notifyBar({
                    cssClass: 'error',
                    html: response.error
                });
            }
        },
        error : function(response, textStatus, xhr){
            $($form).find('.deleteBtn').attr('disabled' , false);
            $.notifyBar({
                cssClass: 'error',
                html: "エラーが発生しました。ステータス：" + textStatus
            });
        }
    });
}

/* キャッチコピー 関連処理 end */

/* クーポン 関連処理 start */

/**
 * クーポン 通常表示、変更表示切替え処理
 * @param  {} obj
 */
function couponChangeBtn(obj){
    $("html,body").animate({scrollTop:0});

    if ($('#edit-coupon').css('display') == 'block') {
        // $(obj).find('[name="coupon"]').val(""); //初期値を削除しない
        $(obj).find("#edit-coupon").hide();
        $(obj).find("#show-coupon").show();
    } else {
        //$("html,body").animate({scrollTop:0});
        // 編集用のフォームへ変更する
        if($('input[name="coupon_add"]').length) {
            $('input[name="coupon_add"]').attr('name', 'coupon_edit');
        }
        $(obj).find('input[name="status"]').removeAttr('checked');
        var $check = $('input[name="check_coupon"]:checked');
        var coupon = JSON.parse($($check).closest('.coupon-box').find('input[name="coupon_copy"]').val());
        var from = $('#from-day').pickadate('picker'); // Date Picker
        var to = $('#to-day').pickadate('picker'); // Date Picker
        from.set('select', [2000, 1, 1]);
        from.set('select', new Date(2000, 1, 1));
        from.set('select', coupon['from_day'], { format: 'yyyy-mm-dd' });
        to.set('select', [2000, 1, 1]);
        to.set('select', new Date(2000, 1, 1));
        to.set('select', coupon['to_day'], { format: 'yyyy-mm-dd' });
        $(obj).find('input[name="coupon_edit_id"]').val(coupon['id']);
        $(obj).find('input[name="title"]').val(coupon['title']);
        $(obj).find('textarea[name="content"]').val(coupon['content']);
        if(coupon['status'] == 1) {
            $('input[name="status"]').attr('checked', 'checked');
        }
        $(obj).find('#edit-coupon').show();
        $(obj).find("#show-coupon").hide();
        Materialize.updateTextFields(); // インプットフィールドの初期化
    }
}

/**
 * クーポン 追加ボタン処理
 * @param  {} obj
 */
function couponAddBtn(obj){
    if ($('#edit-coupon').css('display') == 'block') {
        // $(obj).find('[name="coupon"]').val(""); //初期値を削除しない
        $(obj).find("#edit-coupon").hide();
        $(obj).find("#show-coupon").show();
    } else {
        $("html,body").animate({scrollTop:0});
        // フォームの中身はすべて消す
        $(obj).find('#from-day').pickadate('picker').set("select",null);
        $(obj).find('#to-day').pickadate('picker').set("select",null);
        $(obj).find('#coupon-title').val('');
        $(obj).find('#coupon-content').val('');
        $(obj).find('input[name="status"]').removeAttr('checked');
        // 追加用のフォームへ変更する
        if($('input[name="coupon_edit"]').length) {
            $('input[name="coupon_edit"]').attr('name', 'coupon_add');
        }
        $(obj).find('#edit-coupon').show();
        $(obj).find("#show-coupon").hide();
    }
}

/**
 * クーポン 登録ボタン処理
 */
function couponSaveBtn(){

    if (!confirm('こちらのクーポン内容に変更でよろしいですか？')) {
        return false;
    }

    var $form = $('form[name="edit_coupon"]');
    var from_day = $($form).find('input[name="from_day_submit"]').val();
    var to_day = $($form).find('input[name="to_day_submit"]').val();
    $($form).find('input[name="from_day"]').val(from_day);
    $($form).find('input[name="to_day"]').val(to_day);

    //通常のアクションをキャンセルする
    event.preventDefault();

    $.ajax({
        url : $form.attr('action'), //Formのアクションを取得して指定する
        type: $form.attr('method'),//Formのメソッドを取得して指定する
        data: $form.serialize(), //データにFormがserialzeした結果を入れる
        dataType: 'json', //データにFormがserialzeした結果を入れる
        timeout: 100000,
        beforeSend : function(xhr, settings){
            //Buttonを無効にする
            $($form).find('.saveBtn').attr('disabled' , true);
            //処理中のを通知するアイコンを表示する
            $("#dummy").load("/module/Preloader.ctp");
        },
        complete: function(xhr, textStatus){
            //処理中アイコン削除
            $('.preloader-wrapper').remove();
            $($form).find('.saveBtn').attr('disabled' , false);
        },
        success: function (response, textStatus, xhr) {

            // OKの場合
            if(response.success){
                var $objWrapper = $("#wrapper");
                $($objWrapper).replaceWith(response.html);
                    $.notifyBar({
                    cssClass: 'success',
                    html: response.message
                });
                initialize();
            }else{
            // NGの場合
                $.notifyBar({
                    cssClass: 'error',
                    html: response.error
                });
            }
        },
        error : function(response, textStatus, xhr){
            $($form).find('.saveBtn').attr('disabled' , false);
            $.notifyBar({
                cssClass: 'error',
                html: "エラーが発生しました。ステータス：" + textStatus
            });
        }
    });
}

/**
 * クーポン 削除ボタン処理
 */
function couponDeleteBtn(){

    var $check = $('input[name="check_coupon"]:checked');
    var $form = $($check).closest('.coupon-box').find('form[name="delete_coupon"]');
    var title = $($form).find('input[name="coupon_title"]').val();

    if (!confirm('【'+title+'】\n選択したクーポンを削除してもよろしいですか？')) {
        return false;
    }
    //通常のアクションをキャンセルする
    event.preventDefault();

    $.ajax({
        url : $form.attr('action'), //Formのアクションを取得して指定する
        type: $form.attr('method'),//Formのメソッドを取得して指定する
        data: $form.serialize(), //データにFormがserialzeした結果を入れる
        dataType: 'json', //データにFormがserialzeした結果を入れる
        timeout: 100000,
        beforeSend : function(xhr, settings){
            //Buttonを無効にする
            $($form).find('.deleteBtn').attr('disabled' , true);
            //処理中のを通知するアイコンを表示する
            $("#dummy").load("/module/Preloader.ctp");
        },
        complete: function(xhr, textStatus){
            //処理中アイコン削除
            $('.preloader-wrapper').remove();
            $($form).find('.deleteBtn').attr('disabled' , false);
        },
        success: function (response, textStatus, xhr) {

            // OKの場合
            if(response.success){
                var $objWrapper = $("#wrapper");
                $($objWrapper).replaceWith(response.html);
                    $.notifyBar({
                    cssClass: 'success',
                    html: response.message
                });
                initialize();
            }else{
            // NGの場合
                $.notifyBar({
                    cssClass: 'error',
                    html: response.error
                });
            }
        },
        error : function(response, textStatus, xhr){
            $($form).find('.deleteBtn').attr('disabled' , false);
            $.notifyBar({
                cssClass: 'error',
                html: "エラーが発生しました。ステータス：" + textStatus
            });
        }
    });
}
/* クーポン 関連処理 end */


/* キャスト 関連処理 start */

/**
 * キャスト 通常表示、変更表示切替え処理
 * @param  {} obj
 */
function castChangeBtn(obj){
    $("html,body").animate({scrollTop:0});

    if ($('#edit-cast').css('display') == 'block') {
        // $(obj).find('[name="cast"]').val(""); //初期値を削除しない
        $(obj).find("#edit-cast").hide();
        $(obj).find("#show-cast").show();
    } else {
        //$("html,body").animate({scrollTop:0});
        // 編集用のフォームへ変更する
        if($('input[name="cast_add"]').length) {
            $('input[name="cast_add"]').attr('name', 'cast_edit');
        }
        $(obj).find('input[name="status"]').removeAttr('checked');
        var $check = $('input[name="check_cast"]:checked');
        var cast = JSON.parse($($check).closest('.cast-box').find('input[name="cast_copy"]').val());
        $(obj).find('input[name="cast_edit_id"]').val(cast['id']);
        $(obj).find('input[name="name"]').val(cast['name']);
        $(obj).find('input[name="nickname"]').val(cast['nickname']);
        $(obj).find('input[name="email"]').val(cast['email']);
        if(cast['status'] == 1) {
            $('input[name="status"]').attr('checked', 'checked');
        }
        $(obj).find('#edit-cast').show();
        $(obj).find("#show-cast").hide();
        Materialize.updateTextFields(); // インプットフィールドの初期化
    }
}

/**
 * キャスト 追加ボタン処理
 * @param  {} obj
 */
function castAddBtn(obj){
    if ($('#edit-cast').css('display') == 'block') {
        // $(obj).find('[name="cast"]').val(""); //初期値を削除しない
        $(obj).find("#edit-cast").hide();
        $(obj).find("#show-cast").show();
    } else {
        $("html,body").animate({scrollTop:0});
        // フォームの中身はすべて消す
        $(obj).find('input[name="cast_edit_id"]').val('');
        $(obj).find('input[name="name"]').val('');
        $(obj).find('input[name="nickname"]').val('');
        $(obj).find('input[name="email"]').val('');
        $(obj).find('input[name="status"]').removeAttr('checked');
        // 追加用のフォームへ変更する
        if($('input[name="cast_edit"]').length) {
            $('input[name="cast_edit"]').attr('name', 'cast_add');
        }
        $(obj).find('#edit-cast').show();
        $(obj).find("#show-cast").hide();
    }
}

/**
 * キャスト 登録ボタン処理
 */
function castSaveBtn(){

    if (!confirm('こちらのキャスト内容に変更でよろしいですか？')) {
        return false;
    }

    var $form = $('form[name="edit_cast"]');

    //通常のアクションをキャンセルする
    event.preventDefault();

    $.ajax({
        url : $form.attr('action'), //Formのアクションを取得して指定する
        type: $form.attr('method'),//Formのメソッドを取得して指定する
        data: $form.serialize(), //データにFormがserialzeした結果を入れる
        dataType: 'json', //データにFormがserialzeした結果を入れる
        timeout: 100000,
        beforeSend : function(xhr, settings){
            //Buttonを無効にする
            $($form).find('.saveBtn').attr('disabled' , true);
            //処理中のを通知するアイコンを表示する
            $("#dummy").load("/module/Preloader.ctp");
        },
        complete: function(xhr, textStatus){
            //処理中アイコン削除
            $('.preloader-wrapper').remove();
            $($form).find('.saveBtn').attr('disabled' , false);
        },
        success: function (response, textStatus, xhr) {

            // OKの場合
            if(response.success){
                var $objWrapper = $("#wrapper");
                $($objWrapper).replaceWith(response.html);
                    $.notifyBar({
                    cssClass: 'success',
                    html: response.message
                });
                initialize();
            }else{
            // NGの場合
                $.notifyBar({
                    cssClass: 'error',
                    html: response.error
                });
            }
        },
        error : function(response, textStatus, xhr){
            $($form).find('.saveBtn').attr('disabled' , false);
            $.notifyBar({
                cssClass: 'error',
                html: "エラーが発生しました。ステータス：" + textStatus
            });
        }
    });
}

/**
 * キャスト 削除ボタン処理
 */
function castDeleteBtn(){

    var $check = $('input[name="check_cast"]:checked');
    var $form = $($check).closest('.cast-box').find('form[name="delete_cast"]');
    var title = $($form).find('input[name="cast_title"]').val();

    if (!confirm('【'+title+'】\n選択したキャストを削除してもよろしいですか？')) {
        return false;
    }
    //通常のアクションをキャンセルする
    event.preventDefault();

    $.ajax({
        url : $form.attr('action'), //Formのアクションを取得して指定する
        type: $form.attr('method'),//Formのメソッドを取得して指定する
        data: $form.serialize(), //データにFormがserialzeした結果を入れる
        dataType: 'json', //データにFormがserialzeした結果を入れる
        timeout: 100000,
        beforeSend : function(xhr, settings){
            //Buttonを無効にする
            $($form).find('.deleteBtn').attr('disabled' , true);
            //処理中のを通知するアイコンを表示する
            $("#dummy").load("/module/Preloader.ctp");
        },
        complete: function(xhr, textStatus){
            //処理中アイコン削除
            $('.preloader-wrapper').remove();
            $($form).find('.deleteBtn').attr('disabled' , false);
        },
        success: function (response, textStatus, xhr) {

            // OKの場合
            if(response.success){
                var $objWrapper = $("#wrapper");
                $($objWrapper).replaceWith(response.html);
                    $.notifyBar({
                    cssClass: 'success',
                    html: response.message
                });
                initialize();
            }else{
            // NGの場合
                $.notifyBar({
                    cssClass: 'error',
                    html: response.error
                });
            }
        },
        error : function(response, textStatus, xhr){
            $($form).find('.deleteBtn').attr('disabled' , false);
            $.notifyBar({
                cssClass: 'error',
                html: "エラーが発生しました。ステータス：" + textStatus
            });
        }
    });
}
/* キャスト 関連処理 end */


/* 店舗情報 関連処理 start */
/**
 * 店舗情報 通常表示、変更表示切替え処理
 * @param  {} obj
 */
function tenpoChangeBtn(obj){
    if ($('#edit-tenpo').css('display') == 'block') {
        // $(obj).find('[name="tenpo"]').val(""); //初期値を削除しない
        $(obj).find("#edit-tenpo").hide();
        $(obj).find("#show-tenpo").show();
    } else {
        var tenpo = JSON.parse($(obj).find('input[name="tenpo_copy"]').val());
        var $from = $(obj).find('#bus-from-time').pickatime().pickatime('picker');
        var $to = $(obj).find('#bus-to-time').pickatime().pickatime('picker');
        var from = new Date(tenpo['bus_from_time']);
        var to = new Date(tenpo['bus_to_time']);
        $($from).val(toDoubleDigits(from.getHours()) + ":" + toDoubleDigits(from.getMinutes()));
        $($to).val(toDoubleDigits(to.getHours()) + ":" + toDoubleDigits(to.getMinutes()));
        $(obj).find('input[name="tenpo_edit_id"]').val(tenpo['id']);
        $(obj).find('input[name="name"]').val(tenpo['name']);
        $(obj).find('input[name="pref21"]').val(tenpo['pref21']);
        $(obj).find('input[name="addr21"]').val(tenpo['addr21']);
        $(obj).find('input[name="strt21"]').val(tenpo['strt21']);
        $(obj).find('input[name="tel"]').val(tenpo['tel']);
        $(obj).find('input[name="bus_hosoku"]').val(tenpo['bus_hosoku']);
        $(obj).find('textarea[name="staff"]').val(tenpo['staff']);
        $(obj).find('textarea[name="system"]').val(tenpo['system']);
        //クレジットフィールドにあるタグを取得して配列にセット
        var data = JSON.parse($(obj).find('input[name="credit_hidden"]').val());
        // クレジットフィールドの初期化
        $('.chips-initial').material_chip({
            data:data
        });
        $(obj).find('#edit-tenpo').show();
        $(obj).find("#show-tenpo").hide();
        Materialize.updateTextFields(); // インプットフィールドの初期化
        $($(obj).find('div[name="credit"]')).find('input').prop('disabled',true);

    }
}

/**
 * 店舗情報 登録ボタン処理
 */
function tenpoSaveBtn(){

    if (!confirm('こちらの店舗内容でよろしいですか？')) {
        return false;
    }

    var $form = $('form[name="edit_tenpo"]');
    var tagData = $($form).find('.chips').material_chip('data');
    if (tagData.length > 0) {
        var csvTag = "";
        for (var i = 0; i < tagData.length; i++) {
            csvTag = csvTag += tagData[i].tag + ",";
        }
        csvTag = csvTag.slice(0, -1);
        $($form).find('input[name="credit"]').val(csvTag);
    }
    //通常のアクションをキャンセルする
    event.preventDefault();

    $.ajax({
        url : $form.attr('action'), //Formのアクションを取得して指定する
        type: $form.attr('method'),//Formのメソッドを取得して指定する
        data: $form.serialize(), //データにFormがserialzeした結果を入れる
        dataType: 'json', //データにFormがserialzeした結果を入れる
        timeout: 100000,
        beforeSend : function(xhr, settings){
            //Buttonを無効にする
            $($form).find('.saveBtn').attr('disabled' , true);
            //処理中のを通知するアイコンを表示する
            $("#dummy").load("/module/Preloader.ctp");
        },
        complete: function(xhr, textStatus){
            //処理中アイコン削除
            $('.preloader-wrapper').remove();
            $($form).find('.saveBtn').attr('disabled' , false);
        },
        success: function (response, textStatus, xhr) {

            // OKの場合
            if(response.success){
                var $objWrapper = $("#wrapper");
                $($objWrapper).replaceWith(response.html);
                    $.notifyBar({
                    cssClass: 'success',
                    html: response.message
                });
                initialize();
            }else{
            // NGの場合
                $.notifyBar({
                    cssClass: 'error',
                    html: response.error
                });
            }
        },
        error : function(response, textStatus, xhr){
            $($form).find('.saveBtn').attr('disabled' , false);
            $.notifyBar({
                cssClass: 'error',
                html: "エラーが発生しました。ステータス：" + textStatus
            });
        }
    });
}
/* 店舗情報 関連処理 end */

/* 求人情報 関連処理 start */
/**
 * 求人情報 通常表示、変更表示切替え処理
 * @param  {} obj
 */
function jobChangeBtn(obj){
    if ($('#edit-job').css('display') == 'block') {
        // $(obj).find('[name="job"]').val(""); //初期値を削除しない
        $(obj).find("#edit-job").hide();
        $(obj).find("#show-job").show();
    } else {
        var job = JSON.parse($(obj).find('input[name="job_copy"]').val());
        $(obj).find('input[name="job_edit_id"]').val(job['id']);
        $(obj).find('p[name="name"]').text($(obj).find('.show-job-name').text());
        $(obj).find('select[name="industry"]').val(job['industry']);
        $(obj).find('select[name="job_type"]').val(job['job_type']);

        var $from = $('#work-from-time').pickatime().pickatime('picker');
        var $to = $('#work-to-time').pickatime().pickatime('picker');
        var from = new Date(job['work_from_time']);
        var to = new Date(job['work_to_time']);
        $($from).val(toDoubleDigits(from.getHours()) + ":" + toDoubleDigits(from.getMinutes()));
        $($to).val(toDoubleDigits(to.getHours()) + ":" + toDoubleDigits(to.getMinutes()));
        $(obj).find('input[name="work_time_hosoku"]').val(job['work_time_hosoku']);
        $(obj).find('input[name="qualification_hosoku"]').val(job['qualification_hosoku']);
        $(obj).find('select[name="from_age"]').val(job['from_age']);
        $(obj).find('select[name="to_age"]').val(job['to_age']);
        var dayArray = job['holiday'].split(",");
        $.each(dayArray, function(index, val) {
            $(obj).find('input[name="holiday[]"]').each(function(i,o){
                if (val == $(o).val()) {
                    $(o).prop('checked',true);
                }
                });
        });

        $(obj).find('input[name="holiday_hosoku"]').val(job['holiday_hosoku']);
        $(obj).find('input[name="treatment"]').val(job['treatment']);
        $(obj).find('textarea[name="pr"]').val(job['pr']);
        $(obj).find('input[name="tel1"]').val(job['tel1']);
        $(obj).find('input[name="tel2"]').val(job['tel2']);
        $(obj).find('input[name="email"]').val(job['email']);
        $(obj).find('input[name="lineid"]').val(job['lineid']);
        //待遇フィールドにあるタグを取得して配列にセット
        var data = JSON.parse($('#job').find('input[name="treatment_hidden"]').val());
        // 待遇フィールドの初期化
        $(obj).find('.chips-initial').material_chip({
            data:data
        });
        $(obj).find('#edit-job').show();
        $(obj).find("#show-job").hide();
        Materialize.updateTextFields(); // インプットフィールドの初期化
        $(obj).find('select').material_select(); // セレクトボックスの値を動的に変えたら初期化する必要がある
        $($(obj).find('div[name="credit"]')).find('input').prop('disabled',true);

    }
}

/**
 * 求人情報 登録ボタン処理
 */
function jobSaveBtn(){

    if (!confirm('こちらの求人内容でよろしいですか？')) {
        return false;
    }

    var $form = $('form[name="edit_job"]');
    var tagData = $($form).find('.chips').material_chip('data');
    if (tagData.length > 0) {
        var csvTag = "";
        for (var i = 0; i < tagData.length; i++) {
            csvTag = csvTag += tagData[i].tag + ",";
        }
        csvTag = csvTag.slice(0, -1);
        $($form).find('input[name="treatment"]').val(csvTag);
    }
    //通常のアクションをキャンセルする
    event.preventDefault();

    $.ajax({
        url : $form.attr('action'), //Formのアクションを取得して指定する
        type: $form.attr('method'),//Formのメソッドを取得して指定する
        data: $form.serialize(), //データにFormがserialzeした結果を入れる
        dataType: 'json', //データにFormがserialzeした結果を入れる
        timeout: 1000000,
        beforeSend : function(xhr, settings){
            //Buttonを無効にする
            $($form).find('.saveBtn').attr('disabled' , true);
            //処理中のを通知するアイコンを表示する
            $("#dummy").load("/module/Preloader.ctp");
        },
        complete: function(xhr, textStatus){
            //処理中アイコン削除
            $('.preloader-wrapper').remove();
            $($form).find('.saveBtn').attr('disabled' , false);
        },
        success: function (response, textStatus, xhr) {

            // OKの場合
            if(response.success){
                var $objWrapper = $("#wrapper");
                $($objWrapper).replaceWith(response.html);
                    $.notifyBar({
                    cssClass: 'success',
                    html: response.message
                });
                initialize();
            }else{
            // NGの場合
                $.notifyBar({
                    cssClass: 'error',
                    html: response.error
                });
            }
        },
        error : function(response, textStatus, xhr){
            $($form).find('.saveBtn').attr('disabled' , false);
            $.notifyBar({
                cssClass: 'error',
                html: "エラーが発生しました。ステータス：" + textStatus
            });
        }
    });
}

/**
 * 求人タブのモーダル呼び出し時の処理
 * @param {*} obj 
 */
function modalJobTriggerBtn(obj) {

    // オブジェクトを配列に変換
    //var treatment = Object.entries(JSON.parse($(obj).find('input[name="treatment_hidden"]').val()));
    var $chips = $('#job').find('.chips');
    var $chipList = $('#modal-job').find('.chip');
    $($chips).val($(this).attr('id'));
        // 待遇フィールドにあるタグを取得して配列にセット
        var data = $($chips).material_chip('data');

    var addFlg = true;
    // 配列dataを順に処理
    // タグの背景色を初期化する
    $($chipList).each(function(i,o){
        $(o).removeClass('back-color');
    });
    // インプットフィールドにあるタグは選択済にする
    $.each(data, function(index, val) {
        $($chipList).each(function(i,o){
            if($(o).attr('id') == val.id) {
                $(o).addClass('back-color');
                return true;
            }
        });
    });
}
/* 求人情報 関連処理 end */
/* オーナーデフォルト end */

/* キャストデフォルト start */
// TODO: ショップページとキャストページは分離していることから、jsファイルも分けるか
// 要素の存在判定で読み込まない処理にするか後で考える。
function calendarBtn(obj, action) {
    var $button = $(obj).find('.modal-footer');
    var $form = $(obj).find('#edit-calendar');
    var radio = $($form).find('input[name="title"]:checked').attr('id');
    var timeStart = $($form).find('select[name="time_start"]').val();
    var timeEnd = $($form).find('select[name="time_end"]').val();
    if(timeStart != null) {
        $($form).find("input[name='start']").val($($form).find("input[name='start']").val() + " " + timeStart);
    }
    // TODO: sart日時は必ず生成されるけど、end日時はstartのHH:MMを超えると生成される。
    if(timeEnd != null) {
        $($form).find("input[name='end']").val($($form).find("input[name='end']").val() + " " + timeEnd);
    }
    $($form).find("input[type='hidden'] + input[name='all_day']").val(function () {
        return $($form).find("input[id='all-day']").prop("checked") ? 1 : 0;
    });
    // アクションタイプをhiddenにセットする。コントローラー側で処理分岐のために。
    $($form).find("input[name='crud_type']").val(action);
    if ((action == 'create') || (action == 'update')) {
        if (radio == "work") {
            if (timeStart == null) {
                alert("仕事の場合、最低でも出勤時間は選択して下さい。");
                return;
            }
        }
    }

    event.preventDefault();

    $.ajax({
        url : $form.attr('action'), //Formのアクションを取得して指定する
        type: $form.attr('method'),//Formのメソッドを取得して指定する
        data: $form.serialize(), //データにFormがserialzeした結果を入れる
        dataType: 'json', //データにFormがserialzeした結果を入れる
        timeout: 100000,
        beforeSend : function(xhr, settings){
            //Buttonを無効にする
            $($button).find('.createBtn').attr('disabled' , true);
            $($button).find('.updateBtn').attr('disabled' , true);
            $($button).find('.deleteBtn').attr('disabled' , true);
            $(obj).modal('close');

            //処理中のを通知するアイコンを表示する
            $("#dummy").load("/module/Preloader.ctp");
        },
        complete: function(xhr, textStatus){
            //処理中アイコン削除
            $('.preloader-wrapper').remove();
            $($button).find('.createBtn').attr('disabled' , false);
            $($button).find('.updateBtn').attr('disabled' , false);
            $($button).find('.deleteBtn').attr('disabled' , false);
            //fullcalendarInitialize($("input[name='calendar_path']").val());
            $('#calendar').fullCalendar('refetchEvents');
        },
        success: function (response, textStatus, xhr) {

            // OKの場合
            if(response){
                var $objWrapper = $("#wrapper");
                    // $.notifyBar({
                    // cssClass: 'success',
                    // //html: response.message
                //});
                //initialize();
            }else{
            // NGの場合
                $.notifyBar({
                    cssClass: 'error',
                    //html: response.error
                });
            }
        },
        error : function(response, textStatus, xhr){
            $.notifyBar({
                cssClass: 'error',
                html: "エラーが発生しました。ステータス：" + textStatus
            });
        }
    });

}

/**
 * キャストプロフィール 登録ボタン処理
 */
function profileSaveBtn(obj){

    if (!confirm('こちらのプロフィール内容でよろしいですか？')) {
        return false;
    }
    var $form = obj;

    //通常のアクションをキャンセルする
    event.preventDefault();

    $.ajax({
        url : $form.attr('action'), //Formのアクションを取得して指定する
        type: $form.attr('method'),//Formのメソッドを取得して指定する
        data: $form.serialize(), //データにFormがserialzeした結果を入れる
        dataType: 'json', //データにFormがserialzeした結果を入れる
        timeout: 1000000,
        beforeSend : function(xhr, settings){
            //Buttonを無効にする
            $($form).find('.saveBtn').removeClass('disabled');
            //処理中のを通知するアイコンを表示する
            $("#dummy").load("/module/Preloader.ctp");
        },
        complete: function(xhr, textStatus){
            //処理中アイコン削除
            $('.preloader-wrapper').remove();
            $($form).find('.saveBtn').addClass('disabled');
        },
        success: function (response, textStatus, xhr) {

            // OKの場合
            if(response.success){
                // $.notifyBar({
                    // cssClass: 'success',
                    // //html: response.message
                //});
            }else{
            // NGの場合
                $.notifyBar({
                    cssClass: 'error',
                    html: response.error
                });
            }
        },
        error : function(response, textStatus, xhr){
            $($form).find('.saveBtn').attr('disabled' , false);
            $.notifyBar({
                cssClass: 'error',
                html: response.result+"<br/>エラーが発生しました。ステータス：" + textStatus
            });
        }
    });
}

/**
 * 誕生日用 datepicker初期化処理
 */
function birthdayPickerIni () {
    $('.birthday-picker').pickadate({
        selectMonths: true, // Creates a dropdown to control month
        selectYears: 100, // Creates a dropdown of 15 years to control year,
        closeOnSelect: false, // Close upon selecting a date,
        container: undefined, // ex. 'body' will append picker to body
        monthsFull:  ["1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"],
        monthsShort: ["1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"],
        weekdaysFull: ["日曜日", "月曜日", "火曜日", "水曜日", "木曜日", "金曜日", "土曜日"],
        weekdaysShort:  ["日", "月", "火", "水", "木", "金", "土"],
        weekdaysLetter: ["日", "月", "火", "水", "木", "金", "土"],
        labelMonthNext: "翌月",
        labelMonthPrev: "前月",
        labelMonthSelect: "月を選択",
        labelYearSelect: "年を選択",
        today: "今日",
        clear: "クリア",
        close: "閉じる",
        format: "yyyy-mm-dd",
        min: new Date(1960,1,1),
        max: new Date()
    });
    $('.birthday-picker').pickadate('setDate', new Date());
}

/**
 * 画像を要素に読み込む
 * @param  {} obj
 */
function castImgDisp(obj) {
    if($("#image-file").prop("files").length == 0) {
        $(".cancelBtn").addClass("disabled");
        $(".saveBtn").addClass("disabled");
    } else {
        $(".cancelBtn").removeClass("disabled");
        $(".saveBtn").removeClass("disabled");
    }
    $(document).find(".card-img.new").remove();
    var fileList = $("#image-file").prop("files");
    var imgCount = $(".card-img").length;
    var fimeMax = $("input[name='file_max']").val();

    $.each(fileList, function(index, file){
        //画像ファイルかチェック
        if (file["type"] != "image/jpeg" && file["type"] != "image/png" && file["type"] != "image/gif") {
            alert("jpgかpngかgifファイルを選択してください");
            $("#image-file").val('');
            return false;
        }
    });
    // ファイル数を制限する
    if(fileList.length + imgCount > fimeMax) {
        alert("アップロードできる画像は" + fimeMax + "ファイルまでです。");
        castCancelBtn();
        return;
    }
    for(var i = 0; i < fileList.length; i++){

        imgCount += 1;
        fileload(fileList[i], obj, imgCount);
    }
    $('.materialboxed').materialbox();
    $('.tooltipped').tooltip({delay: 50});

}

/**
 * HTMLを生成する
 * @param  {} file
 * @param  {} obj
 * @param  {} imgCount
 */
function fileload(file, obj, imgCount) {

    var fileReader = new FileReader();
    fileReader.onloadend = function() {
        var html = '<div class="col s6 m4 l3 card-img new image'+imgCount+'">'
                + '<span class="new badge red oki-new"></span>'
                + '<input type="hidden" name="file_name" value="'+file.name+'">'
                + '<div class="card">'
                + '<div class="card-image">'
                + '<img class="materialboxed" data-caption="" src="">'
                + '</div>'
                + '</div>'
                + '</div>';

        var $html = $(html);

        $($html).find('img').attr({width:'100%',height:'120',src:fileReader.result});
        $(obj).append($html);

    }

    fileReader.readAsDataURL(file);
}

/**
 * キャスト画像アップ用リセット リセットボタン処理
 */
function castCancelBtn(){
    $(document).find(".card-img.new").remove();
    $(document).find('#image-file').replaceWith($('#image-file').val('').clone(true));
    $("input[name='top_image']").val('');
}

function castImageSaveBtn(){

    //加工後の横幅を800pxに設定
    var processingWidth = 800;

    //加工後の容量を100KB以下に設定
    var processingCapacity = 100000;
    // form 初期化
    var $form = null;
    var formData = null;
    var blob = [];

    //ファイル選択済みかチェック
    var fileCheck = $("#image-file").val().length;
    if (fileCheck === 0) {
        alert("画像ファイルを選択してください");
        return false;
    }
    if(!confirm('こちらの画像に変更でよろしいですか？')) {
        return false;
    }
    $('.card-img').each(function(index, element){

        console.log(index + ':' + $(element).text());
        //imgタグに表示した画像をimageオブジェクトとして取得
        var image = new Image();
        image.src = $(this).find("img").attr("src");

        var h;
        var w;

        //原寸横幅が加工後横幅より大きければ、縦横比を維持した縮小サイズを取得
        if(processingWidth < image.width) {
            w = processingWidth;
            h = image.height * (processingWidth / image.width);

        //原寸横幅が加工後横幅以下なら、原寸サイズのまま
        } else {
            w = image.width;
            h = image.height;
        }

        //取得したサイズでcanvasに描画
        var canvas = $("#image-canvas");
        var ctx = canvas[0].getContext("2d");
        $("#image-canvas").attr("width", w);
        $("#image-canvas").attr("height", h);
        ctx.drawImage(image, 0, 0, w, h);

        //canvasに描画したデータを取得
        var canvasImage = $("#image-canvas").get(0);

        //オリジナル容量(画質落としてない場合の容量)を取得
        var originalBinary = canvasImage.toDataURL("image/jpeg"); //画質落とさずバイナリ化
        var originalBlob = base64ToBlob(originalBinary); //画質落としてないblobデータをアップロード用blobに設定
        console.log(originalBlob["size"]);

        //オリジナル容量blobデータをアップロード用blobに設定
        var uploadBlob = originalBlob;

        //オリジナル容量が加工後容量以上かチェック
        if(processingCapacity <= originalBlob["size"]) {
            //加工後容量以下に落とす
            var capacityRatio = processingCapacity / originalBlob["size"];
            var processingBinary = canvasImage.toDataURL("image/jpeg", capacityRatio); //画質落としてバイナリ化
            uploadBlob = base64ToBlob(processingBinary); //画質落としたblobデータをアップロード用blobに設定
            console.log(capacityRatio);
            console.log(uploadBlob["size"]);
        }

        blob.push(uploadBlob);
    });

    //アップロード用blobをformDataに設定
    $form = $('#edit-image');
    formData = new FormData($form.get()[0]);
    formData.append("image", blob);

    //通常のアクションをキャンセルする
    event.preventDefault();

    $.ajax({
        url : $form.attr('action'), //Formのアクションを取得して指定する
        type: $form.attr('method'),//Formのメソッドを取得して指定する
        data: formData, //データにFormがserialzeした結果を入れる
        dataType: 'html', //データにFormがserialzeした結果を入れる
        processData: false,
        contentType: false,
        timeout: 1000000,
        beforeSend : function(xhr, settings){
            //Buttonを無効にする
            $($form).find('.saveBtn').removeClass('disabled');
            //処理中のを通知するアイコンを表示する
            $("#dummy").load("/module/Preloader.ctp");
        },
        complete: function(xhr, textStatus){
            //処理中アイコン削除
            $('.preloader-wrapper').remove();
            $($form).find('.saveBtn').addClass('disabled');
            $('.tooltipped').tooltip({delay: 50});
        },
        success: function (response, textStatus, xhr) {

            // OKの場合
            if(response){
                var $objWrapper = $("#wrapper");
                $($objWrapper).replaceWith(response);
                // $.notifyBar({
                //     // cssClass: 'success',
                //     // //html: response.message
                // });
            }else{
            // NGの場合
                $.notifyBar({
                    cssClass: 'error',
                    html: response.error
                });
            }
        },
        error : function(response, textStatus, xhr){
            $($form).find('.saveBtn').attr('disabled' , false);
            $.notifyBar({
                cssClass: 'error',
                html: response.result+"<br/>エラーが発生しました。ステータス：" + textStatus
            });
        }
    });
}

/**
 * キャスト画像 削除ボタン処理
 */
function castImageDeleteBtn(form, obj){

    if (!confirm('削除しますか？')) {
        return false;
    }
    var $form = form;

    //通常のアクションをキャンセルする
    event.preventDefault();

    $.ajax({
        url : $form.attr('action'), //Formのアクションを取得して指定する
        type: $form.attr('method'),//Formのメソッドを取得して指定する
        data: $form.serialize(), //データにFormがserialzeした結果を入れる
        dataType: 'html', //データにFormがserialzeした結果を入れる
        timeout: 100000,
        beforeSend : function(xhr, settings){
            //Buttonを無効にする
            $($form).find('.saveBtn').removeClass('disabled');
            //処理中のを通知するアイコンを表示する
            $("#dummy").load("/module/Preloader.ctp");
        },
        complete: function(xhr, textStatus){
            //処理中アイコン削除
            $('.preloader-wrapper').remove();
            $($form).find('.saveBtn').addClass('disabled');
            $('.tooltipped').tooltip({delay: 50});
        },
        success: function (response, textStatus, xhr) {

            // OKの場合
            if(response){
                var $objWrapper = $("#wrapper");
                $($objWrapper).replaceWith(response);
                // $.notifyBar({
                //     // cssClass: 'success',
                //     // //html: response.message
                // });
            }else{
            // NGの場合
                $.notifyBar({
                    cssClass: 'error',
                    html: response.error
                });
            }
        },
        error : function(response, textStatus, xhr){
            $($form).find('.saveBtn').attr('disabled' , false);
            $.notifyBar({
                cssClass: 'error',
                html: response.result+"<br/>エラーが発生しました。ステータス：" + textStatus
            });
        }
    });
}


/* キャストデフォルト end */

    // all initialize
    function initialize() {

        /* オーナーページ 関連処理 start */
        if($('#owner-default').length) {
            var activeTab = $('#activeTab').val();
            var options = {
                //'swipeable':true, // モバイル時のスワイプでタブ切り替え
                //'responsiveThreshold':991,
                'onShow': function() {   // タブ切り替え時のコールバック
                }
            }
            $('ul.tabs').tabs(options);
            // タブの事前選択
            $('ul.tabs').tabs('select_tab', activeTab);

            // 店舗編集のスクリプト 店舗情報 クレジットフォームを入力不可にする
            $($('#tenpo').find('div[name="credit"]')).find('input').prop('disabled',true);
            // 店舗情報 クレジットタグをフォームに追加する
            $('#tenpo').find('.chip').on('click', function() {
                var $chips = $('#tenpo').find('.chips');
                $($chips).val($(this).children().attr('alt'));
                // クレジットフィールドにあるタグを取得して配列にセット
                var data = $($chips).material_chip('data');
                console.log(data);

                // クリックしたタグを取得
                var newTag = $(this).children().attr('alt');
                var newId = $(this).children().attr('id');
                var addFlg = true;
                // 配列dataを順に処理
                $.each(data, function(index, val) {
                    if(newId == val.id) {
                        addFlg = false;
                    }
                });
                // 重複したクレジットが無い、またはデータが１つも無ければクレジット追加
                if(addFlg || data.length == 0) {
                    data.push({'tag' : newTag, 'image':'/img/common/credit/'+ newTag +'.png', 'id':newId});
                }
                // クレジットフィールドの初期化
                $($chips).material_chip({
                    data:data
                });
                $($chips).find('input').prop('disabled',true);

                return false;
            });
            // 求人情報 待遇タグをフォームに追加する
            $('#modal-job').find('.chip').on('click', function() {
                var $chips = $('#job').find('.chips');
                $($chips).val($(this).attr('id'));
                // 待遇フィールドにあるタグを取得して配列にセット
                var data = $($chips).material_chip('data');

                // クリックしたタグを取得
                var newTag = $(this).attr('value');
                var newId = $(this).attr('id');
                var addFlg = true;
                var removeFlg = false;
                if ($(this).hasClass('back-color')) {
                    $(this).removeClass('back-color');
                    removeFlg = true;
                } else {
                    $(this).addClass('back-color');
                }
                // 配列dataを順に処理
                $.each(data, function(index, val) {
                    if(newId == val.id) {
                        addFlg = false;
                    }
                });
                // 重複した待遇が無い、またはデータが１つも無ければ待遇追加
                if(addFlg || data.length == 0) {
                    data.push({'tag' : newTag, 'id':newId});
                }
                //タグの選択解除
                if (removeFlg) {
                    $.each(data, function(index, val) {
                        if(newId == val.id) {
                            data.splice(index,1);
                            return false;
                        }
                    });
                }
                // 待遇フィールドの初期化
                $($chips).material_chip({
                    data:data
                });
                $($chips).find('input').prop('disabled',true);

                return false;
            });

            /* クーポンタブ 関連処理 start */
            /* クーポン チェックボックスオンオフ時 */
            $(document).on('click','.check-coupon-group', function() {
                var $button = $('#coupon').find('button.changeBtn,button.deleteBtn');
                var $addButton = $('#coupon').find('button.addBtn');

                if ($(this).prop('checked')){
                    $("html,body").animate({scrollTop:$('.targetScroll').offset().top},1200);
                    // 一旦全てをクリアして再チェックする
                    $('.check-coupon-group').prop('checked', false);
                    $(this).prop('checked', true);
                    if($($button).hasClass('disabled')){
                        $($button).removeClass('disabled');
                    }
                    $($addButton).addClass('disabled');
                } else {
                    $($button).each(function(index, element){
                        $($button).addClass('disabled');
                    });
                    $($addButton).removeClass('disabled');
                }
            });

            /* クーポン スイッチオンオフ時 ajax更新 */
            $('.switch_coupon').change(function() {
                var name = $(this).attr('name');
                var $form = $('form[name="'+ name +'"]');
                var status = 1;
                // チェック状態によって値を入れ替える
                if(!$(this).prop('checked')) {
                    status = 0;
                }

                var data = { coupon_switch : status };
                $.ajax({
                    type: 'POST',
                    datatype:'json',
                    url: $($form).attr('action'),
                    data: data,
                    success: function(data,dataType) {
                        console.log(data);
                    },
                    error : function(response, textStatus, xhr){
                        $($form).find('.saveBtn').attr('disabled' , false);
                        $.notifyBar({
                            cssClass: 'error',
                            html: "エラーが発生しました。ステータス：" + textStatus
                        });
                    }
                });
            });
            /* クーポンタブ 関連処理 end */

            /* キャストタブ 関連処理 start */
            $(document).on('click','.check-cast-group', function() {
                var $button = $('#cast').find('button.changeBtn,button.deleteBtn');
                var $addButton = $('#cast').find('button.addBtn');

                if ($(this).prop('checked')){
                    $("html,body").animate({scrollTop:$('.targetScroll').offset().top},1200);
                    // 一旦全てをクリアして再チェックする
                    $('.check-cast-group').prop('checked', false);
                    $(this).prop('checked', true);
                    if($($button).hasClass('disabled')){
                        $($button).removeClass('disabled');
                    }
                    $($addButton).addClass('disabled');
                } else {
                    $($button).each(function(index, element){
                        $($button).addClass('disabled');
                    });
                    $($addButton).removeClass('disabled');
                }
            });

            /* キャスト スイッチオンオフ時 ajax更新 */
            $('.switch_cast').change(function() {
                var name = $(this).attr('name');
                var $form = $('form[name="'+ name +'"]');
                var status = 1;
                // チェック状態によって値を入れ替える
                if(!$(this).prop('checked')) {
                    status = 0;
                }

                var data = { cast_switch : status };
                $.ajax({
                    type: 'POST',
                    datatype:'json',
                    url: $($form).attr('action'),
                    data: data,
                    success: function(data,dataType) {
                        console.log(data);
                    },
                    error : function(response, textStatus, xhr){
                        $($form).find('.saveBtn').attr('disabled' , false);
                        $.notifyBar({
                            cssClass: 'error',
                            html: "エラーが発生しました。ステータス：" + textStatus
                        });
                    }
                });
            });
            /* キャストタブ 関連処理 end */
        }
        /* オーナーページ 関連処理 end */

        /* キャストページ 関連処理 start */
        if($('#cast-default').length) {
            // TODO: ショップページとキャストページは分離していることから、jsファイルも分けるか
            // 要素の存在判定で読み込まない処理にするか後で考える。
            // キャスト用の初期化処理
            fullcalendarSetting();
            birthdayPickerIni();

            if($("#cast-profile").length) {
                var $profile = $("#cast-profile");
                var profile = JSON.parse($($profile).find('input[name="profile_copy"]').val());
                $($profile).find('select[name="age"]').val(profile['age']);
                $($profile).find('select[name="constellation"]').val(profile['constellation']);
                $($profile).find('select[name="blood_type"]').val(profile['blood_type']);
                var birthday = $('#birthday').pickadate('picker'); // Date Picker
                birthday.set('select', [2000, 1, 1]);
                birthday.set('select', new Date(2000, 1, 1));
                birthday.set('select', profile['birthday'], { format: 'yyyy-mm-dd' });

                $($profile).find(":input").on("change", function() {

                    $($profile).find(".saveBtn").removeClass("disabled");
                });

            }
            if($("#edit-image").length) {
                // var $image = $("#edit-image");
                // var image = JSON.parse($($image).find('input[name="image_copy"]').val());

                // $($image).find(":input").on("change", function() {
                //     if($($image).find("#image-file").prop("files").length == 0) {
                //         $($image).find(".cancelBtn").addClass("disabled");
                //         $($image).find(".saveBtn").addClass("disabled");
                //         $(document).find(".card-img.new").remove();
                //     } else {
                //         $($image).find(".cancelBtn").removeClass("disabled");
                //         $($image).find(".saveBtn").removeClass("disabled");
                //     }

                // });
                // $(document).on('click','.new-btn', function() {

                //     var fileList = $("#image-file").prop("files");
                //     var dom = $(document).find(this).closest(".card-img");
                //     var fileName = $(dom).find("input[name='file_name']").val();
                //     $.each(fileList, function(index, file){
                //         //画像ファイルかチェック
                //         if (file["name"] == fileName) {
                //             $("#image-file").prop("files")[index] = "";
                //             return false;
                //         }
                //         fileList.splice
                //     });
                //     $(dom).remove();
                //     $(".material-tooltip").remove();
                //     $('.tooltipped').tooltip({delay: 50});
                // });

            }


        }
        /* キャストページ 関連処理 end */

        /* 共通処理 start */
        // materializecss sideNav サイドバーの初期化
        $(".button-collapse").sideNav();
        $('.button-collapse').sideNav({
            menuWidth: 300,
            edge: 'left',
            closeOnClick: true,
            draggable: true,
            onOpen: function(el) {
               // 開いたときの処理
            },
            onClose: function(el) {
               // 閉じたときの処理
            }
        });

        // materializecss selectbox
        $('select').material_select();
        // materializecss tooltip
        $('.tooltipped').tooltip({delay: 50});
        // materializecss modal
        $('.modal').modal();
        // materializecss Chips
        $('.chips-initial').material_chip();
        // materializecss Chips 追加イベント
        $('.chips').on('chip.add', function(e, chip){
            //alert("chip.add");
        });
        // materializecss Chips 削除イベント
        $('.chips').on('chip.delete', function(e, chip){
            //alert("chip.delete");
        });
        // materializecss Chips 選択イベント
        $('.chips').on('chip.select', function(e, chip){
            //alert("chip.select");
        });


        // show return top button
        var topBtn = $('#return_top');
        $(window).scroll(function () {
            var scrTop = $(this).scrollTop();
            if (scrTop > 100) {
                topBtn.stop().fadeIn('slow');
            } else {
                topBtn.stop().fadeOut();
            }
        });

        // click return top
        $('#return_top a').click(function() {
            $('html,body').animate({scrollTop : 0}, 1000, 'easeOutExpo');
            return false;
        });

        /* ショップページプレビュ クーポン クリックイベント */
        $('.coupon').click(function() {
            $(this).find('.arrow').toggleClass("active");
            $(this).find('.arrow').toggleClass('nonActive');
            if (!$(this).find('.arrow').hasClass('active')) {
                $(this).find('.label').text('クーポンを表示する');
            } else {
                $(this).find('.label').text('クーポンをとじる');
            }
        });
        // TODO: ピッカー系がgoogle chomeブラウザで不具合が出てるっぽい。2019/03/28時点
        // 症状は、クリックしても一瞬表示されるだけ。現時点の解決策としては<label>タグを付与することで表示される
        /* 店舗編集のスクリプト クーポン materializecss Date Picker */
        $('.datepicker').pickadate({
            selectMonths: true, // Creates a dropdown to control month
            selectYears: 15, // Creates a dropdown of 15 years to control year,
            closeOnSelect: false, // Close upon selecting a date,
            container: undefined, // ex. 'body' will append picker to body
            monthsFull:  ["1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"],
            monthsShort: ["1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"],
            weekdaysFull: ["日曜日", "月曜日", "火曜日", "水曜日", "木曜日", "金曜日", "土曜日"],
            weekdaysShort:  ["日", "月", "火", "水", "木", "金", "土"],
            weekdaysLetter: ["日", "月", "火", "水", "木", "金", "土"],
            labelMonthNext: "翌月",
            labelMonthPrev: "前月",
            labelMonthSelect: "月を選択",
            labelYearSelect: "年を選択",
            today: "今日",
            clear: "クリア",
            close: "閉じる",
            format: "yyyy-mm-dd",
        });
        $('.datepicker').pickadate('setDate', new Date());

        $('.timepicker').pickatime({
            default: 'now', // Set default time: 'now', '1:30AM', '16:30'
            fromnow: 0,       // set default time to * milliseconds from now (using with default = 'now')
            twelvehour: false, // Use AM/PM or 24-hour format
            donetext: 'OK', // text for done-button
            cleartext: 'クリア', // text for clear-button
            canceltext: 'キャンセル', // Text for cancel-button,
            container: undefined, // ex. 'body' will append picker to body
            autoclose: false, // automatic close timepicker
            ampmclickable: true, // make AM PM clickable
            aftershow: function(){} //Function for after opening timepicker
        });
        /* 共通処理 end */
    }





/*  document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.modal');
    var instances = M.Modal.init(elems, options);
  });*/

/*    if (navigator.geolocation) {
      alert("この端末では位置情報が取得できます");
    // Geolocation APIに対応していない
    } else {
      alert("この端末では位置情報が取得できません");
    }*/

    // 現在地取得処理
/*    function getPosition() {
      var accessStatus = Geolocator.RequestAccessAsync();
      alert(accessStatus);
      // 現在地を取得
      navigator.geolocation.getCurrentPosition(
        // 取得成功した場合
        function(position) {
            alert("緯度:"+position.coords.latitude+",経度"+position.coords.longitude);
        },
        // 取得失敗した場合
        function(error) {
          switch(error.code) {
            case 1: //PERMISSION_DENIED
              alert("位置情報の利用が許可されていませんafdfddf");
              break;
            case 2: //POSITION_UNAVAILABLE
              alert("現在位置が取得できませんでした");
              break;
            case 3: //TIMEOUT
              alert("タイムアウトになりました");
              break;
            default:
              alert("その他のエラー(エラーコード:"+error.code+")");
              break;
          }
        }
      );
    }*/