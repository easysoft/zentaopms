ALTER TABLE `zt_doc` ADD `cycle` char(10) NOT NULL DEFAULT '' AFTER `chapterType`;
ALTER TABLE `zt_doc` ADD `objects` text NULL AFTER `templateDesc`;
ALTER TABLE `zt_doc` ADD `cycleConfig` text NULL AFTER `cycle`;
ALTER TABLE `zt_doc` ADD `weeklyDate` char(8) NOT NULL DEFAULT '' AFTER `collects`;

CREATE INDEX `templateType` ON `zt_doc`(`templateType`);

DELETE FROM `zt_cron` WHERE `command`='moduleName=weekly&methodName=computeWeekly' AND `type`='zentao';
DELETE FROM `zt_cron` WHERE `command`='moduleName=weekly&methodName=createCycleReport' AND `type`='zentao';
INSERT INTO `zt_cron` (`m`, `h`, `dom`, `mon`, `dow`, `command`, `remark`, `type`, `buildin`, `status`) VALUES('1','0','*','*','*','moduleName=weekly&methodName=createCycleReport','定时生成报告','zentao',1,'normal');

UPDATE `zt_ai_miniprogram` SET `model` = 0 WHERE `model` IS NULL;
UPDATE `zt_ai_prompt` SET `model` = 0 WHERE `model` IS NULL;
UPDATE `zt_ai_promptrole` SET `model` = 0 WHERE `model` IS NULL;

ALTER TABLE `zt_ai_miniprogram` MODIFY `model` varchar(255) NOT NULL DEFAULT '';
ALTER TABLE `zt_ai_prompt` MODIFY `model` varchar(255) NOT NULL DEFAULT '';
ALTER TABLE `zt_ai_promptrole` MODIFY `model` varchar(255) NOT NULL DEFAULT '';

UPDATE `zt_ai_miniprogram` SET `model` = '' WHERE `model` = '0';
UPDATE `zt_ai_prompt` SET `model` = '' WHERE `model` = '0';
UPDATE `zt_ai_promptrole` SET `model` = '' WHERE `model` = '0';

CREATE TABLE IF NOT EXISTS `zt_ops_space` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `owner` varchar(30) NOT NULL DEFAULT '',
  `acl` varchar(30) NOT NULL DEFAULT 'open',
  `desc` TEXT NULL,
  `createdBy` varchar(30) NOT NULL DEFAULT '',
  `createdDate` datetime NULL,
  `updatedBy` varchar(30) NOT NULL DEFAULT '',
  `updatedDate`datetime NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `zt_ops_spaceuser` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `space` smallint(5) unsigned NOT NULL default '0',
  `account` char(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `account` (`account`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `zt_repo` ADD `space` smallint(5) UNSIGNED NOT NULL DEFAULT 0 AFTER `id`;
ALTER TABLE `zt_artifactrepo` ADD `space` smallint(5) UNSIGNED NOT NULL DEFAULT 0 AFTER `id`;

REPLACE INTO `zt_lang` (`lang`, `module`, `section`, `key`, `value`, `system`, `vision`) VALUES
('all',	  'weekly', 'categoryList', 'month',    '月报',       '1', 'rnd'),
('all',	  'weekly', 'categoryList', 'week',     '周报',       '1', 'rnd'),
('all',	  'weekly', 'categoryList', 'day',      '日报',       '1', 'rnd'),
('all',	  'weekly', 'categoryList', 'milestone','里程碑报告', '1', 'rnd');

ALTER TABLE `zt_doc`   CHANGE `lib` `lib` mediumint(8) unsigned NOT NULL DEFAULT '0';
ALTER TABLE `zt_risk`  CHANGE `project` `project` mediumint(8) unsigned NOT NULL DEFAULT '0';
ALTER TABLE `zt_issue` CHANGE `project` `project` mediumint(8) unsigned NOT NULL DEFAULT '0';
