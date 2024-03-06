ALTER TABLE `zt_metric` ADD COLUMN `alias` varchar(90) NOT NULL DEFAULT '' AFTER `code`;
CREATE INDEX `date` ON zt_metriclib (date) USING BTREE;

UPDATE `zt_chart` SET `createdBy` = 'system' where `createdBy` = 'admin';
UPDATE `zt_pivot` SET `createdBy` = 'system' where `createdBy` = 'admin';
UPDATE `zt_pivot` SET `createdBy` = 'system' where `createdBy` = 'admin';
UPDATE `zt_chart` SET `editedBy` = 'system' where `editedBy` = 'admin';
UPDATE `zt_pivot` SET `editedBy` = 'system' where `editedBy` = 'admin';
UPDATE `zt_pivot` SET `editedBy` = 'system' where `editedBy` = 'admin';

UPDATE zt_project SET lifetime = 'long' where lifetime = 'waterfall';
