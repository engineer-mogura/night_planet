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
        console.log($(this).val());
      }
    });
    $($form).find('input[type="textarea"]').each(function(){
      if($(this).val() != "") {
        $(this).next().attr('class','active');
        console.log($(this).val());
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