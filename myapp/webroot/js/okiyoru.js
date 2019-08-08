
document.write("<script type='text/javascript' src='/js/util.js'></script>");
        /** */
$(document).ready(function(){

    allInitialize();

});

/**
 * オール初期化
 */
function allInitialize() {

    if($('#user-default').length) {
        initializeUser();
    }
    if($('#owner-default').length) {
        initializeOwner();
    }
    if($('#cast-default').length) {
        initializeCast();
    }

    // 初期化
    initialize();
}

/**
 * イベントオールクリア
 */
function crearAllevents() {

    if($('#user-default').length) {
        crearUserEvents();
    }
    if($('#owner-default').length) {
        crearOwnerEvents();
    }
    if($('#cast-default').length) {
        crearCastEvents();
    }

    crearCommonEvents();
}

/**
 * ユーザー画面のイベントクリア
 */
function crearUserEvents() {

}

/**
 * オーナー画面のイベントクリア
 */
function crearOwnerEvents() {

    $(document).off('click', '.top-image-changeBtn');
    $(document).off('click', '.top-image-saveBtn');
    $(document).off('click', '.top-image-deleteBtn');
    $(document).off('click', '.catch-changeBtn');
    $(document).off('click', '.catch-saveBtn');
    $(document).off('click', '.catch-deleteBtn');
    $(document).off('click', '.coupon-changeBtn');
    $(document).off('click', '.coupon-addBtn');
    $(document).off('click', '.coupon-saveBtn');
    $(document).off('click', '.check-coupon-group');
    $(document).off('change', '.coupon-switchBtn');
    $(document).off('click', '.coupon-deleteBtn');
    $(document).off('click', '.cast-changeBtn');
    $(document).off('click', '.cast-addBtn');
    $(document).off('click', '.cast-saveBtn');
    $(document).off('click', '.check-cast-group');
    $(document).off('change', '.cast-switchBtn');
    $(document).off('click', '.cast-deleteBtn');
    $(document).off('click', '.tenpo-changeBtn');
    $(document).off('click', '.tenpo-saveBtn');
    $(document).off('click', '.gallery-deleteBtn');
    $(document).off('change', '#image-file');
    $(document).off('click', '.gallery-saveBtn');
    $(document).off('click', '.gallery-chancelBtn');
    $(document).off('click', '.jobModal-callBtn');
    $(document).off('click','.chip-credit');
    $(document).off('click', '.job-changeBtn');
    $(document).off('click', '.job-saveBtn');
    $(document).off('click','.chip-treatment');
}

/**
 * キャスト画面のイベントクリア
 */
function crearCastEvents() {

    $(document).off('input');
    $(document).off('change','select');
    $(document).off('change', '#title, #content, #image-file');
    $(document).off('change', '#modal-title, #modal-content, #modal-image-file');
    $(document).off('change', '#image-file');
    $(document).off('change', '#modal-image-file');
    $(document).off('click', '.createBtn');
    $(document).off('click', '.updateBtn');
    $(document).off('click', '.archiveLink');
    $(document).off('click', '.updateModeBtn');
    $(document).off('click', '.returnBtn');
    $(document).off('click', '.deleteBtn');
    $(document).off('click', '.gallery-deleteBtn');
    $(document).off('change', '#image-file');
    $(document).off('click', '.gallery-saveBtn');
    $(document).off('click', '.gallery-chancelBtn');
}

/**
 * 共通画面のイベントクリア
 */
function crearCommonEvents() {

    $(document).off('click','#return_top a');
}

/**
 * TODO: fileload関数でも対応できそうなので、後で考える。
 * 店舗のトップイメージの変更時画像を差し替える処理
 */
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
 * HTMLを生成する
 * @param  {} file
 * @param  {} obj
 * @param  {} imgCount
 */
function fileload(file, obj, imgCount, path) {

    var fileReader = new FileReader();
    var $html;
    fileReader.onloadend = function() {

        $.get(path, {}, function(html) {

            var $dom = $(html);
            $($dom).addClass("image"+imgCount);
            $($dom).find(".deleteBtn").remove();
            $($dom).find("input[name='name']").val(file.name);
            $($dom).find('img').attr({src:fileReader.result});
            $(obj).append($dom);
            $('.materialboxed').materialbox();
            $('.tooltipped').tooltip({delay: 50});
        });

    }

    fileReader.readAsDataURL(file);
}

/**
 * ショップカードHTMLを生成する
 * @param  {} file
 * @param  {} obj
 * @param  {} imgCount
 */
function createShopCard(form, response, path) {

    var $html;

    $.get(path, {}, function(html) {
        var dom = $(html); // html部品をdomに変換
        var resultDom = $(document).find('.resultSearch');
        var cardTmp = dom.clone(); // ショップカードの部品を複製しておく
        var area = JSON.parse($(document).find("input[name='area_define']").val());
        var genre = JSON.parse($(document).find("input[name='genre_define']").val());
        $(resultDom).find(".card").remove(); // ショップカードの部品を削除しておく ※複製した部品を都度生成して使う
        $(resultDom).find(".message").addClass('hide'); // メッセージ非表示
        $title = '';
        if (($(form).find("[name='area']").val()) !== '' && ($(form).find("[name='genre']").val()) !== '') {
            // コントローラでセットされたtitleを代入してセパレータを追加
            $title +=  area[$(form).find("[name='area']").val()]['label'] + 'の' +
                        genre[$(form).find("[name='genre']").val()]['label'] + '一覧';
        } else if($(form).find("[name='area']").val() !== '') {
            $title +=  area[$(form).find("[name='area']").val()]['label'] + '一覧';
        } else if($(form).find("[name='genre']").val() !== '') {
            $title +=  genre[$(form).find("[name='genre']").val()]['label'] + '一覧';
        }
        $(resultDom).find(".title").text($title);
        $(resultDom).find(".header").text('検索結果 ' + response.length + '件');
        $.each(response, function(index, row) {
            var shopCard = $(cardTmp).clone();
            // 属性などを追加する
            $(shopCard).find('.title').text(row['name'] + '|' + area[row['area']]['label'] + '|' + genre[row['genre']]['label']);
            $(shopCard).find('.catch').text(row['catch']);

            $(resultDom).append($(shopCard));
        })
        // メッセージ表示
        if(!response.length > 0) {
            $(resultDom).find(".message").removeClass('hide');
            $(resultDom).find(".message").text("検索結果が０件でした。条件を変更し、もう一度検索してみてください。");
        }

    });
}

/**
 * 日記アーカイブの画像をカルーセルに描画する
 * @param  {} obj 対象のセレクター
 * @param  {} selectFiles 選択した画像リスト
 * @param  {} delFlg newタグが付いた画像を削除するか
 */
function canvasRender(obj, selectFiles, delFlg) {

    if(delFlg) {
        $(obj).find(".new").remove();
    } else {
        $(obj).find(".card-img").remove();
    }

    var fileList = $(selectFiles).prop("files");
    var imgCount = $(obj).find(".card-img").length;
    var fimeMax = $("input[name='file_max']").val();
    var modulePath = "/module/materialboxed.ctp";

    $.each(fileList, function(index, file){
        //画像ファイルかチェック
        if (file["type"] != "image/jpeg" && file["type"] != "image/png" && file["type"] != "image/gif") {
            alert("jpgかpngかgifファイルを選択してください");
            $(selectFiles).val('');
            return false;
        }
    });
    // ファイル数を制限する
    if(fileList.length + imgCount > fimeMax) {
        alert("アップロードできる画像は" + fimeMax + "ファイルまでです。");
        resetBtn(obj, true);
        return;
    }
    for(var i = 0; i < fileList.length; i++){

        imgCount += 1;
        fileload(fileList[i], $(obj).find("#content,#modal-content").closest(".row"), imgCount, modulePath);
    }

}

/**
 * 画像フォームリセット リセットボタン処理
 * @param  {} obj 対象のセレクター
 * @param  {} delFlg newタグが付いた画像を削除するか
 */
function 
resetBtn(obj, delFlg){

    if(delFlg) {
        $(obj).find(".new").remove();
    } else {
        $(obj).find(".card-img").remove();
    }

    $(obj).find('#image-file').replaceWith($('#image-file').val('').clone(true));
    $(obj).find('#modal-image-file').replaceWith($('#modal-image-file').val('').clone(true));
    $(obj).find("input[name='file_path']").val('');
    $(obj).find("input[name='modal_file_path']").val('');

}

/**
 * モーダル初期化処理
 */
function resetModal(){
    // 通常の表示モードに切り替える。
    $("#modal-diary").find(".updateModeBtn").removeClass("hide");
    $("#modal-diary").find(".returnBtn").addClass("hide");
    $(".modal-edit-diary").addClass('hide');
    $("#view-diary").removeClass('hide');
    $("#modal-diary").find(".updateBtn").addClass("disabled");

    //$(".modal-edit-diary").find('form')[0].reset();
    $(".modal-edit-diary").find("input[name='json_data']").val('');
    $(".modal-edit-diary").find("textarea[name='content']").text('');
    $(".modal-edit-diary").find("textarea").trigger('autoresize');
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
        timeout: 10000,
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
                html: "通信に失敗しました。ステータス：" + textStatus
            });
        }
    });
}


    // common initialize
    function initialize() {

        /* 共通初期化処理 start */
        // materializecss sideNav サイドバーの初期化
        $('nav.nav-header-menu .button-collapse').sideNav({
            menuWidth: 300,
            edge: 'left',
            closeOnClick: false,
            draggable: true,
            onOpen: function(el) {
               // 開いたときの処理
            },
            onClose: function(el) {
               // 閉じたときの処理
            }
        });

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
        $('.slider').slider();
        // materializecss selectbox
        $('select').material_select();
        // materializecss tooltip
        $('.tooltipped').tooltip({delay: 50});
        $('.materialboxed').materialbox();
        $('.scrollspy').scrollSpy();
        $('.collapsible').collapsible();
        Materialize.updateTextFields();
    
        // materializecss modal モーダル表示してる時は、背景のスクロール禁止する
        // $('.modal').modal();
        $('.modal').modal({
            // dismissible: true, // Modal can be dismissed by clicking outside of the modal
            // opacity: 0.5, // Opacity of modal background
            // inDuration: 300, // Transition in duration
            // outDuration: 200, // Transition out duration
            // startingTop: '4%', // Starting top style attribute
            // endingTop: '10%', // Ending top style attribute
            ready: function() {
                scrollPosition = $(window).scrollTop();
                // モーダル表示してる時は、背景画面のスクロールを禁止する
                $('body').addClass('fixed').css({'top': -scrollPosition});
            },
            // モーダル非表示完了コールバック
            complete: function() {
                // モーダル非表示した時は、背景画面のスクロールを解除する
                $('body').removeClass('fixed').css({'top': 0});
                window.scrollTo( 0 , scrollPosition );
            }
        });
        // materializecss Chips
        $(document).find('.chips').material_chip();
        // materializecss Chips 追加イベント
        $('.chips').on('chip.add', function(e, chip){
            console.log(this);
            //alert("chip.add");
        });
        // materializecss Chips 削除イベント
        $('.chips').on('chip.delete', function(e, chip){
            //alert("chip.delete");
        });
        // materializecss Chips 選択イベント
        $('.chips').on('chip.select', function(e, chip){
            console.log(this);
            //alert("chip.select");
        });

        /** 下にスクロールでヘッダー非表示・上にスクロールでヘッダー表示 */
        $(function() {
            var $win = $(window),
                $header = $('#nav-header-menu'),
                headerHeight = $header.outerHeight(),
                startPos = 0;

            $($win).on('load scroll',function() {
                var value = $(this).scrollTop();
                if ( value > startPos && value > headerHeight ) {
                $header.css('top', '-' + headerHeight + 'px');
                } else {
                $header.css('top', '0');
                }
                startPos = value;
            });
        });

        // show return top button
        $(function() {
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
            $(document).on('click','#return_top a',function() {
                $('html,body').animate({scrollTop : 0}, 1000, 'easeOutExpo');
                return false;
            });
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

        // PhotoSwipeを起動する
        initPhotoSwipeFromDOM('.my-gallery');
        /* 共通処理 end */
    }

/**
 * ユーザー画面の初期化処理
 */
function initializeUser() {

    // ユーザーの初期化処理
    var x = $(window).width();
    var y = 600;
    if (x <= y) {
        $('.search-result-card').removeClass('horizontal');
    }else{
        $('.search-result-card').addClass('horizontal');
    }
    $(window).resize(function(){
        //windowの幅をxに代入
        var x = $(window).width();
        //windowの分岐幅をyに代入
        var y = 600;
        if (x <= y) {
            $('.search-result-card').removeClass('horizontal');
        }else{
            $('.search-result-card').addClass('horizontal');
        }
    });
    /* トップ画面 START */
    if($("#top").length) {
        // 検索ボタン押した時
        $(document).on('click', '.searchBtn', function() {
            form = $(this).closest("form[name='search_form']");
            if(($(form).find("input[name='key_word']").val() == "") &&
                ($(form).find("[name='area']").val() == "") &&
                ($(form).find("[name='genre']").val() == "")) {
                    alert("なにかしら条件を入れてね");
                    return false;
            }
            form.submit();
        });

    }
    /* トップ画面 END */

    /* エリア画面 START */
    if($("#area").length) {
        // 検索ボタン押した時
        $(document).on('click', '.searchBtn', function() {
            $form = $(this).closest($(".search-form"));
            if(($form.find("input[name='key_word']").val() == "") &&
                ($form.find("[name='genre']").val() == "")) {
                    alert("なにかしら条件を入れてね");
                    return false;
                }
            
            //通常のアクションをキャンセルする
            event.preventDefault();
            searchAjax("#search-result", $form);
        });
    }
    /* エリア画面 END */

    /* ジャンル画面 START */
    if($("#genre").length) {
        // 検索ボタン押した時
        $(document).on('click', '.searchBtn', function() {
            $form = $(this).closest($(".search-form"));
            if(($form.find("input[name='key_word']").val() == "") &&
                ($form.find("[name='area']").val() == "") &&
                ($form.find("[name='genre']").val() == "")) {
                    alert("なにかしら条件を入れてね");
                    return false;
                }
            //通常のアクションをキャンセルする
            event.preventDefault();
            searchAjax("#search-result", $form);
        });
    }
    /* ジャンル画面 END */

    /* 店舗画面 START */
    if($("#shop").length) {

    }
    /* 店舗画面 END */

    /* キャスト画面 START */
    if($("#cast").length) {

    }
    /* キャスト画面 END */

    /* 日記画面 START */
    if($("#diary").length) {
        // アーカイブ日記をクリックした時
        $(document).on('click', '.archiveLink', function() {

            var $form =$('#view-archive-diary');
            $($form).find("input[name='id']").val($(this).find("input[name='id']").val());
            //通常のアクションをキャンセルする
            event.preventDefault();
            callModalDiaryWithAjax($form, 'user');
            $('.materialboxed').materialbox();
        })
        // いいねボタン押した時
        $(document).on('click', '.likeBtn', function() {
           //$(document).find('.materialboxed').materialbox();
        });
    }
    /* 日記画面 END */

    /* 検索画面 START */
    if($("#search").length) {

        // 検索ボタン押した時
        $(document).on('click', '.searchBtn', function() {
            $form = $(this).closest($(".search-form"));
            if(($form.find("input[name='key_word']").val() == "") &&
                ($form.find("[name='area']").val() == "") &&
                ($form.find("[name='genre']").val() == "")) {
                    alert("なにかしら条件を入れてね");
                    return false;
                }
            //通常のアクションをキャンセルする
            event.preventDefault();
            searchAjax("#search-result", $form);
        });
    }
    /* 検索画面 画面 END */

}

/**
 * TODO: オーナー？ショップ？画面の初期化処理
 */
function initializeOwner() {

    // 店舗編集のスクリプト 店舗情報 クレジットフォームを入力不可にする
    $($('#tenpo').find('div[name="credit"]')).find('input').prop('disabled',true);

    /* トップ画像 関連処理 start */
    // トップ画像 変更、やめるボタン押した時
    $(document).on("click", ".top-image-changeBtn",function() {
        // 画像をクリアする
        $('#top-image').find('#top-image-show').attr({width:'',height:'',src:''});
        $('#top-image').find('[name="top_image"]').val('');

        // 編集フォームが表示されている場合
        if ($('#save-top-image').css('display') == 'block') {
            // ビュー表示
            $('#top-image').find("#save-top-image").hide();
            $('#top-image').find("#show-top-image").show();
        } else {
            // 編集表示
            $('#top-image').find('#save-top-image').show();
            $('#top-image').find("#show-top-image").hide();
        }

    });
    // トップ画像 登録ボタン押した時
    $(document).on("click", ".top-image-saveBtn",function() {

        //ファイル選択済みかチェック
        var fileCheck = $("#top-image-file").val().length;
        if (fileCheck === 0) {
            alert("画像ファイルを選択してください");
            return false;
        }
        if(!confirm('こちらの画像に変更でよろしいですか？')) {
            return false;
        }

        var $form = $('#save-top-image');

        // blobファイル変換
        var blobList = fileConvert("#top-image-canvas", '.top-image-preview');

        // サイズの大きい画像は、POSTで弾かれるので、フォーム内容は消す。
        // POSTでリクエスト内のデータが全部なくなるので。
        // TODO: 後で対策を考えよう
        $($form).find('input[name="top_image_file"]').val("");

        // アップロード用blobをformDataに設定
        var formData = new FormData($form.get()[0]);
        formData.append("top_image_file", blobList[0]);

        //通常のアクションをキャンセルする
        event.preventDefault();
        fileUpAjaxCommon($form, formData, $("#top-image"));
    });
    // トップ画像 削除ボタン押した時
    $(document).on("click", ".top-image-deleteBtn",function() {

        if (!confirm('トップ画像を削除してもよろしいですか？')) {
            return false;
        }

        var $form =$('#delete-top-image');

        //通常のアクションをキャンセルする
        event.preventDefault();
        ajaxCommon($form, $("#top-image"));
    });
    /* トップ画像 関連処理 end */

    /* キャッチコピー 関連処理 start */
    // キャッチコピー 変更、やめるボタン押した時
    $(document).on("click", ".catch-changeBtn",function() {

        // 編集フォームが表示されている場合
        if ($('#save-catch').css('display') == 'block') {
            // $(obj).find('[name="catch"]').val(""); //初期値を削除しない
            $("#catch").find("#save-catch").hide();
            $("#catch").find("#show-catch").show();
        } else {
            // catchという変数にしたかったがcatchはjsで予約語になる
            var json = JSON.parse($("#catch").find('input[name="json_data"]').val());

            var json = JSON.parse($("#catch").find('input[name="json_data"]').val());
            $("#catch").find('input[name="id"]').val(json['id']);
            $("#catch").find('textarea[name="catch"]').val(json['catch']);
            $("#catch").find('#save-catch').show();
            $("#catch").find("#show-catch").hide();
            $('textarea').trigger('autoresize');
            Materialize.updateTextFields(); // インプットフィールドの初期化
        }

    });

    // キャッチコピー 登録ボタン押した時
    $(document).on("click", ".catch-saveBtn",function() {
        var $form = $('#save-catch');
        if($form.find('textarea[name="catch"]').val() == '') {
            alert('キャッチコピーを入力してください。');
            return false;
        }
        if(!confirm('キャッチコピーを変更してもよろしいですか？')) {
            return false;
        }

        //通常のアクションをキャンセルする
        event.preventDefault();
        ajaxCommon($form, $("#catch"));
    });

    // キャッチコピー 削除ボタン押した時
    $(document).on("click", ".catch-deleteBtn",function() {

        if(!confirm('キャッチコピーを削除してもよろしいですか？')) {
            return false;
        }
        var json = JSON.parse($("#catch").find('input[name="json_data"]').val());
        $("#catch").find('input[name="id"]').val(json['id']);

        var $form = $('#delete-catch');

        //通常のアクションをキャンセルする
        event.preventDefault();
        ajaxCommon($form, $("#catch"));
    });
    /* キャッチコピー 関連処理 end */

    /* クーポン 関連処理 start */
    // クーポン 変更、やめるボタン押した時
    $(document).on("click", ".coupon-changeBtn",function() {
        $("html,body").animate({scrollTop:0});

        if ($('#save-coupon').css('display') == 'block') {
            // $(obj).find('[name="coupon"]').val(""); //初期値を削除しない
            $("#coupon").find("#save-coupon").hide();
            $("#coupon").find("#show-coupon").show();
        } else {
            // コントローラ側で処理判断するパラメータ
            $('input[name="crud_type"]').val('update');

            var checked = $('input[name="check_coupon"]:checked');
            var json = JSON.parse($(checked).closest('.coupon-box').find('input[name="json_data"]').val());
            var from = $('#from-day').pickadate('picker'); // Date Picker
            var to = $('#to-day').pickadate('picker'); // Date Picker
            from.set('select', [2000, 1, 1]);
            from.set('select', new Date(2000, 1, 1));
            from.set('select', json['from_day'], { format: 'yyyy-mm-dd' });
            to.set('select', [2000, 1, 1]);
            to.set('select', new Date(2000, 1, 1));
            to.set('select', json['to_day'], { format: 'yyyy-mm-dd' });
            $("#coupon").find('input[name="id"]').val(json['id']);
            $("#coupon").find('input[name="title"]').val(json['title']);
            $("#coupon").find('textarea[name="content"]').val(json['content']);
            if(json['status'] == 1) {
                $('input[name="status"]').attr('checked', 'checked');
            }
            $("#coupon").find('#save-coupon').show();
            $("#coupon").find("#show-coupon").hide();
            $('textarea').trigger('autoresize');
            Materialize.updateTextFields(); // インプットフィールドの初期化
        }
    });

    // クーポン 追加ボタン押した時
    $(document).on("click", ".coupon-addBtn",function() {
        if ($('#save-coupon').css('display') == 'block') {
            // $(obj).find('[name="coupon"]').val(""); //初期値を削除しない
            $("#coupon").find("#save-coupon").hide();
            $("#coupon").find("#show-coupon").show();
        } else {
            $("html,body").animate({scrollTop:0});
            // フォームの中身はすべて消す
            $("#coupon").find('input[name="id"]').val('');
            $("#coupon").find('#from-day').pickadate('picker').set("select",null);
            $("#coupon").find('#to-day').pickadate('picker').set("select",null);
            $("#coupon").find('#coupon-title').val('');
            $("#coupon").find('#coupon-content').val('');
            // コントローラ側で処理判断するパラメータ
            $('input[name="crud_type"]').val('insert');
            $("#save-coupon").find('button').removeClass('disabled');
            $("#coupon").find('#save-coupon').show();
            $("#coupon").find("#show-coupon").hide();
        }
    });

    // クーポン 登録ボタン押した時
    $(document).on("click", ".coupon-saveBtn",function() {

        if (!confirm('こちらの内容に変更でよろしいですか？')) {
            return false;
        }

        var $form = $('form[name="save_coupon"]');
        var from_day = $($form).find('input[name="from_day_submit"]').val();
        var to_day = $($form).find('input[name="to_day_submit"]').val();
        $($form).find('input[name="from_day"]').val(from_day);
        $($form).find('input[name="to_day"]').val(to_day);

        //通常のアクションをキャンセルする
        event.preventDefault();
        ajaxCommon($form, $("#coupon"));
    });

    /* クーポン チェックボックス押した時 */
    $(document).on('click','.check-coupon-group', function() {
        var $button = $('#coupon').find('.coupon-changeBtn,.coupon-deleteBtn');
        var $addButton = $('#coupon').find('.coupon-addBtn');

        if ($(this).prop('checked')){
            $("html,body").animate({scrollTop:$('.targetScroll').offset().top},1200);
            // 一旦全てをクリアして再チェックする
            $('.check-coupon-group').prop('checked', false);
            $(this).prop('checked', true);
            $($button).removeClass('disabled');
            $($addButton).addClass('disabled');
        } else {
            $($button).addClass('disabled');
            $($addButton).removeClass('disabled');
        }
    });

    /* クーポン スイッチ押した時 */
    $(document).on('change', '.coupon-switchBtn', function() {
        var target = $(this).closest('.coupon-box');
        var json = JSON.parse($(target).find('input[name="json_data"]').val());
        var status = 1; // チェック状態初期値
        // チェックされてない場合
        if(!$(this).prop('checked')) {
            status = 0;
        }
        var data = { 'id' : json.id, 'status' : status, 'action' : '/owner/shops/switch_coupon?' + json.shop_id };

        $.ajax({
            type: 'POST',
            datatype:'json',
            url: data.action,
            data: data,
            timeout: 10000,
            beforeSend : function(xhr, settings){
                $("#coupon").find('.coupon-switchBtn').attr('disabled' , true);
            },
            complete: function(xhr, textStatus){
                $("#coupon").find('.coupon-switchBtn').attr('disabled' , false);
            },
            success: function(response,dataType) {
                // OKの場合
                if(response.success){
                    Materialize.toast($(target).find(".coupon-num").text() + response.message, 3000, 'rounded')
                }else{
                // NGの場合
                    Materialize.toast($(target).find(".coupon-num").text() + response.message, 3000, 'rounded')
            }
            },
            error : function(response, textStatus, xhr){
                $("#coupon").find('.coupon-switchBtn').attr('disabled' , false);
                $.notifyBar({
                    cssClass: 'error',
                    html: "通信に失敗しました。ステータス：" + textStatus
                });
            }
        });
    });

    // クーポン 削除ボタン押した時
    $(document).on("click", ".coupon-deleteBtn",function() {

        var checked = $('input[name="check_coupon"]:checked');
        var json = JSON.parse($(checked).closest('.coupon-box').find('input[name="json_data"]').val());

        if (!confirm('【'+json.title+'】\n選択したクーポンを削除してもよろしいですか？')) {
            return false;
        }
        var $form = $('form[id="delete-coupon"]');
        $($form).find("input[name='id']").val(json.id);
        $($form).find("input[name='shop_id']").val(json.shop_id);

        //通常のアクションをキャンセルする
        event.preventDefault();
        ajaxCommon($form, $("#coupon"));
    });
    /* クーポン 関連処理 end */

    /* キャスト 関連処理 start */
    // キャスト 変更、やめるボタン押した時
    $(document).on("click", ".cast-changeBtn",function() {
        $("html,body").animate({scrollTop:0});

        if ($('#save-cast').css('display') == 'block') {
            // $(obj).find('[name="cast"]').val(""); //初期値を削除しない
            $("#cast").find("#save-cast").hide();
            $("#cast").find("#show-cast").show();
        } else {
            // コントローラ側で処理判断するパラメータ
            $('input[name="crud_type"]').val('update');

            var checked = $('input[name="check_cast"]:checked');
            var json = JSON.parse($(checked).closest('.cast-box').find('input[name="json_data"]').val());
            $("#cast").find('input[name="id"]').val(json['id']);
            $("#cast").find('input[name="name"]').val(json['name']);
            $("#cast").find('input[name="nickname"]').val(json['nickname']);
            $("#cast").find('input[name="email"]').val(json['email']);

            if(json['status'] == 1) {
                $('input[name="status"]').attr('checked', 'checked');
            }
            $("#cast").find('#save-cast').show();
            $("#cast").find("#show-cast").hide();
            $('textarea').trigger('autoresize');
            Materialize.updateTextFields(); // インプットフィールドの初期化
        }
    });

    // キャスト 追加ボタン押した時
    $(document).on("click", ".cast-addBtn",function() {
        if ($('#save-cast').css('display') == 'block') {
            $("#cast").find("#save-cast").hide();
            $("#cast").find("#show-cast").show();
        } else {
            $("html,body").animate({scrollTop:0});
            // フォームの中身はすべて消す
            $("#cast").find('input[name="id"]').val('');
            $("#cast").find('input[name="name"]').val('');
            $("#cast").find('input[name="nickname"]').val('');
            $("#cast").find('input[name="email"]').val('');
            // コントローラ側で処理判断するパラメータ
            $('input[name="crud_type"]').val('insert');
            $("#save-cast").find('button').removeClass('disabled');
            $("#cast").find('#save-cast').show();
            $("#cast").find("#show-cast").hide();
        }
    });

     /* キャスト 登録ボタン押した時 */
     $(document).on("click", ".cast-saveBtn",function() {

        if (!confirm('こちらの内容に変更でよろしいですか？')) {
            return false;
        }

        var $form = $('form[name="save_cast"]');

        //通常のアクションをキャンセルする
        event.preventDefault();
        ajaxCommon($form, $("#cast"));
    });

    /* キャスト チェックボックス押した時 */
    $(document).on('click','.check-cast-group', function() {
        var $button = $('#cast').find('.cast-changeBtn,.cast-deleteBtn');
        var $addButton = $('#cast').find('.cast-addBtn');

        if ($(this).prop('checked')){
            $("html,body").animate({scrollTop:$('.targetScroll').offset().top},1200);
            // 一旦全てをクリアして再チェックする
            $('.check-cast-group').prop('checked', false);
            $(this).prop('checked', true);
            $($button).removeClass('disabled');
            $($addButton).addClass('disabled');
        } else {
            $($button).addClass('disabled');
            $($addButton).removeClass('disabled');
        }
    });

    /* キャスト スイッチ押した時 */
    $(document).on('change', '.cast-switchBtn', function() {
        var target = $(this).closest('.cast-box');
        var json = JSON.parse($(target).find('input[name="json_data"]').val());
        var status = 1; // チェック状態初期値
        // チェックされてない場合
        if(!$(this).prop('checked')) {
            status = 0;
        }
        var data = { 'id' : json.id, 'status' : status, 'action' : '/owner/shops/switch_cast/' + json.shop_id };

        $.ajax({
            type: 'POST',
            datatype:'json',
            url: data.action,
            data: data,
            timeout: 10000,
            beforeSend : function(xhr, settings){
                $("#cast").find('.cast-switchBtn').attr('disabled' , true);
            },
            complete: function(xhr, textStatus){
                $("#cast").find('.cast-switchBtn').attr('disabled' , false);
            },
            success: function(response,dataType) {
                // OKの場合
                if(response.success){
                    Materialize.toast($(target).find(".cast-num").text() + response.message, 3000, 'rounded')
                }else{
                // NGの場合
                    Materialize.toast($(target).find(".cast-num").text() + response.message, 3000, 'rounded')
            }
            },
            error : function(response, textStatus, xhr){
                $("#cast").find('.cast-switchBtn').attr('disabled' , false);
                $.notifyBar({
                    cssClass: 'error',
                    html: "通信に失敗しました。ステータス：" + textStatus
                });
            }
        });
    });

    // キャスト 削除ボタン押した時
    $(document).on("click", ".cast-deleteBtn",function() {

        var checked = $('input[name="check_cast"]:checked');
        var json = JSON.parse($(checked).closest('.cast-box').find('input[name="json_data"]').val());

        if (!confirm('【'+json.name+'】\n選択したキャストを削除してもよろしいですか？')) {
            return false;
        }
        var $form = $('form[id="delete-cast"]');
        $($form).find("input[name='id']").val(json.id);
        $($form).find("input[name='shop_id']").val(json.shop_id);
        $($form).find("input[name='dir']").val(json.dir);

        //通常のアクションをキャンセルする
        event.preventDefault();
        ajaxCommon($form, $("#cast"));
    });
    /* キャスト 関連処理 end */

    /* 店舗情報 関連処理 start */
    // 店舗情報 変更、やめるボタン押した時
    $(document).on("click", ".tenpo-changeBtn",function() {
        $("html,body").animate({scrollTop:0});

        if ($('#save-tenpo').css('display') == 'block') {
            // $(obj).find('[name="tenpo"]').val(""); //初期値を削除しない
            $('#tenpo').find("#save-tenpo").hide();
            $('#tenpo').find("#show-tenpo").show();
        } else {
            var json = JSON.parse($('#tenpo').find('input[name="json_data"]').val());
            var $from = $('#tenpo').find('#bus-from-time').pickatime().pickatime('picker');
            var $to = $('#tenpo').find('#bus-to-time').pickatime().pickatime('picker');
            var from = new Date(json['bus_from_time']);
            var to = new Date(json['bus_to_time']);
            $($from).val(toDoubleDigits(from.getHours()) + ":" + toDoubleDigits(from.getMinutes()));
            $($to).val(toDoubleDigits(to.getHours()) + ":" + toDoubleDigits(to.getMinutes()));
            $('#tenpo').find('input[name="id"]').val(json['id']);
            $('#tenpo').find('input[name="name"]').val(json['name']);
            $('#tenpo').find('input[name="pref21"]').val(json['pref21']);
            $('#tenpo').find('input[name="addr21"]').val(json['addr21']);
            $('#tenpo').find('input[name="strt21"]').val(json['strt21']);
            $('#tenpo').find('input[name="tel"]').val(json['tel']);
            $('#tenpo').find('input[name="bus_hosoku"]').val(json['bus_hosoku']);
            $('#tenpo').find('textarea[name="staff"]').val(json['staff']);
            $('#tenpo').find('textarea[name="system"]').val(json['system']);
            //クレジットフィールドにあるタグを取得して配列にセット
            var data = JSON.parse($('#tenpo').find('input[name="credit_hidden"]').val());
            // クレジットフィールドの初期化
            $('.chips-initial').material_chip({
                data:data
            });
            $('#tenpo').find('#save-tenpo').show();
            $('#tenpo').find("#show-tenpo").hide();

            $('textarea').trigger('autoresize'); // テキストエリアの初期化
            Materialize.updateTextFields(); // インプットフィールドの初期化
            $($('#tenpo').find('div[name="credit"]')).find('input').prop('disabled',true);
        }
    });

    // 店舗情報 登録ボタン押した時
    $(document).on("click", ".tenpo-saveBtn",function() {

        if (!confirm('こちらの店舗内容でよろしいですか？')) {
            return false;
        }

        var $form = $('form[name="save_tenpo"]');
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
        ajaxCommon($form, $("#tenpo"));
    });

    /* ギャラリー 関連処理 start */
    // ギャラリー 削除ボタン押した時
    $(document).on("click", ".gallery-deleteBtn",function() {

        var json = $(this).data('delete');

        if (!confirm('選択したギャラリーを削除してもよろしいですか？')) {
            return false;
        }
        var $form = $('form[id="delete-gallery"]');
        $($form).find("input[name='key']").val(json.key);
        $($form).find("input[name='name']").val(json.name);

        //通常のアクションをキャンセルする
        event.preventDefault();
        ajaxCommon($form, $("#gallery"));
    });
    // ギャラリー 画像を選択した時
    $(document).on("change", "#image-file", function() {

        if($("#image-file").prop("files").length == 0) {
            $(".cancelBtn").addClass("disabled");
            $(".createBtn").addClass("disabled");
        } else {
            $(".cancelBtn").removeClass("disabled");
            $(".createBtn").removeClass("disabled");
        }
        $(document).find(".card-img.new").remove();
        var fileList = $("#image-file").prop("files");
        var imgCount = $(".card-img").length;
        var fimeMax = $("input[name='file_max']").val();
        var modulePath = "/module/materialboxed.ctp";

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
            resetBtn($(document), true);
            return false;
        }
        for(var i = 0; i < fileList.length; i++){

            imgCount += 1;
            fileload(fileList[i], $("#gallery .row"), imgCount, modulePath);
        }

    });
    // ギャラリー 登録ボタン押した時
    $(document).on("click", ".gallery-saveBtn",function() {

        //ファイル選択済みかチェック
        var fileCheck = $("#image-file").val().length;
        if (fileCheck === 0) {
            alert("画像ファイルを選択してください");
            return false;
        }
        if(!confirm('こちらの画像に変更でよろしいですか？')) {
            return false;
        }
        // 新しく追加された画像のみを対象にする
        $selecter = $('.card-img.new').find('img');

        var $form = $('#save-gallery');
        // ファイル変換
        var blobList = fileConvert("#image-canvas", $selecter);

        // サイズの大きい画像は、POSTで弾かれるので、フォーム内容は消す。
        // POSTでリクエスト内のデータが全部なくなるので。
        // TODO: 後で対策を考えよう
        $($form).find('#image-file, .file-path').val("");

        //アップロード用blobをformDataに設定
        formData = new FormData($form.get()[0]);
        for(item of blobList) {
          formData.append("image[]", item);
        }
        // for (item of formData) {
        //   console.log(item);
        // }
        //通常のアクションをキャンセルする
        event.preventDefault();
        fileUpAjaxCommon($form, formData, $("#gallery"));

    });
    // ギャラリー やめるボタン押した時
    $(document).on("click", ".gallery-chancelBtn",function() {
        // newタグが付いたファイルを表示エリアから削除する
        $('#gallery').find('.card-img.new').remove();
        // フォーム入力も削除する
        $("#gallery").find("#image-file, .file-path").val("");
    });
    /* ギャラリー 関連処理 end */

    // 求人情報 リストから選ぶボタン押した時
    $(document).on("click", ".jobModal-callBtn",function() {

        // オブジェクトを配列に変換
        //var treatment = Object.entries(JSON.parse($(obj).find('input[name="treatment_hidden"]').val()));
        var $chips = $('#job').find('.chips');
        var $chipList = $('#modal-job').find('.chip-dummy');
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
    });

    // 店舗情報 クレジットタグをフォームに追加する
    $(document).on('click','.chip-credit', function() {
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
        //フレームワークmaterializecssの本来のイベントをキャンセルする
        //※ブラウザでエラー発生するため（Uncaught TypeError: Cannot read property '*' of undefined）
        event.stopImmediatePropagation();

    });
    /* 店舗情報 関連処理 end */

    /* 求人情報 関連処理 start */
    // 求人情報 変更、やめるボタン押した時
    $(document).on("click", ".job-changeBtn",function() {
        $("html,body").animate({scrollTop:0});

        if ($('#save-job').css('display') == 'block') {
            $('#job').find("#save-job").hide();
            $('#job').find("#show-job").show();
        } else {
            var job = JSON.parse($('#job').find('input[name="json_data"]').val());
            $('#job').find('input[name="id"]').val(job['id']);
            $('#job').find('p[name="name"]').text($('#job').find('.show-job-name').text());
            $('#job').find('select[name="industry"]').val(job['industry']);
            $('#job').find('select[name="job_type"]').val(job['job_type']);

            var $from = $('#work-from-time').pickatime().pickatime('picker');
            var $to = $('#work-to-time').pickatime().pickatime('picker');
            var from = new Date(job['work_from_time']);
            var to = new Date(job['work_to_time']);
            $($from).val(toDoubleDigits(from.getHours()) + ":" + toDoubleDigits(from.getMinutes()));
            $($to).val(toDoubleDigits(to.getHours()) + ":" + toDoubleDigits(to.getMinutes()));
            $('#job').find('input[name="work_time_hosoku"]').val(job['work_time_hosoku']);
            $('#job').find('input[name="qualification_hosoku"]').val(job['qualification_hosoku']);
            $('#job').find('select[name="from_age"]').val(job['from_age']);
            $('#job').find('select[name="to_age"]').val(job['to_age']);
            var dayArray = job['holiday'].split(",");
            $.each(dayArray, function(index, val) {
                $('#job').find('input[name="holiday[]"]').each(function(i,o){
                    if (val == $(o).val()) {
                        $(o).prop('checked',true);
                    }
                    });
            });

            $('#job').find('input[name="holiday_hosoku"]').val(job['holiday_hosoku']);
            $('#job').find('input[name="treatment"]').val(job['treatment']);
            $('#job').find('textarea[name="pr"]').val(job['pr']);
            $('#job').find('input[name="tel1"]').val(job['tel1']);
            $('#job').find('input[name="tel2"]').val(job['tel2']);
            $('#job').find('input[name="email"]').val(job['email']);
            $('#job').find('input[name="lineid"]').val(job['lineid']);
            //待遇フィールドにあるタグを取得して配列にセット
            var data = JSON.parse($('#job').find('input[name="treatment_hidden"]').val());
            // 待遇フィールドの初期化
            $('#job').find('.chips-initial').material_chip({
                data:data
            });
            $('#job').find('#save-job').show();
            $('#job').find("#show-job").hide();

            $('textarea').trigger('autoresize'); // テキストエリアの初期化
            Materialize.updateTextFields(); // インプットフィールドの初期化
            $('#job').find('select').material_select(); // セレクトボックスの値を動的に変えたら初期化する必要がある
            $($('#job').find('div[name="credit"]')).find('input').prop('disabled',true);

        }
    });

    // 求人情報 登録ボタン押した時
    $(document).on("click", ".job-saveBtn",function() {

        if (!confirm('こちらの求人内容でよろしいですか？')) {
            return false;
        }

        var $form = $('form[name="save_job"]');
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
        ajaxCommon($form, $("#job"));
    });

    // 求人情報 待遇タグをフォームに追加する
    $(document).on('click','.chip-treatment', function(event) {
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
        //フレームワークmaterializecssの本来のイベントをキャンセルする
        //※ブラウザでエラー発生するため（Uncaught TypeError: Cannot read property '*' of undefined）
        event.stopImmediatePropagation();
    });
}

    /**
     * ajax共通処理
     * @param  {Object} $form 対象のフォーム
     * @param  {Object} $tab jsonのhtmlを挿入する対象セレクタ
     */
    var ajaxCommon = function($form, $tab) {

        $.ajax({
            url : $form.attr('action'), //Formのアクションを取得して指定する
            type: $form.attr('method'),//Formのメソッドを取得して指定する
            data: $form.serialize(), //データにFormがserialzeした結果を入れる
            dataType: 'json', //データにFormがserialzeした結果を入れる
            timeout: 10000,
            beforeSend : function(xhr, settings){
                //Buttonを無効にする
                $($tab).find('button').attr('disabled' , true);
                //処理中のを通知するアイコンを表示する
                $("#dummy").load("/module/Preloader.ctp");
            },
            complete: function(xhr, textStatus){
                //処理中アイコン削除
                $('.preloader-wrapper').remove();
                $($tab).find('button').attr('disabled' , false);
            },
            success: function (response, textStatus, xhr) {

                // OKの場合
                if(response.success){
                    $($tab).replaceWith(response.html);
                        $.notifyBar({
                        cssClass: 'success',
                        html: response.message
                    });
                    crearAllevents();
                    allInitialize();

                }else{
                // NGの場合
                    $.notifyBar({
                        cssClass: 'error',
                        html: response.message
                    });
                }
            },
            error : function(response, textStatus, xhr){
                $($tab).find('button').attr('disabled' , false);
                $.notifyBar({
                    cssClass: 'error',
                    html: "通信に失敗しました。ステータス：" + textStatus
                });
            }
        });
    }

    /** @description 画像アップロードajax共通処理
     * @param  {Object} $form 対象のフォーム
     * @param  {Object} formData 画像データを追加した$formの複製
     * @param  {Object} $tab jsonのhtmlを挿入する対象セレクタ
     */
    var fileUpAjaxCommon = function($form, formData, $tab) {

        $.ajax({
            url : $form.attr('action'), //Formのアクションを取得して指定する
            type: $form.attr('method'),//Formのメソッドを取得して指定する
            data: formData, //データにFormがserialzeした結果を入れる
            dataType: 'json', //データにFormがserialzeした結果を入れる
            processData: false,
            contentType: false,
            timeout: 10000,
            beforeSend : function(xhr, settings){
                //Buttonを無効にする
                $($tab).find('button').attr('disabled' , true);
                //処理中のを通知するアイコンを表示する
                $("#dummy").load("/module/Preloader.ctp");
            },
            complete: function(xhr, textStatus){
                //処理中アイコン削除
                $('.preloader-wrapper').remove();
                $($tab).find('button').attr('disabled' , false);
            },
            success: function (response, textStatus, xhr) {

                // OKの場合
                if(response.success){
                    $($tab).replaceWith(response.html);
                        $.notifyBar({
                        cssClass: 'success',
                        html: response.message
                    });
                    crearAllevents();
                    allInitialize();

                }else{
                // NGの場合
                    $.notifyBar({
                        cssClass: 'error',
                        html: response.message
                    });
                }
            },
            error : function(response, textStatus, xhr){
                $($tab).find('button').attr('disabled' , false);
                $.notifyBar({
                    cssClass: 'error',
                    html: "通信に失敗しました。ステータス：" + textStatus
                });
            }
        });
    }

    /**
     * 検索ajax処理
     * @param  {} replace
     * @param  {} form
     */
    var searchAjax = function(searchResult, form) {

        $.ajax({
            url : form.attr('action'), //Formのアクションを取得して指定する
            type: form.attr('method'),//Formのメソッドを取得して指定する
            data: form.serialize(), //データにFormがserialzeした結果を入れる
            dataType: 'json', //データにFormがserialzeした結果を入れる
            timeout: 10000000,
            beforeSend : function(xhr, settings){
                //Buttonを無効にする
                $(document).find('.searchBtn').addClass('disabled');
                //処理中の通知するアイコンを表示する
                $("#dummy").load("/module/Preloader.ctp");
            },
            complete: function(xhr, textStatus){
                //処理中アイコン削除
                $('.preloader-wrapper').remove();
                $(document).find('.searchBtn').removeClass('disabled');
            },
            success: function (response, textStatus, xhr) {
                $(searchResult).replaceWith(response.html);
            },
            error : function(response, textStatus, xhr){
                $.notifyBar({
                    cssClass: 'error',
                    html: "通信に失敗しました。ステータス：" + textStatus
                });
            }
        });

    }

    /**
     * @description モーダル日記表示 ユーザー、キャストのみで使用
     * @param  {} $form
     */
    var callModalDiaryWithAjax = function($form, userType){
        $.ajax({
            url : $form.attr('action'), //Formのアクションを取得して指定する
            type: $form.attr('method'),//Formのメソッドを取得して指定する
            data: $form.serialize(), //データにFormがserialzeした結果を入れる
            dataType: 'json', //データにFormがserialzeした結果を入れる
            timeout: 10000,
            beforeSend : function(xhr, settings){
                // 他のアーカイブリンクを無効化する
                $(".archiveLink").each(function(i, elem) {
                    $(elem).css('pointer-events', 'none');
                });
                //処理中のを通知するアイコンを表示する
                $("#dummy").load("/module/Preloader.ctp");
            },
            complete: function(xhr, textStatus){
                // 他のアーカイブリンクを有効化する
                $(".archiveLink").each(function(i, elem) {
                    $(elem).css('pointer-events', '');
                });
                //処理中アイコン削除
                $('.preloader-wrapper').remove();
            },
            success: function(response,dataType) {

                $('#modal-diary').modal({
                    dismissible: true, // Modal can be dismissed by clicking outside of the modal
                    opacity: .2, // Opacity of modal background
                    inDuration: 300, // Transition in duration
                    outDuration: 200, // Transition out duration
                    startingTop: '4%', // Starting top style attribute
                    endingTop: '10%', // Ending top style attribute
                    // モーダル表示完了コールバック
                    ready: function() {
                        scrollPosition = $(window).scrollTop();
                        // モーダル表示してる時は、背景画面のスクロールを禁止する
                        $('body').addClass('fixed').css({'top': -scrollPosition});
                        // キャストの日記ディレクトリを取得する
                        var diaryDir = $("input[name='cast_dir']").val() + response['dir'];
                        // 日記カードの要素を複製し、複製したやつを表示するようにする。
                        var diaryCard = $(".diary-card").clone(true).insertAfter(".diary-card").addClass('clone').removeClass('hide');
                        // 日記内容の設定
                        $(diaryCard).find("p[name='created']").text(response['ymd_created']);
                        $(diaryCard).find("p[name='title']").text(response['title']);
                        $(diaryCard).find("p[name='content']").textWithLF(response['content']);
                        $("#modal-diary").find(".like-count").text(response['likes'].length);
                        var images = [];
                        $.each(response, function(key, value) {

                            if(key.match(/image[0-9]*/) && value) {
                                // 値が空じゃない、かつnullじゃない時プッシュする
                                value !== '' && value !== null ?
                                images.push({"id":response['id'],
                                                "path":diaryDir + '/'+ value,
                                                "key":key,
                                                "name":value}) :'';
                            }
                        })
                        // 画像表示するグリッドを決定する
                        if(images.length > 0) {
                            // 画像表示するグリッド,高さを決定する
                            var colClass = "";
                            images.length == 1 ? colClass = 'col s12 m12 l12' : images.length == 2 ?
                            colClass = 'col s6 m6 l6' : colClass = 'col s4 m4 l4';
                            var figure = $(diaryCard).find('figure');
                            var imgClass = "";
                            $.each(images, function(key, value) {
                                var cloneFigure = $(figure).clone(true).removeClass().addClass(colClass).insertAfter(figure);
                                $(cloneFigure).find('a').attr({'href': value['path']});
                                $(cloneFigure).find('img').attr({'src': value['path']});
                            })
                            $(diaryCard).find('figure.hide').remove();
                            initPhotoSwipeFromDOM('.my-gallery');
                        }
                        // キャストの場合JSONデータを保持する
                        if(userType == 'cast') {
                            var dir = $("#diary").find("input[name='cast_dir']").val();
                            $("#delete-diary").find("input[name='id']").val(response['id']);
                            $("#delete-diary").find("input[name='dir_path']").val(dir + response['dir']);
                            $("#modal-edit-diary").find("input[name='id']").val(response['id']);
                            $("#modal-edit-diary").find("input[name='json_data']").val(JSON.stringify(images));
                        }
                    },

                    // モーダル非表示完了コールバック
                    complete: function() {
                        // ユーザーの場合
                        if(userType == 'user') {
                            // モーダル非表示した時は、背景画面のスクロールを解除する
                            $('body').removeClass('fixed').css({'top': 0});
                            window.scrollTo( 0 , scrollPosition );
                            $($(this)[0]["$el"]).find('.clone').remove();

                        } else if(userType == 'cast') {
                            //キャストの場合
                            // モーダルフォームをクリアする
                            $("#modal-edit-diary").find(".card-img").remove();
                            $("#modal-edit-diary").find('#modal-image-file').replaceWith($('#modal-image-file').val('').clone(true));
                            $("#modal-edit-diary").find("input[name='modal_file_path']").val('');
                            // モーダルを初期化
                            resetModal();
                            // モーダル非表示した時は、背景画面のスクロールを解除する
                            $('body').removeClass('fixed').css({'top': 0});
                            window.scrollTo( 0 , scrollPosition );
                            $(".modal-edit-diary").addClass('hide');
                            $("#view-diary").removeClass('hide');
                            $("#view-diary").find('.clone').remove();
                            $(".updateBtn").addClass("disabled");
                        }
                        $('.tooltipped').tooltip({delay: 50});

                    }
                });
                $("#modal-diary").modal('open');
            },
            error : function(response, textStatus, xhr){
                $.notifyBar({
                    cssClass: 'error',
                    html: "通信に失敗しました。ステータス：" + textStatus
                });
            }
        });
    }

/**
 * キャスト画面の初期化処理
 */
function initializeCast() {

    // TODO: ショップページとキャストページは分離していることから、jsファイルも分けるか
    // 要素の存在判定で読み込まない処理にするか後で考える。
    // キャスト用の初期化処理

    birthdayPickerIni();

    /* ダッシュボード 画面 START */
    if($("#dashboard").length) {

        fullcalendarSetting();

        // 登録,更新,削除ボタン押した時
        $(document).on("click",".createBtn, .updateBtn, .deleteBtn", function() {

            var $dashboard = $("#dashboard");
            var $form = $('#edit-calendar');
            var action = $(this).data("action");
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
            $.ajax({
                url : $form.attr('action'), //Formのアクションを取得して指定する
                type: $form.attr('method'),//Formのメソッドを取得して指定する
                data: $form.serialize(), //データにFormがserialzeした結果を入れる
                dataType: 'json', //データにFormがserialzeした結果を入れる
                timeout: 10000,
                beforeSend : function(xhr, settings){
                    //Buttonを無効にする
                    $($form).find('button').attr('disabled' , true);
                    $("#modal-calendar").modal('close');
                    //処理中を通知するアイコンを表示する
                    $("#dummy").load("/module/Preloader.ctp");
                },
                complete: function(xhr, textStatus){
                    //Buttonを有効にする
                    $('.preloader-wrapper').remove();
                    $($form).find('button').attr('disabled' , false);
                    $('#calendar').fullCalendar('refetchEvents');
                },
                success: function (response, textStatus, xhr) {

                    // OKの場合
                    if(response){
                        $.notifyBar({
                        cssClass: 'success',
                        html: response.message
                        });
                    }else{
                        // NGの場合
                        $.notifyBar({
                            cssClass: 'error',
                            html: response.error
                        });
                    }
                },
                error : function(response, textStatus, xhr){
                    $.notifyBar({
                        cssClass: 'error',
                        html: "通信に失敗しました。ステータス：" + textStatus
                    });
                }
            });
        });
 
    }
    /* ダッシュボード 画面 END */

    /* プロフィール 画面 START */
    if($("#profile").length) {
        var $profile = $("#profile");
        var profile = JSON.parse($($profile).find('input[name="json_data"]').val());
        var birthday = $('#birthday').pickadate('picker'); // Date Picker
        birthday.set('select', [2000, 1, 1]);
        birthday.set('select', new Date(2000, 1, 1));
        birthday.set('select', profile['birthday'], { format: 'yyyy-mm-dd' });
        $('textarea').trigger('autoresize'); // テキストエリアを入力文字の幅によりリサイズする
        $('select').material_select();

        // プロフィールを変更した時
        $(document).on("input", function() {
            $($profile).find(".saveBtn").removeClass("disabled");
        });
        // リストボックスを変更した時
        $(document).on("change","select", function() {
            $($profile).find(".saveBtn").removeClass("disabled");
        });
        // 登録ボタン押した時
        $($profile).find(".saveBtn").on("click", function() {
            if (!confirm('こちらのプロフィール内容でよろしいですか？')) {
                return false;
            }
            var $form =$('#save-profile');

            //通常のアクションをキャンセルする
            event.preventDefault();
            ajaxCommon($form, $("#wrapper"));
        });
    }
    // /* プロフィール 画面 END */
    /* 日記 画面 START */
    if($("#diary").length) {

        // 入力フォームに変更があった時
        $(document).on("input", "#title, #content, #image-file", function() {
            $("#edit-diary").find(".createBtn").removeClass("disabled");
            $("#edit-diary").find(".cancelBtn").removeClass("disabled");
        });
        // プロフィールを変更した時
        $(document).on("input","#modal-title, #modal-content, #modal-image-file", function() {
            $(".updateBtn").removeClass("disabled");
        });
        // 画像を選択した時
        $(document).on("change", "#image-file", function() {
            canvasRender("#diary", this, true);
        });
        // モーダル日記で画像を選択した時
        $(document).on("change", "#modal-image-file", function() {
            canvasRender("#modal-diary", this, true);
        });
        // 登録ボタン押した時
        $(document).on("click", ".createBtn",function() {
            if (!confirm('こちらの日記内容でよろしいですか？')) {
                return false;
            }

            var $form = $("#edit-diary");
            var fileCheck = $("#diary").find("#image-file").val().length;

            //ファイル選択済みの場合はajax処理を切り替える
            if (fileCheck > 0) {
                // 新しく追加された画像のみを対象にする
                $selecter = $('.card-img').find('img');

                // ファイル変換
                var blobList = fileConvert("#image-canvas", $selecter);

                // サイズの大きい画像は、POSTで弾かれるので、フォーム内容は消す。
                // POSTでリクエスト内のデータが全部なくなるので。
                // TODO: 後で対策を考えよう
                $($form).find('#image-file, .file-path').val("");

                //アップロード用blobをformDataに設定
                formData = new FormData($form.get()[0]);
                for(item of blobList) {
                    formData.append("image[]", item);
                }
                // for (item of formData) {
                //     console.log(item);
                // }
                //通常のアクションをキャンセルする
                event.preventDefault();
                fileUpAjaxCommon($form, formData, $("#wrapper"));

            } else {
                ajaxCommon($form, $("#wrapper"));

            }

        });
        // 更新ボタン押した時
        $(document).on("click", ".updateBtn",function() {

            // アクションタイプをhiddenにセットする。コントローラー側で処理分岐のために。
            var oldImgList = $('.card-img').not(".new"); // 既に登録した画像リスト
            var newImgList = $('.card-img.new').find('img'); // 追加した画像リスト
            var delList = new Array(); // 削除対象リスト
            if($('#modal-edit-diary').find("input[name='json_data']").val() != '') {
                delList = JSON.parse($('#modal-edit-diary').find("input[name='json_data']").val());
            }
            // 既に登録した画像リストを元に削除対象を絞る
            $(oldImgList).each(function(i, elm1) {
                $.each(delList, function(i, elm2) {
                    if($(elm1).find("input[name='name']").val() == elm2.name) {
                        delList.splice(i, 1);
                        return false;
                    }
                })
            })
            var tmpForm = $('#modal-edit-diary').clone();
            $(tmpForm).find("input[name='del_list']").val(JSON.stringify(delList));
            $(tmpForm).find("input[name='diary_id']").val($('#delete-diary').find("input[name='id']").val());
            $(tmpForm).find("input[name='dir_path']").val($('#delete-diary').find("input[name='dir_path']").val());
            $(tmpForm).find('.card-img').remove();

            var fileCheck = $(tmpForm).find("#modal-image-file").val().length;
            //ファイル選択済みの場合はajax処理を切り替える
            if (fileCheck > 0) {
                // 新しく追加された画像のみを対象にする
                $selecter = $('.card-img.new').find('img');

                // ファイル変換
                var blobList = fileConvert("#image-canvas", $selecter);

                // サイズの大きい画像は、POSTで弾かれるので、フォーム内容は消す。
                // POSTでリクエスト内のデータが全部なくなるので。
                // TODO: 後で対策を考えよう
                $(tmpForm).find('#modal-image-file, .modal-file-path').val("");

                //アップロード用blobをformDataに設定
                formData = new FormData(tmpForm.get()[0]);

                for(item of blobList) {
                    formData.append("image[]", item);
                }
                // for (item of formData) {
                //     console.log(item);
                // }
                //通常のアクションをキャンセルする
                event.preventDefault();
                fileUpAjaxCommon(tmpForm, formData, $("#wrapper"));

            } else {
                ajaxCommon(tmpForm, $("#wrapper"));

            }
        });
        // キャンセルボタン押した時
        $("#diary").on("click", ".cancelBtn", function() {

            if (!confirm('取り消しますか？')) {
                return false;
            }
            $("#diary").find(".createBtn").addClass("disabled");
            $("#diary").find(".updateBtn").addClass("disabled");
            $("#diary").find(".cancelBtn").addClass("disabled");
            $("#diary").find("#title, #content, #image-file, #file-path").val("");
            $("#diary").find(".card-img").remove();
        });

        // アーカイブ日記をクリックした時
        $(document).on("click", ".archiveLink",function() {

            var $form =$('#view-archive-diary');
            $($form).find("input[name='id']").val($(this).find("input[name='id']").val());
            //通常のアクションをキャンセルする
            event.preventDefault();
            callModalDiaryWithAjax($form, 'cast');
            $('.materialboxed').materialbox();
        });
        // モーダル日記の更新モードボタン押した時
        $(document).on("click", ".updateModeBtn",function() {

            $("#modal-diary").find(".updateModeBtn").addClass("hide");
            $("#modal-diary").find(".returnBtn").removeClass("hide");
            // アクションタイプをhiddenにセットする。コントローラー側で処理分岐のために。
            $("#modal-edit-diary").find("input[name='_method']").val('POST');
            $("#modal-edit-diary").find("input[name='title']").val($(".diary-card.clone")
                            .find("p[name='title']").text());
            $("#modal-edit-diary").find("textarea[name='content']").val($(".diary-card.clone")
                            .find("p[name='content']").textWithLF());
            $('textarea').trigger('autoresize'); // テキストエリアを入力文字の幅によりリサイズする
            // 画像が１つ以上ある場合にmaterialboxed生成
            if($("#modal-edit-diary").find("input[name='json_data']").val().length > 0) {
                var images = JSON.parse($("#modal-edit-diary").find("input[name='json_data']").val());
                $.get("/module/materialboxed.ctp", {}, function(html) {
                    var materialTmp = $(html).clone(); // materialboxedの部品を複製しておく
                    $(materialTmp).removeClass('new'); // newバッチを削除する
                    $(materialTmp).find('span.new').remove(); // newバッチを削除する
                    var materials = $();
                    $.each(images, function(index, image) {
                        var material = $(materialTmp).clone();
                        $(material).find("input[name='id']").val(image['id']);
                        $(material).find("input[name='name']").val(image['name']);
                        $(material).find("input[name='key']").val(image['key']);
                        $(material).find("input[name='path']").val(image['path']);
                        $(material).find('img').attr("src",image['path']);
                        materials = materials.add(material);
                    });
                    $(".modal-edit-diary").find(".row").append(materials);
                    $('.materialboxed').materialbox();
                    Materialize.updateTextFields();
                    $('.tooltipped').tooltip({delay: 50});
                });
            }
            // 更新画面を表示する
            $(".modal-edit-diary").removeClass('hide');
            $("#view-diary").addClass('hide');

        });
        // モーダル日記の戻るボタン押した時
        $(document).on("click", ".returnBtn",function() {
            // 画像フォームをクリアする
            $("#modal-edit-diary").find(".card-img").remove();
            $("#modal-edit-diary").find('#modal-image-file').replaceWith($('#modal-image-file').val('').clone(true));
            $("#modal-edit-diary").find("input[name='modal_file_path']").val('');
            // 通常の表示モードに切り替える。
            $("#modal-diary").find(".updateModeBtn").removeClass("hide");
            $("#modal-diary").find(".returnBtn").addClass("hide");
            $(".modal-edit-diary").addClass('hide');
            $("#view-diary").removeClass('hide');
            $("#modal-diary").find(".updateBtn").addClass("disabled");
            $(".modal-edit-diary").find("input[name='title']").val('');
            $(".modal-edit-diary").find("textarea[name='content']").text('');
            $(".modal-edit-diary").find("textarea").trigger('autoresize');
            //resetModal();

        });
        // モーダル日記の削除ボタン押した時
        $(document).on("click", ".deleteBtn",function() {
            // 画像の削除ボタンの場合
            if($(this).data('delete') == 'image') {
                var $image = $(this).closest(".card-img");
                // var serachName = $newImage.find("input[name='name']").val();
                $('.tooltipped').tooltip({delay: 50});
                $image.remove();
                $("#modal-diary").find(".updateBtn").removeClass("disabled");
                return;
            }
            // 日記削除の場合
            if (!confirm('この日記を削除しますか？')) {
                return false;
            }

            var $form = $('form[id="delete-diary"]');

            //通常のアクションをキャンセルする
            event.preventDefault();
            ajaxCommon($form, $("#wrapper"));
            $("#modal-diary").modal('close');
        });

    }
    /* 日記 画面 END */
    /* 画像アップロード 画面 START */
    if($("#gallery").length) {
        // ギャラリー 削除ボタン押した時
        $(document).on("click", ".gallery-deleteBtn",function() {

            var json = $(this).data('delete');

            if (!confirm('選択したギャラリーを削除してもよろしいですか？')) {
                return false;
            }
            var $form = $('form[id="delete-gallery"]');
            $($form).find("input[name='key']").val(json.key);
            $($form).find("input[name='name']").val(json.name);

            //通常のアクションをキャンセルする
            event.preventDefault();
            ajaxCommon($form, $("#wrapper"));
        });
        // ギャラリー 画像を選択した時
        $(document).on("change", "#image-file", function() {

            if($("#image-file").prop("files").length == 0) {
                $(".cancelBtn").addClass("disabled");
                $(".createBtn").addClass("disabled");
            } else {
                $(".cancelBtn").removeClass("disabled");
                $(".createBtn").removeClass("disabled");
            }
            $(document).find(".card-img.new").remove();
            var fileList = $("#image-file").prop("files");
            var imgCount = $(".card-img").length;
            var fimeMax = $("input[name='file_max']").val();
            var modulePath = "/module/materialboxed.ctp";

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
                resetBtn($(document), true);
                return false;
            }
            for(var i = 0; i < fileList.length; i++){

                imgCount += 1;
                fileload(fileList[i], $("#gallery .row"), imgCount, modulePath);
            }

        });
        // ギャラリー 登録ボタン押した時
        $(document).on("click", ".gallery-saveBtn",function() {

            //ファイル選択済みかチェック
            var fileCheck = $("#image-file").val().length;
            if (fileCheck === 0) {
                alert("画像ファイルを選択してください");
                return false;
            }
            if(!confirm('こちらの画像に変更でよろしいですか？')) {
                return false;
            }
            // 新しく追加された画像のみを対象にする
            $selecter = $('.card-img.new').find('img');

            var $form = $('#save-gallery');
            // ファイル変換
            var blobList = fileConvert("#image-canvas", $selecter);

            // サイズの大きい画像は、POSTで弾かれるので、フォーム内容は消す。
            // POSTでリクエスト内のデータが全部なくなるので。
            // TODO: 後で対策を考えよう
            $($form).find('#image-file, .file-path').val("");

            //アップロード用blobをformDataに設定
            formData = new FormData($form.get()[0]);
            for(item of blobList) {
                formData.append("image[]", item);
            }
            // for (item of formData) {
            //     console.log(item);
            // }
            //通常のアクションをキャンセルする
            event.preventDefault();
            fileUpAjaxCommon($form, formData, $("#wrapper"));

        });
        // ギャラリー やめるボタン押した時
        $(document).on("click", ".gallery-chancelBtn",function() {
            // newタグが付いたファイルを表示エリアから削除する
            $('#gallery').find('.card-img.new').remove();
            // フォーム入力も削除する
            $("#gallery").find("#image-file, .file-path").val("");
        });
    /* ギャラリー 関連処理 end */

    }
    /* 画像アップロード 画面 END */
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