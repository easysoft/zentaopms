ALTER TABLE zt_dataview MODIFY `fields` text NULL;
ALTER TABLE zt_dataview MODIFY `objects` text NULL;
ALTER TABLE zt_dataview MODIFY `mode` varchar(50) NOT NULL DEFAULT 'builder';
ALTER TABLE zt_dataview ADD `driver` enum('mysql','duckdb') NOT NULL DEFAULT 'mysql' AFTER `code`;
