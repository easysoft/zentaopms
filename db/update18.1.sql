DELETE FROM `zt_config` WHERE module = 'datatable' AND section = 'executionAll';

update `zt_task` set `assignedTo` = '' where `mode` = 'multi' and `status` != 'done' and `status` != 'closed';

REPLACE INTO `zt_lang` (`lang`, `module`, `section`, `key`, `value`, `system`, `vision`) SELECT `lang`, `module`, 'scrumClassify', `key`, `value`, `system`, `vision` FROM `zt_lang` WHERE `module` = 'process' and `section` = 'classify' ORDER BY id ASC;
REPLACE INTO `zt_lang` (`lang`, `module`, `section`, `key`, `value`, `system`, `vision`) SELECT `lang`, `module`, 'agileplusClassify', `key`, `value`, `system`, `vision` FROM `zt_lang` WHERE `module` = 'process' and `section` = 'classify' ORDER BY id ASC;
REPLACE INTO `zt_lang` (`lang`, `module`, `section`, `key`, `value`, `system`, `vision`) SELECT `lang`, `module`, 'waterfallplusClassify', `key`, `value`, `system`, `vision` FROM `zt_lang` WHERE `module` = 'process' and `section` = 'classify' ORDER BY id ASC;

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
