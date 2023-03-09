DELETE FROM `zt_config` WHERE module = 'datatable' AND section = 'executionAll';

UPDATE `zt_task` SET `assignedTo` = '' WHERE `mode` = 'multi' AND `status` != 'done' AND `status` != 'closed';

REPLACE INTO `zt_lang` (`lang`, `module`, `section`, `key`, `value`, `system`, `vision`) SELECT `lang`, `module`, 'scrumClassify', `key`, `value`, `system`, `vision` FROM `zt_lang` WHERE `module` = 'process' and `section` = 'classify' and `system` = '1' ORDER BY id ASC;
REPLACE INTO `zt_lang` (`lang`, `module`, `section`, `key`, `value`, `system`, `vision`) SELECT `lang`, `module`, 'agileplusClassify', `key`, `value`, `system`, `vision` FROM `zt_lang` WHERE `module` = 'process' and `section` = 'classify' and `system` = '1' ORDER BY id ASC;
REPLACE INTO `zt_lang` (`lang`, `module`, `section`, `key`, `value`, `system`, `vision`) SELECT `lang`, `module`, 'waterfallplusClassify', `key`, `value`, `system`, `vision` FROM `zt_lang` WHERE `module` = 'process' and `section` = 'classify' and `system` = '1' ORDER BY id ASC;

UPDATE `zt_project` AS parent INNER JOIN (select `id`,`parent`,`attribute` from `zt_project` where `parent` != 0 and `type` = 'stage') AS child ON parent.`id` = child.`parent` SET parent.`attribute`='mix' where parent.`grade`=1 and parent.`type`='stage' and parent.`attribute` != child.`attribute`;

REPLACE INTO `zt_grouppriv` (SELECT `group`,`module`,'batchChangeStatus' FROM `zt_grouppriv` WHERE `module` = 'execution' AND `method` = 'batchEdit');

ALTER table `zt_stage` ADD `projectType` varchar(255) NOT NULL DEFAULT '' AFTER `type`;
UPDATE `zt_stage` SET `projectType` = 'waterfall' WHERE `projectType` = '';

REPLACE INTO `zt_stage` (`name`,`percent`,`type`, `projectType`, `createdBy`,`createdDate`,`editedBy`,`editedDate`,`deleted`) VALUES
('需求','10','request','waterfallplus','admin','2020-02-08 21:08:30','admin','2020-02-12 13:50:27','0'),
('设计','10','design','waterfallplus','admin','2020-02-08 21:08:30','admin','2020-02-12 13:50:27','0'),
('开发','50','dev','waterfallplus','admin','2020-02-08 21:08:30','admin','2020-02-12 13:50:27','0'),
('测试','15','qa','waterfallplus','admin','2020-02-08 21:08:30','admin','2020-02-12 13:50:27','0'),
('发布','10','release','waterfallplus','admin','2020-02-08 21:08:30','admin','2020-02-12 13:50:27','0'),
('总结评审','5','review','waterfallplus','admin','2020-02-08 21:08:45','admin','2020-02-12 13:50:27','0');

ALTER table `zt_reviewcl` ADD `type` varchar(255) NOT NULL DEFAULT '' AFTER `category`;
UPDATE `zt_reviewcl` SET `type` = 'waterfall' WHERE `type` = '';

UPDATE `zt_activity` SET `order` = `id` * 5 WHERE `order` = '0';

ALTER table `zt_cmcl` ADD `projectType` varchar(255) NOT NULL DEFAULT '' AFTER `type`;
UPDATE `zt_cmcl` SET `projectType` = 'waterfall' WHERE `projectType` = '';

REPLACE INTO `zt_process` (`model`, `name`, `type`, `abbr`, `desc`, `assignedTo`, `status`, `order`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `assignedBy`, `assignedDate`, `deleted`) VALUES
('waterfallplus', '立项管理', 'project', 'PIM', '', '', '', 55, 'admin', '2020-01-09 10:29:55', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0'),
('waterfallplus', '项目规划', 'project', 'PP', '', '', '', 60, 'admin', '2020-01-09 10:31:16', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0'),
('waterfallplus', '项目监控', 'project', 'PMC', '', '', '', 65, 'admin', '2020-01-09 10:31:16', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0'),
('waterfallplus', '风险管理', 'project', 'RSKM', '', '', '', 70, 'admin', '2020-01-09 10:31:16', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0'),
('waterfallplus', '结项管理', 'project', 'PCM', '', '', '', 75, 'admin', '2020-01-09 10:31:16', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0'),
('waterfallplus', '量化项目管理', 'project', 'QPM', '', '', '', 80, 'admin', '2020-01-09 10:31:16', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0'),
('waterfallplus', '需求开发', 'engineering', 'RDM', '', '', '', 85, 'admin', '2020-01-09 13:14:55', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0'),
('waterfallplus', '设计开发', 'engineering', '', '', '', '', 90, 'admin', '2020-01-09 13:14:55', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0'),
('waterfallplus', '实现与测试', 'engineering', 'EMBEDDED', '', '', '', 95, 'admin', '2020-01-09 13:14:55', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0'),
('waterfallplus', '系统测试', 'engineering', 'ST', '', '', '', 100, 'admin', '2020-01-09 13:14:55', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0'),
('waterfallplus', '客户验收', 'engineering', 'CA', '', '', '', 105, 'admin', '2020-01-09 13:14:55', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0'),
('waterfallplus', '质量保证', 'support', 'QA', '', '', '', 110, 'admin', '2020-01-09 13:14:55', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0'),
('waterfallplus', '配置管理', 'support', 'CM', '', '', '', 115, 'admin', '2020-01-09 13:14:55', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0'),
('waterfallplus', '度量分析', 'support', 'MA', '', '', '', 120, 'admin', '2020-01-09 13:14:55', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0'),
('waterfallplus', '原因分析与解决', 'support', 'CAR', '', '', '', 125, 'admin', '2020-01-09 13:14:55', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0'),
('waterfallplus', '决策分析', 'support', 'DAR', '', '', '', 130, 'admin', '2020-01-09 13:14:55', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0'),
('agileplus', '立项管理', 'project', 'PIM', '', '', '', 55, 'admin', '2020-01-09 10:29:55', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0'),
('agileplus', '项目规划', 'project', 'PP', '', '', '', 60, 'admin', '2020-01-09 10:31:16', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0'),
('agileplus', '项目监控', 'project', 'PMC', '', '', '', 65, 'admin', '2020-01-09 10:31:16', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0'),
('agileplus', '风险管理', 'project', 'RSKM', '', '', '', 70, 'admin', '2020-01-09 10:31:16', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0'),
('agileplus', '结项管理', 'project', 'PCM', '', '', '', 75, 'admin', '2020-01-09 10:31:16', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0'),
('agileplus', '量化项目管理', 'project', 'QPM', '', '', '', 80, 'admin', '2020-01-09 10:31:16', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0'),
('agileplus', '需求开发', 'engineering', 'RDM', '', '', '', 85, 'admin', '2020-01-09 13:14:55', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0'),
('agileplus', '设计开发', 'engineering', '', '', '', '', 90, 'admin', '2020-01-09 13:14:55', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0'),
('agileplus', '实现与测试', 'engineering', 'EMBEDDED', '', '', '', 95, 'admin', '2020-01-09 13:14:55', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0'),
('agileplus', '系统测试', 'engineering', 'ST', '', '', '', 100, 'admin', '2020-01-09 13:14:55', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0'),
('agileplus', '客户验收', 'engineering', 'CA', '', '', '', 105, 'admin', '2020-01-09 13:14:55', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0'),
('agileplus', '质量保证', 'support', 'QA', '', '', '', 110, 'admin', '2020-01-09 13:14:55', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0'),
('agileplus', '配置管理', 'support', 'CM', '', '', '', 115, 'admin', '2020-01-09 13:14:55', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0'),
('agileplus', '度量分析', 'support', 'MA', '', '', '', 120, 'admin', '2020-01-09 13:14:55', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0'),
('agileplus', '原因分析与解决', 'support', 'CAR', '', '', '', 125, 'admin', '2020-01-09 13:14:55', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0'),
('agileplus', '决策分析', 'support', 'DAR', '', '', '', 130, 'admin', '2020-01-09 13:14:55', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0');

UPDATE `zt_chart` SET `sql` = 'SELECT tt.join as `year`, count(1) as number, tt.name from (\r\nselect \r\nt2.name, YEAR(t1.join) as `join`\r\nfrom zt_team t1 \r\nRIGHT JOIN zt_project t2 on t2.id = t1.root\r\nRIGHT JOIN zt_user t3 on t3.account = t1.account\r\nWHERE t1.type = \'project\'\r\nAND t2.deleted = \'0\'\r\n) tt\r\nGROUP BY tt.`name`, tt.join\r\nORDER BY tt.join, number desc, tt.name' WHERE `id` = '1097';
UPDATE `zt_chart` SET `sql` = 'SELECT tt.join as `year`, count(1) as number, tt.setName from (\r\nselect \r\nYEAR(t1.join) as `join`, t4.name as setName \r\nfrom zt_team t1 \r\nRIGHT JOIN zt_project t2 on t2.id = t1.root\r\nLEFT JOIN zt_project t4 on FIND_IN_SET(t4.id,t2.path) and t4.grade = 1\r\nRIGHT JOIN zt_user t3 on t3.account = t1.account\r\nWHERE t1.type = \'project\'\r\nAND t2.deleted = \'0\'\r\nAND t3.deleted = \'0\'\r\n) tt\r\nGROUP BY tt.setName, tt.join\r\nORDER BY tt.join, number desc, tt.setName' WHERE `id` = '1086';

INSERT IGNORE INTO `zt_config` (`vision`, `owner`, `module`, `key`, `value`) VALUES ('', 'system', 'common', 'setCode', '1');
