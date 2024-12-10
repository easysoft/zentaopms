ALTER TABLE `zt_workflowfield` MODIFY `placeholder` VARCHAR(255) NOT NULL DEFAULT '';

ALTER TABLE zt_dataview MODIFY `fields` text NULL;
ALTER TABLE zt_dataview MODIFY `objects` text NULL;
ALTER TABLE zt_dataview MODIFY `mode` varchar(50) NOT NULL DEFAULT 'builder';
ALTER TABLE zt_dataview ADD `driver` enum('mysql','duckdb') NOT NULL DEFAULT 'mysql' AFTER `code`;

ALTER TABLE `zt_doccontent` ADD `html` longtext DEFAULT NULL AFTER `content`;
ALTER TABLE `zt_doccontent` ADD `rawContent` longtext DEFAULT NULL AFTER `content`;
