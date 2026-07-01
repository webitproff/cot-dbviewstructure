<?php
/**
 * DB Structure Viewer plugin for Cotonti v.1+, PHP 8.4+, MySQL 8.0+
 * Filename: plugins/dbviewstructure/lang/dbviewstructure.en.lang.php
 * Purpose: English localization
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

// Plugin configuration localization – the cfg_ prefix is mandatory
$L['cfg_export_path'] = 'Export folder path';
$L['cfg_export_path_hint'] = 'Store files in <code>plugins/dbviewstructure/export/</code>. Set permissions on the <strong>export</strong> folder to 755.';
$L['cfg_log_enabled'] = 'Enable logging';
$L['cfg_max_rows_per_page'] = 'Records per page';
$L['cfg_export_to_browser'] = 'Simultaneous export to browser when saving file to export folder';
$L['cfg_pack_to_zip'] = 'Pack into ZIP';
$L['cfg_pack_to_zip_hint'] = 'Files in the export/ folder will be zipped just like when downloading in the browser';

$L['info_name'] = 'DB Structure Viewer';
$L['info_desc'] = 'View and export database table structure';
$L['info_notes'] = 'Store files in <code>plugins/dbviewstructure/export/</code>. Set permissions on the <strong>export</strong> folder to 755. PHP 8.4+, MySQL 8.0+, Cotonti v.1+';
$L['dbviewstructure_title'] = 'DB Structure Viewer';
$L['dbviewstructure_tab_structure'] = 'Structure';
$L['dbviewstructure_tab_export'] = 'Export';
$L['dbviewstructure_select_table'] = 'Select a table';
$L['dbviewstructure_show'] = 'Show';
$L['dbviewstructure_select_all'] = 'Select all';
$L['dbviewstructure_no_data'] = 'No data';
$L['dbviewstructure_no_tables'] = 'No tables found';
$L['dbviewstructure_no_logs'] = 'No logs available';
$L['dbviewstructure_include_data'] = 'Include data (10 rows)';
$L['dbviewstructure_export_button'] = 'Export';
$L['dbviewstructure_table_name'] = 'Table';
$L['dbviewstructure_table_fields'] = 'Fields';
$L['dbviewstructure_table_engine'] = 'Engine';
$L['dbviewstructure_table_rows'] = 'Rows';
$L['dbviewstructure_logs'] = 'Export logs';
$L['dbviewstructure_log_file'] = 'File';
$L['dbviewstructure_log_format'] = 'Format';
$L['dbviewstructure_log_tables'] = 'Tables';
$L['dbviewstructure_log_download'] = 'Download';
$L['dbviewstructure_log_data'] = 'Data';
$L['dbviewstructure_log_date'] = 'Date';
$L['dbviewstructure_invalid_format'] = 'Invalid format';
$L['dbviewstructure_no_tables_selected'] = 'Select at least one table';
$L['dbviewstructure_export_success'] = 'Export successful';
$L['dbviewstructure_export_failed'] = 'Export failed';
$L['dbviewstructure_log_yes'] = 'Yes';
$L['dbviewstructure_log_no'] = 'No';
$L['dbviewstructure_tables_header'] = 'Table list';
$L['dbviewstructure_table_fields_header'] = 'Table fields';
$L['dbviewstructure_row_id_placeholder'] = 'Row ID';
$L['dbviewstructure_data_row_id'] = 'Row data for ID';
$L['dbviewstructure_data_first_ten'] = 'First 10 rows';
$L['dbviewstructure_field_name'] = 'Field';
$L['dbviewstructure_field_type'] = 'Type';
$L['dbviewstructure_field_null'] = 'NULL';
$L['dbviewstructure_field_key'] = 'Key';
$L['dbviewstructure_field_default'] = 'Default';
$L['dbviewstructure_field_extra'] = 'Extra';
$L['dbviewstructure_tab_logs'] = 'Logs';
$L['dbviewstructure_data_structure_only'] = 'Structure only';
$L['dbviewstructure_include_all_data'] = 'All rows';
$L['dbviewstructure_download_file'] = 'Download export file';
$L['dbviewstructure_php_warning_only'] = 'PHP Array – only for structure or 10 rows';
$L['dbviewstructure_view_tables_fields'] = 'Tables & Fields';
$L['dbviewstructure_view_table_rows'] = 'View rows';

$L['dbviewstructure_clear_logs'] = 'Clear all logs';
$L['dbviewstructure_file_missing'] = 'File missing on server';

$L['dbviewstructure_clear_fields'] = 'Clear fields';
$L['dbviewstructure_tab_combined'] = 'Combined CSV';
$L['dbviewstructure_combined_title'] = 'Combined CSV export';
$L['dbviewstructure_combined_desc'] = 'Construct a CSV file: add columns, choose tables and fields. The base table determines the number of rows.';
$L['dbviewstructure_base_table'] = 'Base table (rows)';
$L['dbviewstructure_add_column'] = 'Add column';
$L['dbviewstructure_csv_col_name'] = 'CSV column name';
$L['dbviewstructure_select_field'] = '— Select a field —';
$L['dbviewstructure_aggregate_none'] = 'No aggregation';
$L['dbviewstructure_aggregate_first_image'] = 'First image (MIN)';
$L['dbviewstructure_aggregate_rest_images'] = 'Rest images (comma separated)';
$L['dbviewstructure_aggregate_all_images'] = 'All images (comma separated)';
