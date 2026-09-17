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