#!/usr/bin/env bash
#
# BuildOne さくらレンタルサーバー デプロイスクリプト（WSLから実行）
#
#   使い方:  bash deploy/deploy.sh          # 通常デプロイ
#            bash deploy/deploy.sh --first  # 初回デプロイ（メンテナンスモードを使わない）
#
# 前提:
#   - WSL の ~/.ssh/config に Host "buildone" が設定済み
#   - 本番の .env はサーバー側にのみ存在し、このスクリプトは触らない
#   - デプロイ後、ローカルで開発を続けるときは `composer install` で
#     dev依存(phpunit等)を戻すこと
#
set -euo pipefail

HOST=buildone
APP_REMOTE='~/buildone-web'   # Laravel本体（Web非公開）
WEB_REMOTE='~/www'            # 公開ディレクトリ（publicの中身）
LOCAL_ROOT="$(cd "$(dirname "$0")/.." && pwd)"

FIRST_DEPLOY=0
[ "${1:-}" = "--first" ] && FIRST_DEPLOY=1

echo "==> 1/6 ローカルビルド（Windows側のツールをcmd.exe経由で実行）"
cd "$LOCAL_ROOT"
cmd.exe /c "composer install --no-dev --optimize-autoloader --no-interaction"
cmd.exe /c "npm run build"

echo "==> 2/6 メンテナンスモード"
if [ "$FIRST_DEPLOY" = "0" ]; then
    ssh $HOST "cd $APP_REMOTE && php artisan down --retry=30" || true
fi

echo "==> 3/6 アプリ本体を転送 ($APP_REMOTE)"
# --delete あり。ただし exclude 対象（.env, storage実行時ファイル等）は削除されない
rsync -rlptzv --delete \
    --exclude='.git/' \
    --exclude='node_modules/' \
    --exclude='public/' \
    --exclude='tests/' \
    --exclude='deploy/' \
    --exclude='.env' \
    --exclude='.env.*' \
    --exclude='bootstrap/cache/*' \
    --exclude='storage/logs/*' \
    --exclude='storage/framework/cache/data/*' \
    --exclude='storage/framework/sessions/*' \
    --exclude='storage/framework/views/*' \
    --exclude='database/*.sqlite' \
    --chmod=D755,F644 \
    ./ "$HOST:$APP_REMOTE/"

echo "==> 4/6 公開ファイルを転送 ($WEB_REMOTE)"
# 公開側は --delete を使わない（サーバー側にしか無いものを守る）
rsync -rlptzv \
    --exclude='index.php' \
    --exclude='.htaccess' \
    --chmod=D755,F644 \
    public/ "$HOST:$WEB_REMOTE/"
# さくら用に書き換えた index.php / .htaccess を配置
rsync -ptzv --chmod=F644 deploy/index.sakura.php "$HOST:$WEB_REMOTE/index.php"
rsync -ptzv --chmod=F644 deploy/htaccess.sakura "$HOST:$WEB_REMOTE/.htaccess"

echo "==> 5/6 サーバー側処理"
ssh $HOST "cd $APP_REMOTE && \
    chmod -R u+rwX storage bootstrap/cache && \
    php artisan optimize:clear && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache"

if [ "$FIRST_DEPLOY" = "0" ]; then
    ssh $HOST "cd $APP_REMOTE && php artisan up"
fi

echo "==> 6/6 動作確認"
curl -sS -o /dev/null -w "https://buildone.sakura.ne.jp/ -> HTTP %{http_code}\n" https://buildone.sakura.ne.jp/

echo "デプロイ完了"
