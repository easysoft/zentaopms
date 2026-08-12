ALTER TABLE `zt_ai_agent` ADD COLUMN `displayPosition` varchar(20) NOT NULL DEFAULT '' COMMENT '显示位置，目前包括：详情页（detail）、表单页（form）' AFTER `module`;
ALTER TABLE `zt_ai_agent` ADD COLUMN `actionPurpose` varchar(100) NOT NULL DEFAULT '' COMMENT '操作目的编码' AFTER `displayPosition`;

UPDATE `zt_ai_agent` SET `displayPosition` = 'detail', `actionPurpose` = `targetForm` WHERE `displayPosition` = '';
ALTER TABLE `zt_ai_agent` ADD COLUMN `skill` int unsigned DEFAULT 0 COMMENT '关联的技能ID' AFTER `knowledgeLib`;

CREATE TABLE `zt_ai_skill` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT 'Skill名称',
  `type` varchar(20) NOT NULL DEFAULT 'private' COMMENT '类型：个人/公开',
  `category` int unsigned NOT NULL DEFAULT 0 COMMENT '技能类型(模块ID)',
  `status` varchar(20) NOT NULL DEFAULT '' COMMENT '状态',
  `fromID` int unsigned NOT NULL DEFAULT 0 COMMENT '来源ID（可将公开技能复制成个人技能）',
  `skillID` varchar(64) NOT NULL DEFAULT '' COMMENT 'ZAI中的skillID',
  `desc` text DEFAULT NULL COMMENT '描述',
  `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '创建人',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  `editedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '最后修改人ID',
  `editedDate` datetime DEFAULT NULL COMMENT '最后修改时间',
  `deleted` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE `zt_ai_useragent` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `account` varchar(30) NOT NULL DEFAULT '' COMMENT '禅道用户名',
  `agent` varchar(255) NOT NULL DEFAULT '' COMMENT 'ZAI agent ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;
CREATE UNIQUE INDEX `uk_account_agent` ON `zt_ai_useragent` (`account`, `agent`);

ALTER TABLE `zt_testrun` ADD COLUMN `caseVersion` smallint unsigned NOT NULL DEFAULT 0 COMMENT '用例版本' AFTER `case`;
ALTER TABLE `zt_suitecase` ADD COLUMN `caseVersion` smallint unsigned NOT NULL DEFAULT 0 COMMENT '用例版本' AFTER `case`;
ALTER TABLE `zt_release` MODIFY COLUMN `releasedDate` datetime NULL;

INSERT INTO `zt_config`(`vision`, `owner`, `module`, `section`, `key`, `value`) VALUES ('', 'system', 'project',   '', 'ganttVersionSettings', 'deliverable');
INSERT INTO `zt_config`(`vision`, `owner`, `module`, `section`, `key`, `value`) VALUES ('', 'system', 'execution', '', 'ganttVersionSettings', 'gantt');

UPDATE `zt_config` SET `value` = 'ui20' WHERE `module` = 'common' AND `section` = 'global' AND `key` = 'showUpgradeGuide';
