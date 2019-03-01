<?php
namespace App\Model\Validation;

use Cake\Validation\Validation;

/**
 * カスタムバリデーションクラス
 */
class CustomValidation extends Validation
{
    /**
     * 電話番号フォーマットのチェック
     * 日本の携帯キャリア(docomo, au)のドメインに限り、連続ドットや@直前のドットを許可する
     * @param string $value
     * @param bool $context
     * @return bool
     */
    public function tel_check($value, $context)
    {
        //boolで返さないとエラー
        return (bool) preg_match('/^[0-9]{2,5}-?[0-9]{2,5}-?[0-9]{2,5}$/', $value);
    }
}