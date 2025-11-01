<?php
/**
 * DB Structure Viewer plugin for Cotonti Siena v.0.9.26, PHP 8.4+, MySQL 8.0+
 * Filename: dbviewstructure.en.lang.php
 * Purpose: English localization
 * Date: 2025-11-01
 * @package dbviewstructure
 * @version 2.0.0
 * @author webitproff
 * @copyright Copyright (c) webitproff 2025
 * @license BSD
 */
defined('COT_CODE') or die('Wrong URL.');

// Plugin configuration
$L['cfg_export_path'] = 'Export folder path';
$L['cfg_export_path_hint'] = 'Files are stored in <code>plugins/dbviewstructure/export/</code>. Set <strong>755</strong> permissions on the <strong>export</strong> folder.';
$L['cfg_log_enabled'] = 'Enable logging';
$L['cfg_max_rows_per_page'] = 'Rows per page';
$L['cfg_export_to_browser'] = 'Simultaneous export to browser when saving to export folder';
$L['cfg_pack_to_zip'] = 'Pack into ZIP';
$L['cfg_pack_to_zip_hint'] = 'Files in the export/ folder will be packed into ZIP just like when downloading to browser';

$L['info_name'] = 'DB Structure Viewer';
$L['info_desc'] = 'View and export database table structure';
$L['info_notes'] = 'Files are stored in <code>plugins/dbviewstructure/export/</code>. Set <strong>755</strong> permissions on the <strong>export</strong> folder. PHP 8.4+, MySQL 8.0+, Cotonti Siena v.0.9.26';

$L['dbviewstructure_title'] = 'DB Structure Viewer';
$L['dbviewstructure_tab_structure'] = 'Structure';
$L['dbviewstructure_tab_export'] = 'Export';
$L['dbviewstructure_select_table'] = 'Select table';
$L['dbviewstructure_show'] = 'Show';
$L['dbviewstructure_select_all'] = 'Select all';
$L['dbviewstructure_no_data'] = 'No data';
$L['dbviewstructure_no_tables'] = 'No tables found';
$L['dbviewstructure_no_logs'] = 'No logs';
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
$L['dbviewstructure_tables_header'] = 'List of tables';
$L['dbviewstructure_table_fields_header'] = 'Table fields';
$L['dbviewstructure_row_id_placeholder'] = 'Row ID';
$L['dbviewstructure_data_row_id'] = 'Data for row ID';
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
$L['dbviewstructure_php_warning_only'] = 'PHP Array — only for structure or 10 rows';
$L['dbviewstructure_view_tables_fields'] = 'Tables and Fields';
$L['dbviewstructure_view_table_rows'] = 'View Rows';