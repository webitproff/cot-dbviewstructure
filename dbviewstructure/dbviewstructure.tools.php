<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=tools
[END_COT_EXT]
==================== */
/**
 * DB Structure Viewer plugin for Cotonti Siena v.0.9.26, PHP 8.4+, MySQL 8.0+
 * Filename: dbviewstructure.tools.php
 * Purpose: Admin panel for viewing and exporting DB structures
 * Date: 2025-11-01
 * @package dbviewstructure
 * @version 2.0.0
 * @author webitproff https://github.com/webitproff/cot-dbviewstructure
 * @copyright Copyright (c) webitproff 2025
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL.');

require_once cot_incfile('dbviewstructure', 'plug', 'functions');
require_once cot_langfile('dbviewstructure', 'plug');

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
// === ОПРЕДЕЛЕНИЕ АКТИВНОЙ ВКЛАДКИ ===
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
    $per_page = (int) Cot::$cfg['plugin']['dbviewstructure']['max_rows_per_page'];
    list($pg, $d, $durl) = cot_import_pagenav('d', $per_page);
    $total = $db->query("SELECT COUNT(*) FROM " . Cot::$db->dbviewstructure_logs)->fetchColumn();
    $logs = $db->query("SELECT * FROM " . Cot::$db->dbviewstructure_logs . " ORDER BY id DESC LIMIT ?, ?", [$d, $per_page])->fetchAll();

    foreach ($logs as $i => $log) {
        $download_url = cot_url('admin', [
            'm' => 'other',
            'p' => 'dbviewstructure',
            'a' => 'download',
            'file' => $log['filename']
        ]);

        $t->assign([
            'LOG_ID'         => $log['id'],
            'LOG_FILENAME'   => htmlspecialchars($log['filename']),
            'LOG_DOWNLOAD'   => $download_url,
            'LOG_FORMAT'     => strtoupper($log['format']),
            'LOG_TABLES'     => $log['tables_count'],
            'LOG_WITH_DATA'  => $log['with_data'] ? $L['dbviewstructure_log_yes'] : $L['dbviewstructure_log_no'],
            'LOG_DATE'       => cot_date('datetime_medium', $log['created_at']),
            'LOG_ODDEVEN'    => cot_build_oddeven($i + 1)
        ]);
        $t->parse('MAIN.LOGS.LOG_ROW');
    }

    if (empty($logs)) {
        $t->parse('MAIN.LOGS.NO_LOGS');
    }

    $t->assign(cot_generatePaginationTags(cot_pagenav('admin', [
        'm' => 'other',
        'p' => 'dbviewstructure',
        'tab' => 'logs'
    ], $d, $total, $per_page, 'd')));
    $t->parse('MAIN.LOGS');
}

// === ЗАГРУЗКА ФАЙЛА ЧЕРЕЗ COTONTI (ОБХОДИТ NGINX 405) ===
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

            // === 1. УПАКОВКА В ZIP ===
            if ($pack_to_zip) {
                $zip_path = dbview_pack_to_zip([$filepath]);
                if ($zip_path) {
                    $final_filepath = $zip_path;
                    $final_format = 'zip';
                }
            }

            // === 2. ЛОГИРОВАНИЕ ===
            if (Cot::$cfg['plugin']['dbviewstructure']['log_enabled']) {
                global $db;
                $db->insert(Cot::$db->dbviewstructure_logs, [
                    'filename'     => basename($final_filepath),
                    'format'       => $final_format,
                    'tables_count' => count($tables),
                    'with_data'    => $with_data || $full_export ? 1 : 0,
                    'created_at'   => (int) Cot::$sys['now']
                ]);
            }

            // === 3. В БРАУЗЕР — ОТПРАВЛЯЕМ ===
            if ($export_to_browser) {
                $download_name = basename($final_filepath);
                $mime = $final_format === 'zip' ? 'application/zip' : 'application/octet-stream';

                header('Content-Type: ' . $mime);
                header('Content-Disposition: attachment; filename="' . $download_name . '"');
                header('Content-Length: ' . filesize($final_filepath));
                readfile($final_filepath);
                exit;
            }

            // === 4. ИНАЧЕ — РЕДИРЕКТ ===
            cot_message($L['dbviewstructure_export_success']);
            cot_redirect(cot_url('admin', [
                'm' => 'other',
                'p' => 'dbviewstructure',
                'tab' => 'export'
            ], '', false));
        }
    }
}

// === ВЫВОД СООБЩЕНИЙ ===
cot_display_messages($t);
$t->parse('MAIN');
$pluginBody = $t->text('MAIN');
