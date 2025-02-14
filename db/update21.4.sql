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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
