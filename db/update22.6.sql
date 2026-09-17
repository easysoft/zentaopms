CREATE TABLE IF NOT EXISTS `zt_rule` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '规则名称',
  `scene` varchar(30) NOT NULL DEFAULT 'automation' COMMENT '场景',
  `type` varchar(30) NOT NULL DEFAULT 'global' COMMENT '类型',
  `workflowGroup` varchar(255) DEFAULT NULL COMMENT '工作流组',
  `objectType` varchar(30) NOT NULL DEFAULT '' COMMENT '对象类型',
  `action` varchar(30) NOT NULL DEFAULT '' COMMENT '动作',
  `alertLevel` varchar(10) NOT NULL DEFAULT 'notice' COMMENT '预警级别',
  `triggerKind` varchar(30) NOT NULL DEFAULT 'object' COMMENT '触发类型',
  `nodes` mediumtext DEFAULT NULL COMMENT '节点',
  `version` mediumint unsigned NOT NULL DEFAULT 0 COMMENT '版本',
  `runAs` varchar(30) NOT NULL DEFAULT '' COMMENT '规则执行人',
  `method` varchar(30) NOT NULL DEFAULT 'sync' COMMENT '执行方法',
  `status` char(30) NOT NULL DEFAULT 'disable' COMMENT '状态',
  `notifyUsers` text NULL COMMENT '通知用户',
  `notifyMethod` varchar(30) NULL COMMENT '通知方式',
  `createdBy` varchar(30) DEFAULT NULL COMMENT '创建人',
  `createdDate` date DEFAULT NULL COMMENT '创建时间',
  `lastEditedBy` varchar(30) DEFAULT NULL COMMENT '最后编辑人',
  `lastEditedDate` date DEFAULT NULL COMMENT '最后编辑时间',
  `lastRunTime` datetime DEFAULT NULL COMMENT '最后运行时间',
  `lastRunResult` varchar(30) NOT NULL DEFAULT '' COMMENT '最后运行结果',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE INDEX `idx_rule_scene_status` ON `zt_rule` (`scene`, `status`);
CREATE INDEX `idx_rule_object_action` ON `zt_rule` (`objectType`, `action`);

CREATE TABLE IF NOT EXISTS `zt_rulequeue` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `rule` int unsigned NOT NULL DEFAULT 0 COMMENT '规则',
  `ruleVersion` mediumint unsigned NOT NULL DEFAULT 0 COMMENT '规则版本',
  `fromObject` mediumtext DEFAULT NULL COMMENT '触发对象',
  `actions` longtext DEFAULT NULL COMMENT '执行动作',
  `status` char(30) NOT NULL DEFAULT 'wait' COMMENT '执行状态',
  `runAs` varchar(30) NOT NULL DEFAULT '' COMMENT '运行方式',
  `log` text DEFAULT NULL COMMENT '日志',
  `triggeredBy` varchar(30) DEFAULT NULL COMMENT '触发执行人',
  `triggeredDate` datetime DEFAULT NULL COMMENT '触发时间',
  `executedTime` datetime DEFAULT NULL COMMENT '执行时间',
  `startedAt` datetime DEFAULT NULL COMMENT '开始时间',
  `finishedAt` datetime DEFAULT NULL COMMENT '完成时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE INDEX `idx_rulequeue_status` ON `zt_rulequeue` (`status`);
CREATE INDEX `idx_rulequeue_rule` ON `zt_rulequeue` (`rule`);

ALTER TABLE `zt_task` ADD COLUMN `deliverable` text DEFAULT NULL COMMENT '任务关联的交付物类型' AFTER `desc`;
ALTER TABLE `zt_task` ADD COLUMN `fromDeliverable` int unsigned NOT NULL DEFAULT 0 COMMENT '由交付物转的任务' AFTER `fromIssue`;

ALTER TABLE `zt_deliverablestage` ADD COLUMN `type` varchar(30) NOT NULL DEFAULT 'execution' AFTER `deliverable`;
UPDATE `zt_deliverablestage` SET `type` = 'project' WHERE `stage` = 'project';
ALTER TABLE `zt_deliverablestage` DROP INDEX `unique`;
ALTER TABLE `zt_deliverablestage` ADD UNIQUE INDEX `unique` (`deliverable`,`type`,`stage`);

ALTER TABLE `zt_projectdeliverable` CHANGE `submitFrom` `sourceID` int unsigned NOT NULL DEFAULT 0 COMMENT '来源对象';
ALTER TABLE `zt_projectdeliverable` ADD COLUMN `sourceType` varchar(20) NOT NULL DEFAULT 'project' COMMENT '来源类型' AFTER `project`;
UPDATE `zt_projectdeliverable` t1 JOIN `zt_project` t2 ON t1.`sourceID` = t2.id AND t2.`type` != 'project' SET t1.`sourceType` = 'execution';

CREATE TABLE IF NOT EXISTS `zt_reviewdeliverable` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `review` int unsigned NOT NULL DEFAULT 0 COMMENT '评审流程',
  `projectdeliverable` int unsigned NOT NULL DEFAULT 0 COMMENT '项目交付物',
  `deliverableReview` int unsigned NOT NULL DEFAULT 0 COMMENT '交付物评审',
  `version` varchar(255) NOT NULL DEFAULT '' COMMENT '交付物版本',
  `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁创建',
  `createdDate` datetime NULL COMMENT '创建时间',
  `editedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁编辑',
  `editedDate` datetime NULL COMMENT '编辑时间',
  `deleted` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;
CREATE UNIQUE INDEX `uk_review_projectdeliverable` ON `zt_reviewdeliverable` (`review`, `projectdeliverable`);
CREATE INDEX `idx_projectdeliverable` ON `zt_reviewdeliverable` (`projectdeliverable`);

INSERT INTO `zt_workflowdatasource` (`type`, `name`, `code`, `buildin`, `vision`, `createdBy`, `createdDate`, `datasource`, `view`, `keyField`, `valueField`) VALUES
('lang', '任务模式', 'taskMode',            '1', 'rnd', 'admin', '1970-01-01 00:00:01', 'taskMode',      '', '', ''),
('lang', '需求类型', 'storyCategory',       '1', 'rnd', 'admin', '1970-01-01 00:00:01', 'storyCategory', '', '', ''),
('sql',  '相关文档', 'taskDocs',            '1', 'or',  'admin', '1970-01-01 00:00:01', 'SELECT `id`,`title` FROM zt_doc WHERE `deleted`=\'0\'',   'view_datasource_taskDocs',  'id', 'title'),
('sql',  '所属场景', 'caseScene',           '1', 'rnd', 'admin', '1970-01-01 00:00:01', 'SELECT `id`,`title` FROM zt_scene WHERE `deleted`=\'0\'', 'view_datasource_caseScene', 'id', 'title'),
('lang', '注入环节', 'bugInjection',        '1', 'rnd', 'admin', '1970-01-01 00:00:01', 'bugInjection', '', '', ''),
('lang', '发现环节', 'bugIdentify',         '1', 'rnd', 'admin', '1970-01-01 00:00:01', 'bugIdentify',  '', '', ''),
('lang', '关闭原因', 'ticketClosedReason',  '1', 'rnd', 'admin', '1970-01-01 00:00:01', 'ticketClosedReason', '', '', ''),
('lang', '反馈优先级','feedbackPri',        '1', 'rnd', 'admin', '1970-01-01 00:00:01', 'feedbackPri',  '', '', ''),
('sql',  '重复工单',  'ticketRepeat',       '1', 'rnd', 'admin', '1970-01-01 00:00:01', 'SELECT `id`,`title` FROM zt_ticket WHERE `deleted`=\'0\'', 'view_datasource_ticketRepeat', 'id', 'title'),
('sql',  '重复反馈',  'feedbackRepeat',     '1', 'rnd', 'admin', '1970-01-01 00:00:01', 'SELECT `id`,`title` FROM zt_feedback WHERE `deleted`=\'0\'', 'view_datasource_feedbackRepeat', 'id', 'title');

UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'taskMode'           LIMIT 1) WHERE `module` = 'task'     AND `field` = 'mode';
UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'storyCategory'      LIMIT 1) WHERE `module` = 'story'    AND `field` = 'category';
UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'taskDocs'           LIMIT 1) WHERE `module` = 'task'     AND `field` = 'docs';
UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'caseScene'          LIMIT 1) WHERE `module` = 'testcase' AND `field` = 'scene';
UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'bugInjection'       LIMIT 1) WHERE `module` = 'bug'      AND `field` = 'injection';
UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'bugIdentify'        LIMIT 1) WHERE `module` = 'bug'      AND `field` = 'identify';
UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'ticketClosedReason' LIMIT 1) WHERE `module` = 'ticket'   AND `field` = 'closedReason';
UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'feedbackPri'        LIMIT 1) WHERE `module` = 'feedback' AND `field` = 'pri';
UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'ticketRepeat'       LIMIT 1) WHERE `module` = 'ticket'   AND `field` = 'repeatTicket';
UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'feedbackRepeat'     LIMIT 1) WHERE `module` = 'feedback' AND `field` = 'repeatFeedback';

DROP VIEW IF EXISTS `view_datasource_taskDocs`;
DROP VIEW IF EXISTS `view_datasource_caseScene`;
DROP VIEW IF EXISTS `view_datasource_ticketRepeat`;
DROP VIEW IF EXISTS `view_datasource_feedbackRepeat`;

CREATE VIEW `view_datasource_taskDocs`       AS SELECT `id`,`title` FROM `zt_doc`      WHERE `deleted` = '0';
CREATE VIEW `view_datasource_caseScene`      AS SELECT `id`,`title` FROM `zt_scene`    WHERE `deleted` = '0';
CREATE VIEW `view_datasource_ticketRepeat`   AS SELECT `id`,`title` FROM `zt_ticket`   WHERE `deleted` = '0';
CREATE VIEW `view_datasource_feedbackRepeat` AS SELECT `id`,`title` FROM `zt_feedback` WHERE `deleted` = '0';

DELETE FROM zt_workflowfield WHERE `module`='ticket'   AND `field`='consumed';
DELETE FROM zt_workflowfield WHERE `module`='ticket'   AND `field`='deleted';
DELETE FROM zt_workflowfield WHERE `module`='feedback' AND `field`='faq';
DELETE FROM zt_workflowfield WHERE `module`='feedback' AND `field`='deleted';
DELETE FROM zt_workflowfield WHERE `module`='testcase' AND `field`='deleted';
DELETE FROM zt_workflowfield WHERE `module`='testcase' AND `field`='color';
DELETE FROM zt_workflowfield WHERE `module`='testcase' AND `field`='project';
DELETE FROM zt_workflowfield WHERE `module`='testcase' AND `field`='execution';
DELETE FROM zt_workflowfield WHERE `module`='testcase' AND `field`='lib';
DELETE FROM zt_workflowfield WHERE `module`='testcase' AND `field`='storyVersion';
DELETE FROM zt_workflowfield WHERE `module`='task'     AND `field`='color';

UPDATE `zt_workflowfield` SET `control` = 'datetime' WHERE `module` = 'ticket'   AND `field` = 'assignedDate';
UPDATE `zt_workflowfield` SET `control` = 'datetime' WHERE `module` = 'ticket'   AND `field` = 'realStarted';
UPDATE `zt_workflowfield` SET `control` = 'datetime' WHERE `module` = 'ticket'   AND `field` = 'startedDate';
UPDATE `zt_workflowfield` SET `control` = 'date'     WHERE `module` = 'ticket'   AND `field` = 'deadline';
UPDATE `zt_workflowfield` SET `control` = 'datetime' WHERE `module` = 'ticket'   AND `field` = 'openedDate';
UPDATE `zt_workflowfield` SET `control` = 'datetime' WHERE `module` = 'ticket'   AND `field` = 'activatedDate';
UPDATE `zt_workflowfield` SET `control` = 'datetime' WHERE `module` = 'ticket'   AND `field` = 'closedDate';
UPDATE `zt_workflowfield` SET `control` = 'datetime' WHERE `module` = 'ticket'   AND `field` = 'finishedDate';
UPDATE `zt_workflowfield` SET `control` = 'datetime' WHERE `module` = 'ticket'   AND `field` = 'editedDate';
UPDATE `zt_workflowfield` SET `control` = 'datetime' WHERE `module` = 'ticket'   AND `field` = 'resolvedDate';
UPDATE `zt_workflowfield` SET `control` = 'radio'    WHERE `module` = 'feedback' AND `field` = 'public';

UPDATE `zt_workflowaction` SET `action` = 'showimport', `method` = 'showimport' WHERE `module` = 'caselib' AND `action` = 'showImport';
UPDATE `zt_workflowaction` SET `action` = 'assignTo' WHERE `module` = 'ticket'   AND `action` = 'assign';
UPDATE `zt_workflowaction` SET `type`   = 'batch'    WHERE `module` = 'testcase' AND `action` = 'batchcreate';
UPDATE `zt_workflowaction` SET `type`   = 'batch'    WHERE `module` = 'testcase' AND `action` = 'batchedit';
UPDATE `zt_workflowaction` SET `type`   = 'batch'    WHERE `module` = 'ticket'   AND `action` = 'batchedit';
UPDATE `zt_workflowaction` SET `type`   = 'batch'    WHERE `module` = 'bug'      AND `action` = 'showimport';
UPDATE `zt_workflowaction` SET `type`   = 'batch'    WHERE `module` = 'caselib'  AND `action` = 'showimport';
UPDATE `zt_workflowaction` SET `type`   = 'batch'    WHERE `module` = 'testcase' AND `action` = 'showimport';
UPDATE `zt_workflowaction` SET `type`   = 'batch'    WHERE `module` = 'task'     AND `action` = 'showimport';
UPDATE `zt_workflowaction` SET `type`   = 'batch'    WHERE `module` = 'feedback' AND `action` = 'showimport';

ALTER TABLE `zt_workflowlayout` ADD `block`      text DEFAULT NULL AFTER `position`;
ALTER TABLE `zt_workflowlayout` ADD `foldable`   tinyint unsigned NOT NULL DEFAULT 0 AFTER `mobileShow`;
ALTER TABLE `zt_workflowlayout` ADD `wrapBefore` tinyint unsigned NOT NULL DEFAULT 0 AFTER `foldable`;
ALTER TABLE `zt_workflowlayout` ADD `wrapAfter`  tinyint unsigned NOT NULL DEFAULT 0 AFTER `wrapBefore`;

UPDATE `zt_workflowlayout` SET `action` = 'assignTo' WHERE `module` = 'ticket'   AND `action` = 'assign';
