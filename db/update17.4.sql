ALTER TABLE `zt_task` ADD `order` mediumint(8) NOT NULL DEFAULT 0 AFTER `activatedDate`;
ALTER TABLE `zt_task` ADD INDEX `order` (`order`);
ALTER TABLE `zt_story` ADD COLUMN `linkRequirements` varchar(255) NOT NULL AFTER `linkStories`;

ALTER TABLE `zt_productplan` ADD `createdBy` varchar(30) NOT NULL AFTER `closedReason`;
ALTER TABLE `zt_productplan` ADD `createdDate` datetime NOT NULL AFTER `createdBy`;

ALTER TABLE `zt_release` ADD `createdBy` varchar(30) NOT NULL AFTER `subStatus`;
ALTER TABLE `zt_release` ADD `createdDate` datetime NOT NULL AFTER `createdBy`;

ALTER TABLE `zt_testtask` ADD `createdBy` varchar(30) NOT NULL AFTER `subStatus`;
ALTER TABLE `zt_testtask` ADD `createdDate` datetime NOT NULL AFTER `createdBy`;

ALTER TABLE `zt_build` ADD `createdBy` varchar(30) NOT NULL AFTER `desc`;
ALTER TABLE `zt_build` ADD `createdDate` datetime NOT NULL AFTER `createdBy`;

INSERT IGNORE INTO `zt_workflowfield` (`module`, `field`, `type`, `length`, `name`, `control`, `expression`, `options`, `default`, `rules`, `placeholder`, `order`, `searchOrder`, `exportOrder`, `canExport`, `canSearch`, `isValue`, `readonly`, `buildin`, `role`, `desc`, `createdBy`, `createdDate`, `editedBy`, `editedDate`) VALUES
('testtask',	'createdBy',	'varchar',	'30',	'由谁创建',	'select',	'',	'user',	'',	'',	'',	393,	0,	0,	'0',	'0',	'0',	'1',	1,      'buildin',	'',	'',	'2022-08-02 14:49',	'',	'0000-00-00 00:00:00'),
('testtask',	'createdDate',	'datetime',	'',	'创建时间',	'datetime',	'',	'',	'',	'',	'',	394,	0,	0,	'0',	'0',	'0',	'1',	1,	'buildin',      '',	'',	'2022-08-02 14:49',	'',	'0000-00-00 00:00:00'),
('productplan',	'createdBy',	'varchar',	'30',	'由谁创建',	'select',	'',	'user',	'',	'',	'',	11,	0,	0,	'0',	'0',	'0',	'1',	1,	'buildin',      '',	'',	'2022-08-02 14:49',	'',	'0000-00-00 00:00:00'),
('productplan',	'createdDate',	'datetime',	'',	'创建时间',	'datetime',	'',	'',	'',	'',	'',	12,	0,	0,	'0',	'0',	'0',	'1',	1,	'buildin',      '',	'',	'2022-08-02 14:49',	'',	'0000-00-00 00:00:00'),
('build',	'createdBy',	'varchar',	'30',	'由谁创建',	'select',	'',	'user',	'',	'',	'',	15,	0,	0,	'0',	'0',	'0',	'1',	1,	'buildin',      '',	'',	'2022-08-02 14:49',	'',	'0000-00-00 00:00:00'),
('build',	'createdDate',	'datetime',	'',	'创建时间',	'datetime',	'',	'',	'',	'',	'',	16,	0,	0,	'0',	'0',	'0',	'1',	1,	'buildin',      '',	'',	'2022-08-02 14:49',	'',	'0000-00-00 00:00:00'),
('release',	'createdBy',	'varchar',	'30',	'由谁创建',	'select',	'',	'user',	'',	'',	'',	14,	0,	0,	'0',	'0',	'0',	'1',	1,	'buildin',      '',	'',	'2022-08-02 14:49',	'',	'0000-00-00 00:00:00'),
('release',	'createdDate',	'datetime',	'',	'创建时间',	'datetime',	'',	'',	'',	'',	'',	15,	0,	0,	'0',	'0',	'0',	'1',	1,	'buildin',      '',	'',	'2022-08-02 14:49',	'',	'0000-00-00 00:00:00');

UPDATE `zt_workflowfield` SET `options`=(SELECT id FROM `zt_workflowdatasource` WHERE `code`='feedbackType' ORDER BY `id` DESC LIMIT 1) WHERE `module`='feedback' AND `field`='type';
UPDATE `zt_workflowfield` SET `options`=(SELECT id FROM `zt_workflowdatasource` WHERE `code`='feedbackSolution' ORDER BY `id` DESC LIMIT 1) WHERE `module`='feedback' AND `field`='solution';
UPDATE `zt_workflowfield` SET `options`=(SELECT id FROM `zt_workflowdatasource` WHERE `code`='feedbackclosedReason' ORDER BY `id` DESC LIMIT 1), `control`='select' WHERE `module`='feedback' AND `field`='closedReason';

UPDATE `zt_project` SET `closedDate`='' AND `closedBy`='' WHERE `status` != 'closed';
UPDATE `zt_grouppriv` SET `method`='exportTemplate' WHERE `method` = 'exportTemplet';
