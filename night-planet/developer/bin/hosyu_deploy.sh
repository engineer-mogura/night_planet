#!/usr/bin/bash
export LC_CTYPE='ja_JP.UTF-8'

############## 説明 ##############
## public, adminを環境へデプロイする
## 前提条件：${BASE_DIR}/app.${PUBLIC}配下に以下が存在すること
## 前提条件：あくまでCAKEPHPの環境が前提です
## public.zip, admin.zip, sitemap.xml(本番環境の場合), maintenance
## [忘備録][CakePHP3]レンタルサーバーへのロリポップへデプロイするにあたってすること
## 注意点
## レンタルサーバーは、ドメイン毎にPHPのバージョンを設定すること。
## アナリティクスレポートは、「/app/config/api_config/client_secrets.json」を本番用に設定すること。
############## 説明 ##############

##現在の絶対パス
DIR_NAME=$(cd $(dirname $0); pwd)
#maintenance、htaccess、public.zip、admin.zip、log 以外を削除する
TARGET='maintenance|.htaccess|public.zip|admin.zip|logs$'

## 現在の環境を引数に環境変数読み込み
source ./env.sh $DIR_NAME

## 環境名表示
show_env_name

read -n1 -p "${ENV_NAME}へデプロイしますか? (y/N): " yn
if [[ $yn = [yY] ]]; then
    read -p "開始します。: "
else
    read -p "中断します。: "
    exit 0
fi

##ログ名
LOG_NAME="${LOG_DIR}${DS}${TIME_STAMP}_hosyu_deploy${S_LOGS}"
############## PUBLIC ##############
############## PUBLIC ##############
read -p "[${PUBLIC_DIR}]にデプロイ開始します："

is_file=true #リリースファイルが存在するかフラグ

#.htaccess, app.php, public.zip, admin.zip, sitemap.xml, maintenance 存在チェック
if [[ ! -e "${BASE_DIR}/app.${PUBLIC}/.htaccess" ]]; then
    read -p ".htaccessが存在しません："
    is_file=false
fi
if [[ ! -e "${BASE_DIR}/app.${PUBLIC}/app.php" ]]; then
    read -p "app.phpが存在しません："
    is_file=false
fi
if [[ ! -e "${WORK_PATH}/maintenance/" ]]; then
    read -p "maintenanceが存在しません："
    is_file=false
fi
if [[ ! -e "${WORK_PATH}/public.zip" ]]; then
    read -p "public.zipが存在しません："
    is_file=false
fi
if [[ ! -e "${WORK_PATH}/admin.zip" ]]; then
    read -p "admin.zipが存在しません："
    is_file=false
fi
# 本番の場合
if [ $HONBAN_ENV_NAME = $ENV_NAME ]; then
    if [[ ! -e "${WORK_PATH}/sitemap.xml" ]]; then
        read -p "sitemap.xmlが存在しません："
        is_file=false
    fi
fi

if ! ${is_file} ; then
    put_log "INFO" "デプロイ失敗しました。" ${LOG_NAME}
    read -p "INFO デプロイ失敗しました："
    exit 1
fi

#public.zip」をコピー移動
cp -r ${WORK_PATH}/public.zip ${PUBLIC_DIR}/
#admin.zip」をコピー移動
cp -r ${WORK_PATH}/admin.zip ${ADMIN_DIR}/

read -p "メンテナンスモードに切り替えます："
# htaccessファイルをrootに移動する
cp -r ${WORK_PATH}/maintenance/ ${PUBLIC_DIR}/
cp ${PUBLIC_DIR}/maintenance/.htaccess ${PUBLIC_DIR}/.htaccess
# ${PUBLIC_DIR}に移動する
cd ${PUBLIC_DIR}

read -p "public.zip を展開します："
#maintenance、htaccess、log public、以外を削除する
ls -a | grep -v -E ${TARGET} | xargs rm -r
# public.zip解凍
unzip ${PUBLIC_DIR}/public.zip
# 解凍したpublicに移動する
cd public

# 解凍したpublic.zip内から[htaccess、log]以外を１つ上の階層に移動する
ls -a | grep -v -E 'htaccess|logs$' | xargs -I '{}' mv {} ${PUBLIC_DIR}


# 本番以外の場合
if [ $HONBAN_ENV_NAME != $ENV_NAME ]; then
    # シェルディレクトリに移動する※DBリストアshellを呼び出す
    cd ${SHELL_PATH}

    # IMGの変更があればIMGをリストアする。
    read -n1 -p "IMGをリストアしますか? (y/N): " yn
    if [[ $yn = [yY] ]]; then
        read -p "リストア開始します。: "
        while :
        do
            read -p "リストアするファイル名を入力してください：" file_name
            if [ "$file_name" != "" ]; then
                # ファイルの存在チェック
                if [[ ! -e "${WORK_PATH}/${file_name}" ]]; then
                    read -p "${file_name}は存在しません："
                    read -n1 -p "スキップしますか? (y/N): " yn
                    if [[ $yn = [yY] ]]; then
                        read -p "リストアスキップします。: "
                        break
                    fi
                else
                    # DBリストアshellを呼び出す
                    . ./img_restore.sh ${file_name}
                    KEKKA=$?
                    # 実行結果
                    if [ $KEKKA = 0 ] ; then
                        read -p "リストア成功しました："
                        break
                    else
                        read -p "$LINENO行：エラーが発生しました："
                        break
                    fi
                fi
            fi
        done
    else
        read -p "リストアスキップします。: "
    fi
fi


# publicのシンポリック追加
# vendor, configもリンク追加した方がよい？ ***********************
rm -rf ${PUBLIC_DIR}/${WEBROOT}/${IMG}
read -p "img削除確認："
ls -la ${PUBLIC_DIR}/${WEBROOT}
read -p "imgシンポリックリンクを作成します："
ln -s ${IMAGE_DIR}/ ${PUBLIC_DIR}/${WEBROOT}/${IMG}
ls -la ${PUBLIC_DIR}/${WEBROOT}
read -p "blogシンポリックリンクを作成します："
ln -s ${BASE_DIR}/${BLOG}/ ${PUBLIC_DIR}/${BLOG}
ls -la ${PUBLIC_DIR}/${BLOG}

############## ADMIN ##############
############## ADMIN ##############
read -p "[${ADMIN_DIR}]にデプロイ開始します："

read -p "メンテナンスモードに切り替えます："
# htaccessファイルをrootに移動する
cp -r ${WORK_PATH}/maintenance/ ${ADMIN_DIR}/
cp ${ADMIN_DIR}/maintenance/.htaccess ${ADMIN_DIR}/.htaccess
# ${ADMIN_DIR}に移動する
cd ${ADMIN_DIR}

read -p "admin.zip を展開します："
#maintenance、htaccess、log admin、以外を削除する
ls -a | grep -v -E ${TARGET} | xargs rm -r
# admin.zip解凍
unzip ${ADMIN_DIR}/admin.zip
read -p "admin.zip解凍: "

# 解凍したadminに移動する
cd admin

# 解凍したadmin.zip内から[htaccess、log]以外を１つ上の階層に移動する
ls -a | grep -v -E 'htaccess|logs$' | xargs -I '{}' mv {} ${ADMIN_DIR}

# adminのシンポリック追加
# vendor, configもリンク追加した方がよい？ ***********************
rm -rf ${ADMIN_DIR}/${WEBROOT}/${IMG}
read -p "img削除確認："
ls -la ${ADMIN_DIR}/${WEBROOT}
read -p "imgシンポリックリンクを作成します："
ln -s ${IMAGE_DIR}/ ${ADMIN_DIR}/${WEBROOT}/${IMG}
ls -la ${ADMIN_DIR}/${WEBROOT}

############## PUBLIC ##############
# mysqlのバージョンは気を付けること
read -p "PUBLIC環境変数ファイルを差し替えます※MySqlのバージョンは気を付けること："
cp -i ${BASE_DIR}/app.${PUBLIC}/app.php ${PUBLIC_DIR}/config/app.php
read -p "app.php更新確認："
ls -la ${PUBLIC_DIR}//config/

#キャッシュはエラーのもとになるため、削除する。
rm -rf ${PUBLIC_DIR}/tmp/cache
read -p "cache削除確認："
ls -la ${PUBLIC_DIR}//bin/

read -p "メンテナンスモードを解除します："
# maintenanceフォルダとhtaccessを削除する
rm -r ${PUBLIC_DIR}/maintenance .htaccess
# htaccessファイルをrootに移動する
cp -i ${BASE_DIR}/app.${PUBLIC}/.htaccess ${PUBLIC_DIR}/.htaccess

# 本番環境の場合はsitemap.xmlをwebrootに配置する
if [ $HONBAN_ENV_NAME = $ENV_NAME ]; then
    cp -i ${BASE_DIR}/app.${PUBLIC}/sitemap.xml ${PUBLIC_DIR}/${WEBROOT}
    read -p "サイトマップを配置しました："
    ls -la ${PUBLIC_DIR}/${WEBROOT}

fi

read -p "[${show_env_name}][${PUBLIC_DIR}]のデプロイが終了しました："
############## PUBLIC ##############

############## ADMIN ##############
# mysqlのバージョンは気を付けること
read -p "ADMIN環境変数ファイルを差し替えます※MySqlのバージョンは気を付けること："
cp -i ${BASE_DIR}/app.${PUBLIC}/app.php ${ADMIN_DIR}/config/app.php
read -p "app.php更新確認："
ls -la ${ADMIN_DIR}//config/

#キャッシュはエラーのもとになるため、削除する。
rm -rf ${ADMIN_DIR}/tmp/cache
read -p "cache削除確認："
ls -la ${ADMIN_DIR}//bin/

cp -i ${BASE_DIR}/app.${PUBLIC}/cake ${ADMIN_DIR}/bin/
read -p "cake削除確認："
ls -la ${ADMIN_DIR}//bin/

read -p "メンテナンスモードを解除します："
# maintenanceフォルダとhtaccessを削除する
rm -r ${ADMIN_DIR}/maintenance .htaccess
# htaccessファイルをrootに移動する
cp -i ${BASE_DIR}/app.${PUBLIC}/.htaccess ${ADMIN_DIR}/.htaccess

read -p "[${show_env_name}][${ADMIN_DIR}]のデプロイが終了しました："

############## ADMIN ##############

############## DBリストア ##############
# シェルディレクトリに移動する※DBリストアshellを呼び出す
# 本番以外の場合
if [ $HONBAN_ENV_NAME != $ENV_NAME ]; then
    cd ${SHELL_PATH}

    # DBの変更があればダンプファイルをインポートする。
    read -n1 -p "DBをリストアしますか? (y/N): " yn
    if [[ $yn = [yY] ]]; then
        read -p "リストア開始します。: "
        while :
        do
            read -p "リストアするファイル名を入力してください：" file_name
            if [ "$file_name" != "" ]; then
                # ファイルの存在チェック
                if [[ ! -e "${WORK_PATH}/${file_name}" ]]; then
                    read -p "${file_name}は存在しません："
                    read -n1 -p "スキップしますか? (y/N): " yn
                    if [[ $yn = [yY] ]]; then
                        read -p "リストアスキップします。: "
                        break
                    fi
                else
                    # DBリストアshellを呼び出す
                    . ./db_restore.sh ${file_name}
                    KEKKA=$?
                    # 実行結果
                    if [ $KEKKA = 0 ] ; then
                        read -p "リストア成功しました："
                        break
                    else
                        echo $KEKKA
                        read -p "$LINENO行：エラーが発生しました："
                        break
                    fi
                fi
            fi
        done
    else
        read -p "リストアスキップします。: "
    fi
fi

############## DBリストア ##############

