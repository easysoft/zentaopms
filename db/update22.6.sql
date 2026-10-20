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

ALTER TABLE `zt_workflowdatasource` MODIFY COLUMN `view` varchar(50) NOT NULL DEFAULT '' COMMENT '视图';

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
('sql',  '重复反馈',  'feedbackRepeat',     '1', 'rnd', 'admin', '1970-01-01 00:00:01', 'SELECT `id`,`title` FROM zt_feedback WHERE `deleted`=\'0\'', 'view_datasource_feedbackRepeat', 'id', 'title'),
('sql',  '交付物',    'taskDeliverable',    '1', 'rnd', 'admin', '1970-01-01 00:00:01', 'SELECT `id`,`name` FROM zt_deliverable WHERE `deleted`=\'0\'', 'view_datasource_taskDeliverable', 'id', 'name');

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
UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'taskDeliverable'    LIMIT 1) WHERE `module` = 'task'     AND `field` = 'deliverable';
UPDATE `zt_workflowfield` SET control = 'multi-select' WHERE `control` = 'multi-selec';

DROP VIEW IF EXISTS `view_datasource_taskDocs`;
DROP VIEW IF EXISTS `view_datasource_caseScene`;
DROP VIEW IF EXISTS `view_datasource_ticketRepeat`;
DROP VIEW IF EXISTS `view_datasource_feedbackRepeat`;
DROP VIEW IF EXISTS `view_datasource_taskDeliverable`;

CREATE VIEW `view_datasource_taskDocs`        AS SELECT `id`,`title` FROM `zt_doc`      WHERE `deleted` = '0';
CREATE VIEW `view_datasource_caseScene`       AS SELECT `id`,`title` FROM `zt_scene`    WHERE `deleted` = '0';
CREATE VIEW `view_datasource_ticketRepeat`    AS SELECT `id`,`title` FROM `zt_ticket`   WHERE `deleted` = '0';
CREATE VIEW `view_datasource_feedbackRepeat`  AS SELECT `id`,`title` FROM `zt_feedback` WHERE `deleted` = '0';
CREATE VIEW `view_datasource_taskDeliverable` AS SELECT `id`,`name`  FROM `zt_deliverable` WHERE `deleted` = '0';

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

UPDATE `zt_case` AS t1
JOIN `zt_case` AS t2 ON t1.`fromCaseID` = t2.`id`
SET t1.`lib` = t2.`lib`
WHERE t1.`fromCaseID` > 0 AND t1.`fromCaseID` != t1.`id` AND t1.`lib` != t2.`lib` AND t2.`product` = 0 AND t2.`lib` > 0;

ALTER TABLE `ops_scan_issues` ADD COLUMN `pipelineExecutionID` int unsigned NOT NULL DEFAULT 0 COMMENT '流水线执行ID' AFTER `ppmID`;

CREATE TABLE IF NOT EXISTS `zt_ai_teammate` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(10) NOT NULL DEFAULT 'rnd' COMMENT '员工名称',
  `desc` varchar(1000) NOT NULL DEFAULT '' COMMENT '员工描述',
  `llm` varchar(255) NOT NULL DEFAULT '' COMMENT '执行 AI 任务时默认使用的模型 ID, 对应 ZAI 中的模型 ID',
  `role` varchar(10) NOT NULL DEFAULT '' COMMENT '员工职位, 与 zt_user.role 来源相同',
  `avatar` text DEFAULT NULL COMMENT '员工头像, 对应 ZAI 中的头像文件路径',
  `agents` varchar(255) NOT NULL DEFAULT '' COMMENT '挂载的智能体ID, 多个用逗号分隔',
  `klibs` varchar(255) NOT NULL DEFAULT '' COMMENT '挂载的知识库ID, 多个用逗号分隔',
  `disabled` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否禁用',
  `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '创建者',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  `editedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '编辑者',
  `editedDate` datetime DEFAULT NULL COMMENT '编辑时间',
  `deleted` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否已删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

INSERT INTO `zt_ai_teammate` (`name`, `desc`, `llm`, `role`, `avatar`, `agents`, `klibs`, `disabled`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `deleted`) VALUES
('向前', '专注于项目全周期管理与资源协调，擅长将复杂业务目标高效转化为可执行的项目计划与交付成果。', '', 'pm', '/static/zentaoaipro/ai-teammate-pm.jpg', 'zt_project_proposal,zt_project_finalreport', '', 0, 'system', '2025-12-29 08:42:37', '', NULL, 0),
('李娟', '专注于测试用例设计与自动化脚本开发，擅长将业务场景高效转化为可执行的测试方案与质量保障体系。', '', 'qa', '/static/zentaoaipro/ai-teammate-qa.jpg', 'zt_story_to_testcase,zt_bug_polishing,zt_automatic_test', '', 0, 'system', '2025-12-29 08:42:37', '', NULL, 0),
('阿道', '专注于后端架构设计与系统性能优化，擅长将业务需求高效转化为稳定可靠的代码实现。', '', 'td', '/static/zentaoaipro/ai-teammate-td.jpg', 'zt_task_polishing,zt_story_to_task,zt_create_doc', '', 0, 'system', '2025-12-29 08:42:37', '', NULL, 0),
('阿禅', '专注于产品全流程体验设计与用户需求挖掘，擅长将抽象业务逻辑高效转化为可落地的产品方案。', '', 'po', '/static/zentaoaipro/ai-teammate-po.jpg', 'zt_story_polishing,zt_story_prototype,zt_release_newsletter,zt_bug_to_story,zt_split_productplan', '', 0, 'system', '2025-12-29 08:42:37', '', NULL, 0);

CREATE TABLE IF NOT EXISTS `zt_ai_task` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `teammate` int unsigned NOT NULL DEFAULT 0 COMMENT '执行此任务的数字员工 ID',
  `type` varchar(50)  NOT NULL DEFAULT '' COMMENT '任务类型',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '任务名称',
  `desc` varchar(1000) NOT NULL DEFAULT '' COMMENT '任务描述',
  `status` varchar(20)  NOT NULL DEFAULT '' COMMENT '任务状态',
  `objectType` varchar(30) NOT NULL DEFAULT '' COMMENT '任务相关对象类型',
  `objectID` int unsigned NOT NULL DEFAULT 0 COMMENT '任务相关对象 ID',
  `agents` varchar(255) NOT NULL DEFAULT '' COMMENT '实际使用的智能体 ID, 多个用逗号分隔',
  `agentsData` text DEFAULT NULL COMMENT '禅道智能体填写的表单数据',
  `klibs` varchar(255) NOT NULL DEFAULT '' COMMENT '实际挂载的知识库ID, 多个用逗号分隔',
  `chatID` int unsigned NOT NULL DEFAULT 0 COMMENT '对话 ID。任务未被队列处理时为 0, 重新处理时会创建新对话并更新此 ID',
  `result` mediumtext DEFAULT NULL COMMENT '任务处理结果，包括要操作的禅道对象、要创建的文档等数据，对此次任务的 AI 总结, 使用 JSON 存储',
  `userPrompt` text DEFAULT NULL COMMENT '验收不通过时，用户补充的提示词',
  `consumed` decimal(10,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '总计耗时',
  `token` int unsigned NOT NULL DEFAULT 0 COMMENT '总计消耗 token',
  `noticeTime` varchar(10) NOT NULL DEFAULT '' COMMENT '任务通知时间',
  `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '创建人',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  `assignedTo` varchar(30) NOT NULL DEFAULT '' COMMENT '负责人',
  `assignedDate` datetime DEFAULT NULL COMMENT '指派时间',
  `verifiedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '验收者',
  `verifiedDate` datetime DEFAULT NULL COMMENT '验收时间',
  `activatedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '激活者',
  `activatedDate` datetime DEFAULT NULL COMMENT '激活时间',
  `cancelledBy` varchar(30) NOT NULL DEFAULT '' COMMENT '取消者',
  `cancelledDate` datetime DEFAULT NULL COMMENT '取消时间',
  `closedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '关闭者',
  `closedDate` datetime DEFAULT NULL COMMENT '关闭时间',
  `executedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '执行者，通过定时任务执行的任务为 system，手动执行为当前用户',
  `executedDate` datetime DEFAULT NULL COMMENT '任务开始执行时间',
  `finishedDate` datetime DEFAULT NULL COMMENT '完成时间',
  `deleted` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否已删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `zt_ai_chat` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `type` varchar(50)  NOT NULL DEFAULT '' COMMENT '对话类型 chat(用户普通聊天)、task(任务处理)、agent(禅道智能体)、miniprogram(通用智能体)',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '对话标题',
  `task` int unsigned NOT NULL DEFAULT 0 COMMENT '当类型为 task 时，对应的 AI 任务 ID',
  `llm` varchar(255) NOT NULL DEFAULT '' COMMENT '实际使用的大模型 ID, 对应 ZAI 中的模型 ID',
  `agents` text DEFAULT NULL COMMENT '实际使用的智能体列表及相关数据(JSON 数组)',
  `klibs` text DEFAULT NULL COMMENT '实际挂载的知识库列表及过滤配置(JSON 数组)',
  `objects` text DEFAULT NULL COMMENT '对话中涉及的禅道对象数据列表(JSON 数组)',
  `prompt` text DEFAULT NULL COMMENT '初始系统提示词',
  `tools` text DEFAULT NULL COMMENT '该对话中提供的工具名称和参数(JSON 数组)',
  `externalID` varchar(255) NOT NULL DEFAULT '' COMMENT '外部对话 ID, 目前为 ZAI 中的对话 ID(GUID 格式)',
  `token` int unsigned NOT NULL DEFAULT 0 COMMENT '对话消耗的 token 数量',
  `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '创建人',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  `updateDate` datetime DEFAULT NULL COMMENT '更新时间',
  `deleted` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否已删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `zt_ai_chat_message` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `chat` int unsigned NOT NULL DEFAULT 0 COMMENT '对话 ID',
  `externalID` varchar(255) NOT NULL DEFAULT '' COMMENT '外部对话 ID, 目前为 ZAI 中的对话 ID(GUID 格式)',
  `role` varchar(50)  NOT NULL DEFAULT '' COMMENT '消息角色: user(用户)、assistant(助手)、system(系统)',
  `content` text DEFAULT NULL COMMENT '消息内容',
  `reasonContent` text DEFAULT NULL COMMENT '思考内容，当角色为 assistant 时记录 AI 的思考过程',
  `tools` text DEFAULT NULL COMMENT '消息中使用的工具名称和定义(JSON 数组)',
  `refs` text DEFAULT NULL COMMENT '消息中引用的数据列表(JSON 数组)',
  `toolCalls` text DEFAULT NULL COMMENT '当角色为 assistant 时，工具调用列表(JSON 数组)',
  `toolCallID` varchar(255) NOT NULL DEFAULT '' COMMENT '当角色为 system 时，工具调用 ID（GUID 格式），用于向 AI 返回工具调用结果',
  `token` int unsigned NOT NULL DEFAULT 0 COMMENT '对话消耗的 token 数量',
  `gid` varchar(50) NOT NULL DEFAULT '' COMMENT '全局 ID',
  `bgid` varchar(50) NOT NULL DEFAULT '' COMMENT '批次 ID',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

INSERT INTO `zt_cron` (`m`, `h`, `dom`, `mon`, `dow`, `command`, `remark`, `type`, `buildin`, `status`) VALUES ('*/1','*','*','*','*','moduleName=aitask&methodName=exec', '执行数字人任务','zentao', 1, 'normal');

CREATE TABLE IF NOT EXISTS `zt_ai_procedure` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '工序名称',
  `module` varchar(30) NOT NULL DEFAULT '' COMMENT '显示位置：story/requirement/epic/task/bug/feedback/ticket',
  `desc` text DEFAULT NULL COMMENT '描述',
  `status` varchar(20) NOT NULL DEFAULT 'draft' COMMENT '状态：draft(待发布)/active(已发布)',
  `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '创建人',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  `editedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '编辑人',
  `editedDate` datetime DEFAULT NULL COMMENT '编辑时间',
  `publishedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '发布人',
  `unpublishedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '下架人',
  `deleted` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否删除（进入回收站）',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='AI工序定义表';

CREATE TABLE IF NOT EXISTS `zt_ai_procedurestep` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `procedure` int unsigned NOT NULL DEFAULT 0 COMMENT '所属工序，对应 zt_ai_procedure.id',
  `agent` int unsigned NOT NULL DEFAULT 0 COMMENT '禅道智能体，对应 zt_ai_agent.id',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '步骤名称，默认取智能体名称',
  `inputs` text DEFAULT NULL COMMENT '输入内容配置JSON；首步骤为空',
  `order` smallint unsigned NOT NULL DEFAULT 0 COMMENT '步骤顺序，从1开始',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='AI工序步骤设计表';

CREATE TABLE IF NOT EXISTS `zt_ai_procedurerun` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `procedure` int unsigned NOT NULL DEFAULT 0 COMMENT '工序定义ID，对应 zt_ai_procedure.id',
  `procedureName` varchar(255) NOT NULL DEFAULT '' COMMENT '工序名称快照',
  `objectType` varchar(30) NOT NULL DEFAULT '' COMMENT '对象类型：story/requirement/epic/task/bug/feedback/ticket',
  `objectID` int unsigned NOT NULL DEFAULT 0 COMMENT '对象ID',
  `objectData` text DEFAULT NULL COMMENT '要处理的对象数据JSON',
  `status` varchar(20) NOT NULL DEFAULT 'doing' COMMENT '运行状态：doing(进行中)/done(已完成)',
  `currentStep` int unsigned NOT NULL DEFAULT 0 COMMENT '当前步骤ID',
  `stepsData` text DEFAULT NULL COMMENT '步骤运行数据JSON, {steps: {id: int, name: string, formData: string, result: string, sessionID: string, finishDate: string, status: wait|done}[]}',
  `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '开始人',
  `createdDate` datetime DEFAULT NULL COMMENT '开始时间',
  `editedDate` datetime DEFAULT NULL COMMENT '更新时间，工作台按此倒序排列',
  `finishedDate` datetime DEFAULT NULL COMMENT '完成时间',
  `deleted` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='AI工序运行实例表';

INSERT INTO `zt_ai_agent` (`id`, `code`, `type`, `name`, `desc`, `model`, `knowledgeLib`, `skill`, `module`, `displayPosition`, `actionPurpose`, `operation`, `cycleType`, `cycleConfig`, `notifyRule`, `source`, `targetForm`, `purpose`, `elaboration`, `role`, `characterization`, `status`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `lastRunDate`, `deleted`) VALUES
(116,	'',	'normal',	'需求价值评估',	'根据WSJF（加权最短作业优先）方法对需求进行价值评估。',	'deepseek-v4-flash',	'',	'',	'story',	'detail',	'empty.empty',	'',	'',	NULL,	NULL,	',story.id,story.product,story.branch,story.module,story.plan,story.source,story.sourceNote,story.fromBug,story.feedback,story.title,story.keywords,story.type,story.pri,story.estimate,story.status,story.color,story.stage,story.mailto,story.openedBy,story.openedDate,story.assignedTo,story.assignedDate,story.spec,story.verify,',	'empty.empty',	'根据WSJF（加权最短作业优先）方法对需求进行价值评估。 \nWSJF 综合分≥ 4.5，优先级改为1，含义：立即排入当前迭代； \nWSJF 综合分为：2.5 – 4.4，优先级改为2，含义：近期迭代优先安排；\n WSJF 综合分≥ 1.5 – 2.4，优先级改为3，含义：排入后续迭代；\n WSJF 综合分< 1.5，优先级改为4，含义：备选积压，暂不安排；  \n\n返回需求的WSJF结果，和用户确认后，调用编辑需求的接口修改\'优先级\'（pri）字段。',	NULL,	'你是一个资深产品经理。',	NULL,	'active',	'admin',	'2026-08-26 18:21:55',	'admin',	'2026-08-26 18:25:00',	NULL,	0),
(117,	'',	'normal',	'需求工作量估算',	'对需求的故事点进行评估。',	'',	'',	'',	'story',	'detail',	'empty.empty',	'',	'',	NULL,	NULL,	',story.id,story.product,story.branch,story.module,story.plan,story.source,story.sourceNote,story.fromBug,story.feedback,story.title,story.keywords,story.type,story.pri,story.estimate,story.status,story.color,story.stage,story.mailto,story.openedBy,story.openedDate,story.assignedTo,story.reviewedDate,story.spec,story.verify,story.reviewedBy,',	'empty.empty',	'对需求进行规模估算，估算单位是故事点。 \n故事点是一个相对单位，一个故事点=一个普通系统的登录注册页面的前后端开发测试工作量。  \n\n先估算故事点，返回给用户，和用户确认故事点后，调用修改需求的接口修改需求的estimate字段。',	NULL,	'你作为一个资深产品经理。',	NULL,	'active',	'admin',	'2026-08-26 18:23:53',	'admin',	'2026-08-26 18:24:26',	NULL,	0),
(118,	'',	'normal',	'需求检查',	'对需求的内容进行检查。',	'deepseek-v4-flash',	'',	'',	'story',	'detail',	'empty.empty',	'',	'',	NULL,	NULL,	',story.id,story.product,story.branch,story.module,story.plan,story.source,story.sourceNote,story.fromBug,story.feedback,story.title,story.keywords,story.type,story.pri,story.estimate,story.status,story.color,story.stage,story.stagedBy,story.mailto,story.openedBy,story.openedDate,story.assignedTo,story.assignedDate,story.lastEditedBy,story.lastEditedDate,story.reviewedBy,story.reviewedDate,story.closedBy,story.closedDate,story.closedReason,story.toBug,story.duplicateStory,story.spec,story.verify,',	'empty.empty',	'请按四个方面逐项检查这条需求：\n 1. 完整性（需求是否完整)； \n2. 无歧义（手否有错别字、模糊词）； \n3. 可测试性（验收标准是否明确）；\n 4. 一致性（是否有冲突、重复）。  \n\n结果返回表格演示，按照序号、检查项、问题、修改建议字段返回。 可提可不提的问题不展示。',	NULL,	'你是需求评审助手。',	NULL,	'active',	'admin',	'2026-08-26 18:25:56',	'admin',	'2026-08-26 18:26:31',	NULL,	0),
(119,	'',	'normal',	'需求完善',	'对需求的标题、描述、验收标准进行完善。',	'',	'',	'',	'story',	'detail',	'story.change',	'',	'',	NULL,	NULL,	',story.id,story.product,story.branch,story.module,story.plan,story.source,story.sourceNote,story.fromBug,story.feedback,story.title,story.keywords,story.type,story.pri,story.estimate,story.status,story.color,story.stage,story.stagedBy,story.mailto,story.openedBy,story.openedDate,story.assignedTo,story.assignedDate,story.lastEditedBy,story.lastEditedDate,story.reviewedBy,story.reviewedDate,story.closedBy,story.closedDate,story.closedReason,story.toBug,story.duplicateStory,story.spec,story.verify,',	'story.change',	'帮忙优化其中各字段的表述，使表述清晰准确。必要时可以修改需求使其更加合理。   \n\n需求描述格式建议使用：作为一名<某种类型的用户>，我希望<达成某些目的>，这样可以<开发的价值>。验收标准建议列举多条。',	NULL,	'请你扮演一名资深的产品经理。',	NULL,	'active',	'admin',	'2026-08-26 18:27:16',	'admin',	'2026-08-26 18:27:38',	NULL,	0),
(124,	'',	'normal',	'建用例',	NULL,	'',	'',	'',	'case',	'form',	'testcase.create',	'',	'',	NULL,	NULL,	NULL,	'testcase.create',	'你的任务目标： \n1. 读取用户提供的需求内容，基于需求提炼测试点。\n2. 只创建 1 个测试用例，不拆分、不批量生成。\n3. 输出内容应可直接用于填写测试用例，不需要额外解释。 \n编写规则：\n1. 必须严格围绕“关联研发需求”编写，不得脱离需求主旨随意扩展无关场景。 \n2. 如果需求信息不足以支撑编写测试用例，先追问用户补充关键信息，再继续生成。 \n3. 一次只输出 1 个最有代表性、最核心的测试用例。 \n4. 默认优先编写“功能测试”类用例；只有当需求明确指向性能、安全、兼容性、异常处理等场景时，才切换对应类型。 \n5. 如果用户明确要求自动化，则按自动化用例思路编写；如果用户未明确要求，则默认按非自动化用例编写。 \n6. 优先级应根据业务影响和验收重要性合理判断：\n- 核心主流程、影响范围大、验收必测的场景，优先级设为 1 或 2 \n- 普通业务场景，优先级设为 3 - 边缘或低频场景，优先级设为 4 \n7. 前置条件必须简洁、明确、可执行，不能含糊，不能写成结论性描述。\n8. 用例步骤不限制固定步数，应以覆盖需求为准；只要能完整表达测试过程。用例步骤可以继续向下细分子步骤。父级用例步骤不生成预期结果。 \n9. 每个最后一个层级的步骤都必须有对应的预期结果，且预期结果必须可验证、可判定，避免使用“正常”“成功即可”“无异常”这类模糊表述。 \n10. 用例名称应准确概括测试目标，避免空泛命名，如“测试一下XX功能”“验证功能是否正常”。 \n11. 在单条用例中，除了主成功路径外，应尽量补充关键失败场景、异常场景、校验不通过场景或边界条件，使覆盖更全面。 \n12. 失败场景必须与当前需求直接相关，优先补充那些最可能影响验收结果、最容易出错、最关键的失败分支。 \n13. 不输出分析过程，不解释编写思路，不补充多余说明，只输出最终测试用例内容。 \n反向约束： \n1. 不要一次生成多个测试用例。 \n2. 不要把一个需求拆成多条独立用例分别输出。 \n3. 不要遗漏关键失败场景；如果需求本身存在明显的失败分支、异常输入、校验规则或权限限制，应纳入当前这 1 条用例中。 \n4. 不要出现模糊指令，如“进行相关操作”“查看是否正确”“确认功能正常”。 \n5. 如果需求不清晰，不要直接猜测后输出；应先向用户追问缺失的信息。 \n生成原则： \n- 优先选择最能体现需求是否实现成功的那个场景。 \n- 在同一条用例中，尽量同时覆盖主流程和关键失败分支。\n- 优先补充那些会导致提交失败、保存失败、校验失败、权限不足、状态不允许、必填项缺失、输入非法等失败场景。\n- 覆盖要全面，但始终只生成 1 条测试用例。',	NULL,	'你是一个测试用例编写智能体，负责根据“关联研发需求”生成并创建测试用例。',	NULL,	'active',	'admin',	'2026-08-26 19:12:43',	'admin',	'2026-08-26 19:14:48',	NULL,	0),
(126,	'',	'normal',	'理解需求',	'用于任务工序设计。',	'',	'',	'',	'task',	'detail',	'empty.empty',	'',	'',	NULL,	NULL,	',task.project,task.parent,task.id,task.execution,task.module,task.story,task.name,task.fromBug,task.type,task.pri,task.estimate,task.left,task.deadline,task.status,task.mailto,task.desc,',	'empty.empty',	'【任务】\n1. 提炼功能点：拆成可独立验证的功能点，逐条编号\n2. 提取约束：业务规则约束、技术约束、合规约束\n3. 识别待确认项：描述中含糊、缺失、自相矛盾的地方\n\n【输出格式】\n## 功能点清单\n| 编号 | 功能点 | 说明 | 前置依赖 |\n## 约束清单\n| 类型 | 约束内容 | 来源 |\n## 待确认项\n| 序号 | 问题 | 为什么必须确认 | 建议澄清对象 |\n\n\n【约束】\n- 不臆造需求里没有的功能\n- 拿不准的一律进待确认项，不要自行假设\n- 功能点数量超过 12 条时，提示需要二次拆分',	NULL,	'你是一名资深产品分析师，擅长把模糊需求拆成可验证的功能点。',	NULL,	'active',	'admin',	'2026-09-20 16:02:41',	'admin',	'2026-09-20 16:15:48',	NULL,	0),
(127,	'',	'normal',	'现状与约束分析',	'用于任务工序设计。',	'',	'',	'',	'task',	'detail',	'empty.empty',	'',	'',	NULL,	NULL,	',task.id,task.project,task.parent,task.execution,task.name,task.story,task.module,task.type,task.pri,task.estimate,task.consumed,task.status,task.left,task.deadline,task.mailto,task.desc,',	'empty.empty',	'【任务】\n1. 说明相关模块的现状（职责、依赖、已知问题）\n2. 列出约束：技术栈限制、历史包袱、并发与性能上限、合规要求\n3. 定位受影响模块，标注改动类型（新增 / 修改 / 废弃）与改动程度\n【输出格式】\n## 现状说明\n## 约束清单\n| 类型 | 约束 | 对设计的限制 |\n## 受影响模块\n| 模块 | 改动类型 | 改动程度 | 风险点 |\n【约束】\n- 只基于给出的材料判断，缺失的信息标注\"需补充\"\n- 不要给解决方案，这一步只做现状和约束',	NULL,	'你是一名系统架构师，擅长评估改动对现有系统的影响。',	NULL,	'active',	'admin',	'2026-09-20 16:10:40',	'admin',	'2026-09-20 16:31:51',	NULL,	0),
(128,	'',	'normal',	'方案设计',	'用于任务工序设计。',	'',	'',	'',	'task',	'detail',	'empty.empty',	'',	'',	NULL,	NULL,	',task.id,task.module,task.type,task.left,task.mailto,task.desc,task.deadline,task.project,task.story,task.pri,task.parent,task.fromBug,task.estimate,task.status,task.name,task.consumed,task.execution,',	'empty.empty',	'【任务】\n给出 2 到 3 个候选方案，每个方案包含：\n1. 核心思路\n2. 具体改动点（按模块列出）\n3. 优点\n4. 缺点与隐患\n5. 改动量粗估（人日）\n【输出格式】\n## 方案一：<名称>\n- 核心思路：\n- 改动点：\n- 优点：\n- 缺点与隐患：\n- 改动量：\n（方案二、方案三同构）\n## 方案差异速览\n| 维度 | 方案一 | 方案二 | 方案三 |\n【约束】\n- 方案之间必须有实质性差异，不要三个方案只差命名\n- 至少有一个方案是\"最小改动可交付\"路线\n- 不要在这一步下结论推荐哪个',	NULL,	'你是一名系统架构师，擅长给出多个可选技术方案。',	NULL,	'active',	'admin',	'2026-09-20 16:12:14',	'admin',	'2026-09-20 16:15:59',	NULL,	0),
(129,	'',	'normal',	'方案选型',	'用于任务工序设计。',	'',	'',	'',	'task',	'detail',	'empty.empty',	'',	'',	NULL,	NULL,	',task.id,task.module,task.type,task.left,task.mailto,task.assignedTo,task.desc,task.pri,task.deadline,task.story,task.project,task.status,task.estimate,task.fromBug,task.parent,task.execution,task.name,task.consumed,',	'empty.empty',	'评价维度与权重：{{weights}}（默认：改动量 25%、风险 25%、性能 20%、可维护性 20%、工期 10%）\n【任务】\n1. 按维度逐一打分（1–5 分）并给出打分依据\n2. 加权计算总分\n3. 推荐首选方案，说明理由\n4. 说明被否方案的关键否决点\n【输出格式】\n## 对比矩阵\n| 维度 | 权重 | 方案一 | 方案二 | 方案三 |\n|------|------|--------|--------|--------|\n| 打分 | | | | |\n| 依据 | | | | |\n## 加权总分\n## 推荐方案与理由\n## 被否方案的否决点\n【约束】\n- 每个分数必须配依据，不允许只给分数\n- 如果两个方案分数接近（差值 ≤ 0.3），必须指出并说明需要人工决策\n- 选型理由要能说服不熟悉技术细节的人，不要只堆术语',	NULL,	'你是一名技术负责人，需要在多方案中做出可解释的决策。',	NULL,	'active',	'admin',	'2026-09-20 16:13:12',	'admin',	'2026-09-20 16:16:04',	NULL,	0),
(130,	'',	'normal',	'接口与数据设计',	'用于任务工序设计。',	'',	'',	'',	'task',	'detail',	'empty.empty',	'',	'',	NULL,	NULL,	',task.id,task.project,task.parent,task.execution,task.module,task.story,task.fromBug,task.name,task.type,task.pri,task.estimate,task.consumed,task.left,task.deadline,task.status,task.color,task.mailto,task.desc,task.openedBy,task.openedDate,task.assignedTo,task.assignedDate,task.estStarted,',	'empty.empty',	'任务】\n1. 设计接口：路径、方法、入参、出参、错误码\n2. 设计数据变更：表结构 DDL、索引、字段说明\n3. 说明关键数据流转路径\n【输出格式】\n## 接口清单\n| 接口名 | 方法 | 路径 | 入参 | 出参 | 错误码 |\n## 数据表变更\n| 表名 | 变更类型 | 字段 | 类型 | 说明 |\n## DDL 脚本\n## 数据流转说明\n【约束】\n- 接口命名和错误码必须沿用现有规范，不自创风格\n- 涉及已有表的字段变更，必须标注是否兼容旧数据、是否需要数据回填\n- 只给设计，不写业务实现代码',	NULL,	'你是一名后端架构师，负责接口与数据模型设计。',	NULL,	'active',	'admin',	'2026-09-20 16:14:15',	'admin',	'2026-09-20 16:30:36',	NULL,	0),
(131,	'',	'normal',	'异常与回滚设计',	'用于任务工序设计。',	'',	'',	'',	'task',	'detail',	'empty.empty',	'',	'',	NULL,	NULL,	',task.id,task.module,task.type,task.left,task.mailto,task.deadline,task.pri,task.story,task.project,task.parent,task.fromBug,task.estimate,task.status,task.consumed,task.name,task.execution,task.desc,task.assignedTo,',	'empty.empty',	'【任务】\n1. 列出所有可能的异常场景：网络、超时、并发、幂等、数据不一致、依赖服务不可用\n2. 每个场景给出处理策略（重试 / 降级 / 熔断 / 补偿 / 报错）\n3. 给出回滚方案：回滚触发条件、回滚步骤、回滚后的数据一致性处理\n4. 给出灰度策略：灰度范围、观察指标、放量条件\n【输出格式】\n## 异常场景清单\n| 编号 | 场景 | 触发条件 | 处理策略 | 兜底方案 |\n## 回滚方案\n- 触发条件：\n- 回滚步骤：\n- 数据一致性处理：\n## 灰度策略\n- 灰度范围 / 观察指标 / 放量条件\n【约束】\n- 每条异常场景都必须有兜底方案，不允许留空\n- 回滚步骤必须可直接执行，不能是\"视情况处理\"\n- 涉及数据变更的，必须说明回滚时数据怎么办',	NULL,	'你是一名负责稳定性的架构师，专职找自己设计的漏洞。',	NULL,	'active',	'admin',	'2026-09-20 16:17:26',	'admin',	'2026-09-20 16:18:39',	NULL,	0),
(132,	'',	'normal',	'风险与评审材料',	'用于工序设计。',	'',	'',	'',	'task',	'detail',	'doc.create',	'',	'',	NULL,	NULL,	',task.id,task.module,task.type,task.left,task.mailto,task.assignedTo,task.desc,task.deadline,task.pri,task.story,task.project,task.parent,task.fromBug,task.estimate,task.status,task.consumed,task.name,task.execution,',	'doc.create',	'【任务】\n1. 汇总风险清单，按概率×影响分级\n2. 汇总所有待决问题，标注需要谁决策\n3. 生成评审要点：评审会上最需要讨论的 5 个问题\n4. 整合成完整的设计文档\n【输出格式】\n## 一、背景与目标\n## 二、需求要点\n## 三、现状与约束\n## 四、方案对比与选型\n## 五、接口与数据设计\n## 六、异常与回滚\n## 七、风险清单\n| 风险 | 概率 | 影响 | 等级 | 应对 |\n## 八、待决问题\n| 问题 | 需谁决策 | 期望时间 |\n## 九、评审要点（Top 5）\n【约束】\n- 文档中不得出现\"待补充\"\"TBD\"而无责任人\n- 评审要点必须是最需要争议的问题，不要把结论当问题',	NULL,	'你是一名技术负责人，负责把设计成果整理成可评审的文档。',	NULL,	'active',	'admin',	'2026-09-20 16:18:59',	'admin',	'2026-09-20 16:34:40',	NULL,	0),
(133,	'',	'normal',	'从需求中提炼测试点',	'用于工序设计。',	'',	'',	'',	'story',	'detail',	'empty.empty',	'',	'',	NULL,	NULL,	',story.id,story.product,story.branch,story.module,story.plan,story.source,story.sourceNote,story.fromBug,story.feedback,story.title,story.keywords,story.type,story.pri,story.estimate,story.status,story.color,story.stage,story.stagedBy,story.mailto,story.openedBy,story.openedDate,story.assignedTo,story.assignedDate,story.lastEditedBy,story.lastEditedDate,story.reviewedBy,story.reviewedDate,story.closedBy,story.closedDate,story.closedReason,story.toBug,story.duplicateStory,story.spec,story.verify,',	'empty.empty',	'【任务】\n1. 界定测试范围（测什么、明确不测什么）\n2. 提炼可测点：每个可观测、可判定的功能点\n3. 标注可测点的验证方式（界面 / 接口 / 数据）\n【输出格式】\n## 测试范围\n- 覆盖：\n- 不覆盖：\n## 可测点清单\n| 编号 | 可测点 | 验证方式 | 对应用户场景 |\n【约束】\n- 只提炼，不设计具体用例\n- 无法验证的点标注\"不可测\"并说明原因',	NULL,	'你是一名资深测试工程师，擅长从需求中提炼可测试的点。',	NULL,	'active',	'admin',	'2026-09-20 16:33:48',	'admin',	'2026-09-20 16:34:34',	NULL,	0),
(134,	'',	'normal',	'需求场景拆解',	'用于工序设计。',	'',	'',	'',	'story',	'detail',	'empty.empty',	'',	'',	NULL,	NULL,	',story.id,story.product,story.branch,story.module,story.plan,story.source,story.sourceNote,story.fromBug,story.feedback,story.title,story.keywords,story.type,story.pri,story.estimate,story.status,story.color,story.stage,story.stagedBy,story.mailto,story.openedBy,story.openedDate,story.assignedTo,story.assignedDate,story.lastEditedBy,story.lastEditedDate,story.reviewedBy,story.reviewedDate,story.closedBy,story.closedDate,story.closedReason,story.toBug,story.duplicateStory,story.spec,story.verify,',	'empty.empty',	'【任务】\n对每个可测点，按以下五类拆场景：\n1. 正向场景：正常流程\n2. 异常场景：非法输入、错误操作、依赖失败\n3. 边界场景：空值、最大最小值、长度临界\n4. 权限场景：不同角色、越权访问\n5. 兼容场景：多端、多浏览器、多数据版本\n【输出格式】\n## 场景清单\n| 可测点编号 | 场景类型 | 场景描述 | 预期结果 |\n【约束】\n- 每个可测点至少覆盖正向和异常两类\n- 每类场景必须给出具体数值或具体条件，不能写\"极端情况\"\n- 场景数量超过 40 条时，提示是否需要拆分用例集',	NULL,	'你是一名资深测试工程师，擅长用分类法保证场景覆盖。',	NULL,	'active',	'admin',	'2026-09-20 16:35:05',	'admin',	'2026-09-20 16:35:35',	NULL,	0),
(135,	'',	'normal',	'研发需求用例编写',	'用于工序设计。',	'',	'',	'',	'story',	'detail',	'story.testcasecreate',	'',	'',	NULL,	NULL,	',story.id,story.product,story.branch,story.module,story.plan,story.source,story.sourceNote,story.fromBug,story.feedback,story.title,story.keywords,story.type,story.pri,story.estimate,story.status,story.color,story.stage,story.stagedBy,story.mailto,story.openedBy,story.openedDate,story.assignedTo,story.assignedDate,story.lastEditedBy,story.lastEditedDate,story.reviewedBy,story.reviewedDate,story.closedBy,story.closedDate,story.closedReason,story.toBug,story.duplicateStory,story.spec,story.verify,',	'story.testcasecreate',	'【任务】\n把每个场景转成一条规范用例，包含：\n- 用例标题：动词开头，一句话说清验证什么\n- 前置条件：执行前必须满足的状态\n- 操作步骤：编号分步，每步一个动作\n- 预期结果：可观察到、可判定的结果\n- 用例类型：功能 / 性能 / 接口 / 兼容 / 安全\n- 优先级：P0 阻塞 / P1 重要 / P2 一般 / P3 低\n\n【约束】\n- 一条用例只验证一个点，不要把多个验证混在一条\n- 步骤和预期必须一一对应\n- 预期结果禁止写\"正常显示\"\"符合预期\"这类空话',	NULL,	'你是一名测试工程师，按团队规范编写测用例。',	NULL,	'active',	'admin',	'2026-09-20 16:36:06',	'admin',	'2026-09-20 16:37:22',	NULL,	0),
(136,	'',	'normal',	'反馈原文规范化',	'用于工序设计。',	'',	'',	'',	'feedback',	'detail',	'feedback.edit',	'',	'',	NULL,	NULL,	',feedback.id,feedback.product,feedback.module,feedback.title,feedback.type,feedback.solution,feedback.desc,feedback.status,feedback.public,feedback.notify,feedback.notifyEmail,feedback.source,feedback.likes,feedback.result,feedback.faq,feedback.openedBy,feedback.openedDate,feedback.reviewedBy,feedback.reviewedDate,feedback.processedBy,feedback.processedDate,feedback.closedBy,feedback.closedDate,feedback.closedReason,feedback.editedBy,feedback.editedDate,feedback.assignedTo,feedback.assignedDate,feedback.feedbackBy,feedback.mailto,',	'feedback.edit',	'任务】\n把原始描述改写为规范结构：\n1. 问题现象：客观描述发生了什么\n2. 影响范围：影响哪些用户、哪些功能、什么场景\n3. 期望结果：客户期望达到什么\n4. 复现线索：时间、操作路径、环境等可复现的信息，能提取多少写多少\n5. 信息完整度评分：对上述四要素逐项打 0–3 分并说明依据\n\n【约束】\n- 保留客户的原始表述作为引用，不要改动原话\n- 不要替客户下判断，比如把\"系统坏了\"改成\"接口超时\"\n- 提取不到的要素写\"未提供\"，不要编造',	NULL,	'你是一名客户成功专员，擅长把口语化反馈整理成规范记录。',	NULL,	'active',	'admin',	'2026-09-20 16:41:53',	'admin',	'2026-09-20 16:42:45',	NULL,	0),
(137,	'',	'normal',	'反馈信息澄清',	'用于工序设计。',	'',	'',	'',	'feedback',	'detail',	'empty.empty',	'',	'',	NULL,	NULL,	',feedback.id,feedback.product,feedback.module,feedback.title,feedback.type,feedback.solution,feedback.desc,feedback.status,feedback.public,feedback.notify,feedback.notifyEmail,feedback.source,feedback.likes,feedback.result,feedback.faq,feedback.openedBy,feedback.openedDate,feedback.reviewedBy,feedback.reviewedDate,feedback.processedBy,feedback.processedDate,feedback.closedBy,feedback.closedDate,feedback.closedReason,feedback.editedBy,feedback.editedDate,feedback.assignedTo,feedback.assignedDate,feedback.feedbackBy,feedback.mailto,',	'empty.empty',	'任务】\n1. 列出缺失的关键信息\n2. 生成澄清问题清单，问题要具体、好回答\n3. 标注每个问题不答会影响什么判断\n【输出格式】\n## 缺失信息清单\n| 信息项 | 为什么重要 | 不获取的后果 |\n## 澄清问题（可直接发给客户）\n1.\n2.\n## 需内部确认的问题\n| 问题 | 问谁 |\n【约束】\n- 问题必须具体，禁止\"请详细描述一下\"\n- 一次最多问 5 个问题，按重要性排序\n- 能从客户资料或历史记录里推断出来的，不要重复问客户',	NULL,	'你是一名客户成功专员，负责向客户澄清信息。',	NULL,	'active',	'admin',	'2026-09-20 16:43:31',	'admin',	'2026-09-20 16:44:02',	NULL,	0),
(138,	'',	'normal',	'反馈去重',	'用于工序设计。',	'',	'',	'',	'feedback',	'detail',	'empty.empty',	'',	'',	NULL,	NULL,	',feedback.id,feedback.product,feedback.module,feedback.title,feedback.type,feedback.solution,feedback.desc,feedback.status,feedback.public,feedback.notify,feedback.notifyEmail,feedback.source,feedback.likes,feedback.result,feedback.faq,feedback.openedBy,feedback.openedDate,feedback.reviewedBy,feedback.reviewedDate,feedback.processedBy,feedback.processedDate,feedback.closedBy,feedback.closedDate,feedback.closedReason,feedback.editedBy,feedback.editedDate,feedback.assignedTo,feedback.assignedDate,feedback.feedbackBy,feedback.mailto,',	'empty.empty',	'【任务】\n1. 与历史反馈逐条比对，判断是否指向同一问题\n2. 给出判定结论：重复 / 疑似重复 / 不重复\n3. 判定为重复或疑似的，给出关联反馈 ID 和判定依据\n【输出格式】\n## 判定结论\n- 结论：重复 / 疑似重复 / 不重复\n## 关联反馈\n| 反馈ID | 标题 | 相似点 | 差异点 | 处理建议 |\n## 判定依据\n\n和用户确认是否要关闭重复反馈，如果用户关闭的话，关闭的话重复反馈选择哪一个。\n\n【约束】\n- 按\"是否为同一根因或同一现象\"判断，不只看措辞相似\n- 疑似重复必须给出具体差异，不要含糊\n- 结论为不重复时，也要说明比对过哪些、为什么排除',	NULL,	'你是一名反馈运营，负责识别重复反馈。',	NULL,	'active',	'admin',	'2026-09-20 16:44:36',	'admin',	'2026-09-20 16:51:55',	NULL,	0),
(139,	'',	'normal',	'反馈处理方案',	'用于工序设计。',	'',	'',	'',	'feedback',	'detail',	'empty.empty',	'',	'',	NULL,	NULL,	',feedback.id,feedback.product,feedback.module,feedback.title,feedback.type,feedback.solution,feedback.desc,feedback.status,feedback.public,feedback.notify,feedback.notifyEmail,feedback.source,feedback.likes,feedback.result,feedback.faq,feedback.openedBy,feedback.openedDate,feedback.reviewedBy,feedback.reviewedDate,feedback.processedBy,feedback.processedDate,feedback.closedBy,feedback.closedDate,feedback.closedReason,feedback.editedBy,feedback.editedDate,feedback.assignedTo,feedback.assignedDate,feedback.feedbackBy,feedback.mailto,',	'empty.empty',	'【任务】\n1. 判断该反馈应转为：研发需求 / Bug / 任务 / 工单 / 待办\n2. 说明判定理由\n3. 如果转需求或 Bug，直接生成符合规范的标题与描述草稿\n【输出格式】\n## 分流建议\n- 建议去向：\n- 置信度：高 / 中 / 低\n## 判定理由\n| 判断维度 | 实际情况 |\n| 是缺陷还是新功能 | |\n| 影响面是单客户还是通用 | |\n| 是否需研发排期 | |\n| 是否紧急需即时响应 | |\n## 目标对象草稿\n- 标题：\n- 描述：\n- 优先级：\n- 所属模块：\n【约束】\n- 只给建议，不要执行转化动作\n- 置信度为\"低\"时必须明确说明人工需要判断什么\n- 目标对象草稿要符合团队既有规范（如 Bug 需含重现步骤/环境/预期与实际）',	NULL,	'你是一名产品负责人，负责判断反馈的处理去向。',	NULL,	'active',	'admin',	'2026-09-20 16:46:23',	'admin',	'2026-09-20 16:47:11',	NULL,	0);

INSERT INTO `zt_ai_procedure` (`id`, `name`, `module`, `desc`, `status`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `publishedBy`, `unpublishedBy`, `deleted`) VALUES
(1,	'研发需求工序',	'story',	'需求完善--需求检查--工作量估算--价值评估',	'draft',	'admin',	'2026-08-26 18:37:14',	'',	NULL,	'',	'',	0),
(3,	'详细设计文档编写',	'task',	'理解需求 → 现状约束 → 方案设计 → 方案选型 → 接口数据 → 异常回滚 → 风险评审',	'draft',	'admin',	'2026-09-20 16:16:42',	'admin',	'2026-09-20 16:29:40',	'',	'',	0),
(4,	'测试用例设计',	'story',	'测试点提炼 → 场景拆解 → 用例编写',	'draft',	'admin',	'2026-09-20 16:37:43',	'admin',	'2026-09-20 16:52:21',	'admin',	'admin',	0),
(5,	'客户反馈受理与分派',	'feedback',	'反馈原文规范 → 反馈信息澄清 → 反馈去重 → 反馈处理方案',	'draft',	'admin',	'2026-09-20 16:47:36',	'admin',	'2026-09-20 16:55:02',	'',	'',	0);

INSERT INTO `zt_ai_procedurestep` (`id`, `procedure`, `agent`, `name`, `inputs`, `order`) VALUES
(30,	3,	126,	'理解需求',	NULL,	1),
(31,	3,	127,	'现状与约束分析',	'{\"1\":30}',	2),
(32,	3,	128,	'方案设计',	'{\"1\":31}',	3),
(33,	3,	129,	'方案选型',	'{\"1\":32}',	4),
(34,	3,	130,	'接口与数据设计',	'{\"1\":32,\"2\":33}',	5),
(35,	3,	131,	'异常与回滚设计',	'{\"1\":34}',	6),
(36,	3,	132,	'风险与评审材料',	'{\"1\":30,\"2\":31,\"3\":32,\"4\":33,\"5\":34,\"6\":35}',	7),
(56,	5,	136,	'反馈原文规范化',	NULL,	1),
(57,	5,	137,	'反馈信息澄清',	NULL,	2),
(58,	5,	138,	'反馈去重',	NULL,	3),
(59,	5,	139,	'反馈处理方案',	NULL,	4),
(60,	4,	133,	'从需求中提炼测试点',	NULL,	1),
(61,	4,	134,	'需求场景拆解',	'{\"1\":60}',	2),
(62,	4,	135,	'研发需求用例编写',	'{\"1\":61,\"2\":60}',	3),
(73,	1,	119,	'需求完善',	NULL,	1),
(74,	1,	118,	'需求检查',	NULL,	2),
(75,	1,	117,	'需求工作量估算',	NULL,	3),
(76,	1,	116,	'需求价值评估',	NULL,	4);

UPDATE `zt_cron` SET `command` = 'moduleName=execution&methodName=computeburn' WHERE `command` = 'moduleName=project&methodName=computeburn';
