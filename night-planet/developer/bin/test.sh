#!/usr/bin/bash
export LC_CTYPE='ja_JP.UTF-8'


############## 説明 ##############
## テスト用
############## 説明 ##############

#現在の絶対パス
DIR_NAME=$(cd $(dirname $0); pwd)

#環境変数読み込み
source ./env.sh $DIR_NAME

# 環境名表示
show_env_name
#ログ名


