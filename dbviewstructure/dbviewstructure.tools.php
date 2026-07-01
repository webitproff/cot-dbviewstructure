<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=tools
[END_COT_EXT]
==================== */
/**
 * DB Structure Viewer plugin for Cotonti Siena v.1+, PHP 8.4+, MySQL 8.0+
 * Filename: plugins/dbviewstructure/dbviewstructure.tools.php
 * Purpose: Admin panel for viewing and exporting DB structures
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

require_once cot_incfile('dbviewstructure', 'plug', 'functions');
require_once cot_langfile('dbviewstructure', 'plug');

// [не сработало. пишем пока в шаблоне в локалсторидж] Старт сессии для сохранения формы при ошибках
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$tab = cot_import('tab', 'G', 'TXT') ?: 'structure';
$action = cot_import('a', 'G', 'ALP');
$selected_table = cot_import('table', 'P', 'TXT');
$row_id = cot_import('id', 'P', 'INT');

$t = new XTemplate(cot_tplfile('dbviewstructure.tools', 'plug'));

// === ВКЛАДКИ ===
$t->assign([
    'TAB_STRUCTURE_ACTIVE' => $tab === 'structure' ? 'active' : '',
    'TAB_EXPORT_ACTIVE'    => $tab === 'export' ? 'active' : '',
    'TAB_LOGS_ACTIVE'      => $tab === 'logs' ? 'active' : '',
    'URL_STRUCTURE'        => cot_url('admin', ['m' => 'other', 'p' => 'dbviewstructure', 'tab' => 'structure']),
    'URL_EXPORT'           => cot_url('admin', ['m' => 'other', 'p' => 'dbviewstructure', 'tab' => 'export']),
    'URL_LOGS'             => cot_url('admin', ['m' => 'other', 'p' => 'dbviewstructure', 'tab' => 'logs']),
    'ROW_ID'               => $row_id ? htmlspecialchars($row_id) : ''
]);

// === ВКЛАДКА "СТРУКТУРА" ===
if ($tab === 'structure') {
    $tables = dbview_get_tables();
    $active_subtab = $selected_table ? 'rows' : 'fields';

    $t->assign([
        'ACTIVE_TAB_ROWS'     => $active_subtab === 'rows' ? 'active show' : '',
        'ACTIVE_TAB_FIELDS'   => $active_subtab === 'fields' ? 'active show' : '',
        'SELECTED_ROWS'       => $active_subtab === 'rows' ? 'true' : 'false',
        'SELECTED_FIELDS'     => $active_subtab === 'fields' ? 'true' : 'false'
    ]);
    if (!empty($tables)) {
        foreach ($tables as $table) {
            $info = dbview_get_table_info($table);
            $t->assign([
                'TABLE_NAME'     => htmlspecialchars($table),
                'TABLE_FIELDS'   => htmlspecialchars(implode(', ', array_column($info['fields'], 'name'))),
                'TABLE_ENGINE'   => htmlspecialchars($info['engine']),
                'TABLE_ROWS'     => number_format($info['rows'])
            ]);
            $t->parse('MAIN.STRUCTURE.TABLE_ROW');
        }
        $t->parse('MAIN.STRUCTURE');
    } else {
        $t->parse('MAIN.STRUCTURE.NO_TABLES');
    }

    foreach ($tables as $table) {
        $t->assign([
            'TABLE_VALUE'    => htmlspecialchars($table),
            'TABLE_SELECTED' => $table === $selected_table ? 'selected' : ''
        ]);
        $t->parse('MAIN.TABLES_LIST');
    }

    if ($selected_table && in_array($selected_table, $tables)) {
        $info = dbview_get_table_info($selected_table, $row_id);
        $t->assign(['SELECTED_TABLE' => htmlspecialchars($selected_table)]);

        if (!empty($info['fields'])) {
            foreach ($info['fields'] as $field) {
                $t->assign([
                    'FIELD_NAME'     => htmlspecialchars($field['name']),
                    'FIELD_TYPE'     => htmlspecialchars($field['type']),
                    'FIELD_NULL'     => htmlspecialchars($field['null']),
                    'FIELD_KEY'      => htmlspecialchars($field['key']),
                    'FIELD_DEFAULT'  => htmlspecialchars($field['default'] ?? '—'),
                    'FIELD_EXTRA'    => htmlspecialchars($field['extra'] ?? '—')
                ]);
                $t->parse('MAIN.TABLE_DETAILS.FIELDS_ROW');
            }
        }

        if (!empty($info['data'])) {
            $t->assign('HAS_DATA', 1);
            $headers = array_keys($info['data'][0]);
            foreach ($headers as $header) {
                $t->assign('DATA_HEADER', htmlspecialchars($header));
                $t->parse('MAIN.TABLE_DETAILS.DATA_HEADER');
            }
            foreach ($info['data'] as $row) {
                foreach ($row as $value) {
                    $t->assign('DATA_CELL', htmlspecialchars($value ?? 'NULL'));
                    $t->parse('MAIN.TABLE_DETAILS.DATA_ROW.DATA_CELL');
                }
                $t->parse('MAIN.TABLE_DETAILS.DATA_ROW');
            }
        } else {
            $t->assign('HAS_DATA', 0);
        }
        $t->parse('MAIN.TABLE_DETAILS');
    }
}

// === ВКЛАДКА "ЭКСПОРТ" ===
if ($tab === 'export') {
    $tables = dbview_get_tables();

    if (!empty($tables)) {
        foreach ($tables as $table) {
            $info = dbview_get_table_info($table);
            $t->assign([
                'TABLE_NAME'     => htmlspecialchars($table),
                'TABLE_FIELDS'   => htmlspecialchars(implode(', ', array_column($info['fields'], 'name'))),
                'TABLE_ENGINE'   => htmlspecialchars($info['engine']),
                'TABLE_ROWS'     => number_format($info['rows'])
            ]);
            $t->parse('MAIN.EXPORT.TABLE_ROW');
        }
    }

    $t->assign([
        'TOTAL_TABLES'    => count($tables),
        'EXPORT_FORM_URL' => cot_url('admin', ['m' => 'other', 'p' => 'dbviewstructure', 'a' => 'export', 'tab' => 'export'])
    ]);
    $t->parse('MAIN.EXPORT');
}

// === ВКЛАДКА "ЛОГИ" ===
if ($tab === 'logs' && Cot::$cfg['plugin']['dbviewstructure']['log_enabled']) {
    // Очистка всех логов
    if ($action === 'clear_logs' && cot_auth('plug', 'dbviewstructure', 'A')) {
        Cot::$db->query("TRUNCATE TABLE " . Cot::$db->dbviewstructure_logs);
        cot_message('Логи успешно очищены');
        cot_redirect(cot_url('admin', ['m' => 'other', 'p' => 'dbviewstructure', 'tab' => 'logs']));
    }

    $per_page = (int) Cot::$cfg['plugin']['dbviewstructure']['max_rows_per_page'];
    list($pg, $d, $durl) = cot_import_pagenav('d', $per_page);
    $total = Cot::$db->query("SELECT COUNT(*) FROM " . Cot::$db->dbviewstructure_logs)->fetchColumn();
    $logs = Cot::$db->query("SELECT * FROM " . Cot::$db->dbviewstructure_logs . " ORDER BY id DESC LIMIT ?, ?", [$d, $per_page])->fetchAll();

    $export_dir = Cot::$cfg['plugin']['dbviewstructure']['export_path'];

    foreach ($logs as $i => $log) {
        $file_fullpath = rtrim($export_dir, '/') . '/' . $log['filename'];
        $file_exists = file_exists($file_fullpath);

        $download_url = cot_url('admin', [
            'm' => 'other',
            'p' => 'dbviewstructure',
            'a' => 'download',
            'file' => $log['filename']
        ]);

        $t->assign([
            'LOG_ID'           => $log['id'],
            'LOG_FILENAME'     => htmlspecialchars($log['filename']),
            'LOG_DOWNLOAD'     => $download_url,
            'LOG_FORMAT'       => strtoupper($log['format']),
            'LOG_TABLES'       => $log['tables_count'],
            'LOG_WITH_DATA'    => $log['with_data'] ? $L['dbviewstructure_log_yes'] : $L['dbviewstructure_log_no'],
            'LOG_DATE'         => cot_date('datetime_medium', $log['created_at']),
            'LOG_ODDEVEN'      => cot_build_oddeven($i + 1),
            'LOG_FILE_EXISTS'  => $file_exists
        ]);
        $t->parse('MAIN.LOGS.LOG_ROW');
    }

    if (empty($logs)) {
        $t->parse('MAIN.LOGS.NO_LOGS');
    }

    $t->assign([
        'CLEAR_LOGS_URL' => cot_url('admin', ['m' => 'other', 'p' => 'dbviewstructure', 'a' => 'clear_logs', 'tab' => 'logs'])
    ]);

    $t->assign(cot_generatePaginationTags(cot_pagenav('admin', [
        'm' => 'other',
        'p' => 'dbviewstructure',
        'tab' => 'logs'
    ], $d, $total, $per_page, 'd')));
    $t->parse('MAIN.LOGS');
}

// === ЗАГРУЗКА ФАЙЛА ===
if ($action === 'download' && cot_auth('plug', 'dbviewstructure', 'A')) {
    $file = cot_import('file', 'G', 'TXT');
    $export_dir = Cot::$cfg['plugin']['dbviewstructure']['export_path'];
    $fullpath = rtrim($export_dir, '/') . '/' . $file;

    if ($file && file_exists($fullpath) && strpos(realpath($fullpath), realpath($export_dir)) === 0) {
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        $mimes = [
            'sql'  => 'text/sql',
            'csv'  => 'text/csv',
            'json' => 'application/json',
            'php'  => 'application/x-php',
            'zip'  => 'application/zip'
        ];
        $mime = $mimes[$ext] ?? 'application/octet-stream';

        header('Content-Type: ' . $mime);
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header('Content-Length: ' . filesize($fullpath));
        header('Cache-Control: no-cache, must-revalidate');
        header('Pragma: no-cache');

        readfile($fullpath);
        exit;
    } else {
        cot_error('File not found or access denied');
        cot_redirect(cot_url('admin', ['m' => 'other', 'p' => 'dbviewstructure', 'tab' => 'logs']));
    }
}

// === ОБРАБОТКА ЭКСПОРТА ===
if ($action === 'export' && cot_auth('plug', 'dbviewstructure', 'A')) {
    $format = cot_import('format', 'P', 'TXT');
    $tables = cot_import('tables', 'P', 'ARR');
    $data_mode = cot_import('data_mode', 'P', 'TXT', 20);
    $export_to_browser = (bool) Cot::$cfg['plugin']['dbviewstructure']['export_to_browser'];
    $pack_to_zip = (bool) Cot::$cfg['plugin']['dbviewstructure']['pack_to_zip'];

    $allowed_formats = ['json', 'sql', 'csv', 'php'];

    if (!in_array($format, $allowed_formats)) {
        cot_error($L['dbviewstructure_invalid_format']);
    } elseif (empty($tables)) {
        cot_error($L['dbviewstructure_no_tables_selected']);
    } elseif ($format === 'php' && $data_mode === 'all') {
        cot_error('Формат PHP Array недоступен при экспорте всех строк. Используйте JSON, SQL или CSV.');
    } else {
        $with_data = ($data_mode === 'ten');
        $full_export = ($data_mode === 'all');

        if ($full_export) {
            $filepath = dbview_export_tables_full($tables, $format);
        } else {
            $filepath = dbview_export_tables($tables, $format, $with_data);
        }

        if (!$filepath) {
            cot_error($L['dbviewstructure_export_failed']);
        } else {
            $final_filepath = $filepath;
            $final_format = $format;

            if ($pack_to_zip) {
                $zip_path = dbview_pack_to_zip([$filepath]);
                if ($zip_path) {
                    $final_filepath = $zip_path;
                    $final_format = 'zip';
                }
            }

            if (Cot::$cfg['plugin']['dbviewstructure']['log_enabled']) {
                Cot::$db->insert(Cot::$db->dbviewstructure_logs, [
                    'filename'     => basename($final_filepath),
                    'format'       => $final_format,
                    'tables_count' => count($tables),
                    'with_data'    => $with_data || $full_export ? 1 : 0,
                    'created_at'   => (int) Cot::$sys['now']
                ]);
            }

            if ($export_to_browser) {
                $download_name = basename($final_filepath);
                $mime = $final_format === 'zip' ? 'application/zip' : 'application/octet-stream';

                header('Content-Type: ' . $mime);
                header('Content-Disposition: attachment; filename="' . $download_name . '"');
                header('Content-Length: ' . filesize($final_filepath));
                readfile($final_filepath);
                exit;
            }

            cot_message($L['dbviewstructure_export_success']);
            cot_redirect(cot_url('admin', ['m' => 'other', 'p' => 'dbviewstructure', 'tab' => 'export'], '', false));
        }
    }
}

// === ВКЛАДКА "КОМБИНИРОВАННЫЙ ЭКСПОРТ" ===
if ($tab === 'combined') {
    $allTables = dbview_get_all_tables_raw();

    $t->assign([
        'COMBINED_FORM_URL' => cot_url('admin', [
            'm'   => 'other',
            'p'   => 'dbviewstructure',
            'a'   => 'export_combined',
            'tab' => 'combined'
        ])
    ]);

    $tablesJs = [];
    foreach ($allTables as $tbl) {
        $tablesJs[$tbl['short']] = $tbl['full'];
    }
    $t->assign('ALL_TABLES_JSON', json_encode($tablesJs));

    foreach ($allTables as $tbl) {
        $t->assign([
            'BASE_TABLE_VALUE' => $tbl['full'],
            'BASE_TABLE_LABEL' => $tbl['short']
        ]);
        $t->parse('MAIN.COMBINED.BASE_TABLE_OPTION');
    }

    // [не сработало. пишем пока в шаблоне в локалсторидж] Восстановление сохранённой формы из сессии после ошибки
    $saved = $_SESSION['dbv_save'] ?? [];
    $t->assign([
        'SAVED_BASE_TABLE' => $saved['base_table'] ?? '',
        'SAVED_COLUMNS'    => json_encode($saved['columns'] ?? [])
    ]);
    // unset($_SESSION['dbv_save']);

    $t->parse('MAIN.COMBINED');
}
// === ОБРАБОТКА КОМБИНИРОВАННОГО ЭКСПОРТА ===
$a_post = cot_import('a', 'P', 'ALP');
if (($action === 'export_combined' || $a_post === 'export_combined') && cot_auth('plug', 'dbviewstructure', 'A')) {
    $baseTable   = cot_import('base_table', 'P', 'TXT');
    $columnDefs  = cot_import('columns', 'P', 'ARR');

    cot_dbviewstructure_log('POST: ' . json_encode($_POST));
    cot_dbviewstructure_log('baseTable: ' . ($baseTable ?: 'EMPTY'));

    $prefix = Cot::$db_x ?? 'cot_';
    if (empty($baseTable) || strpos($baseTable, $prefix) !== 0) {
        cot_dbviewstructure_log('ERROR: Invalid base table');
        $_SESSION['dbv_save'] = ['base_table' => $baseTable, 'columns' => $columnDefs];
        cot_error('Invalid base table: ' . ($baseTable ?: 'empty'));
        header('Location: ' . Cot::$sys['abs_url'] . '/admin/other?p=dbviewstructure&tab=combined');
        exit;
    }

    $baseColumns = dbview_get_table_columns($baseTable);
    $baseIdField = null;
    foreach ($baseColumns as $col) {
        if (preg_match('/_id$/i', $col)) {
            $baseIdField = $col;
            break;
        }
    }
    if (!$baseIdField) {
        cot_dbviewstructure_log('ERROR: No ID field in ' . $baseTable . '. Cols: ' . implode(',', $baseColumns));
        $_SESSION['dbv_save'] = ['base_table' => $baseTable, 'columns' => $columnDefs];
        cot_error('Cannot determine ID field for table: ' . $baseTable);
        header('Location: ' . Cot::$sys['abs_url'] . '/admin/other?p=dbviewstructure&tab=combined');
        exit;
    }

    $defs = [];
    if (is_array($columnDefs)) {
        foreach ($columnDefs as $col) {
            if (!empty($col['csv_header']) && !empty($col['table']) && !empty($col['field'])) {
                $defs[] = [
                    'csv_header' => $col['csv_header'],
                    'table'      => $col['table'],
                    'field'      => $col['field'],
                    'aggregate'  => $col['aggregate'] ?? null,
                    'join_mode'  => $col['join_mode'] ?? 'auto',
                    'join_field' => $col['join_field'] ?? null
                ];
            }
        }
    }

    cot_dbviewstructure_log('defs: ' . json_encode($defs));

    if (empty($defs)) {
        cot_dbviewstructure_log('ERROR: No columns defined');
        $_SESSION['dbv_save'] = ['base_table' => $baseTable, 'columns' => $columnDefs];
        cot_error('No columns defined');
        header('Location: ' . Cot::$sys['abs_url'] . '/admin/other?p=dbviewstructure&tab=combined');
        exit;
    }

    // Единая функция: создаст CSV, при необходимости упакует в ZIP, вернёт путь к итоговому файлу
    cot_dbviewstructure_log('Calling dbview_export_combined_csv');
    $filepath = dbview_export_combined_csv($baseTable, $baseIdField, $defs);
    cot_dbviewstructure_log('Result: ' . ($filepath ?: 'FALSE'));

    if (!$filepath) {
        $_SESSION['dbv_save'] = ['base_table' => $baseTable, 'columns' => $columnDefs];
        cot_error($L['dbviewstructure_export_failed']);
        header('Location: ' . Cot::$sys['abs_url'] . '/admin/other?p=dbviewstructure&tab=combined');
        exit;
    }

    $ext = strtolower(pathinfo($filepath, PATHINFO_EXTENSION));
    $finalFormat = ($ext === 'zip') ? 'zip' : 'csv';

    if (Cot::$cfg['plugin']['dbviewstructure']['log_enabled']) {
        Cot::$db->insert(Cot::$db->dbviewstructure_logs, [
            'filename'     => basename($filepath),
            'format'       => $finalFormat,
            'tables_count' => count(array_unique(array_column($defs, 'table'))),
            'with_data'    => 1,
            'created_at'   => (int) Cot::$sys['now']
        ]);
    }

    $exportToBrowser = (bool) Cot::$cfg['plugin']['dbviewstructure']['export_to_browser'];

    if ($exportToBrowser) {
        $mime = $finalFormat === 'zip' ? 'application/zip' : 'text/csv';
        header('Content-Type: ' . $mime);
        header('Content-Disposition: attachment; filename="' . basename($filepath) . '"');
        header('Content-Length: ' . filesize($filepath));
        readfile($filepath);
        exit;
    }

    cot_message($L['dbviewstructure_export_success']);
    header('Location: ' . Cot::$sys['abs_url'] . '/admin/other?p=dbviewstructure&tab=combined');
    exit;
}

// === ВКЛАДКА "КОМБИНИРОВАННЫЙ" В НАВИГАЦИИ ПО ВКЛАДКАМ ===
$t->assign([
    'TAB_COMBINED_ACTIVE' => $tab === 'combined' ? 'active' : '',
    'URL_COMBINED'        => cot_url('admin', ['m' => 'other', 'p' => 'dbviewstructure', 'tab' => 'combined'])
]);

// === ВЫВОД СООБЩЕНИЙ ===
cot_display_messages($t);
$t->parse('MAIN');
$pluginBody = $t->text('MAIN');
