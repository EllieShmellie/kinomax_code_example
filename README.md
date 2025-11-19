# Стек Kinomax

Это упрощённый Yii2 basic в Docker с PostgreSQL и модулем рассылки о премьерах. Как запустить:

1. Скопируй переменные окружения и при необходимости поправь порты/доступы:
   ```bash
   cp .env.example .env
   ```
   `POSTGRES_PORT` — порт на хосте, `POSTGRES_DB_PORT` — порт внутри контейнера (обычно `5432`).

2. Подними всё и открой http://localhost:8080:
   ```bash
   docker compose up -d --build (cron для отправки писем стартует автоматически в контейнере app)
   ```

3. Если менял настройки БД — поправь `app/config/db.php`, затем накати миграции и сиды:
   ```bash
   docker compose exec app php yii migrate --migrationPath=@app/migrations
   ```

4. В `app/config/params.php` задай реальный `adminEmail`, а SMTP-настройки пропиши в `.env` (`SMTP_HOST`, `SMTP_PORT`, `SMTP_USER`, `SMTP_PASSWORD`, `SMTP_ENCRYPTION`). Если `SMTP_HOST` пуст, письма пишутся в `app/runtime/mail/`. При необходимости вручную запускай отправку:
   ```bash
   docker compose exec app php yii premiere-notify/send
   ```

## Что где лежит
- `app/domain` — бизнес-сущности (премьеры, подписчики) и интерфейсы репозиториев/уведомителя.
- `app/application` — DTO и сценарии (`NotifyUpcomingPremieresHandler`, `GetScheduleQuery`, `SubscribeToPremiereHandler`).
- `app/infrastructure` — ActiveRecord-модели, реализации репозиториев и email-уведомитель.
- `app/presentation` — HTTP-контроллеры, формы и шаблоны расписания.
- `app/config/dependencies.php` — DI-настройки, которые связывают слои.

## Полезные команды
- `composer test` — запустить PHPUnit.
- `composer notify` — вручную отправить уведомления (аналог `php yii premiere-notify/send`).

Дальше можно спокойно менять UI, доменную логику или инфраструктуру — слои разделены и не мешают друг другу.

Не сказать что я прям очень горд этим - пример слишком простой и так далее. Но это легко масштабируется и в целом нормально передает стиль того чего я обычно придерживаюсь при разработке
