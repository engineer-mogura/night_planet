<?php
 
return [

	// バイトサイズ関連
	define('CAPACITY', array(
		'MAX_NUM_BYTES_DIR'=> 10485760 , // ディレクトリ制限バイト数10MB
		'MAX_NUM_BYTES_FILE'=> 2097152 , // ファイル制限バイト数2MB
	)),

	// プロパティ設定
	define('PROPERTY', array(
		'NEW_INFO_MAX'=>'4', // 新着情報関連の表示数
		'FILE_MAX'=>'8', // アップロードファイル数
		'UPDATE_INFO_DAY_MAX'=>'10', // アップロードファイル数
		'SHOW_GALLERY_MAX'=>'2', // キャストのギャラリー最大表示数
	)),

	// 店舗メニュー名
	define('SHOP_MENU_NAME', array(
		'COUPON'=>'coupon', // クーポン
		'WORK_SCHEDULE'=>'work_schedule', // 今日の出勤メンバー
		'EVENT'=>'event', // お知らせ
		'CAST'=>'cast', // キャスト
		'DIARY'=>'diary', // 日記
		'SHOP_TOP_IMAGE'=>'shop_top_image', // 店舗トップ画像
		'SHOP_GALLERY'=>'shop_gallery', // 店内ギャラリー
		'SYSTEM'=>'system', // 店舗情報
		'RECRUIT'=>'recruit', // 求人情報
		'PROFILE'=>'profile', // プロフィール
		'CAST_TOP_IMAGE'=>'cast_top_image', // キャストトップ画像
		'CAST_GALLERY'=>'cast_gallery', // キャストギャラリー
	)),

	// 店舗編集画面のタブ制御設定
	define('TAB_CONTROLE', array(
		'index', // 店舗ダッシュボード 画面
		'notice', // 店舗お知らせ 画面
		'workSchedule', // 出勤管理 画面
	)),

	// パス設定 path.config
	define('PATH_ROOT', array(
		'NO_IMAGE01'=> '/img/common/no-img16.png',
		'NO_IMAGE02'=> '/img/common/noimage.jpg',
		'NO_IMAGE03'=> '/img/common/no-img150_150/no-img7.png',
		'NO_IMAGE04'=> '/img/common/no-img150_150/no-img8.png',
		'NO_IMAGE05'=> '/img/common/no-img150_150/no-img9.png',
		'CAST_TOP_IMAGE'=> '/img/common/cast/top-image.jpg',
		'CREDIT'=> '/img/common/credit/',
		'OWNER'=> 'owner',
		'USER'=> 'user',
		'CAST'=> 'cast',
		'NOTICE'=> 'notice',
		'DIARY'=> 'diary',
		'GALLERY'=> 'gallery',
		'SCHEDULE'=> 'schedule',
		'TMP'=> 'tmp',
		'TOP_IMAGE'=> 'top_image',
		'IMAGE'=> 'image',
		'PROFILE'=> 'profile',
		'CACHE'=> 'cache',
		'IMG'=> 'img', // TODO: こいつは、ルートディレクトリに使ってる。imgってディレクトリ名は不適切だから後で変える
		'COMMON'=> 'common',
		'SHOP'=> 'shop',
	)),

	// SNSパス設定 path.config
	define('SHARER', array(
		'TWITTER'=> 'http://twitter.com/share?url=',
		'FACEBOOK'=> 'https://www.facebook.com/sharer/sharer.php?u=',
		'LINE'=> 'http://line.me/R/msg/text/?',
	)),
	// TODO: リリース前には、パスを本番へ変更する。
	// WEBアプリケーションパス設定 path.config
	define('APP_PATH', array(
		'APP'=> 'https://'. $_SERVER['HTTP_HOST']."/", /** ローカル環境 */
		'APP_ADMIN'=> 'https://"'. $_SERVER['HTTP_HOST']."/'", /** ローカル環境 */
		//'APP'=> 'https://devokiyorugo.work/', /** テスト環境 */
		//'APP_ADMIN'=> 'https://devokiyorugo.work/', /** テスト環境 */
		//'APP'=> 'https://night-planet.com/', /** 本番環境 */
		//'APP_ADMIN'=> 'https://night-planet.com/', /** 本番環境 */
	)),

	// ラベル定数 developer.label menu
	define('DEVELOPER_LM', array(
		'001'=>'開発者リスト',
		'002'=>'ユーザーリスト',
		'003'=>'オーナーリスト',
	)),

	// ラベル定数 label title
	define('LT', array(
		'001'=>'NightPlanet<span style="font-size: small"> ナイプラ</span>',
		'002'=>'Copyright 2018',
		'003'=>'NightPlanet ナイプラ All Rights Reserved.',
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
		'001'=>'ダッシュボード',
		'002'=>'オーナー情報',
		'003'=>'契約内容・お支払い',
	)),

	// ラベル定数 shop.label menu
	define('SHOP_LM', array(
		'001'=>'トップ画像',
		'002'=>'キャッチコピー',
		'003'=>'クーポン',
		'004'=>'キャスト',
		'005'=>'店舗情報',
		'006'=>'店舗ギャラリー',
		// '007'=>'マップ',
		'008'=>'求人情報',
		'009'=>'店舗お知らせ',
		'010'=>'SNS',
		'011'=>'出勤管理',
		'012'=>'ダッシュボード',
	)),

	// ラベル定数 cast.label menu
	define('CAST_LM', array(
		'001'=>'ダッシュボード',
		'002'=>'プロフィール',
		'003'=>'トップ画像',
		'004'=>'日記',
		'005'=>'ギャラリー',
		'006'=>'SNS',
		'007'=>'キャストのトップへ行く',
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

	define('CATCHCOPY','【NightPlanet<span style="font-size: small"> ナイプラ</span>】では、県内特化型ポータルサイトとして、沖縄全域のナイト情報を提供しております。高機能な検索システムを採用しておりますので、お客様にピッタリな情報がすぐに見つかります。店舗ごとに多彩なクーポン券もご用意しておりますので、お店に行く前にクーポン券をチェックしてみるのもいいでしょう。'),

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
	// 確認メッセージ common.message
	define('CONFIRM_M', array(
		'TEL_ME'=>'【_shopname_】に電話を掛けますか？\n電話をする際は【ナイプラ】を見た！で話がスムーズになります。',
	)),

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
	// SEO タイトル
	define('TITLE', array(
		'TOP_TITLE'=>'沖縄のキャバ|クラブ|ガールズバー|スナック|バーの検索サイト。ポータルサイト『_service_name_』',
		'AREA_TITLE'=>'_area_のキャバクラ|クラブ|ガールズバー|スナック|バーを探すなら、ポータルサイト『_service_name_』',
		'SEARCH_TITLE'=>'沖縄のキャバ|クラブ|ガールズバー|スナック|バーの検索サイト。ポータルサイト『_service_name_』',
		'SHOP_TITLE'=>'_area_の_genre_ _shop_のトップページ。ポータルサイト『_service_name_』',
		'NOTICE_TITLE'=>'_area_の_genre_ _shop_のお知らせページです。ポータルサイト『_service_name_』',
		'CAST_TITLE'=>'_area_の_genre_ _shop_|_cast_のトップページ。ポータルサイト『_service_name_』',
		'DIARY_TITLE'=>'_area_の_genre_ _shop_|_cast_の日記ページ。ポータルサイト『_service_name_』',
		'GALLERY_TITLE'=>'_area_の_genre_ _shop_|_cast_のギャラリーページ。ポータルサイト『_service_name_』',
	)),
	// SEO メタ関連
	define('META', array(
		'TOP_DESCRIPTION'=>'沖縄県内のキャバクラ・クラブ・ガールズバー・スナック・バーのポータルサイト『_service_name_』は男女問わず気軽にお店の検索ができます。普段見つからないような穴場スポットも見つかるかも。',
		'AREA_DESCRIPTION'=>'_area_のキャバクラ・クラブ・ガールズバー・スナック・バーをお探しなら『_service_name_』で！店舗毎にお得なクーポン情報もあります！まずは検索から！',
		'SEARCH_DESCRIPTION'=>'沖縄県内のキャバクラ・クラブ・ガールズバー・スナック・バーのポータルサイト『_service_name_』は男女問わず気軽にお店の検索ができます。普段見つからないような穴場スポットも見つかるかも。',
		'SHOP_DESCRIPTION'=>'_catch_copy_ お店探しは『_service_name_』で！',
		'NOTICE_DESCRIPTION'=>'_shop_からのお知らせページです！お得な情報も発信しますのでお見逃しなく！お店探しは『_service_name_』で！',
		'CAST_DESCRIPTION'=>'_cast_のトップページです！様々なキャストが在籍しています！新人キャストも随時紹介していきます！お店探しは『_service_name_』で！',
		'DIARY_DESCRIPTION'=>'_cast_の日記ページです！日々の出来事などを日記に綴っていきますのでお見逃しなく！お店探しは『_service_name_』で！',
		'GALLERY_DESCRIPTION'=>'_cast_のギャラリーページです！毎日更新していきますのでお見逃しなく！お店探しは『_service_name_』で！',
		'USER_NO_INDEX'=>TRUE, // ステージング環境用 使用テンプレート「userDefault.ctp」検索エンジンにインデックスするかしないか
		'OWNER_NO_INDEX'=>TRUE, // ステージング環境用 使用テンプレート「ownerDefault.ctp」検索エンジンにインデックスするかしないか
		'SHOP_NO_INDEX'=>TRUE, // ステージング環境用 使用テンプレート「shopDefault.ctp」検索エンジンにインデックスするかしないか
		'CAST_NO_INDEX'=>TRUE, // ステージング環境用 使用テンプレート「castDefault.ctp」検索エンジンにインデックスするかしないか
		'SIMPLE_NO_INDEX'=>TRUE, // ステージング環境用 使用テンプレート「simpleDefault.ctp」検索エンジンにインデックスするかしないか
		'NO_FOLLOW'=>TRUE, // ステージング環境用 ページ内のリンク先をフォローするかしないか
		// 'USER_NO_INDEX'=>FALSE, // 本番環境用 使用テンプレート「userDefault.ctp」検索エンジンにインデックスするかしないか
		// 'OWNER_NO_INDEX'=>TRUE, // 本番環境用 使用テンプレート「ownerDefault.ctp」検索エンジンにインデックスするかしないか
		// 'SHOP_NO_INDEX'=>TRUE, // 本番環境用 使用テンプレート「shopDefault.ctp」検索エンジンにインデックスするかしないか
		// 'CAST_NO_INDEX'=>TRUE, // 本番環境用 使用テンプレート「castDefault.ctp」検索エンジンにインデックスするかしないか
		// 'SIMPLE_NO_INDEX'=>TRUE, // 本番環境用 使用テンプレート「simpleDefault.ctp」検索エンジンにインデックスするかしないか
		// 'NO_FOLLOW'=>TRUE, // 本番環境用 ページ内のリンク先をフォローするかしないか
	)),

	// API関連プロパティ設定
	define('API', array(
		// 'GOOGLE_MAP_APIS'=>'https://maps.googleapis.com/maps/api/js?key=AIzaSyDgd-t3Wa40gScJKC3ZH3ithzuUUapElu4', // 本番環境用 GoogleマップのAPIキー
		// 'GOOGLE_ANALYTICS_APIS'=>'https://www.googletagmanager.com/gtag/js?id=UA-146237049-1', // 本番環境用 GoogleアナリティクスのAPIキー
		// 'GOOGLE_ANALYTICS_ID'=>'UA-146237049-1', // 本番環境用 GoogleアナリティクスのID
		'GOOGLE_MAP_APIS'=>'https://maps.googleapis.com/maps/api/js?key=AIzaSyDgd-t3Wa40gScJKC3ZH3ithzuUUapElu4', // ステージング環境用 GoogleマップのAPIキー
		'GOOGLE_ANALYTICS_APIS'=>'https://www.googletagmanager.com/gtag/js?id=UA-146237049-1', // ステージング環境用 GoogleアナリティクスのAPIキー
		'GOOGLE_ANALYTICS_ID'=>'UA-146237049-1', // ステージング環境用 GoogleアナリティクスのID
		'GOOGLE_ANALYTICS_VIEW_ID'=>'200669565', // ステージング環境用 Analytics Reporting API V4 view_id
		'GOOGLE_FORM_KEISAI_CONTACT'=>'https://forms.gle/bZ3AQhHPZLH2Q7Dy6', // Googleフォーム 掲載申し込みフォーム
		'INSTAGRAM_USER_NAME'=>'nightplanet91', // INSTAGRAMビジネスアカウントネーム
		'INSTAGRAM_BUSINESS_ID'=>'17841418752048383', // INSTAGRAMビジネスアカウントID
		'INSTAGRAM_GRAPH_API_ACCESS_TOKEN'=>'EAAdniupaAi8BAPK4yZA5JNmLj1ZAecxAqZBqMb025I0TN2QXib2zBVfk9aWohBrPVASgJ86s2k74hSyV9Or7nw5ZAzfjXZAejnarfThA8MEnH6DwOZAOcoDDxZAv66tn2SlNRpCBGwE5gdDLRYvLZCne7ip7bUZASZBGOy9xgvZAEBasFXKCzJda5EG', // #3INSTAGRAMアクセストークン
		'INSTAGRAM_GRAPH_API'=> 'https://graph.facebook.com/v4.0/', // インスタグラムのAPIパス
		'INSTAGRAM_MAX_POSTS'=> 9, // インスタグラムの最大投稿取得数
		'INSTAGRAM_SHOW_MODE'=> 'grid', // インスタグラム表示モード
		'INSTAGRAM_CACHE_TIME'=> 360 // インスタグラムキャッシュタイム
	)),

	//変数展開用
	$_ = function($s){
        return $s;
    }
]
?>