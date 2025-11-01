<?php
/**
 * DB Structure Viewer plugin for Cotonti Siena v.0.9.26, PHP 8.4+, MySQL 8.0+
 * Filename: dbviewstructure.ru.lang.php
 * Purpose: Russian localization
 * Date: 2025-11-01
 * @package dbviewstructure
 * @version 2.0.0
 * @author webitproff
 * @copyright Copyright (c) webitproff 2025
 * @license BSD
 */
defined('COT_CODE') or die('Wrong URL.');

// локализация конфигурации плагина, - префикс cfg_ обязателен
$L['cfg_export_path'] = 'Путь к папке экспорта';
$L['cfg_export_path_hint'] = 'Файлы хранить в <code>plugins/dbviewstructure/export/</code>. На папку <strong>export</strong> установить права 755.';
$L['cfg_log_enabled'] = 'Включить логирование';
$L['cfg_max_rows_per_page'] = 'Записей на страницу';
$L['cfg_export_to_browser'] = 'Одновременный экспорт в браузер при сохранении файла в папке экспорта';
$L['cfg_pack_to_zip'] = 'Упаковывать в ZIP';
$L['cfg_pack_to_zip_hint'] = 'Файлы в папке export/ будут запакованы в ZIP как и при скачивании в браузер';


$L['info_name'] = 'DB Structure Viewer';
$L['info_desc'] = 'Просмотр и экспорт структуры таблиц БД';
$L['info_notes'] = 'Файлы хранить в <code>plugins/dbviewstructure/export/</code>. На папку <strong>export</strong> установить права 755. PHP 8.4+, MySQL 8.0+, Cotonti Siena v.0.9.26';
$L['dbviewstructure_title'] = 'DB Structure Viewer';
$L['dbviewstructure_tab_structure'] = 'Структура';
$L['dbviewstructure_tab_export'] = 'Экспорт';
$L['dbviewstructure_select_table'] = 'Выберите таблицу';
$L['dbviewstructure_show'] = 'Показать';
$L['dbviewstructure_select_all'] = 'Выделить все';
$L['dbviewstructure_no_data'] = 'Нет данных';
$L['dbviewstructure_no_tables'] = 'Таблицы не найдены';
$L['dbviewstructure_no_logs'] = 'Логи отсутствуют';
$L['dbviewstructure_include_data'] = 'Включить данные (10 строк)';
$L['dbviewstructure_export_button'] = 'Экспорт';
$L['dbviewstructure_table_name'] = 'Таблица';
$L['dbviewstructure_table_fields'] = 'Поля';
$L['dbviewstructure_table_engine'] = 'Движок';
$L['dbviewstructure_table_rows'] = 'Строк';
$L['dbviewstructure_logs'] = 'Логи экспорта';
$L['dbviewstructure_log_file'] = 'Файл';
$L['dbviewstructure_log_format'] = 'Формат';
$L['dbviewstructure_log_tables'] = 'Таблиц';
$L['dbviewstructure_log_download'] = 'Загрузка';
$L['dbviewstructure_log_data'] = 'Данные';
$L['dbviewstructure_log_date'] = 'Дата';
$L['dbviewstructure_invalid_format'] = 'Недопустимый формат';
$L['dbviewstructure_no_tables_selected'] = 'Выберите хотя бы одну таблицу';
$L['dbviewstructure_export_success'] = 'Экспорт успешен';
$L['dbviewstructure_export_failed'] = 'Ошибка экспорта';
$L['dbviewstructure_log_yes'] = 'Да';
$L['dbviewstructure_log_no'] = 'Нет';
$L['dbviewstructure_tables_header'] = 'Список таблиц';
$L['dbviewstructure_table_fields_header'] = 'Поля таблицы';
$L['dbviewstructure_row_id_placeholder'] = 'ID строки';
$L['dbviewstructure_data_row_id'] = 'Данные строки ID';
$L['dbviewstructure_data_first_ten'] = 'Первые 10 строк';
$L['dbviewstructure_field_name'] = 'Поле';
$L['dbviewstructure_field_type'] = 'Тип';
$L['dbviewstructure_field_null'] = 'NULL';
$L['dbviewstructure_field_key'] = 'Ключ';
$L['dbviewstructure_field_default'] = 'По умолчанию';
$L['dbviewstructure_field_extra'] = 'Extra';
$L['dbviewstructure_tab_logs'] = 'Логи';
$L['dbviewstructure_data_structure_only'] = 'Только структура';
$L['dbviewstructure_include_all_data'] = 'Все строки';
$L['dbviewstructure_download_file'] = 'Скачать файл экспорта';
$L['dbviewstructure_php_warning_only'] = 'PHP Array — только для структуры или 10 строк';
$L['dbviewstructure_view_tables_fields'] = 'Таблицы и Поля';
$L['dbviewstructure_view_table_rows'] = 'Просмотр строк';
