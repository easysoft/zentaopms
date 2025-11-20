ALTER TABLE `zt_workflowgroup` CHANGE `projectModel` `projectModel` varchar(30) NOT NULL DEFAULT '';
INSERT INTO `zt_workflowgroup` (`type`, `projectModel`, `projectType`, `name`, `code`, `status`, `vision`, `main`) VALUES
('project',	'agileplus',	  'product',	'融合敏捷式产品研发',	'agileplusproduct',	    'normal',	'rnd',	'1'),
('project',	'agileplus',	  'project',	'融合敏捷式项目研发',	'agileplusproject',	    'normal',	'rnd',	'1'),
('project',	'waterfallplus',  'product',	'融合瀑布式产品研发',	'waterfallplusproduct',	'normal',	'rnd',	'1'),
('project',	'waterfallplus',  'project',	'融合瀑布式项目研发',	'waterfallplusproject',	'normal',	'rnd',	'1'),
('project',	'kanban',	      'product',	'看板式产品研发',	    'kanbanproduct',	    'normal',	'rnd',	'1'),
('project',	'kanban',	      'project',	'看板式项目研发',	    'kanbanproject',	    'normal',	'rnd',	'1'),
('project',	'ipd',	          'ipd',	    'IPD集成产品研发',	    'ipdproduct',	        'normal',	'rnd',	'1'),
('project',	'ipd',	          'tpd',	    'IPD预研产品研发',	    'tpdproduct',	        'normal',	'rnd',	'1'),
('project',	'ipd',	          'cbb',	    'IPD平台产品研发',	    'cbbproduct',	        'normal',	'rnd',	'1'),
('project',	'ipd',	          'cpdproduct',	'IPD定制产品研发',	    'cpdproduct',	        'normal',	'rnd',	'1'),
('project',	'ipd',	          'cpdproject',	'IPD定制项目研发',	    'cpdproject',	        'normal',	'rnd',	'1');

ALTER TABLE `zt_doc` ADD `isDeliverable` tinyint(1) NOT NULL DEFAULT 0 AFTER `acl`;
ALTER TABLE `zt_deliverable` ADD `workflowGroup` int(8) NOT NULL DEFAULT '0' AFTER `id`;
ALTER TABLE `zt_deliverable` ADD `activity` int(8) unsigned NOT NULL DEFAULT '0' AFTER `name`;
ALTER TABLE `zt_deliverable` ADD `trimmable` char(30) NOT NULL DEFAULT '0' AFTER `activity`;
ALTER TABLE `zt_deliverable` ADD `trimRule` varchar(255) NOT NULL AFTER `trimmable`;
ALTER TABLE `zt_deliverable` ADD `template` text NOT NULL AFTER `trimRule`;
ALTER TABLE `zt_deliverable` ADD `status` varchar(30) NOT NULL DEFAULT 'enabled' AFTER `name`;
ALTER TABLE `zt_deliverable` ADD `category` varchar(255) NOT NULL DEFAULT '' AFTER `lastEditedDate`;
ALTER TABLE `zt_deliverable` ADD `builtin` tinyint(1) unsigned NOT NULL DEFAULT '0' AFTER `module`;

CREATE TABLE IF NOT EXISTS `zt_deliverablestage` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `deliverable` int(8) unsigned NOT NULL DEFAULT 0,
  `stage` varchar(30) NOT NULL,
  `required` varchar(30) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE UNIQUE INDEX `unique` ON `zt_deliverablestage`(`deliverable`,`stage`);
ALTER TABLE `zt_module` ADD `extra` varchar(30) NOT NULL DEFAULT '';

ALTER TABLE `zt_process` ADD `workflowGroup` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `id`;
ALTER TABLE `zt_process` ADD `module` int(8) unsigned NOT NULL DEFAULT '0' AFTER `name`;
ALTER TABLE `zt_activity` ADD `workflowGroup` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `process`;

UPDATE `zt_process`  SET `editedDate` = NULL WHERE `editedDate` LIKE '1970-01-01%';
UPDATE `zt_process`  SET `assignedDate` = NULL WHERE `assignedDate` LIKE '1970-01-01%';
UPDATE `zt_activity` SET `editedDate` = NULL WHERE `editedDate` LIKE '1970-01-01%';
UPDATE `zt_activity` SET `assignedDate` = NULL WHERE `assignedDate` LIKE '1970-01-01%';

ALTER TABLE `zt_searchindex` CHANGE `content` `content` longtext NULL;

CREATE TABLE IF NOT EXISTS `zt_projectdeliverable` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `project` int(8) unsigned NOT NULL,
  `review` int(8) unsigned NOT NULL DEFAULT '0',
  `deliverable` varchar(30) NOT NULL,
  `name` varchar(255) NOT NULL,
  `doc` int(8) unsigned NOT NULL DEFAULT '0',
  `docVersion` smallint(6) unsigned NOT NULL DEFAULT '0',
  `status` varchar(30) NOT NULL DEFAULT '',
  `version` varchar(255) NULL,
  `createdBy` varchar(30) NOT NULL DEFAULT '',
  `createdDate` date NULL,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE INDEX `project` ON `zt_projectdeliverable` (`project`);
CREATE UNIQUE INDEX `project_deliverable_doc` ON `zt_projectdeliverable` (`project`, `deliverable`, `doc`);

ALTER TABLE `zt_approvalflowobject` ADD `relatedBy` varchar(30) NOT NULL DEFAULT '' AFTER `objectID`;
ALTER TABLE `zt_approvalflowobject` ADD `relatedDate`  datetime NULL AFTER `relatedBy`;

ALTER TABLE `zt_reviewcl` ADD `workflowGroup` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `id`;

UPDATE `zt_lang` SET `value` = '单元测试环节' WHERE `module` = 'testcase' AND `section` = 'stageList' AND `key` = 'unittest'   AND `value` = '单元测试阶段';
UPDATE `zt_lang` SET `value` = '功能测试环节' WHERE `module` = 'testcase' AND `section` = 'stageList' AND `key` = 'feature'    AND `value` = '功能测试阶段';
UPDATE `zt_lang` SET `value` = '集成测试环节' WHERE `module` = 'testcase' AND `section` = 'stageList' AND `key` = 'intergrate' AND `value` = '集成测试阶段';
UPDATE `zt_lang` SET `value` = '系统测试环节' WHERE `module` = 'testcase' AND `section` = 'stageList' AND `key` = 'system'     AND `value` = '系统测试阶段';
UPDATE `zt_lang` SET `value` = '冒烟测试环节' WHERE `module` = 'testcase' AND `section` = 'stageList' AND `key` = 'smoke'      AND `value` = '冒烟测试阶段';
UPDATE `zt_lang` SET `value` = '版本验证环节' WHERE `module` = 'testcase' AND `section` = 'stageList' AND `key` = 'bvt'        AND `value` = '版本验证阶段';

ALTER TABLE `zt_review` ADD `version` varchar(255) NOT NULL DEFAULT '' AFTER `docVersion`;
ALTER TABLE `zt_review` ADD `deliverable` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `title`;
ALTER TABLE `zt_review` ADD `isBaseline` tinyint(1) DEFAULT '0' AFTER `status`;
ALTER TABLE `zt_review` ADD `type` varchar(30) NOT NULL DEFAULT '' AFTER `version`;
UPDATE `zt_review` SET `status` = 'reviewing' WHERE `status` = 'wait';
UPDATE `zt_review` SET `status` = 'pass' WHERE `status` = 'auditing' OR `status` = 'done';
UPDATE `zt_review` SET `type` = 'deliverable';

UPDATE `zt_review` AS t1
JOIN `zt_object` AS t2 ON t1.object = t2.id
SET t1.version = t2.version;

UPDATE `zt_object` SET `category` = 'intergrate' WHERE `category` = 'ITTC';
UPDATE `zt_object` SET `category` = 'system' WHERE `category` = 'STTC';

ALTER TABLE `zt_object` ADD `status` varchar(20) NOT NULL DEFAULT '' AFTER `type`;
ALTER TABLE `zt_object` ADD `approval`  mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `status`;
ALTER TABLE `zt_object` ADD `reviewResult` varchar(20) NOT NULL DEFAULT '' AFTER `approval`;
ALTER TABLE `zt_object` ADD `reviewOpinion` text NULL AFTER `reviewResult`;
ALTER TABLE `zt_object` ADD `reviewers` text NULL AFTER `reviewResult`;
ALTER TABLE `zt_object` ADD `categoryVersion` text DEFAULT NULL AFTER `category`;

ALTER TABLE `zt_designspec` ADD `docs` text NULL AFTER `files`;
ALTER TABLE `zt_designspec` ADD `docVersions` text NULL AFTER `docs`;
UPDATE `zt_designspec` AS t1
JOIN `zt_design` AS t2 ON t1.design = t2.id AND t2.version = t1.version
SET t1.docs = t2.docs, t1.docVersions = t2.docVersions;
ALTER TABLE `zt_design` DROP COLUMN `docs`;
ALTER TABLE `zt_design` DROP COLUMN `docVersions`;

ALTER TABLE `zt_reviewissue` ADD `assignedTo` char(30) COLLATE 'utf8mb4_general_ci' NOT NULL DEFAULT '' AFTER `createdDate`;
ALTER TABLE `zt_reviewissue` ADD `assignedDate` datetime NULL AFTER `assignedTo`;

REPLACE INTO `zt_grouppriv`(`group`, `module`, `method`) SELECT `group`, `module`, 'active' as `method` FROM `zt_grouppriv` WHERE `module` = 'reviewissue' AND `method` = 'updateStatus';
REPLACE INTO `zt_grouppriv`(`group`, `module`, `method`) SELECT `group`, `module`, 'close' as `method` FROM `zt_grouppriv` WHERE `module` = 'reviewissue' AND `method` = 'updateStatus';
DELETE FROM `zt_grouppriv` WHERE `module` = 'reviewissue' AND `method` = 'updateStatus';

INSERT INTO `zt_metric` (`purpose`, `scope`, `object`, `stage`, `type`, `name`, `alias`, `code`, `unit`, `desc`, `definition`, `when`, `createdBy`, `createdDate`, `builtin`, `deleted`, `dateType`) VALUES
('scale', 'user', 'reviewissue', 'released', 'php', '按人员统计的被指派的评审意见数	', '被指派的评审意见数', 'count_of_assigned_reviewissue_in_user', 'count', '按人员统计的被指派的评审意见数表示每个人被指派的评审意见数量之和，反映了每个人员需要处理的评审意见数量的规模。该数值越大，说明需要投入越多的时间处理评审意见。', '所有评审意见个数求和\r\n指派给为某人\r\n过滤已删除的评审意见\r\n过滤已关闭的评审意见\r\n过滤已删除项目的评审意见', '', 'system', '2024-05-07 08:00:00', '1', '0', 'nodate');

ALTER TABLE `zt_approvalnode` CHANGE `result` `result` varchar(20) NOT NULL DEFAULT '';

DELETE FROM `zt_workflowgroup` WHERE `projectModel` = 'kanban';
UPDATE `zt_project` SET `workflowGroup` = '0' WHERE `model` = 'kanban';

CREATE TABLE IF NOT EXISTS `zt_decision` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `workflowGroup` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `stage` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `order` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `type` varchar(30) NOT NULL DEFAULT '',
  `category` varchar(30) NOT NULL DEFAULT '',
  `builtin` enum('0','1') NOT NULL DEFAULT '0',
  `createdBy` char(30) NOT NULL DEFAULT '',
  `createdDate` datetime NULL,
  `editedBy` char(30) NOT NULL DEFAULT '',
  `editedDate` datetime NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `zt_stage` ADD `workflowGroup` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `id`;
ALTER TABLE `zt_stage` ADD `order` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `editedDate`;

ALTER TABLE `zt_object` ADD `execution` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `project`;
ALTER TABLE `zt_object` ADD `categoryTitle` varchar(255) NOT NULL DEFAULT '' AFTER `category`;
ALTER TABLE `zt_object` MODIFY COLUMN `type` enum('reviewed','taged','decision') NOT NULL DEFAULT 'reviewed';

ALTER TABLE `zt_auditcl` ADD `workflowGroup` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `id`;

UPDATE zt_activity SET optional = CASE
    WHEN optional = 'yes' THEN 'no'
    WHEN optional = 'no' THEN 'yes'
    ELSE optional
END;

ALTER TABLE `zt_process` CHANGE `model` `model` varchar(30) NOT NULL DEFAULT '';

ALTER TABLE `zt_auditplan` ADD `cycleType` varchar(10) NOT NULL DEFAULT 'noCycle' COMMENT '周期类型' AFTER `status`;
ALTER TABLE `zt_auditplan` ADD `cycleConfig` varchar(100) NOT NULL DEFAULT '[]' COMMENT '周期设置' AFTER `cycleType`;
ALTER TABLE `zt_auditplan` ADD `cyclePlan` smallint unsigned  NOT NULL DEFAULT 0 COMMENT '生成计划(提前的天数)' AFTER `cycleConfig`;
ALTER TABLE `zt_auditplan` ADD `deadline` date NULL COMMENT '过期时间' AFTER `cyclePlan`;
ALTER TABLE `zt_auditplan` ADD `templateID` int unsigned NOT NULL DEFAULT 0 COMMENT '周期性活动检查ID' AFTER `deadline`;
ALTER TABLE `zt_auditplan` DROP COLUMN `dateType`;
ALTER TABLE `zt_auditplan` DROP COLUMN `config`;
ALTER TABLE `zt_auditplan` DROP COLUMN `checkBy`;

INSERT INTO `zt_cron` (`m`, `h`, `dom`, `mon`, `dow`, `command`, `remark`, `type`, `buildin`, `status`) VALUES('2','2','*','*','*','moduleName=auditplan&methodName=ajaxCreateCycleAuditplan','生成周期性活动检查','zentao',1,'normal');

UPDATE `zt_auditplan` SET `checkDate` = NULL WHERE `checkDate` = '0000-00-00';

CREATE TABLE IF NOT EXISTS `zt_projectchange` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `project` int unsigned NOT NULL DEFAULT 0 COMMENT '所属项目',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '变更名称',
  `urgency` varchar(30) NOT NULL DEFAULT '' COMMENT '变更等级',
  `type` varchar(30) NOT NULL DEFAULT '' COMMENT '变更类型',
  `deliverable` varchar(255) NOT NULL DEFAULT '' COMMENT '变更对象',
  `status` varchar(30) NOT NULL DEFAULT '' COMMENT '变更状态',
  `owner` varchar(30) NOT NULL DEFAULT '' COMMENT '负责人',
  `reason` varchar(1000) NOT NULL DEFAULT '' COMMENT '变更原因',
  `desc` text NULL COMMENT '变更描述',
  `deadline` datetime NULL COMMENT '期望完成时间',
  `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '创建人',
  `createdDate` datetime NULL COMMENT '创建时间',
  `editedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '编辑人',
  `editedDate` datetime NULL COMMENT '编辑时间',
  `deleted` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否删除',
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE INDEX `project` ON `zt_projectchange` (`project`);

ALTER TABLE `zt_nc` ADD `execution` int unsigned NOT NULL DEFAULT 0 COMMENT '所属执行' AFTER `project`;
ALTER TABLE `zt_nc` ADD `deliverable` int unsigned NOT NULL DEFAULT 0 COMMENT '交付物' AFTER `auditplan`;

ALTER TABLE `zt_workflowgroup` ADD `disabledFeatures` varchar(255) NOT NULL DEFAULT '' COMMENT '项目流程关闭的功能' AFTER `disabledModules`;

ALTER TABLE `zt_stage` MODIFY COLUMN `projectType` varchar(30) NOT NULL DEFAULT '' COMMENT '所属项目流程的类型';

DELETE FROM `zt_doc` WHERE `templateType` IN ('PP', 'SRS', 'HLDS', 'DDS', 'ADS', 'DBDS', 'ITTC', 'STTC') AND `builtin` = '1';

ALTER TABLE `zt_object`
DROP `range`,
ADD `items` text NULL DEFAULT NULL COMMENT '评审条目列表' AFTER `data`,
DROP `storyEst`,
DROP `taskEst`,
DROP `requestEst`,
DROP `testEst`,
DROP `devEst`,
DROP `designEst`;

ALTER TABLE `zt_review`
DROP `auditedBy`,
DROP `lastAuditedBy`,
DROP `lastAuditedDate`,
DROP `toAuditBy`,
DROP `toAuditDate`,
DROP `auditResult`;