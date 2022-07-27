ALTER TABLE `zt_mr` CHANGE COLUMN `gitlabID` `hostID`  mediumint(8) UNSIGNED NOT NULL AFTER `id`;
ALTER TABLE `zt_mr` MODIFY COLUMN `sourceProject`  varchar(50) NOT NULL AFTER `hostID`;
ALTER TABLE `zt_mr` MODIFY COLUMN `targetProject`  varchar(50) NOT NULL AFTER `sourceBranch`;
UPDATE `zt_project` SET `status`='closed' WHERE `type` IN ('sprint', 'stage', 'kanban') AND `model`='' AND `status` = 'done';
ALTER TABLE `zt_repo` ADD COLUMN `serviceHost`  varchar(50) NOT NULL AFTER `client`;
ALTER TABLE `zt_repo` ADD COLUMN `serviceProject`  varchar(100) NOT NULL AFTER `serviceHost`;
UPDATE `zt_repo` SET `serviceHost` = `client`, `serviceProject` = `path` WHERE `SCM` = 'Gitlab' AND `client` <> '' AND `path` <> '';
UPDATE `zt_repo` SET `client` = '', `path` = '' WHERE `SCM` = 'Gitlab';

UPDATE `zt_workflowfield` SET `options`='user' WHERE `buildin`='1' AND `options`='8';
INSERT IGNORE INTO `zt_workflowfield` (`module`, `field`, `type`, `length`, `name`, `control`, `expression`, `options`, `default`, `rules`, `placeholder`, `order`, `searchOrder`, `exportOrder`, `canExport`, `canSearch`, `isValue`, `readonly`, `buildin`, `desc`, `createdBy`, `createdDate`, `editedBy`, `editedDate`) VALUES
('testtask',	'execution',	'mediumint',	'8',	'所属执行',	'select',	'',	'44',	'0',	'',	'',	5,	0,	0,	'0',	'1',	'0',	'1',	1,	'',	'',	'2022-03-18 09:16:38',	'',	'0000-00-00 00:00:00'),
('testcase',	'execution',	'mediumint',	'8',	'所属执行',	'select',	'',	'44',	'0',	'',	'',	4,	0,	0,	'0',	'1',	'0',	'1',	1,	'',	'',	'2022-03-18 09:16:38',	'',	'0000-00-00 00:00:00'),
('task',	'execution',	'mediumint',	'8',	'所属执行',	'select',	'',	'44',	'0',	'',	'',	4,	0,	0,	'0',	'1',	'0',	'1',	1,	'',	'',	'2022-03-18 09:16:38',	'',	'0000-00-00 00:00:00'),
('bug',	        'execution',	'mediumint',	'8',	'所属执行',	'select',	'',	'44',	'0',	'',	'',	6,	0,	0,	'0',	'1',	'0',	'1',	1,	'',	'',	'2022-03-18 09:16:38',	'',	'0000-00-00 00:00:00'),
('build',	'execution',	'mediumint',	'8',	'所属执行',	'select',	'',	'44',	'0',	'',	'',	5,	0,	0,	'0',	'1',	'0',	'1',	1,	'',	'',	'2022-03-18 09:16:38',	'',	'0000-00-00 00:00:00');
