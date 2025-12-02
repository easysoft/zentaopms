CREATE INDEX `objectType` ON `zt_stakeholder` (`objectType`);

CREATE TABLE IF NOT EXISTS `zt_autocache` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(30) NOT NULL DEFAULT '',
  `fields` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;
CREATE UNIQUE INDEX `cache` ON `zt_autocache` (`code`, `fields`);
REPLACE INTO `zt_workflowaction` (`module`, `action`, `method`, `name`, `type`, `batchMode`, `extensionType`, `open`, `position`, `layout`, `show`, `order`, `buildin`, `role`, `virtual`, `conditions`, `verifications`, `hooks`, `linkages`, `js`, `css`, `toList`, `blocks`, `desc`, `status`, `vision`, `createdBy`, `createdDate`, `editedBy`, `editedDate`) VALUES
('product', 'requirement', 'requirement', '用户需求列表', 'single', 'different', 'none', 'normal', 'browse', 'normal', 'direct', 0, 1, 'buildin', 0, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 'enable', 'rnd', 'admin', '2024-12-16 11:22:30', '', NULL),
('product', 'epic',        'epic',        '业务需求列表', 'single', 'different', 'none', 'normal', 'browse', 'normal', 'direct', 0, 1, 'buildin', 0, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 'enable', 'rnd', 'admin', '2024-12-16 11:22:30', '', NULL);

DELETE FROM `zt_config` WHERE `module` = 'datatable' AND `section` IN ('productBrowseRequirement', 'productBrowseEpic') AND `key` = 'cols';

-- DROP TABLE IF EXISTS `zt_casespec`;
CREATE TABLE IF NOT EXISTS `zt_casespec` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `case` mediumint(9) NOT NULL DEFAULT '0',
  `version` smallint(6) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `precondition` text NULL,
  `files` text NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB;
CREATE UNIQUE INDEX `case` ON `zt_casespec`(`case`,`version`);

ALTER TABLE `zt_taskteam` ADD COLUMN `storyVersion` smallint(6) NOT NULL DEFAULT '1' AFTER `status`;
UPDATE `zt_taskteam` AS t1 LEFT JOIN `zt_task` AS t2 ON t1.`task` = t2.`id` SET t1.`storyVersion` = t2.`storyVersion` WHERE t2.`storyVersion` IS NOT NULL;

ALTER TABLE `zt_searchdict` DROP INDEX `PRIMARY`;
ALTER TABLE `zt_searchdict` ADD UNIQUE `key_value` (`key`, `value`);

ALTER TABLE `zt_trainrecords` DROP INDEX `PRIMARY`;
ALTER TABLE `zt_trainrecords` ADD UNIQUE `object` (`user`, `objectId`, `objectType`);

ALTER TABLE `zt_burn` DROP INDEX `PRIMARY`;
ALTER TABLE `zt_burn` ADD UNIQUE `execution_task` (`execution`,`date`,`task`);

ALTER TABLE `zt_projectproduct` DROP INDEX `PRIMARY`;
ALTER TABLE `zt_projectproduct` ADD UNIQUE `project_product` (`project`, `product`, `branch`);

ALTER TABLE `zt_userview` ADD `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`id`);

INSERT INTO `zt_cron` (`m`, `h`, `dom`, `mon`, `dow`, `command`, `remark`, `type`, `buildin`, `status`, `lastTime`) VALUES
('30', '1', '*', '*', '*', 'moduleName=instance&methodName=cronCleanBackup', 'Devops服务备份清理',   'zentao', 1, 'normal', NULL);

DELETE FROM `zt_grouppriv` WHERE `module` = 'testcase' AND `method` = 'exportfreemind';
INSERT INTO `zt_grouppriv` SELECT `group`, 'testcase', 'exportfreemind' FROM `zt_grouppriv` WHERE `module` = 'testcase' AND `method` = 'exportxmind';

ALTER TABLE `zt_docblock` MODIFY `content` mediumtext NULL;

UPDATE `zt_workflowdatasource` SET `datasource` = 'select `id`,`title` from zt_story where `deleted`=\'0\' and `type`=\'story\''       WHERE `type` = 'sql' AND `buildin` = '1' AND `code` = 'stories';
UPDATE `zt_workflowdatasource` SET `datasource` = 'select `id`,`name` from zt_task where `deleted`=\'0\' and `vision`=\'rnd\''         WHERE `type` = 'sql' AND `buildin` = '1' AND `code` = 'tasks';
UPDATE `zt_workflowdatasource` SET `datasource` = 'select `id`,`name` from zt_task where `deleted`=\'0\' and `vision`=\'rnd\''         WHERE `type` = 'sql' AND `buildin` = '1' AND `code` = 'bugs';
UPDATE `zt_workflowdatasource` SET `datasource` = 'select `id`,`name` from zt_build where `deleted`=\'0\''                             WHERE `type` = 'sql' AND `buildin` = '1' AND `code` = 'builds';
UPDATE `zt_workflowdatasource` SET `datasource` = 'select `id`,`name` from zt_module where `deleted`=\'0\''                            WHERE `type` = 'sql' AND `buildin` = '1' AND `code` = 'modules';
UPDATE `zt_workflowdatasource` SET `datasource` = 'select `id`,`title` from zt_productplan where `deleted`=\'0\''                      WHERE `type` = 'sql' AND `buildin` = '1' AND `code` = 'plans';
UPDATE `zt_workflowdatasource` SET `datasource` = 'select `id`,`title` from zt_case where `deleted`=\'0\''                             WHERE `type` = 'sql' AND `buildin` = '1' AND `code` = 'cases';
UPDATE `zt_workflowdatasource` SET `datasource` = 'select `id`,`name` from zt_task where `deleted`=\'0\' and `vision`=\'lite\''        WHERE `type` = 'sql' AND `buildin` = '1' AND `code` = 'litetasks';
UPDATE `zt_workflowdatasource` SET `datasource` = 'select `id`,`name` from zt_module where `deleted`=\'0\''                            WHERE `type` = 'sql' AND `buildin` = '1' AND `code` = 'litemodules';
UPDATE `zt_workflowdatasource` SET `datasource` = 'select `id`,`title` from zt_feedback where `deleted`=\'0\''                         WHERE `type` = 'sql' AND `buildin` = '1' AND `code` = 'feedbacks';
UPDATE `zt_workflowdatasource` SET `datasource` = 'select `id`,`title` from zt_story where `deleted`=\'0\' and `type`=\'requirement\'' WHERE `type` = 'sql' AND `buildin` = '1' AND `code` = 'requirements';
UPDATE `zt_workflowdatasource` SET `datasource` = 'select `id`,`title` from zt_story where `deleted`=\'0\' and `type`=\'epic\''        WHERE `type` = 'sql' AND `buildin` = '1' AND `code` = 'epics';
