ALTER TABLE `zt_metric` ADD COLUMN `alias` varchar(90) NOT NULL DEFAULT '' AFTER `code`;
CREATE INDEX `date` ON zt_metriclib (date) USING BTREE;
