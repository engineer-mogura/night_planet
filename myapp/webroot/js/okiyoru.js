   //　サイドバーの初期化
   $(document).ready(function(){
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
      },
    });

    // click return top
    $('#return_top a').click(function() {
      $('html,body').animate({scrollTop : 0}, 1000, 'easeOutExpo');
      return false;
    });

    $('.tooltipped').tooltip({delay: 50});

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

    $('.modal').modal();

    /* 店舗ページの設定 クーポンクリックイベント */
    $('.coupon').click(function() {
        $(this).find('.arrow').toggleClass("active");
        $(this).find('.arrow').toggleClass('nonActive');
        if (!$(this).find('.arrow').hasClass('active')) {
            $(this).find('.label').text('クーポンを表示する');
        } else {
            $(this).find('.label').text('クーポンをとじる');
        }
    });

    /* 店舗編集の設定 トップ画像編集 画像プレビュ */

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

  });

    /* トップ画像タブの通常表示、変更表示切替え */
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

    /* キャッチコピータブの通常表示、変更表示切替え */
    function catchChangeBtn(obj){
      if ($('#edit-catch').css('display') == 'block') {
        $(obj).find('img.prev-image').remove();
        // $(obj).find('[name="catch"]').val(""); //初期値を削除しない
        $(obj).find("#edit-catch").hide();
        $(obj).find("#show-catch").show();
      } else {
        $(obj).find('#edit-catch').show();
        $(obj).find("#show-catch").hide();
      }
    }
    /*  */
    function removeConfirm() {
      var res = confirm("トップ画像を消去してもよろしいですか？");
      if( res == true ) {
          // 削除する
          window.location.href = "https://www.nishishi.com/";
      } else {
          // キャンセル
          return false;
      }

    }

    /*  */
    function check(obj) {
      var res = confirm("こちらの画像に変更でよろしいですか？");
      if( res == true ) {
          console.log(obj);
          // 削除する
          obj.submit();
      } else {
          // キャンセル
          return false;
      }

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