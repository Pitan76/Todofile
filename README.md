# Todofile v0.0.2
PHP製CLIタスクランナー

Todofileは `todofile.json5` で定義します

## 使い方

### インストール
※インストールにはPHPのパッケージマネージャである https://getcomposer.org/ が必要です。

```bash
composer global require pitan76/todo
```

### todofile.json の作成
`todofile.json5` もしくは `todofile.json` はTodofileが読み込むタスクファイルで、実行するコマンドや設定を定義します。<br />
設定は以下のとおりです。

```json
{
  "<タスク名>": "<コマンド>",
  "<タスク名>": [
    "<コマンド1>",
    "<コマンド2>"
  ]
}

```

#### 例

```json5
{
  // 設定
  "@config": {
    // コマンド実行エラーを無視して次のコマンドを実行する
    "ignoreCommandExecuteException": false
  },
  // 以下のように"タスク名": "コマンド" もしくは "タスク名": {"コマンド", ...} のように記述する
  "lint": "vendor/bin/phpstan analyse src",
  "test": [
    "vendor/bin/phpunit --testdox"
  ],
  "composer": [
    "composer install",
    "echo installed"
  ]
}
```

### 実行
```bash
todo <タスク名>
```

## セットアップ
```bash
git clone git@github.com:Pitan76/Todofile.git
# git clone https://github.com/Pitan76/Todofile.git
cd Todofile
composer install
```

## 実行
```
# hello
./todo hello
```

```
# 静的解析
./todo lint

# テスト
./todo test
```

## 初期メモ (ガチであんま関係ない)

### 依存関係
```bash
composer require --dev phpunit/phpunit phpstan/phpstan
composer require symfony/console
```

### composer.jsonを変えたらコレ
```bash
# composer直呼び
composer dump-autoload

# or

# todofileでやってみる
./todo composer-reload
```
