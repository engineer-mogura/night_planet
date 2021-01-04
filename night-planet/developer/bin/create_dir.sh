#!/usr/bin/bash
export LC_CTYPE='ja_JP.UTF-8'

#現在の絶対パス
DIR_NAME=$(cd $(dirname $0); pwd)

#環境変数読み込み
source ./env.sh $DIR_NAME

# 環境名表示
show_env_name

#イメージ側ログディレクトリ
I_DIR=$IMAGE_DIR$DS

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