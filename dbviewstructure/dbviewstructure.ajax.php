<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=ajax
[END_COT_EXT]
==================== */

/**
 * DB Structure Viewer plugin for Cotonti Siena v.0.9.26
 * Filename: plugins/dbviewstructure/dbviewstructure.ajax.php
 * Purpose: AJAX handler for combined export column fields
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

// Проверяем авторизацию
if (!cot_auth('plug', 'dbviewstructure', 'A')) {
    cot_sendheaders('application/json', '403 Forbidden');
    echo json_encode(['error' => 'Access denied']);
    exit;
}

$a = cot_import('a', 'G', 'ALP');

if ($a === 'get_columns') {
    $table = cot_import('table', 'G', 'TXT');
    $prefix = Cot::$db_x ?? 'cot_';

    // Защита: только таблицы с префиксом Cotonti
    if (empty($table) || strpos($table, $prefix) !== 0) {
        cot_sendheaders('application/json', '400 Bad Request');
        echo json_encode(['error' => 'Invalid table name']);
        exit;
    }

    // Проверяем существование таблицы
    try {
        $exists = Cot::$db->query("SHOW TABLES LIKE ?", [$table])->fetchColumn();
        if (!$exists) {
            cot_sendheaders('application/json', '404 Not Found');
            echo json_encode(['error' => 'Table not found']);
            exit;
        }
    } catch (Exception $e) {
        cot_sendheaders('application/json', '500 Internal Server Error');
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }

    $columns = dbview_get_table_columns($table);

    // Отправляем JSON
    ob_clean();
    cot_sendheaders('application/json', '200 OK');
    echo json_encode($columns);
    exit;
}

// Если action не распознан
ob_clean();
cot_sendheaders('application/json', '400 Bad Request');
echo json_encode(['error' => 'Invalid action']);
exit;