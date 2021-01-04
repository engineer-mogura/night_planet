#!/usr/bin/bash
export LC_CTYPE='ja_JP.UTF-8'


############## 説明 ##############
# IMGディレクトリをWEBROOT配下にリストアをします。
# 前提条件
#  IMGファイルを作業ディレクトリに配置すること。
# パラメータ
#  $1 リストアするファイル名 [*.zip]
# 
############## 説明 ##############
echo $0
read -p "IMGディレクトリをWEBROOT配下にリストアをします。1: "

#現在の絶対パス
DIR_NAME=$(cd $(dirname $0); pwd)

#環境変数読み込み
source ./env.sh $DIR_NAME

# 環境名表示
show_env_name

#ログ名
LOG_NAME="${LOG_DIR}${DS}${TIME_STAMP}_img_restore${S_LOGS}"

#引数チェック
if [ $# != 1 ]; then
    echo "引数エラー: $*"
    return 1
fi

#コピー元
#COPY_FROM=$WORK_PATH$DS$1$WEBROOT$IMG
COPY_FROM=$WORK_PATH$DS$1
#コピー先
COPY_TO=$BASE_DIR/$IMAGE

#リストアファイル存在チェック
if [[ ! -e $COPY_FROM ]]; then
  # 実行する処理、以下は例
    echo "リストアファイルが存在しません。"
    return 1
fi
put_log "INFO" " IMGディレクトリのリストアを開始します。【${COPY_TO}】" ${LOG_NAME}

# IMGディレクトリ存在している場合は削除する
if [[ -d $COPY_TO ]]; then
  put_log "INFO" "${COPY_TO}を削除します。【${COPY_TO}】" ${LOG_NAME}
  rm -r $COPY_TO
fi
#コピー元からコピー先へコピーする
cp -r $COPY_FROM $COPY_TO

if [ $? = 0 ]; then
  put_log "INFO" " IMGディレクトリのリストアが正常終了しました。ファイル【${FILE_PATH}】" ${LOG_NAME}
  return 0
else
  put_log "ERROR" " IMGディレクトリのリストアが異常終了しました。ファイル【${FILE_PATH}】" ${LOG_NAME}
  return 1
fi



