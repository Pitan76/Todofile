# Todofile v0.0.1
PHP製CLIタスクランナー

## セットアップ
```bash
git clone https://github.com/Pitan76/Todofile.git
cd Todofile
composer install
```

## 実行
```
# hello
./todofile hello
```

```
# 静的解析
./todofile lint

# テスト
./todofile test
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
./todofile composer-reload
```