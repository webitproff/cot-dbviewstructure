<?php
/**
 * DB Structure Viewer plugin for Cotonti v.1+, PHP 8.4+, MySQL 8.0+
 * Filename: plugins/dbviewstructure/inc/dbviewstructure.functions.php
 * Purpose: Core database functions
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
/*             case 'csv':
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
                break; */
			case 'csv':
				$content = '';
				foreach ($data as $table => $info) {
					if (empty($info['data'])) continue;

					// Заголовки БЕЗ имени таблицы
					$headers = array_map(fn($f) => $f['name'], $info['fields']);
					$content .= '"' . implode('","', $headers) . "\"\n";

					foreach ($info['data'] as $row) {
						$row_csv = array_map(
							fn($v) => str_replace('"', '""', $v ?? ''),
							array_values($row) // гарантируем порядок без ключей
						);
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

/**
 * Получение всех таблиц с префиксом cot_ (для селектора)
 * @return array
 */
function dbview_get_all_tables_raw()
{
    $prefix = Cot::$db_x ?? 'cot_';
    $tables = [];
    $result = Cot::$db->query("SHOW TABLES LIKE ?", ["$prefix%"])->fetchAll(PDO::FETCH_COLUMN);
    foreach ($result as $fullName) {
        $tables[] = [
            'full'  => $fullName,
            'short' => str_replace($prefix, '', $fullName)
        ];
    }
    return $tables;
}

/**
 * Получение списка полей таблицы (для зависимого селектора)
 * @param string $tableName Полное имя таблицы (с префиксом)
 * @return array
 */
function dbview_get_table_columns($tableName)
{
    $quoted = dbview_quote_identifier($tableName);
    try {
        $cols = Cot::$db->query("SHOW COLUMNS FROM $quoted")->fetchAll(PDO::FETCH_ASSOC);
        return array_column($cols, 'Field');
    } catch (Exception $e) {
        cot_log("DBViewStructure: Error fetching columns for $tableName: " . $e->getMessage(), 'plug');
        return [];
    }
}
/**
 * Комбинированный экспорт: CSV + опциональный ZIP (МОНОЛИТНАЯ ФУНКЦИЯ)
 * 
 * Делает всё одной функцией:
 * - определяет JOIN-связи для присоединяемых таблиц
 * - строит SQL-запрос с GROUP BY, подзапросами для картинок (first_image / rest_images)
 * - выполняет запрос и получает данные
 * - записывает CSV с BOM для Excel, заменяя относительные пути картинок на абсолютные
 * - при включённой настройке `pack_to_zip` упаковывает полученный CSV в ZIP-архив (без повреждений)
 * - возвращает путь к итоговому файлу (ZIP или CSV) либо false при ошибке
 *
 * @param string $baseTable   Полное имя базовой таблицы (например, x92374_market)
 * @param string $baseIdField Имя ID-поля в базовой таблице (например, fieldmrkt_id)
 * @param array  $columnDefs  Массив определений колонок из формы
 * @return string|false       Путь к файлу или false при ошибке
 */
function dbview_export_combined_csv($baseTable, $baseIdField, array $columnDefs)
{
	global $cfg;
    // === Настройки путей и переменные ===
    $exportDir = Cot::$cfg['plugin']['dbviewstructure']['export_path'];
    $filename  = 'db_export_combined_' . cot_date('Ymd_His', Cot::$sys['now']);
    $csvFile   = rtrim($exportDir, '/') . '/' . $filename . '.csv';   // полный путь к CSV
    $zipFile   = rtrim($exportDir, '/') . '/' . $filename . '.zip';  // полный путь к ZIP

    // Берём ПРАВИЛЬНЫЙ абсолютный URL сайта.
    // В Cotonti нет Cot::$sys['site_url'], зато есть Cot::$sys['abs_url'].
    // Он формируется в common.php и содержит, например, "https://abuyfile.com/"
    $siteUrl = Cot::$sys['abs_url'] ?? $cfg['mainurl'];
    $siteUrl = rtrim($siteUrl, '/');                     // убираем завершающий слеш, если есть
    cot_dbviewstructure_log("DEBUG: siteUrl for images = " . $siteUrl); // пишем в лог для проверки

    // Проверяем, нужно ли упаковывать в ZIP (настройка плагина)
    $packToZip = (bool) Cot::$cfg['plugin']['dbviewstructure']['pack_to_zip'];

    // ══════ ШАГ 1. Сбор уникальных JOIN-связей для каждой присоединяемой таблицы ══════
    // $joinedTables хранит: [полное_имя_таблицы => [алиас, условие_JOIN]]
    $joinedTables = [];
    $aliasCounter = 0;

    foreach ($columnDefs as $def) {
        $tbl = $def['table'];
        // Пропускаем саму базовую таблицу, для неё JOIN не нужен
        if ($tbl !== $baseTable && !isset($joinedTables[$tbl])) {
            $aliasCounter++;
            $alias = 'j' . $aliasCounter;   // псевдоним для SQL-запроса (j1, j2, ...)

            // Определяем условие JOIN: либо ручное (manual), либо авто
            if (!empty($def['join_mode']) && $def['join_mode'] === 'manual' && !empty($def['join_field'])) {
                $joinCondition = dbview_detect_join_condition_manual_selection($baseTable, $baseIdField, $tbl, $alias, $def['join_field']);
            } else {
                $joinCondition = dbview_detect_join_condition($baseTable, $baseIdField, $tbl, $alias);
            }

            // Если не удалось определить связь – прерываем экспорт
            if ($joinCondition === false) {
                cot_dbviewstructure_log("ERROR: Cannot detect JOIN condition for $tbl");
                return false;
            }

            $joinedTables[$tbl] = [$alias, $joinCondition];
        }
    }

    // ══════ ШАГ 2. Формирование SELECT-частей запроса и заголовков CSV ══════
    $quotedBase  = dbview_quote_identifier($baseTable);   // экранированное имя базовой таблицы
    $selectParts = [];                                    // части SELECT
    $headers     = [];                                    // названия колонок CSV

    foreach ($columnDefs as $def) {
        $tbl   = $def['table'];        // полное имя таблицы
        $field = $def['field'];        // нужное поле
        $agg   = $def['aggregate'] ?? null;   // тип агрегации (first_image, rest_images, all_images или пусто)

        if ($tbl === $baseTable) {
            // Поле из самой базовой таблицы – просто добавляем его с префиксом
            $selectParts[] = "$quotedBase." . dbview_quote_identifier($field);
        } else {
            // Поле из присоединяемой таблицы
            list($alias, $cond) = $joinedTables[$tbl];
            $qField = dbview_quote_identifier($field);   // экранированное имя поля
            $qAlias = dbview_quote_identifier($alias);   // экранированный алиас
            $qTbl   = dbview_quote_identifier($tbl);     // экранированное имя таблицы

            if ($agg === 'first_image') {
                // Подзапрос для получения первой картинки (MIN по att_order и att_id)
                $selectParts[] = "(SELECT $qAlias.$qField 
                                   FROM $qTbl AS $qAlias 
                                   WHERE $cond 
                                   ORDER BY $qAlias.att_order, $qAlias.att_id 
                                   LIMIT 1)";
            } elseif ($agg === 'rest_images') {
                // Подзапрос для получения остальных картинок, кроме первой (через GROUP_CONCAT)
                // В подзапросе NOT IN заменяем псевдоним на 'sub_inner' для корректной работы
                $innerCond = str_replace("`$alias`.", "`sub_inner`.", $cond);
                $selectParts[] = "(SELECT GROUP_CONCAT($qAlias.$qField SEPARATOR ', ') 
                                   FROM $qTbl AS $qAlias 
                                   WHERE $cond 
                                     AND $qAlias.att_id NOT IN (
                                         SELECT MIN(sub_inner.att_id) 
                                         FROM $qTbl AS sub_inner 
                                         WHERE $innerCond
                                     ))";
            } elseif ($agg === 'all_images') {
                // Все картинки (включая первую) через запятую – просто GROUP_CONCAT без фильтрации
                $selectParts[] = "(SELECT GROUP_CONCAT($qAlias.$qField SEPARATOR ', ') 
                                   FROM $qTbl AS $qAlias 
                                   WHERE $cond)";
            } else {
                // Обычное поле – просто берём его через алиас
                $selectParts[] = "$qAlias.$qField";
            }
        }
        $headers[] = $def['csv_header'];   // сохраняем заголовок для CSV
    }

    // ══════ ШАГ 3. Сборка JOIN-части SQL-запроса ══════
    $joinClauses = '';
    foreach ($joinedTables as $tbl => list($alias, $cond)) {
        $joinClauses .= " LEFT JOIN " . dbview_quote_identifier($tbl) . " AS " . dbview_quote_identifier($alias) . " ON $cond";
    }

    // ══════ ШАГ 4. Выполнение SQL-запроса ══════
    $sql = "SELECT " . implode(', ', $selectParts) . 
           " FROM $quotedBase" . 
           $joinClauses . 
           " GROUP BY $quotedBase.$baseIdField";   // группируем по ID базовой таблицы, чтобы не было дублей

    cot_dbviewstructure_log("SQL: $sql");   // пишем SQL в лог для отладки

    try {
        $rows = Cot::$db->query($sql)->fetchAll(PDO::FETCH_NUM);   // получаем все строки как нумерованные массивы
    } catch (Exception $e) {
        cot_dbviewstructure_log("Query error: " . $e->getMessage());
        return false;
    }

    // ══════ ШАГ 5. Запись CSV-файла с BOM и преобразованием относительных путей в абсолютные ══════
    $fp = fopen($csvFile, 'w');
    if (!$fp) {
        cot_dbviewstructure_log("Cannot open file: $csvFile");
        return false;
    }

    // Записываем BOM (Byte Order Mark), чтобы Excel нормально открывал UTF-8
    fwrite($fp, "\xEF\xBB\xBF");
    // Заголовки колонок
    fputcsv($fp, $headers, ',', '"', '\\');

    // Обрабатываем каждую строку данных
    foreach ($rows as $row) {
        for ($i = 0; $i < count($row); $i++) {
            $val = $row[$i];
            if (empty($val)) continue;   // пустые ячейки не трогаем

            // Пути к картинкам могут быть перечислены через ", " (результат GROUP_CONCAT)
            $parts = explode(', ', $val);
            foreach ($parts as &$part) {
                $part = ltrim($part, '/');                // убираем ведущий слеш, если он есть
                if (strpos($part, 'attacher/') === 0) {   // путь начинается с "attacher/"
                    $part = $siteUrl . '/' . $part;        // делаем абсолютный: https://.../attacher/...
                }
            }
            unset($part);
            // Собираем обратно строку
            $row[$i] = implode(', ', $parts);
        }
        fputcsv($fp, $row, ',', '"', '\\');
    }
    fclose($fp);

    // Если упаковка в ZIP не требуется или нет ZipArchive, просто возвращаем CSV
    if (!$packToZip || !class_exists('ZipArchive')) {
        return $csvFile;
    }

    // ══════ ШАГ 6. Упаковка CSV в ZIP (с гарантированной целостностью) ══════
    // Убеждаемся, что файл полностью записан и виден системе
    clearstatcache(true, $csvFile);

    $zip = new ZipArchive();
    $res = $zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);
    if ($res !== true) {
        cot_dbviewstructure_log("ZIP ERROR: Cannot create archive, code: $res");
        return $csvFile;   // если ZIP не создался, отдаём хоть CSV
    }

    // Самый надёжный способ добавить файл – addFile(), особенно на Windows
    if (!$zip->addFile($csvFile, basename($csvFile))) {
        cot_dbviewstructure_log("ZIP ERROR: Failed to add file to archive");
        $zip->close();
        @unlink($zipFile);   // удаляем битый архив
        return $csvFile;
    }

    // Закрываем архив и проверяем успешность
    if (!$zip->close()) {
        cot_dbviewstructure_log("ZIP ERROR: Failed to finalize archive");
        @unlink($zipFile);
        return $csvFile;
    }

    // Финальная проверка: существует ли архив и не пустой ли он
    clearstatcache(true, $zipFile);
    if (!file_exists($zipFile) || filesize($zipFile) === 0) {
        cot_dbviewstructure_log("ZIP ERROR: Archive empty or not created");
        @unlink($zipFile);
        return $csvFile;
    }

    cot_dbviewstructure_log("ZIP created: $zipFile, size: " . filesize($zipFile));
    // CSV остаётся на сервере (мы его не удаляем)
    return $zipFile;
}
/**
 * Ручное определение условия JOIN.
 * Поле в присоединяемой таблице указывается вручную в интерфейсе.
 * Для attacher — всегда особая логика, ручной ключ игнорируется.
 *
 * @param string $baseTable   Полное имя базовой таблицы
 * @param string $baseIdField Имя ID-поля в базовой таблице
 * @param string $joinTable   Полное имя присоединяемой таблицы
 * @param string $joinAlias   Алиас присоединяемой таблицы
 * @param string $joinField   Поле в присоединяемой таблице, выбранное вручную
 * @return string|false
 */
function dbview_detect_join_condition_manual_selection($baseTable, $baseIdField, $joinTable, $joinAlias, $joinField)
{
    $quotedBase  = dbview_quote_identifier($baseTable);
    $quotedAlias = dbview_quote_identifier($joinAlias);
    $prefix      = Cot::$db_x ?? 'cot_';
    $shortBase   = str_replace($prefix, '', $baseTable);

    if ($joinTable === $prefix . 'attacher') {
        cot_dbviewstructure_log("JOIN MANUAL: attacher special case for {$shortBase}");
        return "{$quotedAlias}.att_item = {$quotedBase}.{$baseIdField} 
                AND {$quotedAlias}.att_area = " . Cot::$db->quote($shortBase);
    }

    $cols = dbview_get_table_columns($joinTable);
    if (in_array($joinField, $cols)) {
        $quotedCol = dbview_quote_identifier($joinField);
        cot_dbviewstructure_log("JOIN MANUAL: {$joinTable}.{$joinField} = {$baseTable}.{$baseIdField}");
        return "{$quotedAlias}.{$quotedCol} = {$quotedBase}.{$baseIdField}";
    }

    cot_dbviewstructure_log("JOIN MANUAL ERROR: field {$joinField} not found in {$joinTable}");
    return false;
}
/**
 * Автоопределение условия JOIN между базовой и присоединяемой таблицей
 * 
 * Алгоритм поиска поля для JOIN (по порядку):
 * 1. Если joinTable = cot_attacher — особая логика (att_item + att_area)
 * 2. Ищем точное совпадение имени поля с baseIdField
 * 3. Ищем поле заканчивающееся на _id или _item, содержащее имя базовой таблицы
 * 4. Fallback: любое поле заканчивающееся на _id
 * 5. Если ничего не найдено — возвращаем false (экспорт прервётся с ошибкой)
 *
 * @param string $baseTable   Полное имя базовой таблицы (например x92374_market)
 * @param string $baseIdField Имя ID-поля в базовой таблице (например fieldmrkt_id)
 * @param string $joinTable   Полное имя присоединяемой таблицы (например x92374_i18n4marketpro_pages)
 * @param string $joinAlias   Алиас присоединяемой таблицы в SQL-запросе (j1, j2...)
 *
 * @return string|false Условие JOIN (без слова ON) или false если связь не найдена
 */
function dbview_detect_join_condition($baseTable, $baseIdField, $joinTable, $joinAlias)
{
    $quotedBase  = dbview_quote_identifier($baseTable);
    $quotedAlias = dbview_quote_identifier($joinAlias);
    $prefix = Cot::$db_x ?? 'cot_';
    $shortBase = str_replace($prefix, '', $baseTable);

    // Шаг 1: таблица attacher — всегда связь через att_item + att_area
    if ($joinTable === $prefix . 'attacher') {
        cot_dbviewstructure_log("JOIN: attacher special case for {$shortBase}");
        return "{$quotedAlias}.att_item = {$quotedBase}.{$baseIdField} 
                AND {$quotedAlias}.att_area = " . Cot::$db->quote($shortBase);
    }

    $cols = dbview_get_table_columns($joinTable);

    // Шаг 2: точное совпадение имени поля с baseIdField
    foreach ($cols as $col) {
        if (strtolower($col) === strtolower($baseIdField)) {
            $quotedCol = dbview_quote_identifier($col);
            cot_dbviewstructure_log("JOIN: exact match {$col} = {$baseIdField}");
            return "{$quotedAlias}.{$quotedCol} = {$quotedBase}.{$baseIdField}";
        }
    }

    // Шаг 3: поле _id или _item, содержащее имя базовой таблицы
    foreach ($cols as $col) {
        $colLower = strtolower($col);
        if (preg_match('/_(id|item)$/i', $col) && strpos($colLower, strtolower($shortBase)) !== false) {
            $quotedCol = dbview_quote_identifier($col);
            cot_dbviewstructure_log("JOIN: name match {$col} contains {$shortBase}");
            return "{$quotedAlias}.{$quotedCol} = {$quotedBase}.{$baseIdField}";
        }
    }

    // Шаг 4: fallback — любое поле заканчивающееся на _id
    foreach ($cols as $col) {
        if (preg_match('/_id$/i', $col)) {
            $quotedCol = dbview_quote_identifier($col);
            cot_dbviewstructure_log("JOIN: fallback {$col} (first _id field found)");
            return "{$quotedAlias}.{$quotedCol} = {$quotedBase}.{$baseIdField}";
        }
    }

    // Шаг 5: ничего не подошло
    cot_dbviewstructure_log("JOIN ERROR: no suitable field in {$joinTable}. Columns: " . implode(', ', $cols));
    return false;
}
/**
 * Логирование в файл для dbviewstructure
 */
function cot_dbviewstructure_log(string $message): void
{
    global $cfg;
    if (empty($cfg['plugin']['dbviewstructure']['log_enabled'])) {
        return;
    }
    $logFile = $cfg['plugins_dir'] . '/dbviewstructure/logs/export.log';
    $logDir = dirname($logFile);
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
}
