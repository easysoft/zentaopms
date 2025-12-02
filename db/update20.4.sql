ALTER TABLE `zt_doclib` ADD `parent` mediumint(8) NOT NULL DEFAULT '0' AFTER `vision`;

CREATE TABLE IF NOT EXISTS `zt_duckdbqueue` (
  `object` varchar(255) NOT NULL DEFAULT '',
  `updatedTime` datetime NULL,
  `syncTime` datetime NULL
) ENGINE=InnoDB;
CREATE UNIQUE INDEX `object` ON `zt_duckdbqueue`(`object`);

ALTER TABLE `zt_metriclib` ADD COLUMN `repo` char(30) NOT NULL DEFAULT '';

REPLACE INTO `zt_grouppriv` (`group`, `module`, `method`) SELECT `group`, 'zanode', 'instruction' FROM `zt_grouppriv` WHERE `module` in ('zanode', 'zahost') AND `method` = 'browse';

UPDATE `zt_doc` SET `order` = id;

ALTER TABLE `zt_story` ADD `verifiedDate` datetime NULL AFTER `retractedDate`;
