
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
        if ($('#edit-top-image').css('display') == 'block') {
            $(obj).find('img.prev-image').remove();
            $(obj).find('[name="top_image"]').val("");
            $(obj).find("#edit-top-image").hide();
            $(obj).find("#show-top-image").show();
        } else {
            $(obj).find('#edit-top-image').show();
            $(obj).find("#show-top-image").hide();
        }
    }

    /**
     * トップ画像 登録ボタン処理
     */
    function topImageSaveBtn(){

        var $form = $('#edit-top-image');

        if($form.find('input[name="top_image"]').val() == '') {
            alert('画像を選択してください。');
            return false;
        }
        if(!confirm('こちらの画像に変更でよろしいですか？')) {
            return false;
        }
        // FormData オブジェクトを作成
        var formData = new FormData($form.get()[0]);

        //通常のアクションをキャンセルする
        event.preventDefault();

        $.ajax({
            url : $form.attr('action'), //Formのアクションを取得して指定する
            type: $form.attr('method'),//Formのメソッドを取得して指定する
            data: formData, //データにFormがserialzeした結果を入れる
            dataType: 'json', //データにFormがserialzeした結果を入れる
            processData: false,
            contentType: false,
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
                var $objWrapper = $("#wrapper");
                console.log(response);
                $($objWrapper).replaceWith(response.html);
                //Alertで送信結果を表示する
                if(response.success){
                    $.notifyBar({
                        cssClass: 'success',
                        html: response.message
                    });
                    initialize();
                }else{
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
     * トップ画像 削除ボタン処理
     */
    function topImageDeleteBtn(){

        var $form = $('#delete-top-image');

        if (confirm('トップ画像を削除してもよろしいですか？')) {
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
                    var $objWrapper = $("#wrapper");
                    console.log(response);
                    $($objWrapper).replaceWith(response.html);
                    //Alertで送信結果を表示する
                    if(response.success){
                        $.notifyBar({
                            cssClass: 'success',
                            html: response.message
                        });
                        initialize();
                    }else{
                        $.notifyBar({
                            cssClass: 'error',
                            html: response.error
                        });
                    }
                },
                error : function(response){
                    $($form).find('.deleteBtn').attr('disabled' , false);
                    $.notifyBar({
                        cssClass: 'error',
                        html: "エラーが発生しました。ステータス：" + textStatus
                    });
                }
            });
        }
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
            $(obj).find('#edit-catch').show();
            $(obj).find("#show-catch").hide();
        }
    }

    /* キャッチコピー 関連処理 end */

    /* クーポン 関連処理 start */

    /**
     * クーポン 通常表示、変更表示切替え処理
     * @param  {} obj
     */
    function couponChangeBtn(obj){
        if ($('#edit-coupon').css('display') == 'block') {
            // $(obj).find('[name="coupon"]').val(""); //初期値を削除しない
            $(obj).find("#edit-coupon").hide();
            $(obj).find("#show-coupon").show();
        } else {
            $("html,body").animate({scrollTop:0});
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
            $('#text-coupon-title').val('');
            $('#text-coupon-content').val('');
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
            timeout: 10000,
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
                var $objWrapper = $("#wrapper");
                console.log(response);
                $($objWrapper).replaceWith(response.html);
                //Alertで送信結果を表示する
                if(response.success){
                    $.notifyBar({
                        cssClass: 'success',
                        html: response.message
                    });
                    initialize();
                }else{
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

        if (confirm('【'+title+'】\n選択したクーポンを削除してもよろしいですか？')) {
            //通常のアクションをキャンセルする
            event.preventDefault();

            $.ajax({
                url : $form.attr('action'), //Formのアクションを取得して指定する
                type: $form.attr('method'),//Formのメソッドを取得して指定する
                data: $form.serialize(), //データにFormがserialzeした結果を入れる
                dataType: 'json', //データにFormがserialzeした結果を入れる
                timeout: 10000,
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
                    var $objWrapper = $("#wrapper");
                    console.log(response);
                    $($objWrapper).replaceWith(response.html);
                    //Alertで送信結果を表示する
                    if(response.success){
                        $.notifyBar({
                            cssClass: 'success',
                            html: response.message
                        });
                        initialize();
                    }else{
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
            console.log("tenpo");
            console.log(tenpo);
            var from = $('#from-day').pickadate('picker'); // Date Picker
            var to = $('#to-day').pickadate('picker'); // Date Picker
            from.set('select', [2000, 1, 1]);
            from.set('select', new Date(2000, 1, 1));
            from.set('select', tenpo['from_day'], { format: 'yyyy-mm-dd' });
            to.set('select', [2000, 1, 1]);
            to.set('select', new Date(2000, 1, 1));
            to.set('select', tenpo['to_day'], { format: 'yyyy-mm-dd' });
            $('input[name="tenpo_edit_id"]').val(tenpo['id']);
            $('input[name="title"]').val(tenpo['title']);
            $('textarea[name="content"]').val(tenpo['content']);
            if(tenpo['status'] == 1) {
                $('input[name="status"]').attr('checked', 'checked');
            }
            $(obj).find('#edit-tenpo').show();
            $(obj).find("#show-tenpo").hide();
        }
    }

    /* 店舗情報 関連処理 end */

    // all initialize
    function initialize() {

        var activeTab = $('#activeTab').val();
        var options = {
            //'swipeable':true, // モバイル時のスワイプでタブ切り替え
            //'responsiveThreshold':991,
            'onShow': function() { // タブ切り替え時のコールバック
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
        // materializecss Chips 選択エリアイベント
        // 店舗編集のスクリプト 店舗情報 クレジットイベント
        $('.chip-box > .chip').on('click', function() {
            $input = $('#tenpo').find('div[name="credit"] > input');
            $($input).val($(this).children().attr('alt'));
            $chips = $('#tenpo').find('div[name="credit"]');
            var data = [];
            // クレジットフィールドにあるタグを取得して配列にセット
            $($chips.find('.chip')).each(function(i, el) {
                var item = $(el).text().replace('close','');
                data.push({'tag' : item, 'image':'/img/common/credit/'+ item +'.png'});
            });

            // クリックしたタグを取得
            var newTag = $(this).children().attr('alt');
            var addFlg = true;
            // 配列dataを順に処理
            $.each(data, function(index, val) {
                if(newTag == val.tag) {
                    addFlg = false;
                }
            });
            // 重複したクレジットが無い、またはデータが１つも無ければクレジット追加
            if(addFlg || data.length == 0) {
                data.push({'tag' : newTag, 'image':'/img/common/credit/'+ newTag +'.png'});
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

        /* 店舗編集のスクリプト トップ画像編集 画像変更時プレビュ */
        $(function(){
            $('#file').change(function(){
                $('img.prev-image').remove();
                var file = $(this).prop('files')[0];
                if(!file.type.match('image.*')){
                    alert("許可されたファイルタイプではありません。\n画像ファイルを選択してください。");
                    return;
                }
                var fileReader = new FileReader();
                fileReader.onloadend = function() {
                    $('#result').html('<img width="100%" height="300" class="prev-image" src="' + fileReader.result + '"/>');
                    $('.prev-image').css({
                    'background-color':'gray',
                    'object-fit':'contain'});
                }
                fileReader.readAsDataURL(file);
            });
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

            /* 店舗編集のスクリプト クーポン スイッチオンオフ時 ajax更新 */
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