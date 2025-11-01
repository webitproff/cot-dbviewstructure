<?php
/**
 * DB Structure Viewer plugin for Cotonti Siena v.0.9.26, PHP 8.4+, MySQL 8.0+
 * Filename: dbviewstructure.functions.php
 * Purpose: Core database functions
 * Date: 2025-11-01
 * @package dbviewstructure
 * @version 2.0.0
 * @author webitproff
 * @copyright Copyright (c) webitproff 2025 https://github.com/webitproff/cot-dbviewstructure
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL.');

Cot::$db->registerTable('dbviewstructure_logs');

/**
 * Получение списка таблиц без префикса
 */
function dbview_get_tables()
{
    global $db;
    $prefix = Cot::$db_x ?? 'cot_';
    $tables = [];
    $result = $db->query("SHOW TABLES LIKE ?", ["$prefix%"])->fetchAll(PDO::FETCH_COLUMN);
    foreach ($result as $table) {
        $tables[] = str_replace($prefix, '', $table);
    }
    return $tables;
}

/**
 * Безопасное экранирование идентификатора
 */
function dbview_quote_identifier($name)
{
    return '`' . str_replace('`', '``', $name) . '`';
}

/**
 * Получение информации о таблице
 */
function dbview_get_table_info($table, $id = null)
{
    global $db;
    $prefix = Cot::$db_x ?? 'cot_';
    $full_table = $prefix . $table;
    $info = ['fields' => [], 'data' => [], 'engine' => '', 'rows' => 0];

    try {
        $quoted_table = dbview_quote_identifier($full_table);
        $status = $db->query("SHOW TABLE STATUS WHERE Name = ?", [$full_table])->fetch();
        if (!$status) return $info;

        $info['engine'] = $status['Engine'] ?? 'Unknown';
        $info['rows'] = $status['Rows'] ?? 0;

        $fields = $db->query("SHOW FULL COLUMNS FROM $quoted_table")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($fields as $field) {
            $info['fields'][] = [
                'name' => $field['Field'],
                'type' => $field['Type'],
                'null' => $field['Null'],
                'key' => $field['Key'],
                'default' => $field['Default'],
                'extra' => $field['Extra']
            ];
        }

        if (!empty($info['fields'])) {
            $query = "SELECT * FROM $quoted_table";
            if ($id !== null) {
                $id_field = $info['fields'][0]['name'];
                $quoted_field = dbview_quote_identifier($id_field);
                $query .= " WHERE $quoted_field = ? LIMIT 1";
                $data = $db->query($query, [(int)$id])->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $query .= " LIMIT 10";
                $data = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
            }
            $info['data'] = $data;
        }
    } catch (Exception $e) {
        cot_log("DBViewStructure: Error fetching table info for `$full_table`: " . $e->getMessage(), 'plug');
    }

    return $info;
}

/**
 * Упаковка файлов в ZIP (только для сервера)
 */
function dbview_pack_to_zip($filepaths)
{
    if (!class_exists('ZipArchive')) {
        cot_log("DBViewStructure: ZipArchive not available", 'plug');
        return false;
    }

    $export_dir = Cot::$cfg['plugin']['dbviewstructure']['export_path'];
    $zip_filename = 'db_export_bundle_' . cot_date('Ymd_His', Cot::$sys['now']) . '.zip';
    $zip_filepath = rtrim($export_dir, '/') . '/' . $zip_filename;

    $zip = new ZipArchive();
    if ($zip->open($zip_filepath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
        cot_log("DBViewStructure: Failed to create ZIP: $zip_filepath", 'plug');
        return false;
    }

    foreach ($filepaths as $filepath) {
        if (file_exists($filepath)) {
            $zip->addFile($filepath, basename($filepath));
        }
    }

    $zip->close();

    // УДАЛЯЕМ ОРИГИНАЛ ТОЛЬКО ЕСЛИ НЕ НУЖЕН ДЛЯ БРАУЗЕРА
    if (!Cot::$cfg['plugin']['dbviewstructure']['export_to_browser']) {
        foreach ($filepaths as $filepath) {
            @unlink($filepath);
        }
    }

    return $zip_filepath;
}

/**
 * Экспорт структуры + 10 строк
 */
function dbview_export_tables($tables, $format, $with_data = false)
{
    global $db;
    $prefix = Cot::$db_x ?? 'cot_';
    $export_dir = Cot::$cfg['plugin']['dbviewstructure']['export_path'];
    $filename = 'db_export_' . cot_date('Ymd_His', Cot::$sys['now']) . '.' . $format;
    $filepath = rtrim($export_dir, '/') . '/' . $filename;
    $data = [];

    foreach ($tables as $table) {
        $info = dbview_get_table_info($table, null);
        if (!empty($info['fields'])) {
            $data[$table] = [
                'fields' => $info['fields'],
                'engine' => $info['engine'],
                'rows' => $info['rows']
            ];
            if ($with_data) {
                $data[$table]['data'] = $info['data'];
            }
        }
    }

    if (empty($data)) return false;

    try {
        switch ($format) {
            case 'json':
                $content = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                break;
            case 'sql':
                $content = "-- DB Export\n-- Date: " . cot_date('Y-m-d H:i:s', Cot::$sys['now']) . "\n\n";
                foreach ($data as $table => $info) {
                    $full_table = $prefix . $table;
                    $quoted_table = dbview_quote_identifier($full_table);
                    $create = $db->query("SHOW CREATE TABLE $quoted_table")->fetch(PDO::FETCH_ASSOC);
                    $content .= "DROP TABLE IF EXISTS $quoted_table;\n" . $create['Create Table'] . ";\n\n";
                    if ($with_data && !empty($info['data'])) {
                        foreach ($info['data'] as $row) {
                            $values = array_map(fn($val) => $val === null ? 'NULL' : $db->quote($val), $row);
                            $content .= "INSERT INTO $quoted_table VALUES (" . implode(', ', $values) . ");\n";
                        }
                        $content .= "\n";
                    }
                }
                break;
            case 'csv':
                $content = "Table,Field,Type,Null,Key,Default,Extra\n";
                foreach ($data as $table => $info) {
                    foreach ($info['fields'] as $field) {
                        $content .= sprintf(
                            '"%s","%s","%s","%s","%s","%s","%s"',
                            str_replace('"', '""', $table),
                            str_replace('"', '""', $field['name']),
                            str_replace('"', '""', $field['type']),
                            $field['null'],
                            $field['key'],
                            str_replace('"', '""', $field['default'] ?? ''),
                            str_replace('"', '""', $field['extra'])
                        ) . "\n";
                    }
                }
                break;
            case 'php':
                $content = "<?php\n// DB Export\n// Date: " . cot_date('Y-m-d H:i:s', Cot::$sys['now']) . "\nreturn " . var_export($data, true) . ";\n";
                break;
            default:
                return false;
        }

        if (!file_put_contents($filepath, $content)) {
            cot_log("DBViewStructure: Failed to write file: $filepath", 'plug');
            return false;
        }

        return $filepath;
    } catch (Exception $e) {
        cot_log("DBViewStructure: Export error: " . $e->getMessage(), 'plug');
        return false;
    }
}

/**
 * Полный экспорт (все строки)
 */
function dbview_export_tables_full($tables, $format)
{
    global $db;
    $prefix = Cot::$db_x ?? 'cot_';
    $export_dir = Cot::$cfg['plugin']['dbviewstructure']['export_path'];
    $filename = 'db_export_full_' . cot_date('Ymd_His', Cot::$sys['now']) . '.' . $format;
    $filepath = rtrim($export_dir, '/') . '/' . $filename;
    $data = [];

    foreach ($tables as $table) {
        $info = dbview_get_table_info($table, null);
        if (!empty($info['fields'])) {
            $data[$table] = [
                'fields' => $info['fields'],
                'engine' => $info['engine'],
                'rows' => $info['rows']
            ];
            $quoted_table = dbview_quote_identifier($prefix . $table);
            $full_data = $db->query("SELECT * FROM $quoted_table")->fetchAll(PDO::FETCH_ASSOC);
            $data[$table]['data'] = $full_data;
        }
    }

    if (empty($data)) return false;

    try {
        switch ($format) {
            case 'json':
                $content = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                break;
            case 'csv':
                $content = '';
                foreach ($data as $table => $info) {
                    if (empty($info['data'])) continue;
                    $headers = array_map(fn($f) => $f['name'], $info['fields']);
                    $content .= '"' . $table . '","' . implode('","', $headers) . "\"\n";
                    foreach ($info['data'] as $row) {
                        $row_csv = array_map(fn($v) => str_replace('"', '""', $v ?? ''), $row);
                        $content .= '"' . implode('","', $row_csv) . "\"\n";
                    }
                    $content .= "\n";
                }
                break;
            case 'sql':
                $content = "-- DB Export (Full)\n-- Date: " . cot_date('Y-m-d H:i:s', Cot::$sys['now']) . "\n\n";
                foreach ($data as $table => $info) {
                    $full_table = $prefix . $table;
                    $quoted_table = dbview_quote_identifier($full_table);
                    $create = $db->query("SHOW CREATE TABLE $quoted_table")->fetch(PDO::FETCH_ASSOC);
                    $content .= "DROP TABLE IF EXISTS $quoted_table;\n" . $create['Create Table'] . ";\n\n";
                    if (!empty($info['data'])) {
                        foreach ($info['data'] as $row) {
                            $values = array_map(fn($val) => $val === null ? 'NULL' : $db->quote($val), $row);
                            $content .= "INSERT INTO $quoted_table VALUES (" . implode(', ', $values) . ");\n";
                        }
                        $content .= "\n";
                    }
                }
                break;
            default:
                return false;
        }

        if (!file_put_contents($filepath, $content)) {
            cot_log("DBViewStructure: Failed to write full file: $filepath", 'plug');
            return false;
        }

        return $filepath;
    } catch (Exception $e) {
        cot_log("DBViewStructure: Full export error: " . $e->getMessage(), 'plug');
        return false;
    }
}