# cot-dbviewstructure
# DB Structure Viewer для Cotonti Siena
cot-dbviewstructure
DB Structure Viewer for Cotonti Siena

Cotonti PHP MySQL License Version

Plugins to view the database structure and export in formats: JSON, SQL, CSV, PHP Array.

DB Structure Viewer for Cotonti Siena CMF
DB Structure Viewer for Cotonti Siena — Detailed documentation

Introduction

DB Structure Viewer is an administrative plugin for CMF Cotonti Siena (version 0.9.26 and above), designed to interactively view the database structure and export tables in several convenient formats. This document is a detailed guide to functionality, architecture, configuration, security and the application scenario of the plugin. It describes all the key features, internal mechanisms and operating recommendations, which makes it useful for both developers and administrators.
Who will benefit from this plugin

    System administrators who need to quickly check the DB structure without access to phpMyAdmin or SSH.
    Developers who document a database or prepare migration/exports for tests.
    QA engineers who need to get small samples of data to play bugs.
    Teams in which version control and auditing operations is important - thanks to the built-in logging.

Review of functionality (details)

The plugin provides three main interfaces in the Tools section of the Cotonti admin panel: Structure, Export and Logs. Below is an expanded list of opportunities and use scenarios.

    View tables and fields
        List of all tables in the database, without the Cotonti prefix. This is convenient for understanding the “clean” names of entities that are used in the project.
        For each table is displayed:
            List of fields and their order.
            Data type (e.g. INT, VARCHAR(255), TEXT, DATETIME, etc.).
            Flag of NULL (YES/NO).
            Keys (PRI, UNI, MUL - in the usual notation of MySQL).
            The default value.
            Additional properties (AUTO_INCREMENT and others).
        Information about the table engine (InnoDB, MyISAM, etc.) and approximate number of rows.

    View the contents of the table
        The ability to quickly get the first 10 lines to understand the form of data.
        Finding a line on its primary field (ID) is useful when debugging specific records.
        Data output is securely shielded for correct display in the admin template.

    Export structure and data
        Supported formats: JSON, SQL (Dump), CSV and PHP Array.
        Three export modes:
            Only structure (without data) - for the documentation of the scheme.
            The structure + the first 10 lines is to create a compact export-example.
            Full export of all lines is a full-fledged data dump (suitable for transfers / backups, if the volume is permissible).
        Support for mass selection of tables and options "Separate all".
        The configuration allows you to: save the file to the server folder in the export/, send the file in the response to the browser, or both at the same time.
        Packing option in ZIP: If enabled, the files after creation can be compressed into a single archive.
        Login of created exports (feed name, format, number of tables, a sign of data availability, timestamp) for subsequent audit.

    Login and History of Operations
        Table of logs cot_dbviewstructure_logskeeps records of export operations.
        In the adminka, a page with a pathification and links to download export files is available.
        Logs help track who (if there is an appropriate integration with an authorization system) and when it performed exports.

    Safety of work with OBD
        Table names and fields are shielded by a wrapping function to avoid errors and partially reduce the risk of injection when working with dynamic identifiers.
        Prepared expressions are used to transfer values in SQL where possible.
        Access to the tool and to individual actions (export/downloading) is limited to the standard rights of Cotonti (plug-auth).

    Localization
        The plugin contains language files: Russian and English versions, support for additional languages (ua, etc.) is ready.
        All interface texts are placed in language files, which makes translation easier and adaptable.

Benefits of Use

    Convenience – all features are available directly from the Cotonti admin panel.
    Flexibility of export - you can choose the format, the volume of data and specific tables.
    Security – protection against SQL injections is used, safe access to the DB.
    Transparency – with the logic included, the history of all exports is preserved.
    Compatibility – works with PHP 8.4+ and MySQL 8.0+.
    Localization – available in three languages (en, en, ua).

Installation

    Download the archive with GitHub DB Structure Viewer for Cotonti Siena
    Unpack the archive and folder dbviewstructurePump in the folder plugins
    Go to Adminka → Plugins → Install → dbviewstructure
    Configure:

    export_path – Way to export folder (default plugins/dbviewstructure/export/)
    log_enabled – Include export logging
    export_to_browser — Simultaneous export to browser
    max_rows_per_page — Number of logs on the log page
    pack_to_zip — Pack files in ZIP

Usage
Admin → Others → DB Structure Viewer
Tabs:
```
Tab 	What he does
Structure 	Table list + detailed information
Export 	Selection of tables, format, volume of data
Logs 	History of exports (if inclusive)
```
Export: what you can
```
Format 	Structure 	10 lines 	All lines
JSON 	Yes 	Yes 	Yes
SQL 	Yes 	Yes 	Yes
CSV 	Yes 	Yes 	Yes
PHP 	Yes 	Yes 	No. (too big)
```
    PHP Array is for small volumes only. With "all lines" is a mistake.

Technical architecture (internal device)
Structure of directories and files
```
plugins/dbviewstructure/
├── dbviewstructure.setup.php
├── dbviewstructure.tools.php
├── inc/
│   └── dbviewstructure.functions.php
├── tpl/
│   └── dbviewstructure.tools.tpl
├── export/
├── lang/
│   ├── dbviewstructure.ru.lang.php
│   └── dbviewstructure.en.lang.php
└── setup/
   ├── dbviewstructure.install.sql
   └── dbviewstructure.uninstall.sql
```
The plugin is built in accordance with the standard Cotonti structure and divided into logical layers:

    dbviewstructure.setup.php– registration of expansion in the system, metadata (version, author), and determination of configuration parameters of the plug-oriented UI.
    dbviewstructure.tools.php– the main controller of operation in the admin panel. Processes the input settings (GET/POST), prepares data for the template, evokes the export/receivement functions and displays the template.
    inc/dbviewstructure.functions.php – set of auxiliary functions:
        Obtaining a table list dbview_get_tables().
        Receiving detailed information about the table dbview_get_table_info().
        Export to formats dbview_export_tables()and dbview_export_tables_full().
        Creation of ZIP-archive dbview_pack_to_zip().
        Edumate shielding and bug/logging processing identities.
    tpl/dbviewstructure.tools.tpl– interface template on XTemplate/Bootstrap, responsible for visual representation and JS-interactivity (e.g.,_ selectall for export).
    setup/dbviewstructure.install.sql– SQL-script to create a table of meads when installing.
    lang/– language files for interface translation.

The advantage of such architecture is the simplicity of accompaniment and extensibility: logic is separated from representation, language and configuration are taken separately.
Examples of use and scripts

    Quick inspection of the structure after the deck
        Task: You need to make sure that the new migrate_flag column has appeared in all the desired tables.
        Actions: Open Structure → Tables and Fields → find the names of the tables and check the presence of a field in the list of fields of each table.

    Getting an example of data for testing
        Task: QA asks to show an example of records for the orders table.
        Actions: Structure → View rows → Select orders table → view the first 10 lines, if necessary, specify a specific ID.

    Preparation of exports for migration
        Task: to transfer the structure and data of several tables to the test server.
        Actions: Export → select tables → select SQL format → select “All strings” mode → enable the package in ZIP if necessary → download the archive (via the browser or from the export/ folder).

    Audit of Change
        Task: to track when the export was carried out with the data and who did it.
        Actions: Logs → view relevant records with information about files and time of creation.

Configuration and Operation Recommendations

    Establish the rights to the folder plugins/dbviewstructure/export/At least 75. If the server is configured so that the web process should be entitled to record, it may be required to 775 or 775 for a web server group.
    With large amounts of data, prefer to export via SQL and storing files outside the web directory, or provide access to the export/ through a secure download mechanism. By default, the plugin sends files through a script, which reduces the risks of direct access.
    Include logging for projects where transaction tracking is important. Logs do not contain sensitive data, only metadata of operations (fetal name, format, number of tables, data flag, timestamp).
    Do not use the “All strings” mode for tables with a huge number of records if the server is not prepared: this can lead to the exhaustion of memory/continual execution. For such cases, it is better to use the layout of the server (mysqldump) and manage streaming / parting.
    Regularly clean old files in the export/ and/or implement the rotation: store for a limited time so as not to overwhelm the drive.

Safety – details

    Shielding identifiers: function dbview_quote_identifier()wraps the names in reverse apostrophes and shields internal characters, preventing incorrect analysis of identifiers.
    Prepared queries: When transmitting dynamic values (e.g., ID), prepared PDO parameters are used, which reduces the risk of SQL injections.
    Control of rights: access to tools is limited to the capabilities of Cotonti (soft/autth rights). Download and export operations require the plugin administrator rights.
    Logs do not contain the contents of exported data - only the name of the file and export parameters are stored in the logs. This is important for compliance with safety and privacy.
    Download-release through the script: to prevent direct access to the export files, the download is organized through the plugin script, which further checks the correctness of the path and the right to access.

Subtleties of implementation (important comments)

    PHP Array format does not support the export of a full set of lines (the option is not available when selecting “all strings”), because var_export()can generate extremely large files and potentially lead to exceeding the memory/time limits.
    The ZIP archive is created using the ZipArchive extension. If the extension is not available on the server, the archive will skip and the operation will be pledged.
    Function dbview_get_tables()uses SHOW TABLES LIKE ?the Cotonti Base Prefix (Cot::$db_x) for the correct collection of only CMS tables. With a custom prefix, it is important to correctly configure the Cotonti parameter $db_x.
    SQL is used in export SHOW CREATE TABLEto obtain an accurate DDL scheme of the table, which guarantees the correctness of the restoration of the structure on another basis.

Debugging and troubleshooting

    If the tables are not displayed:
        Check the prefix value Cot::$db_xand the presence of tables with the specified prefix.
        Make sure that the user of the database has the right to SHOW TABLESand SHOW CREATE TABLE.
    If the files are not recorded in the export/:
        Check the rights to the folder and owner of the web server process.
        Make sure that the export path in the plugin settings is specified correctly and exists.
    If ZIP is not created:
        Check the availability of PHP extension zip(ZipArchive).
    If the export "all lines" falls from memory:
        Use the server’s dump (mysqldump) or break up exports by tables and parts.

Frequently Asked Questions (FAQ)

Q: Can only specific table fields be exported?
A: The current version exports the full structure and/or table data. Filtering by fields is not implemented by default, but adding such an option is possible through the refinement of the controller and export functions.

Q: Where are the export files stored?
A: Logs of operations are stored in the table cot_dbviewstructure_logsin the database, and the files themselves - in the folder plugins/dbviewstructure/export/(or are available for download via the plugin interface).

Q: How safe is it to send files through a browser?
A: Files are given through the script with a check, so direct access over the URL to the export/ can be strictly limited. It is recommended to place an export/ out of a public root or configure access based on security requirements.
How to Expand the Plugin – Ideas and Entrance Points

    Add export to XML or YAML formats for integration with other systems.
    Implement the streaming of large tables (chunked export) to minimize the use of memory.
    Add the option to choose only part of the columns when exporting.
    Integrate with the Cotonti authentication/journalization system to record who exactly initiated the export (since only the file name and parameters are recorded in the log - you can add IP and IP ID).
    Add CLI teams for planned exports and integration with backup server system.

License

BSD License © 2025 webitproff
Support and Contribution

The source code and issues are in repository: https://github.com/webitproff/cot-dbview
Pull-requests are welcome. If you are planning a serious revision, open an issue, describe the task and discuss architectural changes.
Contacts of the author

Author: Webitproff
GitHub: https://github.com/webitproff/cot-dbviewstructure
___

[![Cotonti](https://img.shields.io/badge/Cotonti-Siena%200.9.26-green)](https://github.com/Cotonti/Cotonti)
[![PHP](https://img.shields.io/badge/PHP-8.4%2B-blue)](https://php.net)
![MySQL](https://img.shields.io/badge/MySQL-8.0%2B-yellow) 
![License](https://img.shields.io/badge/License-BSD-red) 
![Version](https://img.shields.io/badge/Version-2.0.0-orange)

Плагин для **просмотра структуры базы данных** и **экспорта** в форматы: **JSON, SQL, CSV, PHP Array**.

<img src="https://raw.githubusercontent.com/webitproff/cot-dbviewstructure/refs/heads/main/dbviewstructure-cotonti-webitproff.webp" alt="DB Structure Viewer для Cotonti Siena CMF">

---


# DB Structure Viewer для Cotonti Siena — Подробная документация

## Введение

DB Structure Viewer — это административный плагин для CMF Cotonti Siena (версия 0.9.26 и выше), предназначенный для интерактивного просмотра структуры базы данных и экспорта таблиц в несколько удобных форматов. Этот документ — подробное руководство по функционалу, архитектуре, настройке, безопасности и сценарию использования плагина. В нём описаны все ключевые возможности, внутренние механизмы и рекомендации по эксплуатации, что делает его полезным как для разработчиков, так и для администраторов.

---

## Кому будет полезен этот плагин

- Системным администраторам, которым нужно быстро проверить структуру БД без доступа к phpMyAdmin или SSH.
- Разработчикам, которые документируют базу данных или готовят миграции/экспорты для тестов.
- QA-инженерам, которым нужно получить небольшие выборки данных для воспроизведения багов.
- Командам, в которых контроль версий и аудит операций важен — благодаря встроенному логированию.

---

## Обзор функционала (подробно)

Плагин предоставляет три главным образом интерфейса в разделе «Инструменты» админ-панели Cotonti: Структура, Экспорт и Логи. Ниже — расширённый список возможностей и сценариев использования.

1. Просмотр таблиц и полей
   - Список всех таблиц в базе, без префикса Cotonti. Это удобно для понимания «чистых» имён сущностей, которые используются в проекте.
   - Для каждой таблицы отображается:
     - Список полей и их порядок.
     - Тип данных (например, INT, VARCHAR(255), TEXT, DATETIME и т.д.).
     - Флаг NULL (YES/NO).
     - Ключи (PRI, UNI, MUL — в привычной нотации MySQL).
     - Значение по умолчанию.
     - Дополнительные свойства (AUTO_INCREMENT и другие).
   - Информация о движке таблицы (InnoDB, MyISAM и пр.) и примерное количество строк.

2. Просмотр содержимого таблицы
   - Возможность быстро получить первые 10 строк для понимания формы данных.
   - Поиск строки по её первичному полю (ID) — полезно при отладке конкретных записей.
   - Вывод данных безопасно экранированный для корректного отображения в шаблоне админки.

3. Экспорт структуры и данных
   - Поддерживаемые форматы: JSON, SQL (дамп), CSV и PHP Array.
   - Три режима экспорта:
     - Только структура (без данных) — для документирования схемы.
     - Структура + первые 10 строк — для создания компактного экспорта-примера.
     - Полный экспорт всех строк — полноценный дамп данных (подходит для переносов/бэкапов, если объём допустим).
   - Поддержка массового выбора таблиц и опции «Выделить все».
   - Конфигурация позволяет: сохранять файл на сервер в папку export/, отдать файл в ответе браузеру, или и то и другое одновременно.
   - Опция упаковки в ZIP: если включена, файлы после создания могут быть сжаты в единый архив.
   - Логирование создаваемых экспортов (имя файла, формат, количество таблиц, признак наличия данных, timestamp) для последующего аудита.

4. Логирование и история операций
   - Таблица логов `cot_dbviewstructure_logs` хранит записи об операциях экспорта.
   - В админке доступна страница с пагинацией и ссылками на скачивание файлов экспорта.
   - Логи помогают отслеживать, кто (при наличии соответствующей интеграции с системой авторизации) и когда выполнял экспорты.

5. Безопасность работы с БД
   - Имена таблиц и полей экранируются функцией-обёрткой, чтобы избежать ошибок и частично снизить риск инъекций при работе с динамическими идентификаторами.
   - Для передачи значений в SQL используются подготовленные выражения (prepared statements), где это возможно.
   - Доступ к инструменту и к отдельным действиям (экспорт/скачивание) ограничивается стандартными правами Cotonti (plug-auth).

6. Локализация
   - Плагин содержит языковые файлы: русскую и английскую версии, готова поддержка дополнительных языков (ua и др.).
   - Все тексты интерфейса вынесены в языковые файлы, что упрощает перевод и адаптацию.


## Преимущества использования

1. **Удобство** — все функции доступны прямо из панели администратора Cotonti.
2. **Гибкость экспорта** — можно выбрать формат, объём данных и конкретные таблицы.
3. **Безопасность** — используется защита от SQL-инъекций, безопасное обращение к БД.
4. **Прозрачность** — при включённом логировании сохраняется история всех экспортов.
5. **Совместимость** — работает с PHP 8.4+ и MySQL 8.0+.
6. **Локализация** — доступен на трёх языках (ru, en, ua).

---

## Установка

1. Скачайте архив с [GitHub **DB Structure Viewer для Cotonti Siena**](https://github.com/webitproff/cot-dbviewstructure/)
2. Распакуйте архив и папку `dbviewstructure` закачайте в папку `plugins`
3. Зайдите в **Админка → Плагины → Установить → dbviewstructure**
4. Настройте:
- **export_path** — Путь к папке экспорта (по умолчанию `plugins/dbviewstructure/export/`)
- **log_enabled** — Включить логирование экспортов
- **export_to_browser** — Одновременный экспорт в браузер
- **max_rows_per_page** — Кол-во записей на странице логов
- **pack_to_zip** — Упаковывать файлы в ZIP

  
## Использование

### Админка → Другие → DB Structure Viewer

#### Вкладки:

| Вкладка | Что делает |
|--------|-----------|
| **Структура** | Список таблиц + детальная информация |
| **Экспорт** | Выбор таблиц, формата, объёма данных |
| **Логи** | История экспортов (если включено) |

---

## Экспорт: что можно

| Формат | Структура | 10 строк | Все строки |
|-------|-----------|----------|------------|
| JSON  | Yes       | Yes      | Yes        |
| SQL   | Yes       | Yes      | Yes        |
| CSV   | Yes       | Yes      | Yes        |
| PHP   | Yes       | Yes      | No (слишком большой) |

> **PHP Array** — только для малых объёмов. При "все строки" — **ошибка**.

---

## Техническая архитектура (внутренне устройство)

### Структура директорий и файлов
```
plugins/dbviewstructure/
├── dbviewstructure.setup.php
├── dbviewstructure.tools.php
├── inc/
│   └── dbviewstructure.functions.php
├── tpl/
│   └── dbviewstructure.tools.tpl
├── export/
├── lang/
│   ├── dbviewstructure.ru.lang.php
│   └── dbviewstructure.en.lang.php
└── setup/
   ├── dbviewstructure.install.sql
   └── dbviewstructure.uninstall.sql
```

Плагин построен в соответствии со стандартной структурой Cotonti и разделён на логические слои:

- `dbviewstructure.setup.php` — регистрация расширения в системе, метаданные (версия, автор), и определение конфигурационных параметров плагино-ориентированного UI.
- `dbviewstructure.tools.php` — основной контроллер работы в админ-панели. Обрабатывает входные параметры (GET/POST), подготавливает данные для шаблона, вызывает функции экспорта/получения структуры и отображает шаблон.
- `inc/dbviewstructure.functions.php` — набор вспомогательных функций:
  - Получение списка таблиц `dbview_get_tables()`.
  - Получение подробной информации о таблице `dbview_get_table_info()`.
  - Экспорт в форматы `dbview_export_tables()` и `dbview_export_tables_full()`.
  - Создание ZIP-архива `dbview_pack_to_zip()`.
  - Утилиты экранирования идентификаторов и обработки ошибок/логирования.
- `tpl/dbviewstructure.tools.tpl` — шаблон интерфейса на XTemplate/Bootstrap, отвечает за визуальное представление и JS-интерактивность (например, select_all для экспорта).
- `setup/dbviewstructure.install.sql` — SQL-скрипт для создания таблицы логов при установке.
- `lang/` — языковые файлы для перевода интерфейса.

Преимущество такой архитектуры — простота сопровождения и расширяемость: логика отделена от представления, язык и конфигурация вынесены отдельно.

---

## Примеры использования и сценарии

1. Быстрый осмотр структуры после деплоя
   - Задача: необходимо убедиться, что новая колонка migrate_flag появилась во всех нужных таблицах.
   - Действия: открыть Структура → Таблицы и поля → найти названия таблиц и проверить наличие поля в списке полей каждой таблицы.

2. Получение примера данных для тестирования
   - Задача: QA просит показать пример записей для таблицы orders.
   - Действия: Структура → Просмотр строк → выбрать таблицу orders → просмотреть первые 10 строк, при необходимости указать конкретный ID.

3. Подготовка экспорта для миграции
   - Задача: перенести структуру и данные нескольких таблиц на тестовый сервер.
   - Действия: Экспорт → выбрать таблицы → выбрать формат SQL → выбрать режим «Все строки» → включить упаковку в ZIP при необходимости → скачать архив (через браузер или из папки export/).

4. Аудит изменений
   - Задача: отследить, когда был выполнен экспорт с данными и кто это сделал.
   - Действия: Логи → просмотреть соответствующие записи с информацией о файлах и времени создания.

---

## Рекомендации по конфигурации и эксплуатации

- Установите права на папку `plugins/dbviewstructure/export/` как минимум 755. Если сервер настроен так, что веб-процесс должен иметь право записи, может потребоваться 775 или 775 для группы веб-сервера.
- При больших объёмах данных предпочитайте экспорт через SQL и хранение файлов за пределами веб-директории, либо обеспечьте доступ к export/ через безопасный механизм скачивания. По умолчанию плагин отдаёт файлы через скрипт, что снижает риски прямого доступа.
- Включайте логирование для проектов, где важна отслеживаемость операций. Логи не содержат чувствительных данных, только метаданные операций (имя файла, формат, количество таблиц, флаг с данными, timestamp).
- Не используйте режим «Все строки» для таблиц с огромным количеством записей, если сервер не подготовлен: это может привести к исчерпанию памяти/времени выполнения. Для таких случаев лучше использовать дамп средствами сервера (mysqldump) и управлять потоковой загрузкой/разбиением по частям.
- Регулярно очищайте старые файлы в папке export/ и/или реализуйте ротацию: храните в течение ограниченного времени, чтобы не переполнить диск.

---

## Безопасность — подробности

- Экранирование идентификаторов: функция `dbview_quote_identifier()` обёртывает имена в обратные апострофы и экранирует внутренние символы, предотвращая некорректный разбор идентификаторов.
- Подготовленные запросы: при передаче динамических значений (например, ID) используются подготовленные параметры PDO, что уменьшает риск SQL-инъекций.
- Контроль прав: доступ к инструментам ограничивается возможностями Cotonti (права plug/auth). Операции скачивания и экспорта требуют прав администратора плагина.
- Логи не содержат содержимого экспортированных данных — в логах хранится только имя файла и параметры экспорта. Это важно для соответствия безопасности и приватности.
- Download-релиз через скрипт: чтобы предотвратить прямой доступ к файлам export/, загрузка организована через скрипт плагина, который дополнительно проверяет корректность пути и права на доступ.

---

## Тонкости реализации (важные замечания)

- PHP Array формат не поддерживает экспорт полного набора строк (опция недоступна при выборе «все строки»), потому что `var_export()` может генерировать чрезвычайно большие файлы и потенциально привести к превышению лимитов памяти/времени выполнения.
- ZIP-архив создаётся с использованием расширения ZipArchive. Если расширение отсутствует на сервере, архивирование пропустится, а операция будет залогирована.
- Функция `dbview_get_tables()` использует `SHOW TABLES LIKE ?` с префиксом базы Cotonti (`Cot::$db_x`) для корректного сбора только таблиц CMS. При кастомной префиксации важно корректно настроить параметр Cotonti `$db_x`.
- При экспорте SQL используется `SHOW CREATE TABLE` для получения точной DDL-схемы таблицы, что гарантирует корректность восстановления структуры на другой базе.

---

## Отладка и устранение неполадок

- Если таблицы не отображаются:
  - Проверьте значение префикса `Cot::$db_x` и наличие таблиц с указанным префиксом.
  - Убедитесь, что пользователь базы имеет права на `SHOW TABLES` и `SHOW CREATE TABLE`.
- Если файлы не записываются в export/:
  - Проверьте права на папку и владельца процесса веб-сервера.
  - Убедитесь, что путь экспорта в настройках плагина указан корректно и существует.
- Если ZIP не создаётся:
  - Проверьте доступность расширения PHP `zip` (ZipArchive).
- Если экспорт "всё строки" падает по памяти:
  - Используйте дамп средствами сервера (mysqldump) или разбивайте экспорт по таблицам и частям.

---

## Частые вопросы (FAQ)

В: Можно ли экспортировать только конкретные поля таблицы?  
О: Текущая версия экспортирует полную структуру и/или данные таблицы. Фильтрация по полям не реализована по умолчанию, но добавление такой опции возможно через доработку контроллера и функций экспорта.

В: Где хранятся лог-файлы экспортов?  
О: Логи операций хранятся в таблице `cot_dbviewstructure_logs` в базе данных, а сами файлы — в папке `plugins/dbviewstructure/export/` (или доступны для скачивания через интерфейс плагина).

В: Насколько безопасно отдавать файлы через браузер?  
О: Файлы отдаются через скрипт с проверкой, поэтому прямой доступ по URL к export/ можно жестко ограничить. Рекомендуется размещать export/ вне публичного корня или настроить доступ, исходя из требований безопасности.

---

## Как расширять плагин — идеи и точки входа

- Добавить экспорт в форматы XML или YAML для интеграций с другими системами.
- Реализовать потоковую выгрузку больших таблиц (chunked export) для минимизации использования памяти.
- Добавить возможность выбирать только часть столбцов при экспорте.
- Интегрировать с системой аутентификации/журналирования Cotonti для записи, кто именно инициировал экспорт (пока в лог записывается только имя файла и параметры — можно добавить ID пользователя и IP).
- Добавить CLI-команды для плановых экспортов и интеграции с бэкап-системой сервера.

---

## Лицензия

BSD License © 2025 webitproff

---

## Поддержка и вклад

Исходный код и issues находятся в репозитории: https://github.com/webitproff/cot-dbviewstructure  
Pull-requests приветствуются. Если вы планируете серьёзную доработку, откройте issue, опишите задачу и обсудим архитектурные изменения.

---

## Контакты автора

Автор: webitproff  
GitHub: https://github.com/webitproff/cot-dbviewstructure

