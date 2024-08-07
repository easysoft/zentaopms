ALTER TABLE `zt_doclib` ADD `parent` mediumint(8) NOT NULL DEFAULT '0' AFTER `vision`;

CREATE TABLE IF NOT EXISTS `zt_duckdbqueue` (
  `object` varchar(255) NOT NULL DEFAULT '',
  `updatedTime` datetime NULL,
  `syncTime` datetime NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE UNIQUE INDEX `object` ON `zt_duckdbqueue`(`object`);

ALTER TABLE `zt_metriclib` ADD COLUMN `repo` char(30) NOT NULL DEFAULT '';
