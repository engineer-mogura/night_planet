<?php
use Cake\Core\Configure;
 
return [


	// キャスト用設定 cast.config
	define('CAST_CONFIG', array(
		'FILE_MAX'=>'8', // アップロードファイル数
		'TITLE_EXCERPT'=>'10', // 抜粋文字数
		'CONTENT_EXCERPT'=>'10', // 抜粋文字数
		'ELLIPSIS'=>'...', // 省略文字
	)),

	// パス設定 path.config
	define('PATH_ROOT', array(
		'NO_IMAGE01'=> '/img/common/no-img16.png',
		'NO_IMAGE02'=> '/img/common/no-img150_150/no-img6.png',
		'NO_IMAGE03'=> '/img/common/no-img150_150/no-img7.png',
		'NO_IMAGE04'=> '/img/common/no-img150_150/no-img8.png',
		'NO_IMAGE05'=> '/img/common/no-img150_150/no-img9.png',
		'SLASH'=> '/',
		'OWNER'=> 'owner',
		'USER'=> 'user',
		'CAST'=> 'cast',
		'DIARY'=> 'diary',
		'TMP'=> 'tmp',
		'IMAGE'=> 'image',
		'IMG'=> 'img', // TODO: こいつは、ルートディレクトリに使ってる。imgってディレクトリ名は不適切だから後で変える

	)),

	// ラベル定数 developer.label menu
	define('DEVELOPER_LM', array(
		'001'=>'開発者リスト',
		'002'=>'ユーザーリスト',
		'003'=>'オーナーリスト',
	)),

	// ラベル定数 label title
	define('LT', array(
		'001'=>'OKIYORU Go',
		'002'=>'Copyright 2018',
		'003'=>'OKIYORU Go All Rights Reserved.',
		'004'=>'管理画面',
	)),

	// ラベル定数 owner.label menu
	define('USER_LM', array(
		'001'=>'新着情報',
		'002'=>'特集',
		'003'=>'ランキング',
		'004'=>'トップページ',
		'005'=>'facebook',
		'006'=>'店舗の掲載をご希望の方',
		'007'=>'店舗ログイン',
	)),

	// ラベル定数 owner.label menu
	define('OWNER_LM', array(
		'001'=>'トップ画像を編集する',
		'002'=>'キャッチコピーを編集する',
		'003'=>'クーポンを編集する',
		'004'=>'キャストを編集する',
		'005'=>'店舗情報を編集する',
		'006'=>'店内を編集する',
		'007'=>'マップを編集する',
		'008'=>'求人情報を編集する',
		'009'=>'キャストのトップへ行く',
	)),

	// ラベル定数 cast.label menu
	define('CAST_LM', array(
		'001'=>'ダッシュボード',
		'002'=>'プロフィールを編集する',
		'003'=>'日記を書く',
		'004'=>'画像をアップする',
		'005'=>'キャストのトップへ行く',
	)),

	// ラベルボタン定数 common.label button
	define('COMMON_LB', array(
		'001'=>'表示',
		'002'=>'編集',
		'003'=>'追加',
		'004'=>'削除',
		'005'=>'変更',
		'006'=>'登録',
		'050'=>'でログイン中',
		'051'=>'ログイン',
		'008'=>'',
	)),

	// ラベル定数 common.label menu
	define('COMMON_LM', array(
		'001'=>'よくある質問',
		'002'=>'お問い合わせ',
		'003'=>'プライバシーポリシー',
		'004'=>'トップに戻る',
		'005'=>'ご利用規約',
		'006'=>'ログイン',
		'007'=>'ログアウト',
	)),

	define('CATCHCOPY','【おきよる】では、県内特化型ポータルサイトとして、沖縄全域のナイト情報を提供しております。(\※ソープ、デリヘル等の風俗情報を除く)。高機能な検索システムを採用しておりますので、お客様にピッタリな情報がすぐに見つかります。更に店舗ごとに多彩なクーポン券などご用意しておりますのでお店に行く前に検索してクーポン券があるのかチェックしてみるのもいいでしょう。'),

	// 所属エリアリスト
	'area' => [
		'miyako'=> [
			'area_label' => "宮古島",
			'area_path' => "miyako"
		],
		'ishigaki'=> [
			'area_label' => '宮古島',
			'area_path' => 'ishigaki'
		],
		'naha'=> [
			'area_label' => '宮古島',
			'area_path' => 'naha'
		],
		'nanjo'=> [
			'area_label' => '宮古島',
			'area_path' => 'nanjo'
		],
		'tomigusuku'=> [
			'area_label' => '宮古島',
			'area_path' => 'tomigusuku'
		],
		'urasoe'=> [
			'area_label' => '宮古島',
			'area_path' => 'urasoe'
		],
		'ginowan'=> [
			'area_label' => '宮古島',
			'area_path' => 'ginowan'
		],
		'okinawashi'=> [
			'area_label' => '宮古島',
			'area_path' => 'okinawashi'
		],
		'uruma'=> [
			'area_label' => '宮古島',
			'area_path' => 'uruma'
		],
		'nago'=> [
			'area_label' => '宮古島',
			'area_path' => 'nago'
		],

	],
	// 業種リスト
	'genre' => [
		'caba'=> [
			'genre_label' => "キャバクラ",
			'genre_path' => "caba"
		],
		'snack'=> [
			'genre_label' => 'スナック',
			'genre_path' => 'snack'
		],
		'girlsbar'=> [
			'genre_label' => 'ガールズバー',
			'genre_path' => 'girlsbar'
		],
		'bar'=> [
			'genre_label' => 'バー',
			'genre_path' => 'bar'
		],
	],

	// 共通メッセージ common.message
	define('COMMON_M', array(
		'LOGINED'=>'ログインしました。',
		'LOGGED_OUT'=>'ログアウトしました。',
	)),

	// 確認メッセージ info.confirmation.message
	'icm' => [
		'001' => '',
		'002' => '',
	],
	// 結果メッセージ info.result.message
	define('RESULT_M', array(
		'SIGNUP_SUCCESS'=>'登録しました。',
		'UPDATE_SUCCESS'=>'編集しました。',
		'DELETE_SUCCESS'=>'削除しました。',
		'AUTH_SUCCESS'=>'認証完了しました。ログインしてください。',
		'SIGNUP_FAILED'=>'登録に失敗しました。もう一度登録しなおしてください。',
		'UPDATE_FAILED'=>'編集に失敗しました。もう一度編集しなおしてください。',
		'DELETE_FAILED'=>'削除に失敗しました。もう一度削除しなおしてください。',
		'AUTH_FAILED'=>'認証に失敗しました。もう一度登録しなおしてください。',
		'REGISTERED_FAILED'=>'すでに登録されてます。ログインしてください。',
		'FRAUD_INPUT_FAILED'=>'ユーザー名またはパスワードが不正です。',
	)),
]
?>