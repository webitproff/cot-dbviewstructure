
# cot-dbviewstructure
# DB Structure Viewer for Cotonti Siena
### Full New "combined data export from multiple related tables"

[![Version](https://img.shields.io/badge/version-3.0.0-green.svg)](https://github.com/webitproff/cot-dbviewstructure/releases)
[![Cotonti Compatibility](https://img.shields.io/badge/Cotonti-v.1+-orange.svg)](https://github.com/Cotonti/Cotonti)
[![PHP](https://img.shields.io/badge/PHP-8.4-purple.svg)](https://www.php.net/releases/8_4_0.php)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-blue.svg)](https://www.mysql.com/)
[![Bootstrap v5.3.8](https://img.shields.io/badge/Bootstrap-v5.3.8-blueviolet.svg)](https://getbootstrap.com/)
[![License](https://img.shields.io/badge/license-BSD-blue.svg)](https://github.com/webitproff/cot-dbviewstructure/blob/main/LICENSE)

## **[Releases here!](https://github.com/webitproff/cot-dbviewstructure/releases)**

<img width="1200" height="800" alt="combined data export from multiple related tables" src="https://github.com/user-attachments/assets/5615d8b7-587a-4f20-841a-7efb4841ed0f" />

**Official marketplace page:**  
[https://abuyfile.com/ru/market/cotonti/plugs/cot-plug-db-view-structure](https://abuyfile.com/ru/market/cotonti/plugs/cot-plug-db-view-structure)

---

**link to the combined export file that was imported into [Google Docs](https://docs.google.com/spreadsheets/d/1E32VLFimtXsvN1W0CpAYCtqUzc392T6jM4QL351Co58/edit?usp=sharing)**

---

## Introduction

DB Structure Viewer is an administrative plugin for CMF Cotonti Siena (version 0.9.26 and above) that lets you browse the database structure, export tables in several formats, and perform **combined data export from multiple related tables** into a single CSV file with flexible JOIN settings and image aggregation.

The plugin is aimed at administrators, developers, and QA engineers who need quick access to data and DB structure directly from the Cotonti control panel.

---

## Who will benefit from this plugin

- **System administrators** – quickly check the DB structure without phpMyAdmin or SSH.
- **Developers** – documentation, migration preparation, debugging.
- **QA engineers** – obtain data samples for testing.
- **Site owners** – export products, users, and other content for external services (marketplaces, analytics).

---

## Feature overview

### 1. View structure and table contents
- List of all Cotonti tables (without prefix).
- For each table: columns, types, NULL, keys, default values, extra attributes.
- View the first 10 rows or a specific row by ID.

### 2. Standard export (Export tab)
- Select one or more tables.
- Formats: JSON, SQL, CSV, PHP Array.
- Three modes: structure only, structure + 10 rows, all rows.
- Save to server and/or send to browser.
- Optional ZIP compression.

### 3. Combined CSV export (Combined CSV tab)
- **Base table** defines the rows of the resulting CSV.
- Dynamically add columns specifying:
  - column header,
  - source table,
  - field,
  - (optional) aggregation type for images.
- Automatic JOIN detection between tables or manual selection of the join field.
- Special aggregations for the `attacher` table:
  - **First image (MIN)** – the earliest uploaded file,
  - **Rest images (comma separated)** – all subsequent files.
- Automatic conversion of relative image paths to absolute URLs.
- Form settings are saved in the browser’s localStorage, so they survive page reloads.

### 4. Logging
- All export operations are recorded in the `cot_dbviewstructure_logs` table.
- View logs with pagination and download links.
- «Clear all logs» button to wipe the entire history.
- Checks whether the exported file still exists on the server; if it has been deleted, a warning is displayed.

---

## Installation

1. Download the archive from [GitHub](https://github.com/webitproff/cot-dbviewstructure/releases) or grab the latest version from the [marketplace](https://abuyfile.com/ru/market/cotonti/plugs/cot-plug-db-view-structure).
2. Unpack and upload the `dbviewstructure` folder to your site’s `plugins` directory.
3. In the admin panel go to **Extensions → dbviewstructure → Install**.
4. Configure the plugin (click «Configuration»):
   - `export_path` – folder for saving files (default `plugins/dbviewstructure/export/`).
   - `log_enabled` – enable logging (recommended).
   - `export_to_browser` – also send the file to the browser.
   - `max_rows_per_page` – number of log entries per page.
   - `pack_to_zip` – pack files into ZIP.

---

## Usage

In the admin panel: **Tools → DB Structure Viewer**. Four tabs are available.

### Structure tab
- **Tables & Fields** – summary list with basic info.
- **View rows** – select a table and optionally an ID to filter.

### Export tab
- Select tables (checkboxes).
- Choose format (SQL, CSV, JSON, PHP Array).
- Data mode: structure / 10 rows / all rows.
- Click «Export».

### Combined CSV tab (★ new)
- Select the base table.
- Add columns:
  - enter the header,
  - pick a table,
  - wait for fields to load,
  - choose a field,
  - (optional) set image aggregation,
  - if needed, switch JOIN to manual and pick the join field.
- Buttons «+ Add column», «Clear fields».
- Click «Export» — generates CSV and, if configured, ZIP.

### Logs tab
- Table with export history.
- For each log: ID, filename, format, number of tables, data included, date.
- If the file exists – download link; otherwise red text «File missing from server».
- «Clear all logs» button.

---

## Usage examples

### Simple structure export
1. Export tab → check the desired tables.
2. Choose SQL format, mode «Structure only».
3. Click «Export».

### Combined export of products with images
1. Combined CSV tab.
2. Base table: `market`.
3. Add columns:
   - «SKU», table `market`, field `fieldmrkt_pcod`.
   - «Title», table `market`, field `fieldmrkt_title`.
   - «Main photo», table `attacher`, field `att_path`, aggregation «First image (MIN)».
   - «Additional photos», table `attacher`, field `att_path`, aggregation «Rest images (comma separated)».
4. Click «Export».

### Export with multilingual data
- Base table `market`.
- For columns from the table `i18n4marketpro_pages`:
  - table `i18n4marketpro_pages`,
  - field, e.g. `ipage_title`,
  - if auto‑JOIN fails, switch to «JOIN: manual» and select `ipage_id`.
- Other columns – similar.

---

## Technical architecture

```
plugins/dbviewstructure/
├── dbviewstructure.setup.php
├── dbviewstructure.tools.php
├── dbviewstructure.ajax.php
├── inc/
│   └── dbviewstructure.functions.php
├── tpl/
│   └── dbviewstructure.tools.tpl
├── export/
├── logs/
├── lang/
│   └── dbviewstructure.ru.lang.php
└── setup/
    ├── dbviewstructure.install.sql
    └── dbviewstructure.uninstall.sql
```

- **`dbviewstructure.tools.php`** – controller: processes requests, manages tabs.
- **`dbviewstructure.ajax.php`** – handles AJAX requests to load table columns.
- **`inc/dbviewstructure.functions.php`** – all functions for DB operations, export, packing, JOIN detection.
- **`tpl/dbviewstructure.tools.tpl`** – templates for all tabs with JavaScript (localStorage, dynamic columns).

---

## Security

- Table and column names are escaped by `dbview_quote_identifier()`.
- PDO prepared statements are used.
- Access is restricted by `plug: dbviewstructure` rights (admins only).
- Files are served through a script that validates the path and permissions.

---

## Requirements

- Cotonti Siena 0.9.26+
- PHP 8.4+
- MySQL 8.0+
- PHP `zip` extension (for ZIP packing)

---

## License

BSD License © 2025-2026 webitproff

---

## Changelog

**v2.1.0 (2026-07-01):**
- Added «Combined CSV» tab:
  - dynamic CSV construction from multiple tables,
  - automatic and manual JOIN,
  - image aggregations (first/rest images),
  - absolute URLs for images,
  - form state saved in localStorage.
- Improved «Logs» tab:
  - checks if exported file still exists,
  - mass clear logs button.
- Minor fixes and ZIP stability improvements.

---

## Support & Contributing

Issues and pull requests: [https://github.com/webitproff/cot-dbviewstructure](https://github.com/webitproff/cot-dbviewstructure)  
Official Cotonti Market page: [https://abuyfile.com/ru/market/cotonti/plugs/cot-plug-db-view-structure](https://abuyfile.com/ru/market/cotonti/plugs/cot-plug-db-view-structure)

Author: **webitproff**  
GitHub: [https://github.com/webitproff](https://github.com/webitproff)


___
РУССКИЙ
___

# cot-dbviewstructure
# DB Structure Viewer для Cotonti Siena

[![Version](https://img.shields.io/badge/version-3.0.0-green.svg)](https://github.com/webitproff/cot-dbviewstructure/releases)
[![Cotonti Compatibility](https://img.shields.io/badge/Cotonti-v.1+-orange.svg)](https://github.com/Cotonti/Cotonti)
[![PHP](https://img.shields.io/badge/PHP-8.4-purple.svg)](https://www.php.net/releases/8_4_0.php)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-blue.svg)](https://www.mysql.com/)
[![Bootstrap v5.3.8](https://img.shields.io/badge/Bootstrap-v5.3.8-blueviolet.svg)](https://getbootstrap.com/)
[![License](https://img.shields.io/badge/license-BSD-blue.svg)](https://github.com/webitproff/cot-dbviewstructure/blob/main/LICENSE)

## **[Releases here!](https://github.com/webitproff/cot-dbviewstructure/releases)**


<img width="1200" height="800" alt="combined data export from multiple related tables" src="https://github.com/user-attachments/assets/5615d8b7-587a-4f20-841a-7efb4841ed0f" />
  
[**Официальная страница плагина в маркетплейсе:**](https://abuyfile.com/ru/market/cotonti/plugs/cot-plug-db-view-structure)

---

**ссылка на файл комбинированного экспорта, который был импортирован в [Google-таблицы](https://docs.google.com/spreadsheets/d/1E32VLFimtXsvN1W0CpAYCtqUzc392T6jM4QL351Co58/edit?usp=sharing)**

---

## Введение

DB Structure Viewer — административный плагин для CMF Cotonti Siena (версия 0.9.26 и выше), позволяющий просматривать структуру базы данных, экспортировать таблицы в нескольких форматах, а также выполнять **комбинированный экспорт данных из нескольких связанных таблиц** в единый CSV‑файл с гибкой настройкой связей (JOIN) и агрегацией картинок.

Плагин ориентирован на администраторов, разработчиков и QA‑инженеров, которым нужен быстрый доступ к данным и структуре БД прямо из панели управления Cotonti.

---

## Кому будет полезен этот плагин

- **Системным администраторам** — быстрая проверка структуры БД без доступа к phpMyAdmin или SSH.
- **Разработчикам** — документирование, подготовка миграций, отладка.
- **QA-инженерам** — получение выборок данных для тестирования.
- **Владельцам сайтов** — выгрузка товаров, пользователей и другого контента для внешних сервисов (маркетплейсы, аналитика).

---

## Обзор функционала

### 1. Просмотр структуры и содержимого таблиц
- Список всех таблиц Cotonti (без префикса).
- Для каждой таблицы: поля, типы, NULL, ключи, значение по умолчанию, extra‑атрибуты.
- Просмотр первых 10 строк или конкретной строки по ID.

### 2. Обычный экспорт (вкладка «Экспорт»)
- Выбор одной или нескольких таблиц.
- Форматы: JSON, SQL, CSV, PHP Array.
- Три режима: только структура, структура + 10 строк, все строки.
- Сохранение на сервер и/или отдача в браузер.
- Упаковка в ZIP (опционально).

### 3. Комбинированный экспорт CSV (вкладка «Комбинированный CSV»)
- **Базовая таблица** задаёт строки итогового CSV.
- Динамическое добавление колонок с указанием:
  - названия колонки,
  - таблицы‑источника,
  - поля,
  - (опционально) типа агрегации для картинок.
- Автоматическое определение связей (JOIN) между таблицами или ручной выбор поля связи.
- Специальные агрегации для таблицы `attacher`:
  - **Первая картинка (MIN)** – самый ранний файл,
  - **Остальные картинки (через запятую)** – все последующие.
- Автоматическое преобразование относительных путей картинок в абсолютные URL.
- Сохранение настроек формы в localStorage браузера для восстановления после перезагрузки.

### 4. Логирование
- Запись всех операций экспорта в таблицу `cot_dbviewstructure_logs`.
- Просмотр логов с пагинацией и ссылками на скачивание.
- Кнопка «Очистить все логи» для полной очистки истории.
- Проверка наличия файла на сервере: если файл удалён, отображается предупреждение.

---

## Установка

1. Скачайте архив с [GitHub](https://github.com/webitproff/cot-dbviewstructure/releases) или возьмите последнюю версию из [маркетплейса](https://abuyfile.com/ru/market/cotonti/plugs/cot-plug-db-view-structure).
2. Распакуйте архив и загрузите папку `dbviewstructure` в каталог `plugins` вашего сайта.
3. В админ‑панели перейдите: **Расширения → dbviewstructure → Установить**.
4. Настройте плагин (кнопка «Конфигурация»):
   - `export_path` — путь для сохранения файлов (по умолчанию `plugins/dbviewstructure/export/`).
   - `log_enabled` — включить логирование (рекомендуется).
   - `export_to_browser` — одновременно отдавать файл в браузер.
   - `max_rows_per_page` — записей на странице логов.
   - `pack_to_zip` — упаковывать файлы в ZIP.

---

## Использование

В админ‑панели: **Инструменты → DB Structure Viewer**. Доступны четыре вкладки.

### Вкладка «Структура»
- **Таблицы и Поля** — общий список с основной информацией.
- **Просмотр строк** — выбор таблицы и ID для фильтрации.

### Вкладка «Экспорт»
- Выбор таблиц (чекбоксы).
- Выбор формата (SQL, CSV, JSON, PHP Array).
- Режим данных: структура / 10 строк / все строки.
- Кнопка «Экспорт».

### Вкладка «Комбинированный CSV» (★ новая)
- Выбор базовой таблицы.
- Добавление колонок:
  - введите название,
  - выберите таблицу,
  - дождитесь загрузки полей,
  - выберите поле,
  - (опционально) настройте агрегацию для картинок,
  - при необходимости переключите JOIN в ручной режим и укажите поле связи.
- Кнопки «+ Добавить колонку», «Очистить поля».
- Кнопка «Экспорт» — запускает формирование CSV и, если настроено, ZIP.

### Вкладка «Логи»
- Таблица с историей экспортов.
- Для каждого лога: ID, имя файла, формат, количество таблиц, наличие данных, дата.
- Если файл существует — ссылка на скачивание; если нет — красный текст «Файл отсутствует на сервере».
- Кнопка «Очистить все логи».

---

## Примеры использования

### Простой экспорт структуры
1. Вкладка «Экспорт» → отметить нужные таблицы.
2. Выбрать формат SQL, режим «Только структура».
3. Нажать «Экспорт».

### Комбинированный экспорт товаров с картинками
1. Вкладка «Комбинированный CSV».
2. Базовая таблица: `market`.
3. Добавить колонки:
   - «Артикул», таблица `market`, поле `fieldmrkt_pcod`.
   - «Название», таблица `market`, поле `fieldmrkt_title`.
   - «Главное фото», таблица `attacher`, поле `att_path`, агрегация «Первая картинка (MIN)».
   - «Доп. фото», таблица `attacher`, поле `att_path`, агрегация «Остальные картинки».
4. Нажать «Экспорт».

### Экспорт с мультиязычными данными
- Базовая таблица `market`.
- Для колонок из таблицы `i18n4marketpro_pages`:
  - таблица `i18n4marketpro_pages`,
  - поле, например, `ipage_title`,
  - если авто‑JOIN не сработал, включить «JOIN: вручную» и выбрать `ipage_id`.
- Остальные колонки — аналогично.

---

## Техническая архитектура

```
plugins/dbviewstructure/
├── dbviewstructure.setup.php
├── dbviewstructure.tools.php
├── dbviewstructure.ajax.php
├── inc/
│   └── dbviewstructure.functions.php
├── tpl/
│   └── dbviewstructure.tools.tpl
├── export/
├── logs/
├── lang/
│   └── dbviewstructure.ru.lang.php
└── setup/
    ├── dbviewstructure.install.sql
    └── dbviewstructure.uninstall.sql
```

- **`dbviewstructure.tools.php`** — контроллер: обрабатывает запросы, управляет вкладками.
- **`dbviewstructure.ajax.php`** — отвечает на AJAX‑запросы для загрузки списка полей таблицы.
- **`inc/dbviewstructure.functions.php`** — все функции работы с БД, экспорта, упаковки, определения JOIN.
- **`tpl/dbviewstructure.tools.tpl`** — шаблоны всех вкладок с JavaScript (сохранение в localStorage, динамические колонки).

---

## Безопасность

- Имена таблиц и полей экранируются функцией `dbview_quote_identifier()`.
- Используются подготовленные выражения PDO.
- Доступ к плагину ограничен правами `plug: dbviewstructure` (только администраторы).
- Файлы отдаются через скрипт с проверкой пути и прав.

---

## Требования

- Cotonti Siena 0.9.26+
- PHP 8.4+
- MySQL 8.0+
- Расширение PHP `zip` (для ZIP‑упаковки)

---

## Лицензия

BSD License © 2025-2026 webitproff

---

## Обновления

**v2.1.0 (01.07.2026):**
- Добавлена вкладка «Комбинированный CSV»:
  - динамическое конструирование CSV из нескольких таблиц,
  - автоматический и ручной JOIN,
  - агрегации для изображений (первая/остальные картинки),
  - абсолютные URL для картинок,
  - сохранение формы в localStorage.
- Обновлён интерфейс вкладки «Логи»:
  - проверка существования файла,
  - кнопка массовой очистки логов.
- Мелкие исправления и улучшения стабильности ZIP‑упаковки.

---

## Поддержка и вклад

Issues и pull‑requests: [https://github.com/webitproff/cot-dbviewstructure](https://github.com/webitproff/cot-dbviewstructure)  
Официальная страница на Cotonti Market: [https://abuyfile.com/ru/market/cotonti/plugs/cot-plug-db-view-structure](https://abuyfile.com/ru/market/cotonti/plugs/cot-plug-db-view-structure)

Автор: **webitproff**  
GitHub: [https://github.com/webitproff](https://github.com/webitproff)
