<?php
/**
 * [BEGIN_COT_EXT]
 * Hooks=global
 * [END_COT_EXT]
 */
 
/**
 * DB Structure Viewer plugin for Cotonti Siena v.0.9.26, PHP 8.4+, MySQL 8.0+
 * Filename: dbviewstructure.global.php
 * Purpose: Connect to hook "global" in Cotonti Core. Here is Required for Administration button after update plugin, else Administration button may be lost.
 * Date: 2026-02-02
 * @package dbviewstructure
 * @version 2.0.0
 * @author webitproff https://github.com/webitproff/cot-dbviewstructure
 * @copyright Copyright (c) webitproff 2025
 * @license BSD
 */
 


defined('COT_CODE') or die('Wrong URL.');

require_once cot_langfile('dbviewstructure', 'plug');
