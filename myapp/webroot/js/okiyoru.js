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


  });


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