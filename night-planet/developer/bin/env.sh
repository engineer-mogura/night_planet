#!/usr/bin/bash

#共通 START
#タイムスタンプ
TIME_STAMP=`date +%Y%m%d_%H%M%S`
#スラッシュ
DS=/
#logフォルダ
LOGS=/logs
#imgフォルダ
IMG=img
#デベロッパフォルダ
DEVELOPER=/developer
#作業フォルダ
WORK=/work
#シェルフォルダ
BIN=/bin
#ウェブルートフォルダ
WEBROOT=webroot
#バックアップフォルダ
BACKUP=/np_backup
#エリアフォルダ配列
AREA=(naha nanjo tomigusuku itoman haebaru yonabaru urasoe ginowan chatan nishihara okinawashi uruma nago miyakojima ishigakijima)
#ジャンルフォルダ配列
GENRE=(cabacula snack girlsbar club lounge pub bar)
#その他フォルダ配列
OTHER=(common owner user tmp)
#環境名
LOCAL_ENV_NAME="開発環境"
HOSYU_ENV_NAME="保守環境"
HONBAN_ENV_NAME="本番環境"
LOCAL_DIR="vagrant"
HOSYU_DIR="dev-night-planet"
HONBAN_DIR="night-planet"

#共通 END

#プレフィックス START
#P_LOGS=.log
#プレフィックス END

#サフィックス START
S_LOGS=.log
#サフィックス END

#環境ごとの変数を定義する
#●●●●●●●●●●● 開発環境 START ●●●●●●●●●●●●●●
if [[ $1 =~ $LOCAL_DIR ]] ;then
    ENV_NAME=$LOCAL_ENV_NAME
    #ベースフォルダ
    BASE_DIR="/${LOCAL_DIR}/night-planet"
    ##パブリック
    PUBLIC='public'
    ##アドミン
    ADMIN='admin'
    ##イメージ
    IMAGE='img'
    ##ブログ
    BLOG='blog'
    ##パブリックパス
    PUBLIC_DIR=$BASE_DIR/$PUBLIC
    ##アドミンパス
    ADMIN_DIR=$BASE_DIR/$ADMIN
    ##イメージパス
    IMAGE_DIR=$BASE_DIR/$IMAGE$WEBROOT$IMG
    #データベース
    # データベース名
    DB_NAME=okiyoru_db
    # データベースホスト
    DB_HOST=localhost
    # ユーザー名
    DB_USER=tomori3
    # パスワード
    DB_PASS=tomori3
#●●●●●●●●●●● 保守環境 START ●●●●●●●●●●●●●●
elif [[ $1 =~ $HOSYU_DIR ]] ;then
    ##ベース環境
    ENV_NAME=$HOSYU_ENV_NAME
    ##ベースフォルダ
    BASE_DIR="${HOME}/web/${HOSYU_DIR}"
    ##パブリック
    PUBLIC='devokiyorugo.work'
    ##アドミン
    ADMIN='admin.devokiyorugo.work'
    ##イメージ
    IMAGE='img.devokiyorugo.work'
    ##ブログ
    BLOG='blog'
    ##パブリックパス
    PUBLIC_DIR=$BASE_DIR/$PUBLIC
    ##アドミンパス
    ADMIN_DIR=$BASE_DIR/$ADMIN
    ##イメージパス
    IMAGE_DIR=$BASE_DIR/$IMAGE/$WEBROOT/$IMG
    #データベース
    # データベース名
    DB_NAME="LAA0818998-devokiyorugo"
    # データベースホスト
    DB_HOST="mysql139.phy.lolipop.lan"
    # ユーザー名
    DB_USER="LAA0818998"
    # パスワード
    DB_PASS="XGlHVwLz"
#●●●●●●●●●●● 本番環境 START ●●●●●●●●●●●●●●
elif [[ $1 =~ $HONBAN_DIR ]] ;then
    #ベース環境
    ENV_NAME=$HONBAN_ENV_NAME
    #ベースフォルダ
    BASE_DIR="${HOME}/web/${HOSYU_DIR}"
    ##パブリック
    PUBLIC='night-planet.com'
    ##アドミン
    ADMIN='admin.night-planet.com'
    ##イメージ
    IMAGE='img.night-planet.com'
    ##ブログ
    BLOG='blog'
    ##パブリックパス
    PUBLIC_DIR=$BASE_DIR/$PUBLIC
    ##アドミンパス
    ADMIN_DIR=$BASE_DIR/$ADMIN
    ##イメージパス
    IMAGE_DIR=$BASE_DIR/$IMAGE$WEBROOT$IMG
    #データベース
    # データベース名
    DB_NAME="LAA0818998-product910"
    # データベースホスト
    DB_HOST="mysql140.phy.lolipop.lan"
    # ユーザー名
    DB_USER="LAA0818998"
    # パスワード
    DB_PASS="product910"
fi

#実行結果ログ
LOG_DIR=${BASE_DIR}${DEVELOPER}${WORK}${LOGS}
# 作業ディレクトリ
WORK_PATH=${BASE_DIR}${DEVELOPER}${WORK}
# 便利なシェルディレクトリ
SHELL_PATH=${BASE_DIR}${DEVELOPER}${BIN}

# 現在の環境名を表示する
# 引数 無し
function show_env_name () {
    echo "★☆★☆★☆★☆　${ENV_NAME} が対象です。　★☆★☆★☆★☆"
    echo "★☆★☆★☆★☆　${ENV_NAME} が対象です。　★☆★☆★☆★☆"
    echo "★☆★☆★☆★☆　${ENV_NAME} が対象です。　★☆★☆★☆★☆"
}

# ログを書き込む
# 条件 引数が３つ存在すること。
# 引数 
#   $1 ログタイプ 
#   $2 内容 
#   $3 出力先 
function put_log() {
    #引数チェック
    if [ $# = 3 ]; then
        echo "$1:${TIME_STAMP}　$2" >> "$3"
    else
        echo "put_log 引数エラー: ログに書き込めませんでした。"
    fi
}


