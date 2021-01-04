#!/usr/bin/bash
export LC_CTYPE='ja_JP.UTF-8'


############## 説明 ##############
# SQLファイルを読み込みデータベースのリストアをします。
# 前提条件
#  リストアファイルを作業ディレクトリに配置すること。
# パラメータ
#  $1 リストアするファイル名 [*.sql]
# 
############## 説明 ##############

#現在の絶対パス
DIR_NAME=$(cd $(dirname $0); pwd)

#環境変数読み込み
source ./env.sh $DIR_NAME

# 環境名表示
show_env_name

#ログ名
LOG_NAME="${LOG_DIR}${DS}${TIME_STAMP}_db_restore${S_LOGS}"

#引数チェック
if [ $# != 1 ]; then
    echo "引数エラー: $*"
    exit 1
fi

#ファイルパス
FILE_PATH=$WORK_PATH$DS$1

#リストアファイル存在チェック
if [[ ! -e $FILE_PATH ]]; then
  # 実行する処理、以下は例
    echo "リストアファイルが存在しません。"
    exit 1
fi

# 他のユーザからバックアップを読み込めないようにする
umask 077

#圧縮ファイルを解凍する
#unzip ${FILE_PATH} $BASE_DIR
#拡張子を除いたファイル名を取得する
#BASE_NAME=`basename -s ".gz" $BASE_DIR$DS$1`
# mysqldump実行（ファイルサイズ圧縮の為gzで圧縮しておきます。）
zcat ${FILE_PATH} | mysql ${DB_NAME} -u${DB_USER} -h${DB_HOST} -p

if [ $? = 0 ]; then
  put_log "INFO" "${TIME_STAMP} DBリストアが正常終了しました。ファイル【${FILE_PATH}】" ${LOG_NAME}
  return 0
else
  put_log "ERROR" "${TIME_STAMP} DBリストアが異常終了しました。ファイル【${FILE_PATH}】" ${LOG_NAME}
  return 1
fi


