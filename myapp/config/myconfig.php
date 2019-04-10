<?php
use Cake\Core\Configure;
 
return [

	// ラベル定数 owner.label menu
	define('CAST_CONFIG', array(
		'file_max'=>'8',
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
	'cm' => [
		'001' => 'ログインしました。',
		'002' => 'ログアウトしました。',
	],
	// 確認メッセージ info.confirmation.message
	'icm' => [
		'001' => '',
		'002' => '',
	],
	// 結果メッセージ info.result.message
	'irm' => [
		'001' => '登録に成功しました。',
		'002' => '編集に成功しました。',
		'003' => '削除に成功しました。',
		'050' => '登録に失敗しました。もう一度登録しなおしてください。',
		'051' => '編集に失敗しました。もう一度編集しなおしてください。',
		'052' => '削除に失敗しました。もう一度削除しなおしてください。',
		'053' => '認証に失敗しました。もう一度登録しなおしてください。',
		'054' => '認証完了しました。ログインしてください。',
		'055' => 'すでに登録されてます。ログインしてください。',
	],
	// チェックエラーメッセージ error.check.message
	'ecm' => [
		'001' => 'ユーザー名またはパスワードが不正です。',
		'002' => '編集に成功しました。',
		'003' => '削除に成功しました。',
		'004' => '登録に失敗しました。もう一度登録しなおしてください。',
		'005' => '編集に失敗しました。もう一度編集しなおしてください。',
		'006' => '削除に失敗しました。もう一度削除しなおしてください。',
	],

]
?>