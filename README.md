# Todofile
PHP製CLIタスクランナー

## セットアップ
```bash
git clone https://github.com/Pitan76/Todofile.git
cd Todofile
composer install
```

## 実行
```
./todofile hello
```

## 初期メモ (ガチであんま関係ない)

### 依存関係
```bash
composer require --dev phpunit/phpunit phpstan/phpstan
composer require symfony/console
```

### composer.jsonを変えたらコレ
```bash
composer dump-autoload
```