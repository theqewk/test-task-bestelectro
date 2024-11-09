## Стек

-   Laravel 10
-   Vue 3
-   Inertia.js
-   TailwindCSS
-   Vite

## Требования к системе

-   PHP >= 8.1
-   nginx + php-fpm
-   Node.js v20
-   npm v10
-   Composer
-   PostgreSQL v14

## Установка проекта

1. Настройте nginx

2. Клонируйте репозиторий

```bash
git clone https://github.com/theqewk/test-task-bestelectro.git
```

3. Установите зависимости

```bash
composer install
npm install
```

4. Настройте окружение

```bash
cp .env.example .env
php artisan key:generate
```

5. Настройте базу данных

-   Создайте базу данных
-   Обновите данные для подключения к БД в файле .env

6. Выполните миграции и заполните базу данных начальными данными

```bash
php artisan migrate --seed
```

7. Запустите сборку фронтенда

```bash
npm run build
```
