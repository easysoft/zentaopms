ALTER TABLE `zt_ai_agent` MODIFY COLUMN `skill` varchar(255) NOT NULL DEFAULT '' COMMENT '关联的技能ID列表';
UPDATE `zt_ai_agent` SET `skill` = '' WHERE `skill` = '0';
