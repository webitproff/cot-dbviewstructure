<?php
/* ====================
[BEGIN_COT_EXT]
Code=dbviewstructure
Name=DB Structure Viewer
Description=Просмотр структуры таблиц БД и экспорт в JSON, SQL, CSV, PHP. Поддержка выборочного экспорта, логирования, фильтрации.
Version=3.0.0
Date=July 1Th, 2026
Author=webitproff https://github.com/webitproff/
Copyright=Copyright (c) webitproff 2026 https://github.com/webitproff/cot-dbviewstructure
Auth_guests=R
Lock_guests=12345A
Auth_members=RW
Lock_members=12345A
[END_COT_EXT]

[BEGIN_COT_EXT_CONFIG]
export_path=01:string::plugins/dbviewstructure/export/:
log_enabled=02:radio:0:1:
export_to_browser=03:radio:0:1:
max_rows_per_page=04:select:5,6,7,8,9,10,15,20:10:
pack_to_zip=05:radio:0:1:
[END_COT_EXT_CONFIG]
==================== */

defined('COT_CODE') or die('Wrong URL.');

/**
 * DB Structure Viewer plugin for CMF Cotonti v.1+, PHP v.8.4+, MySQL v.8.0
 * Filename: plugins/dbviewstructure/dbviewstructure.setup.php
 * Purpose: Registers metadata and configuration for the DB Structure Viewer plugin in the Cotonti admin panel.
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
