/**
 * メソッド参照
 */
  var toDoubleDigits = function(num) {
      num += "";
      if (num.length === 1) {
        num = "0" + num;
      }
      return num;
    };

  /**
   * フィールドの値が存在したらclass="active"を追加する
   * @param  {} $form
   */
  var addClassAction = function($form) {
    $($form).find('input[type="text"]').each(function(){
      if($(this).val() != "") {
        $(this).next().attr('class','active');
      }
    });
    $($form).find('input[type="textarea"]').each(function(){
      if($(this).val() != "") {
        $(this).next().attr('class','active');
      }
    });
  };

  // 引数のBase64の文字列をBlob形式にする
  var base64ToBlob = function(base64) {
    var base64Data = base64.split(',')[1], // Data URLからBase64のデータ部分のみを取得
          data = window.atob(base64Data), // base64形式の文字列をデコード
          buff = new ArrayBuffer(data.length),
          arr = new Uint8Array(buff),
          blob,
          i,
          dataLen;
    // blobの生成
    for (i = 0, dataLen = data.length; i < dataLen; i++) {
        arr[i] = data.charCodeAt(i);
    }
    blob = new Blob([arr], {type: 'image/jpeg'});
    return blob;
  }

  /**
   * ファイル変換処理
   * @param  {} canvas
   * @param  {} form
   * @param  {} imgList
   */
  var fileConvert = function(canvasArea, form, imgList) {

    //加工後の横幅を800pxに設定
    var processingWidth = 800;

    //加工後の容量を100KB以下に設定
    var processingCapacity = 100000;
    // form 初期化
    var $form = null;
    var formData = null;
    var blob = [];

    $(imgList).each(function(index, element){

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
      var canvas = $(canvasArea);
      var ctx = canvas[0].getContext("2d");
      $(canvasArea).attr("width", w);
      $(canvasArea).attr("height", h);
      ctx.drawImage(image, 0, 0, w, h);

      //canvasに描画したデータを取得
      var canvasImage = $(canvasArea).get(0);

      //オリジナル容量(画質落としてない場合の容量)を取得
      var originalBinary = canvasImage.toDataURL("image/jpeg"); //画質落とさずバイナリ化
      var originalBlob = base64ToBlob(originalBinary); //画質落としてないblobデータをアップロード用blobに設定

      //オリジナル容量blobデータをアップロード用blobに設定
      var uploadBlob = originalBlob;

      //オリジナル容量が加工後容量以上かチェック
      if(processingCapacity <= originalBlob["size"]) {
          //加工後容量以下に落とす
          var capacityRatio = processingCapacity / originalBlob["size"];
          var processingBinary = canvasImage.toDataURL("image/jpeg", capacityRatio); //画質落としてバイナリ化
          uploadBlob = base64ToBlob(processingBinary); //画質落としたblobデータをアップロード用blobに設定
      }

      blob.push(uploadBlob);
    });

    //アップロード用blobをformDataに設定
    $form = $(form);
    formData = new FormData($form.get()[0]);
    formData.append("image", blob);
    return formData;

  }


  /**
   * 
   */
  var fullcalendarSetting = function() {
    var jsonPath = $("input[name='calendar_path']").val();
    var $form = $("#edit-calendar");
    var jsonPath = $($form).find("input[name='calendar_path']").val();
    var $calendar = $("#modal-calendar");
    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();
    var array = [{
      title: 'All Day Event',
      start: new Date(y, m, 1),
      time_start: '13:00',
      time_end: '14:00',
      active: '1'
    }, {
      id: 1,
      cast_id: '0',
      title: 'Long Event',
      start: new Date(y, m, d - 5),
      end: new Date(y, m, d - 2),
      time_start: '13:00',
      time_end: '14:00',
      active: '1'
  }, {
      id: 2,
      cast_id: '0',
      title: 'Repeating Event',
      start: new Date(y, m, d - 3, 16, 0),
      time_start: '13:00',
      time_end: '14:00',
      allDay: false,
      active: '1'
  }, {
      id: 3,
      cast_id: '0',
      title: 'Repeating Event',
      start: new Date(y, m, d + 4, 16, 0),
      time_start: '13:00',
      time_end: '14:00',
      allDay: false,
      active: '1'
  }, {
      id: 4,
      cast_id: '0',
      title: 'travel',
      start: new Date(y, m, d + 1, 19, 0),
      end: new Date(y, m, d + 1, 22, 30),
      time_start: '13:00',
      time_end: '14:00',
      allDay: false,
      active: '1'
  }, {
      id: 5,
      cast_id: '0',
      title: 'Click for Google',
      start: new Date(y, m, 28),
      end: new Date(y, m, 29),
      time_start: '13:00',
      time_end: '14:00',
      active: '1'
  }];

      // 初期処理
      $('#calendar').fullCalendar({

      // ここに各種オプションを書いていくと設定が適用されていく
      //ヘッダーの設定
      header: {
          left: 'today month,basicWeek',
          center: 'title',
          right: 'prev next'
      },
      editable: true, // イベントを編集するか
      allDaySlot: false, // 終日表示の枠を表示するか
      eventDurationEditable: false, // イベント期間をドラッグしで変更するかどうか
      slotEventOverlap: false, // イベントを重ねて表示するか
      selectable: true,
      selectHelper: true,
      firstDay : 1,
      timeFormat: 'H:mm',
      select: function(start, end, allDay) {
          //日の枠内を選択したときの処理
      },
      dayClick: function(date, jsEvent, view, resourceObj) {

          $($calendar).find(".event-name").text('イベント追加');
          $($calendar).find(".description").text('イベント追加を行います。');
          $($calendar).find(".createBtn").show(); //登録ボタン表示
          $($calendar).find(".updateBtn").hide(); //更新ボタン非表示
          $($calendar).find(".deleteBtn").hide(); //削除ボタン非表示

          // フォーム内を初期化
          $($form)[0].reset();
          $($form).find('select').material_select(); // セレクトボックスの値を動的に変えたら初期化する必要がある

          $($form).find("input[name='start']").val(date.format("YYYY-MM-DD"));
          $($form).find("input[name='end']").val(date.format("YYYY-MM-DD")); // TODO:１日スパンで登録を行うのでendにも同じ日付を入れておく

          $($form).find("input[name='active']").val(1);
          $($form).find(".target-day").text(date.format());
          //クリックした日付が取れるよ
          modalCalendarTriggerBtn();
          console.log('Clicked on: ' + date.format());
          console.log('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);
          console.log('Current view: ' + view.name);
          console.log(date.format());
          console.log(jsEvent);
          console.log(view);
          $($calendar).modal('open');

      },
      eventClick: function(calEvent, jsEvent, view) {

          $($calendar).find(".event-name").text('イベント編集');
          $($calendar).find(".description").text('イベント編集を行います。');
          $($calendar).find(".createBtn").hide(); //登録ボタン非表示
          $($calendar).find(".updateBtn").show(); //更新ボタン表示
          $($calendar).find(".deleteBtn").show(); //削除ボタン表示
          // フォーム内を初期化
          $($form)[0].reset();
          //イベントをクリックしたときの処理
          modalCalendarTriggerBtn();

          var targetDay = calEvent['start'].format("YYYY-MM-DD");
          var timeStart = "";
          var timeEnd = "";
          if (calEvent['end'] != null) {
            targetDay += '～'+ calEvent['end'].format("YYYY-MM-DD");
          }
          if (calEvent['start'] != null) {
            timeStart = calEvent['start'].format("HH:mm");
          }
          if (calEvent['end'] != null) {
            timeEnd = calEvent['end'].format("HH:mm");
          } else if (calEvent['time_end'] != null) {
            timeEnd = calEvent['time_end'];
          }
          if (calEvent['allDay'] != null) {
            $($form).find("input[name='all_day']").val(calEvent['allDay']);
          }
          if (calEvent['active'] != null) {
            $($form).find("input[name='active']").val(calEvent['active']);
          }
          $($form).find("input[name='id']").val(calEvent['id']);
          $($form).find("input[name='start']").val(calEvent['start'].format("YYYY-MM-DD"));
          $($form).find("input[name='end']").val(calEvent['start'].format("YYYY-MM-DD")); // TODO:１日スパンで登録を行うのでendにも同じ日付を入れておく

          $($form).find("input[name='active']").val(1);
          $($form).find(".target-day").text(targetDay);
          var $title = $($form).find('input[name="title"]');
          var startval =  $($form).find('#time-start option');
          $($form).find("#time-start option").each(function(index, element){
            if(element.value == timeStart) {
              $("#time-start").val(timeStart);
            }
            if(element.value == timeEnd) {
              $("#time-end").val(timeEnd);
            }
          });

          $($title).each(function(index, element){
            if($(element).val() == calEvent.title) {
              $(element).prop('checked', true);
            }
          });
          $($form).find('select').material_select(); // セレクトボックスの値を動的に変えたら初期化する必要がある
          console.log('Event: ' + calEvent.title);
          console.log('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);
          console.log('View: ' + view.name);
          console.log(calEvent);
          console.log(jsEvent);
          console.log(view);
          $($calendar).modal('open');

      },
      eventDrop: function(item, delta,revertFunc,jsEvent,ui, view) {
          //ドロップした情報
          alert('Clicked on: ' + item.title);
          //ドロップしたことを元に戻したいとき
          revertFunc();
      },
      droppable: true,// イベントをドラッグできるかどうか
      events:jsonPath
    });
  }

  /**
   * カレンダーの再描画
   */
  var fullcalendarInitialize = function(jsonPath) {
    $.ajax({ // json読み込み開始
      type: 'GET',
      url: jsonPath,
      dataType: 'json',
      timeout: 10000,
  })
    .then(
      function(json) { // jsonの読み込みに成功した時
        console.log('成功');
        // $('#calendar').fullCalendar('removeEvents');
        // $('#calendar').fullCalendar('addEventSource', json);
        // $('#calendar').fullCalendar('rerenderEvents');
        //$('#calendar').fullCalendar('refetchEvents');
      },
      function() { //jsonの読み込みに失敗した時
        console.log('失敗');
      }
    );

  }

 /**
 * URLパラメータ値を取得します
 *
 * @param  name {string} パラメータのキー文字列
 * @return  url {url} 対象のURL文字列（任意）
 */
function getParam(name, url) {
  if (!url) url = window.location.href;
  name = name.replace(/[\[\]]/g, "\\$&");
  var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
      results = regex.exec(url);
  if (!results) return null;
  if (!results[2]) return '';
  return decodeURIComponent(results[2].replace(/\+/g, " "));
}

  /**
   * ajax共通処理
   */
  var ajaxCommon = function($form) {

    //通常のアクションをキャンセルする
    event.preventDefault();

    $.ajax({
      url : $form.attr('action'), //Formのアクションを取得して指定する
      type: $form.attr('method'),//Formのメソッドを取得して指定する
      data: $form.serialize(), //データにFormがserialzeした結果を入れる
      //dataType: 'html', //データにFormがserialzeした結果を入れる
      timeout: 10000,
      beforeSend : function(xhr, settings){
          //Buttonを無効にする
          $(document).find('.saveBtn').addClass('disabled');
          $(document).find('.createBtn').addClass('disabled');
          $(document).find('.updateBtn').addClass('disabled');
          $(document).find('.cancelBtn').addClass('disabled');
          //処理中のを通知するアイコンを表示する
          $("#dummy").load("/module/Preloader.ctp");
      },
      complete: function(xhr, textStatus){

        //処理中アイコン削除
        $('.preloader-wrapper').remove();
        $(document).find('.saveBtn').addClass('disabled');
        $(document).find('.createBtn').addClass('disabled');
        $(document).find('.updateBtn').addClass('disabled');
        $(document).find('.cancelBtn').addClass('disabled');
      },
      success: function (response, textStatus, xhr) {

        // OKの場合
        if(typeof(response) !== "object"){
            // TODO: モーダル表示時は、背景画面をFIXIDしているので、
            // close処理を呼んで、FIXIDを解除する
            $(".modal").modal('close');
              var $objWrapper = $("#wrapper");
              $($objWrapper).replaceWith(response);
              //初期化
              initialize();

              // $.notifyBar({
              //     // cssClass: 'success',
              //     // //html: response.message
              // });
          }else{
            // var response = $.parseJSON(response);
          // NGの場合
              $.notifyBar({
                  cssClass: 'error',
                  html: response['errors']
              });

          }
      },
      error : function(response, textStatus, xhr){
        $(document).find('.saveBtn').removeClass('disabled');
        $(document).find('.createBtn').removeClass('disabled');
        $(document).find('.updateBtn').removeClass('disabled');
        $(document).find('.cancelBtn').removeClass('disabled');
        $.notifyBar({
          cssClass: 'error',
          html: "通信に失敗しました。ステータス：" + textStatus
        });
      }
  });
  return false;

}

/**
 * 画像アップロードajax共通処理
 */
var fileUpAjaxCommon = function($form, formData) {

  //通常のアクションをキャンセルする
  event.preventDefault();

  $.ajax({
      url : $form.attr('action'), //Formのアクションを取得して指定する
      type: $form.attr('method'),//Formのメソッドを取得して指定する
      data: formData, //データにFormがserialzeした結果を入れる
      //dataType: 'html', //データにFormがserialzeした結果を入れる
      processData: false,
      contentType: false,
      timeout: 1000000,
      beforeSend : function(xhr, settings){
          //Buttonを無効にする
          $(document).find('.saveBtn').addClass('disabled');
          $(document).find('.createBtn').addClass('disabled');
          $(document).find('.updateBtn').addClass('disabled');
          $(document).find('.cancelBtn').addClass('disabled');
          //処理中のを通知するアイコンを表示する
          $("#dummy").load("/module/Preloader.ctp");
      },
      complete: function(xhr, textStatus){
          //処理中アイコン削除
          $('.preloader-wrapper').remove();
          $(document).find('.saveBtn').addClass('disabled');
          $(document).find('.createBtn').addClass('disabled');
          $(document).find('.updateBtn').addClass('disabled');
          $(document).find('.cancelBtn').addClass('disabled');
      },
      success: function (response, textStatus, xhr) {
        // OKの場合
        if(typeof(response) !== "object"){
          // TODO: モーダル表示時は、背景画面をFIXIDしているので、
            // close処理を呼んで、FIXIDを解除する
            $(".modal").modal('close');
                var $objWrapper = $("#wrapper");
                $($objWrapper).replaceWith(response);
                //初期化
                initialize();
                // $.notifyBar({
                //     // cssClass: 'success',
                //     // //html: response.message
                // });
        }else{
            // var response = $.parseJSON(response);
            // NGの場合
            $.notifyBar({
                cssClass: 'error',
                html: response['errors']
            });

        }
      },
      error : function(response, textStatus, xhr){
          $(document).find('.saveBtn').removeClass('disabled');
          $(document).find('.createBtn').removeClass('disabled');
          $(document).find('.updateBtn').removeClass('disabled');
          $(document).find('.cancelBtn').removeClass('disabled');
          $.notifyBar({
              cssClass: 'error',
              html: "通信に失敗しました。ステータス：" + textStatus
          });
      }
  });
}

     /**
     * フルカレンダーのモーダル呼び出し時の処理
     * @param {*} obj 
     */
    function modalCalendarTriggerBtn(obj) {

      // オブジェクトを配列に変換
      //var treatment = Object.entries(JSON.parse($(obj).find('input[name="treatment_hidden"]').val()));
      // var $chips = $('#job').find('.chips');
      // var $chipList = $('#modal-job').find('.chip');
      // $($chips).val($(this).attr('id'));
      //  // 待遇フィールドにあるタグを取得して配列にセット
      //  var data = $($chips).material_chip('data');

      // var addFlg = true;
      // // 配列dataを順に処理
      // // タグの背景色を初期化する
      // $($chipList).each(function(i,o){
      //     $(o).removeClass('back-color');
      // });
      // // インプットフィールドにあるタグは選択済にする
      // $.each(data, function(index, val) {
      //     $($chipList).each(function(i,o){
      //         if($(o).attr('id') == val.id) {
      //             $(o).addClass('back-color');
      //             return true;
      //         }
      //     });
      // });
  }



  var isJSON = function(arg) {
    arg = (typeof arg === "function") ? arg() : arg;
    if (typeof arg  !== "string") {
        return false;
    }
    try {
    arg = (!JSON) ? eval("(" + arg + ")") : JSON.parse(arg);
        return true;
    } catch (e) {
        return false;
    }
};

$(document).ready(function () {

  var idArr=[];
  var duplicateIdArr = [];
  [].forEach.call(document.querySelectorAll('[id]'), function(elm){
    var id = elm.getAttribute('id');
    if(idArr.indexOf(id) !== -1) {
      duplicateIdArr.push(id);
    } else {
      idArr.push(id);
    }
  });
  if(duplicateIdArr.length > 0) {
    console.error('IDの重複があります:', duplicateIdArr);
  } else {
    console.success('IDの重複はありません。');
  }
});

/**
 * jquery(セレクタ).textWithLFメソッドを追加する。
 * 改行付きの文字列に変換する。
 */
(function(r){function l(a){return b(a,c,t,function(a){return u[a]})}function m(a){return b(a,f,v,function(a){return w[a]})}function b(a,b,d,e){return a&&d.test(a)?a.replace(b,e):a}function d(a){if(null==a)return"";if("string"==typeof a)return a;if(Array.isArray(a))return a.map(d)+"";var b=a+"";return"0"==b&&1/a==-(1/0)?"-0":b}var u={"&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#39;"},c=/[&<>"']/g,t=new RegExp(c.source),w={"&amp;":"&","&lt;":"<","&gt;":">","&quot;":'"',"&#39;":"'"},f=/&(?:amp|lt|gt|quot|#39);/g,
v=new RegExp(f.source),e=/<(?:.|\n)*?>/mg,n=new RegExp(e.source),g=/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g,p=new RegExp(g.source),h=/<br\s*\/?>/mg,q=new RegExp(h.source);r.fn.textWithLF=function(a){var c=typeof a;return"undefined"==c?m(b(b(this.html(),h,q,"\n"),e,n,"")):this.html("function"==c?function(c,f){var k=a.call(this,c,m(b(b(f,h,q,"\n"),e,n,"")));return"undefined"==typeof k?k:b(l(d(k)),g,p,"$1<br>")}:b(l(d(a)),g,p,"$1<br>"))}})(jQuery);