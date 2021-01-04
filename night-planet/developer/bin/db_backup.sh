#!/usr/bin/bash
export LC_CTYPE='ja_JP.UTF-8'


############## 説明 ##############
# 指定日数のpublic, adminのlogディクレトリ配下のログを削除します。
# パラメータ 無し
# 
# 
############## 説明 ##############

#現在の絶対パス
DIR_NAME=$(cd $(dirname $0); pwd)

#環境変数読み込み
source ./env.sh $DIR_NAME

# 環境名表示
show_env_name

#ログ名
LOG_NAME="${LOG_DIR}${DS}${TIME_STAMP}_db_backup${S_LOGS}"

# 他のユーザからバックアップを読み込めないようにする
umask 077

# バックアップファイルを保存するディレクトリ
BACKUP_PATH=${BASE_DIR}${BACKUP}

# ファイル名を定義(※ファイル名で日付がわかるようにしておきます)
FILE_NAME="${TIME_STAMP}.sql.gz"

echo ${BACKUP_PATH}${DS}${FILE_NAME}
# mysqldump実行（ファイルサイズ圧縮の為gzで圧縮しておきます。）
mysqldump ${DB_NAME} -u${DB_USER} -p${DB_PASS} -h${DB_HOST} | gzip > ${BACKUP_PATH}${DS}${FILE_NAME}

FILE_SIZE=`wc -c ${BACKUP_PATH}${DS}${FILE_NAME} | awk '{print $1}'`
if [ $? = 0 ]; then
  put_log "INFO" "DBバックアップが正常終了しました。ファイルサイズ【${FILE_SIZE}KB】" ${LOG_NAME}
else
  put_log "ERROR" "DBバックアップが異常終了しました。ファイルサイズ【${FILE_SIZE}KB】" ${LOG_NAME}
fi


