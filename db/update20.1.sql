ALTER TABLE `zt_pivot` ADD `driver` enum('mysql', 'duckdb') not NULL default 'mysql' AFTER `code`;
ALTER TABLE `zt_chart` ADD `driver` enum('mysql', 'duckdb') not NULL default 'mysql' AFTER `code`;
