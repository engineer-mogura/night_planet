#!/usr/bin/bash
export LC_CTYPE='ja_JP.UTF-8'

#環境変数読み込み
. ./conf.txt

#イメージ側ログディレクトリ
I_DIR=$IMAGE_DIR$DS

echo "$I_DIR"

#対象環境を表示
if [ $BASE_ENV = $LOCAL_ENV ]; then
  echo "☆彡★☆彡　${BASE_ENV} が対象です。　☆彡★☆彡"
elif [ $BASE_ENV = $HOSYU_ENV ]; then
  echo "☆彡★☆彡　${BASE_ENV} が対象です。　☆彡★☆彡"
else
  echo "☆彡★☆彡　${BASE_ENV} が対象です。　☆彡★☆彡"
fi

# エリア、ジャンルのディレクトリを作成する
echo -n "エリア、ジャンルのディレクトリを作成します。よろしいですか？ [Y/n]: "
read ANS

case $ANS in
  "" | [Yy]* )
    # ここに「Yes」の時の処理を書く
    echo "作成開始します。"
    # エリア、ジャンルディレクトリ
    for item1 in ${AREA[@]}; do
        for item2 in ${GENRE[@]}; do
            echo "$I_DIR$item1"/"$item2"
            mkdir -p "$I_DIR$item1"/"$item2"
        done
    done
    # その他ディレクトリ
    for item in ${OTHER[@]}; do
        echo "$I_DIR$item"
        mkdir -p "$I_DIR$item"
    done
    ;;
  * )
    # ここに「No」の時の処理を書く
    echo "終了します。"
    ;;
esac