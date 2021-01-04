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
LOG_NAME="${LOG_DIR}${DS}${TIME_STAMP}_delete_log${S_LOGS}"

#パブリック側ログディレクトリ
P_DIR_LOG=$PUBLIC_DIR$LOGS
#アドミン側ログディレクトリ
A_DIR_LOG=$ADMIN_DIR$LOGS

echo "$P_DIR_LOG"
echo "$A_DIR_LOG"

#削除日数
DAY=30

# 指定日数を超えるログを削除する
echo -n "指定日数( ${DAY} )日を超えるログを削除します。よろしいですか？ [Y/n]: "
read ANS

case $ANS in
  "" | [Yy]* )
    # ここに「Yes」の時の処理を書く
    echo "以下のファイルが削除対象になります。"
    find $P_DIR_LOG -name '*.log' -mtime +$DAY -ls
    find $A_DIR_LOG -name '*.log' -mtime +$DAY -ls

    echo -n "削除する環境の確認はしましたか？ [Y/n]: "
    read REALLY
    case $REALLY in
      "" | [Yy]* )
        # ここに「Yes」の時の処理を書く
        put_log "INFO" "ログ削除開始します。" ${LOG_NAME}
        find $P_DIR_LOG -name '*.log' -mtime +$DAY -delete
        find $A_DIR_LOG -name '*.log' -mtime +$DAY -delete
        put_log "INFO" "ログ削除が正常終了しました。" ${LOG_NAME}
        ;;
      * )
        # ここに「No」の時の処理を書く
        echo "終了します。"
        ;;
    esac
    ;;
  * )
    # ここに「No」の時の処理を書く
    echo "終了します。"
    ;;
esac