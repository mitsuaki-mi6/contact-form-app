# COACHTECH お問い合わせフォーム

## 概要

COACHTECHの課題である「お問い合わせフォーム」のWebアプリケーションです。
ユーザーからのお問い合わせ内容を受け付け、管理画面からお問い合わせ一覧の閲覧、検索、詳細確認、削除を行うことができます。

## ER図

- docs/ER Diagram.pptxを参照ください

## 環境構築手順

1. Laravelプロジェクトの作成 (Laravel 10.x)

- Laravel 10.x を指定してプロジェクトを作成
  docker run --rm \
   -u "$(id -u):$(id -g)" \
   -v "$(pwd):/var/www/html" \
   -w /var/www/html \
   -e COMPOSER_CACHE_DIR=/tmp/composer_cache \
   laravelsail/php82-composer:latest \
   composer create-project laravel/laravel:^10.0 contact-form-app

2. Laravel Sailのインストール

- プロジェクトディレクトリに移動
  cd contact-form-app

- Laravel Sailをインストール
  docker run --rm \
   -u "$(id -u):$(id -g)" \
   -v "$(pwd):/var/www/html" \
   -w /var/www/html \
   -e COMPOSER_CACHE_DIR=/tmp/composer_cache \
   laravelsail/php82-composer:latest \
   composer require laravel/sail --dev

- Sailの設定ファイルをパブリッシュ（MySQLを選択）
  docker run --rm \
   -u "$(id -u):$(id -g)" \
   -v "$(pwd):/var/www/html" \
   -w /var/www/html \
   -e COMPOSER_CACHE_DIR=/tmp/composer_cache \
   laravelsail/php82-composer:latest \
   php artisan sail:install --with=mysql

3. .env ファイルの設定

- `.env` ファイルを開き、データベース接続情報が以下と一致していることを確認。
  DB_CONNECTION=mysql
  DB_HOST=mysql
  DB_PORT=3306
  DB_DATABASE=laravel
  DB_USERNAME=sail
  DB_PASSWORD=password

4. phpMyAdminの追加

- `compose.yaml` を開き、`mysql` サービスの後に以下の設定を追加。
- `compose.yaml` に追加する内容:
  phpmyadmin:
  image: 'phpmyadmin:latest'
  ports: - '${FORWARD_PHPMYADMIN_PORT:-8080}:80'
        environment:
            PMA_HOST: mysql
            PMA_USER: '${DB_USERNAME}'
  PMA_PASSWORD: '${DB_PASSWORD}'
  networks: - sail
  depends_on: - mysql

5. Sailの起動とエイリアス設定

   (1) Sailの起動
   - /vendor/bin/sail up -d

   (2) エイリアスを設定して 'sail' だけでコマンドを実行できるようにする
   - echo "alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'" >> ~/.bashrc
   - exec $SHELL

6. アプリケーションキーの生成
   - sail artisan key:generate

7. データベースのマイグレーションと初期データ投入

   以下のコマンドでテーブルを作成し、初期データを投入します。
   - sail artisan migrate --seed

8. フロントエンドのセットアップ (Vite & Tailwind CSS)

   (1) 提供リポジトリのresourcesディレクトリと入れ替え
   - 以下のリポジトリをクローンし、resourcesディレクトリを丸ごと入れ替え。

     a) ダウンロードフォルダーに移動
     - cd /mnt/d/COACHTECK_Work/clone_202607
     - git clone https://github.com/coachtech-prepared-file/Preparedblade-ConfirmationTest-ContactForm.git

     b) エクスプローラでプロジェクトフォルダを開く

     c) プロジェクト内の resources フォルダを削除

     d) クローンしたリポジトリ内の resources フォルダをプロジェクト直下にコピー

   (2) NPM依存パッケージのインストール
   - cd ~/contact-form-app
   - sail npm install

   (3)Tailwind CSSのインストール
   - sail npm install -D tailwindcss@^3.4.0 postcss autoprefixer
   - sail npm install alpinejs

   (4) 設定ファイルの生成
   - sail npx tailwindcss init -p

   (5) Tailwind CSSのテンプレートパス設定
   - `tailwind.config.js` を開き、以下のように設定

/** @type {import('tailwindcss').Config} \*/
export default {
content: [
"./resources/**/_.blade.php",
"./resources/\*\*/_.js",
"./resources/\*_/_.vue",
],
theme: {
extend: {},
},
plugins: [],
}

## 使用技術

- OS: Dockerが動作する任意のOS
- PHP: 8.2
- Laravel: 10.x
- データベース: MySQL 8.0
- サーバー: Nginx
- フロントエンド: Vite, Tailwind CSS ^3.4.0
- 開発ツール: Docker, Laravel Sail, phpMyAdmin

## 開発環境URL

- ローカル環境: `http://localhost`
- phpMyAdmin（データベース確認用）: `http://localhost:8080`

## ルーティング一覧

| メソッド | パス                    | 概要                                     |
| :------- | :---------------------- | :--------------------------------------- |
| GET      | `/`                     | お問い合わせ入力画面                     |
| POST     | `/contacts/confirm`     | お問い合わせ確認画面                     |
| POST     | `/contacts`             | お問い合わせ送信処理（データ保存）       |
| GET      | `/thanks`               | サンクスページ                           |
| GET      | `/admin`                | 管理画面（ログイン必須・一覧表示・検索） |
| GET      | `/admin/contacts/{id}`  | お問い合わせ詳細ページ                   |
| DELETE   | `/admin/contacts/{id}`  | お問い合わせデータ削除                   |
| POST     | `/admin/tags`           | タグ作成                                 |
| GET      | `/admin/tags/{id}/edit` | タグ編集画面表示                         |
| PUT      | `/admin/tags/{id}`      | タグ更新処理                             |
| DELETE   | `/admin/tags/{id}`      | タグ削除                                 |

## 補足
- 基本要件までの実装となります。
- カバレージが65%と要件の70%に達していません。イシュー#17を追加しこの後対応予定です。
- よって、イシュー#7,#8,#17はOpenに残っている状況です。

## 作成者

中村満明
