<?php
/* ====================
[BEGIN_COT_EXT]
Code=dbviewstructure
Name=DB Structure Viewer
Category=tools
Description=Просмотр структуры таблиц БД и экспорт в JSON, SQL, CSV, PHP. Поддержка выборочного экспорта, логирования, фильтрации.
Version=2.0.0
Date=2025-11-01
Author=webitproff https://github.com/webitproff/
Copyright=Copyright (c) webitproff 2025 https://github.com/webitproff/cot-dbviewstructure
SQL=dbviewstructure.install.sql
UninstallSQL=dbviewstructure.uninstall.sql
Auth_guests=R
Lock_guests=12345A
Auth_members=RW
Lock_members=12345A
Hooks=tools
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
 * DB Structure Viewer plugin for CMF Cotonti Siena v.0.9.26, PHP v.8.4+, MySQL v.8.0
 * Filename: dbviewstructure.setup.php
 * Purpose: Registers metadata and configuration for the DB Structure Viewer plugin in the Cotonti admin panel.
 * Date: 2025-11-01
 * @package dbviewstructure
 * @version 2.0.0
 * @author webitproff
 * @copyright Copyright (c) webitproff 2025 https://github.com/webitproff/cot-dbviewstructure
 * @license BSD
 */