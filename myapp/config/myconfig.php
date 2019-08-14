<?php
 
return [


	// バイトサイズ関連
	define('CAPACITY', array(
		'MAX_NUM_BYTES_DIR'=> 10485760 , // ディレクトリ制限バイト数10MB
		'MAX_NUM_BYTES_FILE'=> 1048576 , // ファイル制限バイト数1MB
	)),

	// プロパティ設定
	define('PROPERTY', array(
		'NEW_INFO_MAX'=>'10', // 新着情報関連の表示数
		'FILE_MAX'=>'8', // アップロードファイル数
	)),

	// 店舗編集画面のタブ制御設定
	define('TAB_CONTROLE', array(
		'notice', // 店舗お知らせ
		'', // 店舗お知らせ
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
		'NOTICE'=> 'notice',
		'DIARY'=> 'diary',
		'EVENT'=> 'event',
		'TMP'=> 'tmp',
		'IMAGE'=> 'image',
		'IMG'=> 'img', // TODO: こいつは、ルートディレクトリに使ってる。imgってディレクトリ名は不適切だから後で変える
		'COMMON'=> 'common',

	)),
	// SNSパス設定 path.config
	define('SHARER', array(
		'TWITTER'=> 'http://twitter.com/share?url=',
		'FACEBOOK'=> 'https://www.facebook.com/sharer/sharer.php?u=',
		'LINE'=> 'http://line.me/R/msg/text/?',
	)),

	// ラベル定数 developer.label menu
	define('DEVELOPER_LM', array(
		'001'=>'開発者リスト',
		'002'=>'ユーザーリスト',
		'003'=>'オーナーリスト',
	)),

	// ラベル定数 label title
	define('LT', array(
		'001'=>'(仮)OKIYORU Go',
		'002'=>'Copyright 2018',
		'003'=>'(仮)OKIYORU Go All Rights Reserved.',
		'004'=>'管理画面',
		'005'=>'<span class="area-logo-tail">☽ナイト検索☽</span>',
		'006'=>'オーナーログイン画面',
		'007'=>'キャストログイン画面',
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
		'001'=>'オーナーメニュー１',
		'002'=>'オーナーメニュー２',
		'003'=>'オーナーメニュー３',
		'004'=>'オーナーメニュー４',
		'005'=>'オーナーメニュー５',
	)),
	// ラベル定数 shop.label menu
	define('SHOP_LM', array(
		'001'=>'トップ画像',
		'002'=>'キャッチコピー',
		'003'=>'クーポン',
		'004'=>'キャスト',
		'005'=>'店舗情報',
		'006'=>'店舗ギャラリー',
		'007'=>'マップ',
		'008'=>'求人情報',
		'009'=>'店舗お知らせ',
	)),

	// ラベル定数 cast.label menu
	define('CAST_LM', array(
		'001'=>'ダッシュボード',
		'002'=>'プロフィール',
		'003'=>'日記',
		'004'=>'ギャラリー',
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

	define('CATCHCOPY','【(仮)OKIYORU Go】では、県内特化型ポータルサイトとして、沖縄全域のナイト情報を提供しております。(\※ソープ、デリヘル等の風俗情報を除く)。高機能な検索システムを採用しておりますので、お客様にピッタリな情報がすぐに見つかります。更に店舗ごとに多彩なクーポン券などご用意しておりますのでお店に行く前に検索してクーポン券があるのかチェックしてみるのもいいでしょう。'),

	// 所属エリアリスト
	define('AREA', array(
		'miyakojima'=> [
			'label' => "宮古島",
			'path' => "miyakojima",
			'image' => "/img/common/area/area_1.png"
		],
		'ishigakijima'=> [
			'label' => "石垣島",
			'path' => "ishigakijima",
			'image' => "/img/common/area/area_2.png"
		],
		'naha'=> [
			'label' => "那覇",
			'path' => "naha",
			'image' => "/img/common/area/area_3.png"
		],
		'nanjo'=> [
			'label' => "南城",
			'path' => "nanjo",
			'image' => "/img/common/area/area_4.png"
		],
		'tomigusuku'=> [
			'label' => "豊見城",
			'path' => "tomigusuku",
			'image' => "/img/common/area/area_5.png"
		],
		'urasoe'=> [
			'label' => "浦添",
			'path' => "urasoe",
			'image' => "/img/common/area/area_6.png"
		],
		'ginowan'=> [
			'label' => "宜野湾",
			'path' => "ginowan",
			'image' => "/img/common/area/area_7.png"
		],
		'okinawashi'=> [
			'label' => "沖縄市",
			'path' => "okinawashi",
			'image' => "/img/common/area/area_8.png"
		],
		'uruma'=> [
			'label' => "うるま",
			'path' => "uruma",
			'image' => "/img/common/area/area_9.png"
		],
		'nago'=> [
			'label' => "名護",
			'path' => "nago",
			'image' => "/img/common/area/area_10.png"
		],
	)),
	// 業種リスト
	define('GENRE', array(
		'cabacula'=> [
			'label' => "キャバクラ",
			'path' => "cabacula",
			'image' => "/img/common/genre/genre_1.png"
		],
		'snack'=> [
			'label' => "スナック",
			'path' => "snack",
			'image' => "/img/common/genre/genre_2.png"
		],
		'girlsbar'=> [
			'label' => "ガールズバー",
			'path' => "girlsbar",
			'image' => "/img/common/genre/genre_3.png"
		],
		'club'=> [
			'label' => "クラブ",
			'path' => "club",
			'image' => "/img/common/genre/genre_4.png"
		],
		'lounge'=> [
			'label' => "ラウンジ",
			'path' => "lounge",
			'image' => "/img/common/genre/genre_5.png"
		],
		'pub'=> [
			'label' => "パブ",
			'path' => "pub",
			'image' => "/img/common/genre/genre_6.png"
		],
		'bar'=> [
			'label' => "バー",
			'path' => "bar",
			'image' => "/img/common/genre/genre_7.png"
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
		'DISPLAY_SUCCESS'=>'を表示にしました',
		'HIDDEN_SUCCESS'=>'を非表示にしました',
		'AUTH_SUCCESS'=>'認証完了しました。ログインしてください。',
		'DUPLICATE'=>'同じ画像はアップできませんでした。',
		'SIGNUP_FAILED'=>'登録に失敗しました。もう一度登録しなおしてください。',
		'UPDATE_FAILED'=>'編集に失敗しました。もう一度編集しなおしてください。',
		'DELETE_FAILED'=>'削除に失敗しました。もう一度削除しなおしてください。',
		'AUTH_FAILED'=>'認証に失敗しました。もう一度登録しなおしてください。',
		'REGISTERED_FAILED'=>'すでに登録されてます。ログインしてください。',
		'FRAUD_INPUT_FAILED'=>'ユーザー名またはパスワードが不正です。',
		'CHANGE_FAILED'=>'切り替えに失敗しました。もう一度お試しください。',
	)),
	// メールメッセージ info.result.message
	define('MAIL', array(
		'AUTH_CONFIRMATION'=>'入力したアドレスにメールを送りました。メールを確認し認証を完了するようキャストへお伝えください。
			</ br>今から〇〇時間以内に完了しないと、やり直しになりますのでご注意ください。',
	)),
]
?>