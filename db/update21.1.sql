CREATE TABLE IF NOT EXISTS `zt_system` (
  `id` MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL DEFAULT '',
  `product` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
  `integrated` ENUM('0','1') NOT NULL DEFAULT '0',
  `latestRelease` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
  `latestDate` DATETIME NULL,
  `children` VARCHAR(255) NOT NULL DEFAULT '',
  `status` ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `desc` mediumtext NULL,
  `createdBy` VARCHAR(30) NOT NULL DEFAULT '',
  `createdDate` DATETIME NULL,
  `editedBy` VARCHAR(30) NOT NULL DEFAULT '',
  `editedDate` DATETIME NULL,
  `deleted` ENUM('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;
CREATE INDEX `idx_product` ON `zt_system`(`product`);
CREATE INDEX `idx_status` ON `zt_system`(`status`);

ALTER TABLE `zt_release` ADD `system` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0' AFTER `name`;
ALTER TABLE `zt_release` ADD `releases` VARCHAR(255) NOT NULL DEFAULT '' AFTER `system`;

ALTER TABLE `zt_build` ADD `system` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0' AFTER `name`;

CREATE INDEX `idx_system` ON `zt_release`(`system`);
CREATE INDEX `idx_system` ON `zt_build`(`system`);

ALTER TABLE `zt_review` ADD `toAuditBy` varchar(30) not NULL default '' AFTER `lastAuditedDate`;
ALTER TABLE `zt_review` ADD `toAuditDate` datetime NULL AFTER `toAuditBy`;

ALTER TABLE `zt_design` ADD `storyVersion` smallint(6) UNSIGNED NOT NULL DEFAULT '1' AFTER `story`;

ALTER TABLE zt_dataview MODIFY `fields` text NULL;
ALTER TABLE zt_dataview MODIFY `objects` text NULL;
ALTER TABLE zt_dataview MODIFY `mode` varchar(50) NOT NULL DEFAULT 'builder';
ALTER TABLE zt_dataview ADD `driver` enum('mysql','duckdb') NOT NULL DEFAULT 'mysql' AFTER `code`;

CREATE TABLE IF NOT EXISTS `zt_pivotspec` (
  `pivot` mediumint(8) NOT NULL,
  `version` varchar(10) NOT NULL,
  `driver` enum('mysql', 'duckdb') NOT NULL default 'mysql',
  `mode` varchar(10) NOT NULL default 'builder',
  `name` text NULL,
  `desc` text NULL,
  `sql` text NULL,
  `fields` text NULL,
  `langs` text NULL,
  `vars` text NULL,
  `objects` text NULL,
  `settings` text NULL,
  `filters` text NULL,
  `createdDate` datetime NULL
) ENGINE=InnoDB;
CREATE UNIQUE INDEX `idx_pivot_version` ON `zt_pivotspec`(`pivot`, `version`);

ALTER TABLE `zt_pivot` ADD `version` varchar(10) NOT NULL DEFAULT '1' AFTER `builtin`;
ALTER TABLE `zt_chart` ADD `version` varchar(10) NOT NULL DEFAULT '1' AFTER `builtin`;
ALTER TABLE `zt_pivotdrill` ADD `version` varchar(10) NOT NULL DEFAULT '1' AFTER `pivot`;
ALTER TABLE `zt_pivot` CHANGE `mode` `mode` varchar(10) NOT NULL DEFAULT 'builder';
ALTER TABLE `zt_pivot` CHANGE `sql` `sql` text NULL;
ALTER TABLE `zt_pivot` CHANGE `fields` `fields` text NULL;
ALTER TABLE `zt_pivot` CHANGE `langs` `langs` text NULL;
ALTER TABLE `zt_pivot` CHANGE `vars` `vars` text NULL;
ALTER TABLE `zt_pivot` CHANGE `objects` `objects` text NULL;
ALTER TABLE `zt_pivot` CHANGE `settings` `settings` text NULL;
ALTER TABLE `zt_pivot` CHANGE `filters` `filters` text NULL;

CREATE TABLE IF NOT EXISTS `zt_mark` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `objectType` varchar(50) NOT NULL DEFAULT '',
  `objectID` mediumint(8) unsigned NOT NULL DEFAULT 0,
  `version` varchar(50) NOT NULL DEFAULT '',
  `account` char(30) NOT NULL DEFAULT '',
  `date` datetime NULL,
  `mark` varchar(50) NOT NULL DEFAULT '',
  `extra` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;
CREATE INDEX `idx_object` ON `zt_mark`(`objectType`,`objectID`);
CREATE INDEX `idx_account` ON `zt_mark`(`account`);

UPDATE `zt_grouppriv` SET `module` = 'cache', `method` = 'setting' WHERE `module` = 'admin' AND `method` = 'cache';
REPLACE INTO `zt_grouppriv` SELECT `group`, 'cache', 'flush' FROM `zt_grouppriv` WHERE `module` = 'cache' AND `method` = 'setting';
REPLACE INTO `zt_grouppriv` SELECT DISTINCT `group`, 'system', 'create' FROM `zt_grouppriv` WHERE `module` IN ('release', 'projectrelease', 'build', 'projectbuild') AND `method` = 'create';

UPDATE `zt_pivot` SET `version` = '1';
UPDATE `zt_pivot` SET `builtin` = '1', `createdDate` = '2009-03-14' WHERE `id` >= 1000 AND `id` <= 1028;
REPLACE INTO `zt_pivotspec` SELECT `id`,`version`,`driver`,`mode`,`name`,`desc`,`sql`,`fields`,`langs`,`vars`,`objects`,`settings`,`filters`,`createdDate` FROM `zt_pivot`;
UPDATE `zt_pivotdrill` SET `version` = '1';

DELETE FROM `zt_cron` WHERE `command` = 'moduleName=misc&methodName=cleanCache';

DELETE FROM `zt_object` WHERE `type` = 'taged' AND `deleted` = '1';

ALTER TABLE `zt_doccontent` ADD `rawContent` longtext DEFAULT NULL AFTER `content`;

CREATE INDEX `AID` ON `zt_relation` (`AType`, `AID`);
CREATE INDEX `BID` ON `zt_relation` (`BType`, `BID`);
