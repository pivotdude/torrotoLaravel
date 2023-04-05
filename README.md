# Instalation
### 1. Установка зависимостей
Выполните команду: `composer install`
### 2. Переименование файла
Переименуйте файл `.env.example` в `.env`, прописать в файле `APP_KEY`
### 3. Проверка настроек подключения к базе данных
Убедитесь, что настройки подключения к базе данных, в файле `.env` верны.
### 4. Выполнения миграции
Чтобы провести миграции пропишите в терминале `php artisan migrate`
### 5. Запуск seeder`ов
`php artisan db:seed --class=RoleSeeder` <br>
`php artisan db:seed --class=StatusesSeeder` <br>
`php artisan db:seed --class=UserSeeder` <br>
`php artisan db:seed --class=OrdersSeeder` <br>

# Run
#### Запустите ваш mysql сервер
#### Выполните команду: `php artisan serve`
