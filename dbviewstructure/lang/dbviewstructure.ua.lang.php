<?php
/**
 * DB Structure Viewer plugin for Cotonti v.1+, PHP 8.4+, MySQL 8.0+
 * Filename: plugins/dbviewstructure/lang/dbviewstructure.ua.lang.php
 * Purpose: Ukrainian localization
 * Date: 01 July 2026 
 * 
 * Source: https://github.com/webitproff/cot-dbviewstructure
 * Page in Cotonti Marketplace: https://abuyfile.com/ru/market/cotonti/plugs/cot-plug-db-view-structure
 * 
 * @package dbviewstructure
 * @version 3.0.0
 * @author webitproff
 * @copyright Copyright (c) webitproff 2026 https://github.com/webitproff
 * @license BSD
 */
defined('COT_CODE') or die('Wrong URL.');

// локалізація конфігурації плагіна – префікс cfg_ обов'язковий
$L['cfg_export_path'] = 'Шлях до теки експорту';
$L['cfg_export_path_hint'] = 'Файли зберігати в <code>plugins/dbviewstructure/export/</code>. На теку <strong>export</strong> встановити права 755.';
$L['cfg_log_enabled'] = 'Увімкнути логування';
$L['cfg_max_rows_per_page'] = 'Записів на сторінку';
$L['cfg_export_to_browser'] = 'Одночасний експорт у браузер під час збереження файлу в теці експорту';
$L['cfg_pack_to_zip'] = 'Пакувати в ZIP';
$L['cfg_pack_to_zip_hint'] = 'Файли в теці export/ будуть запаковані в ZIP так само, як і під час завантаження в браузер';

$L['info_name'] = 'DB Structure Viewer';
$L['info_desc'] = 'Перегляд та експорт структури таблиць БД';
$L['info_notes'] = 'Файли зберігати в <code>plugins/dbviewstructure/export/</code>. На теку <strong>export</strong> встановити права 755. PHP 8.4+, MySQL 8.0+, Cotonti v.1+';
$L['dbviewstructure_title'] = 'DB Structure Viewer';
$L['dbviewstructure_tab_structure'] = 'Структура';
$L['dbviewstructure_tab_export'] = 'Експорт';
$L['dbviewstructure_select_table'] = 'Виберіть таблицю';
$L['dbviewstructure_show'] = 'Показати';
$L['dbviewstructure_select_all'] = 'Вибрати все';
$L['dbviewstructure_no_data'] = 'Немає даних';
$L['dbviewstructure_no_tables'] = 'Таблиці не знайдено';
$L['dbviewstructure_no_logs'] = 'Логи відсутні';
$L['dbviewstructure_include_data'] = 'Включити дані (10 рядків)';
$L['dbviewstructure_export_button'] = 'Експорт';
$L['dbviewstructure_table_name'] = 'Таблиця';
$L['dbviewstructure_table_fields'] = 'Поля';
$L['dbviewstructure_table_engine'] = 'Рушій';
$L['dbviewstructure_table_rows'] = 'Рядків';
$L['dbviewstructure_logs'] = 'Логи експорту';
$L['dbviewstructure_log_file'] = 'Файл';
$L['dbviewstructure_log_format'] = 'Формат';
$L['dbviewstructure_log_tables'] = 'Таблиць';
$L['dbviewstructure_log_download'] = 'Завантаження';
$L['dbviewstructure_log_data'] = 'Дані';
$L['dbviewstructure_log_date'] = 'Дата';
$L['dbviewstructure_invalid_format'] = 'Неприпустимий формат';
$L['dbviewstructure_no_tables_selected'] = 'Виберіть хоча б одну таблицю';
$L['dbviewstructure_export_success'] = 'Експорт успішний';
$L['dbviewstructure_export_failed'] = 'Помилка експорту';
$L['dbviewstructure_log_yes'] = 'Так';
$L['dbviewstructure_log_no'] = 'Ні';
$L['dbviewstructure_tables_header'] = 'Список таблиць';
$L['dbviewstructure_table_fields_header'] = 'Поля таблиці';
$L['dbviewstructure_row_id_placeholder'] = 'ID рядка';
$L['dbviewstructure_data_row_id'] = 'Дані рядка ID';
$L['dbviewstructure_data_first_ten'] = 'Перші 10 рядків';
$L['dbviewstructure_field_name'] = 'Поле';
$L['dbviewstructure_field_type'] = 'Тип';
$L['dbviewstructure_field_null'] = 'NULL';
$L['dbviewstructure_field_key'] = 'Ключ';
$L['dbviewstructure_field_default'] = 'За замовчуванням';
$L['dbviewstructure_field_extra'] = 'Extra';
$L['dbviewstructure_tab_logs'] = 'Логи';
$L['dbviewstructure_data_structure_only'] = 'Тільки структура';
$L['dbviewstructure_include_all_data'] = 'Всі рядки';
$L['dbviewstructure_download_file'] = 'Завантажити файл експорту';
$L['dbviewstructure_php_warning_only'] = 'PHP Array — тільки для структури або 10 рядків';
$L['dbviewstructure_view_tables_fields'] = 'Таблиці й Поля';
$L['dbviewstructure_view_table_rows'] = 'Перегляд рядків';

$L['dbviewstructure_clear_logs'] = 'Очистити всі логи';
$L['dbviewstructure_file_missing'] = 'Файл відсутній на сервері';

$L['dbviewstructure_clear_fields'] = 'Очистити поля';
$L['dbviewstructure_tab_combined'] = 'Комбінований CSV';
$L['dbviewstructure_combined_title'] = 'Комбінований експорт у CSV';
$L['dbviewstructure_combined_desc'] = 'Сконструюйте CSV-файл: додайте стовпці, виберіть таблиці та поля. Базова таблиця визначає кількість рядків.';
$L['dbviewstructure_base_table'] = 'Базова таблиця (рядки)';
$L['dbviewstructure_add_column'] = 'Додати стовпець';
$L['dbviewstructure_csv_col_name'] = 'Назва стовпця в CSV';
$L['dbviewstructure_select_field'] = '— Виберіть поле —';
$L['dbviewstructure_aggregate_none'] = 'Без агрегації';
$L['dbviewstructure_aggregate_first_image'] = 'Перше зображення (MIN)';
$L['dbviewstructure_aggregate_rest_images'] = 'Інші зображення (через кому)';
$L['dbviewstructure_aggregate_all_images'] = 'Всі зображення (через кому)';
