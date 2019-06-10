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
		'NO_IMAGE02'=> '/img/common/noimage.jpg',
		'NO_IMAGE03'=> '/img/common/no-img150_150/no-img7.png',
		'NO_IMAGE04'=> '/img/common/no-img150_150/no-img8.png',
		'NO_IMAGE05'=> '/img/common/no-img150_150/no-img9.png',
		'AREA01'=> '/img/common/top/top1.jpg',
		'CREDIT'=> '/img/common/credit/',
		'OWNER'=> 'owner',
		'USER'=> 'user',
		'CAST'=> 'cast',
		'DIARY'=> 'diary',
		'TMP'=> 'tmp',
		'IMAGE'=> 'image',
		'IMG'=> 'img', // TODO: こいつは、ルートディレクトリに使ってる。imgってディレクトリ名は不適切だから後で変える
		'COMMON'=> 'common',

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
		'005'=>'<span class="area-logo-tail">☽ナイト検索☽</span>',
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
		'052'=>'もっと見る',
		'053'=>'店舗詳細',
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
	define('AREA', array(
		'miyakojima'=> [
			'label' => "宮古島",
			'path' => "miyakojima"
		],
		'ishigakijima'=> [
			'label' => "石垣島",
			'path' => "ishigakijima"
		],
		'naha'=> [
			'label' => "那覇",
			'path' => "naha"
		],
		'nanjo'=> [
			'label' => "南城",
			'path' => "nanjo"
		],
		'tomigusuku'=> [
			'label' => "豊見城",
			'path' => "tomigusuku"
		],
		'urasoe'=> [
			'label' => "浦添",
			'path' => "urasoe"
		],
		'ginowan'=> [
			'label' => "宜野湾",
			'path' => "ginowan"
		],
		'okinawashi'=> [
			'label' => "沖縄市",
			'path' => "okinawashi"
		],
		'uruma'=> [
			'label' => "うるま",
			'path' => "uruma"
		],
		'nago'=> [
			'label' => "名護",
			'path' => "nago"
		],
	)),
	// 業種リスト
	define('GENRE', array(
		'cabacula'=> [
			'label' => "キャバクラ",
			'path' => "cabacula"
		],
		'snack'=> [
			'label' => "スナック",
			'path' => "snack"
		],
		'girlsbar'=> [
			'label' => "ガールズバー",
			'path' => "girlsbar"
		],
		'club'=> [
			'label' => "クラブ",
			'path' => "club"
		],
		'lounge'=> [
			'label' => "ラウンジ",
			'path' => "lounge"
		],
		'pub'=> [
			'label' => "パブ",
			'path' => "pub"
		],
	)),
	// 星座リスト
	define('CONSTELLATION', array(
		'constellation1'=> [
			'label' => "おひつじ座",
			'path' => "constellation1"
		],
		'constellation2'=> [
			'label' => "おうし座",
			'path' => "constellation2"
		],
		'constellation3'=> [
			'label' => "ふたご座",
			'path' => "constellation3"
		],
		'constellation4'=> [
			'label' => "かに座",
			'path' => "constellation4"
		],
		'constellation5'=> [
			'label' => "しし座",
			'path' => "constellation5"
		],
		'constellation6'=> [
			'label' => "おとめ座",
			'path' => "constellation6"
		],
		'constellation7'=> [
			'label' => "てんびん座",
			'path' => "constellation7"
		],
		'constellation8'=> [
			'label' => "さそり座",
			'path' => "constellation8"
		],
		'constellation9'=> [
			'label' => "いて座",
			'path' => "constellation9"
		],
		'constellation10'=> [
			'label' => "やぎ座",
			'path' => "constellation10"
		],
		'constellation11'=> [
			'label' => "みずがめ座",
			'path' => "constellation11"
		],
		'constellation12'=> [
			'label' => "うお座",
			'path' => "constellation12"
		],

	)),
	// 血液型リスト
	define('BLOOD_TYPE', array(
		'blood_type1'=> [
			'label' => "A型",
			'path' => "blood_type1"
		],
		'blood_type2'=> [
			'label' => "B型",
			'path' => "blood_type2"
		],
		'blood_type3'=> [
			'label' => "O型",
			'path' => "blood_type3"
		],
		'blood_type4'=> [
			'label' => "AB型",
			'path' => "blood_type4"
		],

	)),
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