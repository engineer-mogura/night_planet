#!/usr/bin/bash
export LC_CTYPE='ja_JP.UTF-8'

#環境変数読み込み
. ./conf.txt

#パブリック側ログディレクトリ
P_DIR_LOG=$PUBLIC_DIR$LOGS
#アドミン側ログディレクトリ
A_DIR_LOG=$ADMIN_DIR$LOGS

echo "$P_DIR_LOG"
echo "$A_DIR_LOG"

#削除日数
DAY=30

#対象環境を表示
if [ $BASE_ENV = $LOCAL_ENV ]; then
  echo "☆彡★☆彡　${BASE_ENV} が対象です。　☆彡★☆彡"
elif [ $BASE_ENV = $HOSYU_ENV ]; then
  echo "☆彡★☆彡　${BASE_ENV} が対象です。　☆彡★☆彡"
else
  echo "☆彡★☆彡　${BASE_ENV} が対象です。　☆彡★☆彡"
fi

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
        echo "削除開始します。"
        find $P_DIR_LOG -name '*.log' -mtime +$DAY -delete
        find $A_DIR_LOG -name '*.log' -mtime +$DAY -delete

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