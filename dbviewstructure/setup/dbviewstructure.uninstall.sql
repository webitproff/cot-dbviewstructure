/**
 * DB Structure Viewer plugin for CMF Cotonti Siena v.0.9.26
 * Filename: dbviewstructure.uninstall.sql
 * Purpose: Removes the export logs table.
 * Date: 2025-10-30
 * @package dbviewstructure
 * @version 1.1.2
 * @author webitproff
 * @copyright Copyright (c) webitproff 2025
 * @license BSD
 */
DROP TABLE IF EXISTS `cot_dbviewstructure_logs`;