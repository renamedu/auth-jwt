
## Введение
_auth-jwt_ - упрощенный сервис с регистрацией и авторизацией с использованием jwt токена.

## Начало работы
Чтобы начать работу с _auth-jwt_ локально:
1. Клонирировать/скачать этот репозиторий.
2. Поместить репозиторий в корневую папку веб-сервера (например, htdocs в XAMPP).
3. Импортировать файл бд `auth-jwt.sql` в базу данных.
4. Выполнять POST запросы на любой роут, например `http://localhost/auth-jwt/feed`.

## Использование
- _/register_ - регистрация нового пользователя. Принимает параметры string **email**, string **password**. 
    Возвращает **user_id**, **password_check_status** (good/perfect).
    При отсутствии email/password - error: Not valid data
    При невалидном email - error: Email not valid
    Если email существует в базе - error: Email is already in use
    При слабом пароле (<6 символов) - error: weak_password
- _/authorize_ - авторизация пользователя. Принимает параметры string **email**, string **password**.
    Возвращает **access_token** (jwt токен).
    При отсутствии email/password - error: Not enough data
    При невалидном email или несоответствующем пароле - error: Not valid data
- _/feed_ - проверка токена. Принимает параметр **jwt** (access_token).
    Возвращает код 200.
    При отсутствии или неверном токене - error: unauthorized

## База данных
- Создание таблицы users (id, email, password, created)

