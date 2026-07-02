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