
    document.write("<script type='text/javascript' src='/js/util.js'></script>");
            /** */
    $(document).ready(function(){
        // 初期化
        initialize();
    });

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
            $('input[name="status"]').removeAttr('checked');
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
            $('input[name="coupon_edit_id"]').val(coupon['id']);
            $('input[name="title"]').val(coupon['title']);
            $('textarea[name="content"]').val(coupon['content']);
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
            $('#from-day').pickadate('picker').set("select",null);
            $('#to-day').pickadate('picker').set("select",null);
            $('#coupon-title').val('');
            $('#coupon-content').val('');
            $('input[name="status"]').removeAttr('checked');
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
            var $from = $('#bus-from-time').pickatime().pickatime('picker');
            var $to = $('#bus-to-time').pickatime().pickatime('picker');
            var from = new Date(tenpo['bus_from_time']);
            var to = new Date(tenpo['bus_to_time']);
            $($from).val(toDoubleDigits(from.getHours()) + ":" + toDoubleDigits(from.getMinutes()));
            $($to).val(toDoubleDigits(to.getHours()) + ":" + toDoubleDigits(to.getMinutes()));
            $('input[name="tenpo_edit_id"]').val(tenpo['id']);
            $('input[name="name"]').val(tenpo['name']);
            $('input[name="pref21"]').val(tenpo['pref21']);
            $('input[name="addr21"]').val(tenpo['addr21']);
            $('input[name="strt21"]').val(tenpo['strt21']);
            $('input[name="tel"]').val(tenpo['tel']);
            $('input[name="bus_hosoku"]').val(tenpo['bus_hosoku']);
            $('textarea[name="staff"]').val(tenpo['staff']);
            $('textarea[name="system"]').val(tenpo['system']);
            //クレジットフィールドにあるタグを取得して配列にセット
            var data = JSON.parse($(obj).find('input[name="chip_hidden"]').val());
            // クレジットフィールドの初期化
            $('.chips-initial').material_chip({
                data:data
            });
            $(obj).find('#edit-tenpo').show();
            $(obj).find("#show-tenpo").hide();
            Materialize.updateTextFields(); // インプットフィールドの初期化
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
        var tagData = $('.chips').material_chip('data');
        if (tagData.length > 0) {
            var csvTag = "";
            for (var i = 0; i < tagData.length; i++) {
                csvTag = csvTag += tagData[i].tag + ",";
            }
            csvTag = csvTag.slice(0, -1);
            $($form).find('input[name="credit"]').val(csvTag);
        }

        console.log($form.serialize());
        console.log($form);
        //通常のアクションをキャンセルする
        event.preventDefault();
        var tagData = $('.chips').material_chip('data');
        console.log(tagData);
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
    function testBtn() {
        alert(busFromTime.val());
    }

    /* 店舗情報 関連処理 end */

    // all initialize
    function initialize() {

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

        // 店舗編集のスクリプト 店舗情報 クレジットフォームを入力不可にする
        $($('#tenpo').find('div[name="credit"]')).find('input').prop('disabled',true);

        $('.chip-box > .chip').on('click', function() {
            var $chips = $('#tenpo').find('.chips');
            $($chips).val($(this).children().attr('alt'));
             // クレジットフィールドにあるタグを取得して配列にセット
             var data = $('.chips-initial').material_chip('data');
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
            $('.chips-initial').material_chip({
                data:data
            });
            $($chips).find('input').prop('disabled',true);

            return false;
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

        /* 店舗編集のスクリプト クーポン チェックボックスオンオフ時 */
        $(function(){
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

        });

        /* 店舗編集のスクリプト クーポン materializecss Date Picker */
        $('.datepicker').pickadate({
            selectMonths: true, // Creates a dropdown to control month
            selectYears: 15, // Creates a dropdown of 15 years to control year,
            today: '今日',
            clear: 'クリア',
            close: 'OK',
            closeOnSelect: false, // Close upon selecting a date,
            container: undefined // ex. 'body' will append picker to body
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