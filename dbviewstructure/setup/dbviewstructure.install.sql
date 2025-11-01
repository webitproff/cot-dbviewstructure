/**
 * DB Structure Viewer plugin for CMF Cotonti Siena v.0.9.26, PHP v.8.4+, MySQL v.8.0
 * Filename: dbviewstructure.install.sql
 * Purpose: Creates the database table for storing export logs with full indexing and compatibility.
 * Date: 2025-11-01
 * @package dbviewstructure
 * @version 2.0.0
 * @author webitproff https://github.com/webitproff
 * @copyright Copyright (c) webitproff 2025
 * @license BSD
 */

CREATE TABLE IF NOT EXISTS `cot_dbviewstructure_logs` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `filename` VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `format` VARCHAR(10) COLLATE utf8mb4_unicode_ci NOT NULL,
    `tables_count` INT NOT NULL,
    `with_data` TINYINT(1) DEFAULT 0,
    `created_at` INT UNSIGNED NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    KEY `idx_filename` (`filename`),
    KEY `idx_format` (`format`),
    KEY `idx_created_at` (`created_at`),
    KEY `idx_with_data` (`with_data`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;