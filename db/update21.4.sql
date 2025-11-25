CREATE INDEX `status_createdDate` ON `zt_queue`(`status`, `createdDate`);
CREATE INDEX `cron_createdDate` ON `zt_queue`(`cron`, `createdDate`);
CREATE INDEX `status_deleted` ON `zt_measqueue`(`status`, `deleted`);

ALTER TABLE `zt_action` ADD COLUMN `files` text NULL AFTER `comment`;
ALTER TABLE `zt_actionrecent` ADD COLUMN `files` text NULL AFTER `comment`;

UPDATE `zt_module` SET `path` = CONCAT(',', `path`) WHERE LEFT(`path`, 1) != ',';
UPDATE `zt_module` SET `path` = CONCAT(`path`, ',') WHERE RIGHT(`path`, 1) != ',';

DROP TABLE IF EXISTS `zt_service`;

CREATE TABLE IF NOT EXISTS `zt_extuser` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `account` char(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE `zt_releaserelated` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `release` int(11) unsigned NOT NULL,
  `objectID` int(11) unsigned NOT NULL,
  `objectType` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;
CREATE INDEX `objectID` ON `zt_releaserelated` (`objectID`);
CREATE INDEX `objectType` ON `zt_releaserelated` (`objectType`);
CREATE UNIQUE INDEX `unique` ON `zt_releaserelated` (`release`, `objectID`, `objectType`);

ALTER TABLE `zt_doccontent` ADD COLUMN `addedBy` varchar(30) NOT NULL DEFAULT '' AFTER `type`;
ALTER TABLE `zt_doccontent` ADD COLUMN `addedDate` datetime NULL AFTER `addedBy`;
ALTER TABLE `zt_doccontent` ADD COLUMN `editedBy` varchar(30) NOT NULL DEFAULT '' AFTER `addedDate`;
ALTER TABLE `zt_doccontent` ADD COLUMN `editedDate` datetime NULL AFTER `editedBy`;
ALTER TABLE `zt_doccontent` ADD COLUMN `fromVersion` smallint(6) unsigned NOT NULL DEFAULT 0 AFTER `version`;

INSERT INTO `zt_cron` (`m`, `h`, `dom`, `mon`, `dow`, `command`, `remark`, `type`, `buildin`, `status`) VALUES ('*/5', '*', '*', '*', '*', 'moduleName=upgrade&methodName=ajaxInitReleaseRelated', '更新发布关联数据', 'zentao', 1, 'normal');

UPDATE `zt_workflowaction` SET `layout` = 'side' WHERE `module` = 'caselib' AND `action` = 'editCase';
UPDATE `zt_workflowlayout` SET `position` = 'info' WHERE `module` = 'caselib' AND `action` = 'editCase';
