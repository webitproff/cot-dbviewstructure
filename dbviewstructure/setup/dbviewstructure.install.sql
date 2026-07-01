/**
 * DB Structure Viewer plugin for CMF Cotonti v.1+, PHP v.8.4+, MySQL v.8.0
 * Filename: plugins/dbviewstructure/setup/dbviewstructure.install.sql
 * Purpose: Creates the database table for storing export logs with full indexing and compatibility.
 * Date: 01 July 2026 
 * 
 * Source: https://github.com/webitproff/cot-dbviewstructure
 * Page in Cotonti Marketplace: https://abuyfile.com/ru/market/cotonti/plugs/cot-plug-db-view-structure
 * 
 * package dbviewstructure
 * version 3.0.0
 * author webitproff
 * copyright Copyright (c) webitproff 2026 https://github.com/webitproff
 * license BSD
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
