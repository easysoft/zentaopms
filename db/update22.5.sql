ALTER TABLE `ops_runner` MODIFY COLUMN `labels` varchar(255) NOT NULL DEFAULT '' COMMENT '标签';
ALTER TABLE `ops_runner` ADD COLUMN `isDefault` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否为默认 runner(0:不是, 1:是)' AFTER `status`;
ALTER TABLE `ops_runner` ADD COLUMN `runtime`  varchar(50) NOT NULL DEFAULT '' COMMENT '运行方式' AFTER `arch`;

ALTER TABLE `ops_scan_issues`
CHANGE COLUMN `message` `title` varchar(500) NOT NULL DEFAULT '' COMMENT '问题描述信息',
CHANGE COLUMN `line` `startLine` int unsigned NOT NULL DEFAULT 0 COMMENT '问题起始行',
CHANGE COLUMN `resolved` `resolvedDate` datetime DEFAULT NULL COMMENT '问题解决时间',
CHANGE COLUMN `closed` `closedDate` datetime DEFAULT NULL COMMENT '问题关闭时间',
CHANGE COLUMN `ignored` `ignoredDate` bigint NOT NULL DEFAULT 0 COMMENT '问题忽略到期时间',
MODIFY COLUMN `scanMethod` varchar(20) NOT NULL DEFAULT '' COMMENT '扫描方法（check/smell/ai）';

ALTER TABLE `ops_scan_issues`
ADD COLUMN `ppmID` int unsigned NOT NULL DEFAULT 0 COMMENT '合并请求ID',
ADD COLUMN `content` text DEFAULT NULL COMMENT '问题详细内容',
ADD COLUMN `endLine` int unsigned NOT NULL DEFAULT 0 COMMENT '问题结束行',
ADD COLUMN `oldCode` text DEFAULT NULL COMMENT '原代码片段',
ADD COLUMN `newCode` text DEFAULT NULL COMMENT '建议代码片段',
ADD COLUMN `category` varchar(30) NOT NULL DEFAULT 'other' COMMENT '问题类别（critical,high,medium,low）',
ADD COLUMN `severity` varchar(30) NOT NULL DEFAULT '' COMMENT '严重程度（bug,security,performance,maintainability,test,style,documentation,other）',
ADD COLUMN `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '创建人',
ADD COLUMN `editedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '更新人';

CREATE INDEX `idx_createdByTaskID` ON `ops_scan_issues` (`createdByTaskID`);
CREATE INDEX `idx_ppmID` ON `ops_scan_issues` (`ppmID`);

-- DROP TABLE IF EXISTS `zt_ai_vectorqueue`;
CREATE TABLE IF NOT EXISTS `zt_ai_vectorqueue` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `objectType` varchar(255) NOT NULL DEFAULT '' COMMENT '对象类型',
  `objectID` int unsigned NOT NULL DEFAULT 0 COMMENT '对象ID',
  `retries` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '重试次数',
  `lastError` text NULL DEFAULT NULL COMMENT '最后错误',
  `lastSyncTime` datetime NULL DEFAULT NULL COMMENT '最后同步时间',
  `createdDate` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `editedDate` datetime NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;
CREATE UNIQUE INDEX `uk_object` ON `zt_ai_vectorqueue` (`objectType`, `objectID`);
CREATE INDEX `idx_retries` ON `zt_ai_vectorqueue` (`retries`);

INSERT INTO `zt_cron` (`m`, `h`, `dom`, `mon`, `dow`, `command`, `remark`, `type`, `buildin`, `status`, `lastTime`, `timeout`) VALUES
('*/5', '*', '*', '*', '*', 'moduleName=zai&methodName=syncVectorization', '自动同步向量化数据', 'zentao', 1, 'normal', NULL, 300);

CREATE TABLE IF NOT EXISTS `zt_teamgroup` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `type` char(30) NOT NULL DEFAULT '' COMMENT '类型',
  `logo` text DEFAULT NULL COMMENT '团队logo',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '团队名称',
  `manager` text DEFAULT NULL COMMENT '负责人',
  `parent` int unsigned NOT NULL DEFAULT 0 COMMENT '所属团队',
  `grade` smallint unsigned NOT NULL DEFAULT 1 COMMENT '级别',
  `path` text DEFAULT NULL COMMENT '路径',
  `status` char(30) NOT NULL DEFAULT 'enable' COMMENT '状态',
  `slogan` varchar(255) NOT NULL DEFAULT '' COMMENT '团队口号',
  `declaration` text DEFAULT NULL COMMENT '团队信条',
  `createdBy` char(30) NOT NULL DEFAULT '' COMMENT '由谁创建',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  `lastEditedBy` char(30) NOT NULL DEFAULT '' COMMENT '由谁编辑',
  `lastEditedDate` datetime DEFAULT NULL COMMENT '编辑时间',
  `disbandedDate` datetime DEFAULT NULL COMMENT '解散时间',
  `deleted` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='团队组织结构表';

CREATE TABLE IF NOT EXISTS `zt_pi` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `ART` int unsigned NOT NULL DEFAULT 0 COMMENT '项目组 ID',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '名称',
  `product` text DEFAULT NULL COMMENT '关联产品',
  `team` text DEFAULT NULL COMMENT '关联团队',
  `status` char(30) NOT NULL DEFAULT 'normal' COMMENT '状态',
  `desc` text DEFAULT NULL COMMENT '描述',
  `acl` char(30) NOT NULL DEFAULT 'extends' COMMENT '访问控制',
  `whitelist` text DEFAULT NULL COMMENT '白名单',
  `teamkanban` int unsigned NOT NULL DEFAULT 0 COMMENT '团队看板',
  `plankanban` int unsigned NOT NULL DEFAULT 0 COMMENT '项目看板',
  `createdBy` char(30) NOT NULL DEFAULT '' COMMENT '由谁创建',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  `lastEditedBy` char(30) NOT NULL DEFAULT '' COMMENT '由谁编辑',
  `lastEditedDate` datetime DEFAULT NULL COMMENT '编辑时间',
  `closedBy` char(30) NOT NULL DEFAULT '' COMMENT '由谁关闭',
  `closedDate` datetime DEFAULT NULL COMMENT '关闭时间',
  `closedReason` text DEFAULT NULL COMMENT '关闭原因',
  `activatedBy` char(30) NOT NULL DEFAULT '' COMMENT '由谁激活',
  `activatedDate` datetime DEFAULT NULL COMMENT '激活时间',
  `deleted` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='PI表';

CREATE TABLE IF NOT EXISTS `zt_pistory` (
  `pi` int unsigned NOT NULL DEFAULT 0 COMMENT 'PI ID',
  `story` int unsigned NOT NULL DEFAULT 0 COMMENT '需求 ID',
  `order` int unsigned NOT NULL DEFAULT 0 COMMENT '顺序'
) ENGINE=InnoDB COMMENT='PI需求关联表';
CREATE UNIQUE INDEX `uk_pistory` ON `zt_pistory` (`pi`,`story`);

CREATE TABLE IF NOT EXISTS `zt_piexecution` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `pi` int unsigned NOT NULL DEFAULT 0 COMMENT 'PI ID',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '名称',
  `begin` date DEFAULT NULL COMMENT '开始时间',
  `end` date DEFAULT NULL COMMENT '结束时间',
  `order` int unsigned NOT NULL DEFAULT 0 COMMENT '顺序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='PI执行表';

CREATE TABLE IF NOT EXISTS `zt_art` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '名称',
  `status` char(30) NOT NULL DEFAULT 'normal' COMMENT '状态',
  `product` text DEFAULT NULL COMMENT '关联产品',
  `RTE` varchar(255) NOT NULL DEFAULT '' COMMENT 'RTE',
  `manager` varchar(255) NOT NULL DEFAULT '' COMMENT '负责人',
  `PO` varchar(255) NOT NULL DEFAULT '' COMMENT 'PO',
  `architect` varchar(255) NOT NULL DEFAULT '' COMMENT '架构师',
  `team` varchar(255) NOT NULL DEFAULT '' COMMENT '团队',
  `desc` longtext DEFAULT NULL COMMENT '描述',
  `acl` varchar(30) NOT NULL DEFAULT 'open' COMMENT '访问控制',
  `whitelist` text DEFAULT NULL COMMENT '白名单',
  `createdBy` varchar(255) NOT NULL DEFAULT '' COMMENT '由谁创建',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  `lastEditedBy` char(30) NOT NULL DEFAULT '' COMMENT '由谁编辑',
  `lastEditedDate` datetime DEFAULT NULL COMMENT '编辑时间',
  `closedBy` varchar(255) NOT NULL DEFAULT '' COMMENT '由谁关闭',
  `closedDate` datetime DEFAULT NULL COMMENT '关闭时间',
  `activatedBy` varchar(255) NOT NULL DEFAULT '' COMMENT '由谁激活',
  `activatedDate` datetime DEFAULT NULL COMMENT '激活时间',
  `deleted` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='项目组表';

CREATE TABLE IF NOT EXISTS `zt_kanbanlinks` (
  `kanban` int unsigned NOT NULL DEFAULT 0 COMMENT '看板ID',
  `from` char(30) NOT NULL DEFAULT '' COMMENT '源ID',
  `to` char(30) NOT NULL DEFAULT '' COMMENT '目标ID'
) ENGINE=InnoDB COMMENT='看板关联表';
CREATE UNIQUE INDEX `uk_kanbanlinks` ON `zt_kanbanlinks`(`kanban`,`from`,`to`);

ALTER TABLE `zt_kanbanlane`   ADD `team` int unsigned NOT NULL DEFAULT 0 COMMENT '团队'  AFTER `execution`;
ALTER TABLE `zt_kanbancolumn` ADD `execution` int unsigned NOT NULL DEFAULT 0 COMMENT '执行' AFTER `type`;
ALTER TABLE `zt_kanbancolumn` ADD `piexecution` int unsigned NOT NULL DEFAULT 0 COMMENT 'PI执行' AFTER `execution`;
ALTER TABLE `zt_kanbancolumn` ADD `capacity` varchar(255) NOT NULL DEFAULT '0' COMMENT '容量' AFTER `execution`;

ALTER TABLE `zt_team` ADD `teamgroup` int unsigned NOT NULL DEFAULT 0 COMMENT '团队组' AFTER `type`;
ALTER TABLE `zt_risk` ADD `PI` int unsigned NOT NULL DEFAULT 0 COMMENT '规划' AFTER `execution`;
ALTER TABLE `zt_risk` ADD `team` int unsigned NOT NULL DEFAULT 0 COMMENT '团队' AFTER `PI`;
ALTER TABLE `zt_story` ADD `cardColor` char(30) NOT NULL DEFAULT '' COMMENT '卡片颜色' AFTER `color`;
ALTER TABLE `zt_project` ADD `PI` int unsigned NOT NULL DEFAULT 0 COMMENT 'PI' AFTER `market`;
ALTER TABLE `zt_effort` ADD `team` int unsigned NOT NULL DEFAULT 0 COMMENT '团队' AFTER `execution`;

ALTER TABLE `zt_story` ADD COLUMN `prevReviewers` text DEFAULT NULL AFTER `stagedBy`;

ALTER TABLE `zt_ai_agent` ADD `type` varchar(20) NOT NULL DEFAULT 'normal' COMMENT '智能体类型：normal普通/timer定时' AFTER `code`;
ALTER TABLE `zt_ai_agent` ADD `operation` varchar(30) NOT NULL DEFAULT '' COMMENT '定时操作目的：report/risk/notify' AFTER `actionPurpose`;
ALTER TABLE `zt_ai_agent` ADD `cycleType` varchar(10) NOT NULL DEFAULT '' COMMENT '周期类型：day/week/month' AFTER `operation`;
ALTER TABLE `zt_ai_agent` ADD `cycleConfig` text NULL DEFAULT NULL COMMENT '周期配置' AFTER `cycleType`;
ALTER TABLE `zt_ai_agent` ADD `notifyRule` text NULL DEFAULT NULL COMMENT '通知规则JSON：roles/users/methods' AFTER `cycleConfig`;
ALTER TABLE `zt_ai_agent` ADD `lastRunDate` datetime NULL DEFAULT NULL COMMENT '最近一次定时执行时间' AFTER `editedDate`;

UPDATE `zt_ai_agent` SET `module` = 'task', `displayPosition` = 'form', `targetForm` = 'task.batchcreate', `actionPurpose` = 'task.batchcreate' WHERE `targetForm` = 'execution.batchcreatetask' OR `actionPurpose` = 'execution.batchcreatetask';

CREATE TABLE IF NOT EXISTS `zt_ai_timerlog` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `agent` int unsigned NOT NULL DEFAULT 0 COMMENT '智能体ID',
  `status` varchar(20) NOT NULL DEFAULT '' COMMENT '执行状态：success/fail/partial',
  `successCount` int unsigned NOT NULL DEFAULT 0 COMMENT '成功数量',
  `failCount` int unsigned NOT NULL DEFAULT 0 COMMENT '失败数量',
  `message` varchar(500) NOT NULL DEFAULT '' COMMENT '展示文案',
  `error` varchar(1000) NOT NULL DEFAULT '' COMMENT '错误摘要',
  `createdDate` datetime NULL DEFAULT NULL COMMENT '执行时间',
  `deleted` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `zt_ai_timerqueue` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `agent` int unsigned NOT NULL DEFAULT 0 COMMENT '智能体ID',
  `objectType` varchar(30) NOT NULL DEFAULT '' COMMENT '对象类型',
  `objectID` int unsigned NOT NULL DEFAULT 0 COMMENT '对象ID',
  `status` varchar(10) NOT NULL DEFAULT 'wait' COMMENT '状态：wait/doing/done',
  `content` mediumtext NULL DEFAULT NULL COMMENT 'AI通知正文',
  `toList` varchar(1000) NOT NULL DEFAULT '' COMMENT '通知人账号列表',
  `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁创建',
  `createdDate` datetime NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE INDEX `idx_agent_status` ON `zt_ai_timerqueue`(`agent`, `status`);
CREATE INDEX `idx_agent_object` ON `zt_ai_timerqueue`(`agent`, `objectType`, `objectID`);

REPLACE INTO `zt_cron` (`m`, `h`, `dom`, `mon`, `dow`, `command`, `remark`, `type`, `buildin`, `status`, `lastTime`, `timeout`) VALUES
('*/5', '*', '*', '*', '*', 'moduleName=ai&methodName=runTimerAgents', '执行定时智能体', 'zentao', 1, 'normal', NULL, 300);

ALTER TABLE `zt_ai_useragent` ADD COLUMN `type` varchar(30) NOT NULL DEFAULT '' COMMENT '类型：'' | executor' AFTER `agent`;

ALTER TABLE `zt_notify` MODIFY COLUMN `action` int unsigned NOT NULL DEFAULT 0;

-- DROP TABLE IF EXISTS `zt_errorlog`;
CREATE TABLE IF NOT EXISTS `zt_errorlog` (
  `md5` char(32) NOT NULL DEFAULT '' COMMENT '错误签名MD5',
  `file` varchar(255) NOT NULL DEFAULT '' COMMENT '错误文件',
  `line` int unsigned NOT NULL DEFAULT 0 COMMENT '错误行号',
  `level` smallint unsigned NOT NULL DEFAULT 0 COMMENT '错误级别',
  `message` text DEFAULT NULL COMMENT '错误信息',
  `trace` text DEFAULT NULL COMMENT '错误堆栈',
  PRIMARY KEY (`md5`)
) ENGINE=InnoDB COMMENT='错误本体';

-- DROP TABLE IF EXISTS `zt_errorlogreq`;
CREATE TABLE IF NOT EXISTS `zt_errorlogreq` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `requestID` varchar(64) NOT NULL DEFAULT '' COMMENT '请求ID',
  `md5` char(32) NOT NULL DEFAULT '' COMMENT '错误签名MD5',
  `module` varchar(30) NOT NULL DEFAULT '' COMMENT '模块',
  `method` varchar(100) NOT NULL DEFAULT '' COMMENT '方法',
  `account` varchar(30) NOT NULL DEFAULT '' COMMENT '用户账号',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '请求地址',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='错误请求记录';
CREATE UNIQUE INDEX `uk_requestID_md5` ON `zt_errorlogreq`(`requestID`, `md5`);
CREATE INDEX `idx_requestID`   ON `zt_errorlogreq`(`requestID`);
CREATE INDEX `idx_md5`         ON `zt_errorlogreq`(`md5`);
CREATE INDEX `idx_createdDate` ON `zt_errorlogreq`(`createdDate`);

REPLACE INTO `zt_cron` (`m`, `h`, `dom`, `mon`, `dow`, `command`, `remark`, `type`, `buildin`, `status`, `lastTime`, `timeout`) VALUES
('*/5', '*', '*', '*', '*', 'moduleName=errorlog&methodName=deleteLog', '删除过期错误日志', 'zentao', 1, 'normal', NULL, 300);

DELETE FROM `zt_workflowfield`  WHERE `module` = 'ticket' AND `field` = 'consumed';
DELETE FROM `zt_workflowlayout` WHERE `module` = 'ticket' AND `field` = 'consumed';

ALTER TABLE `zt_workflowrule` ADD COLUMN `builtin` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否为内置规则(0:不是, 1:是)' AFTER `rule`;
UPDATE `zt_workflowrule` SET `builtin` = 1 WHERE `type` = 'system';
