<?php
/**
 * DB Structure Viewer plugin for Cotonti Siena v.0.9.26, PHP 8.4+, MySQL 8.0+
 * Filename: dbviewstructure.ua.lang.php
 * Purpose: Ukrainian localization
 * Date: 2025-11-01
 * @package dbviewstructure
 * @version 2.0.0
 * @author webitproff
 * @copyright Copyright (c) webitproff 2025
 * @license BSD
 */
defined('COT_CODE') or die('Wrong URL.');

// Конфігурація плагіна
$L['cfg_export_path'] = 'Шлях до папки експорту';
$L['cfg_export_path_hint'] = 'Файли зберігаються в <code>plugins/dbviewstructure/export/</code>. Встановіть права <strong>755</strong> на папку <strong>export</strong>.';
$L['cfg_log_enabled'] = 'Увімкнути логування';
$L['cfg_max_rows_per_page'] = 'Записів на сторінку';
$L['cfg_export_to_browser'] = 'Одночасний експорт у браузер при збереженні у папку експорту';
$L['cfg_pack_to_zip'] = 'Пакувати в ZIP';
$L['cfg_pack_to_zip_hint'] = 'Файли в папці export/ будуть запаковані в ZIP, як і при завантаженні в браузер';

$L['info_name'] = 'DB Structure Viewer';
$L['info_desc'] = 'Перегляд та експорт структури таблиць БД';
$L['info_notes'] = 'Файли зберігаються в <code>plugins/dbviewstructure/export/</code>. Встановіть права <strong>755</strong> на папку <strong>export</strong>. PHP 8.4+, MySQL 8.0+, Cotonti Siena v.0.9.26';

$L['dbviewstructure_title'] = 'DB Structure Viewer';
$L['dbviewstructure_tab_structure'] = 'Структура';
$L['dbviewstructure_tab_export'] = 'Експорт';
$L['dbviewstructure_select_table'] = 'Оберіть таблицю';
$L['dbviewstructure_show'] = 'Показати';
$L['dbviewstructure_select_all'] = 'Вибрати все';
$L['dbviewstructure_no_data'] = 'Немає даних';
$L['dbviewstructure_no_tables'] = 'Таблиці не знайдені';
$L['dbviewstructure_no_logs'] = 'Логи відсутні';
$L['dbviewstructure_include_data'] = 'Включити дані (10 рядків)';
$L['dbviewstructure_export_button'] = 'Експорт';
$L['dbviewstructure_table_name'] = 'Таблиця';
$L['dbviewstructure_table_fields'] = 'Поля';
$L['dbviewstructure_table_engine'] = 'Движок';
$L['dbviewstructure_table_rows'] = 'Рядків';
$L['dbviewstructure_logs'] = 'Логи експорту';
$L['dbviewstructure_log_file'] = 'Файл';
$L['dbviewstructure_log_format'] = 'Формат';
$L['dbviewstructure_log_tables'] = 'Таблиць';
$L['dbviewstructure_log_download'] = 'Завантаження';
$L['dbviewstructure_log_data'] = 'Дані';
$L['dbviewstructure_log_date'] = 'Дата';
$L['dbviewstructure_invalid_format'] = 'Недопустимий формат';
$L['dbviewstructure_no_tables_selected'] = 'Оберіть хоча б одну таблицю';
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
$L['dbviewstructure_view_tables_fields'] = 'Таблиці та Поля';
$L['dbviewstructure_view_table_rows'] = 'Перегляд рядків';