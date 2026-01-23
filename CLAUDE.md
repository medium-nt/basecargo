# BaseCargo - Система управления грузоперевозками Китай-Россия

Laravel 12 проект для управления грузоперевозками. Основная сущность — CargoShipment (груз).

## Основные команды

```bash
# Полная установка проекта
composer setup

# Запуск dev-среды (serve + queue + logs + vite)
composer dev

# Запуск тестов
composer test
php artisan test
php artisan test --filter TestName

# Очистка кешей
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

## Ролевая модель

Роли через `users.role_id → roles.id`:
- **admin** — полный доступ ко всему
- **manager** — просмотр и редактирование всех грузов
- **agent** — создание грузов, видит только закреплённые за ним грузы (`responsible_user_id`)
- **client** — только просмотр своих грузов (`client_id`)

Хелперы в User модели: `isAdmin()`, `isManager()`, `isAgent()`, `isClient()`, `roleName()`.

## Архитектура

### Роуты
Вынесены в отдельные файлы модульности:
- `routes/web.php` — главный файл, включает автологин для local
- `routes/cargo_shipments.php` — CRUD для грузов
- `routes/users.php` — управление пользователями

Все роуты внутри `/admin` требуют `auth` middleware.

### Policy-based авторизация
- `App\Policies\CargoShipmentPolicy` — права для грузов
- `App\Policies\UserPolicy` — права для пользователей

Проверка в роутах через `->can()`.

### Валидация
- `App\Http\Requests\CargoShipmentRequest`
- `App\Http\Requests\UsersRequest`

### Контроллёры
- `App\Http\Controllers\CargoShipmentController`
- `App\Http\Controllers\UsersController`

## Структура БД

Основные таблицы:
- `users` — пользователи (с role_id)
- `roles` — роли (admin, manager, agent, client)
- `cargo_shipments` — грузы (60+ полей)

Поля `cargo_shipments`:
- `public_id` — UUID для публичной идентификации
- `cargo_status` — статус груза
- `client_id` — клиент-владелец
- `responsible_user_id` — ответственный агент/менеджер

## Фронтенд

- AdminLTE 3.15 (`jeroennoten/laravel-adminlte`)
- Blade шаблоны в `resources/views/`
- QR коды: `endroid/qr-code`
- Публичный QR доступ без авторизации: `/qr/{uuid}`

## Локальная разработка

Автологин для local окружения: `/autologin/{email}`

Регистрация отключена (`Auth::routes(['register' => false])`).

## Паттерны проекта

1. **Policy-based авторизация** — все права через Policy, проверка в роутах через `->can()`
2. **Модульные роуты** — маршруты вынесены в отдельные файлы
3. **FormRequest валидация** — проверка данных в отдельных классах
4. **UUID для публичных ссылок** — `public_id` для QR кодов и публичного доступа
