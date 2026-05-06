ALTER TABLE `zt_project` ADD `schedule` mediumtext DEFAULT NULL AFTER `days`;

ALTER TABLE `zt_projectdeliverable` ADD COLUMN `submittedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '提交人' AFTER `createdDate`;
ALTER TABLE `zt_projectdeliverable` ADD COLUMN `submittedDate` datetime DEFAULT NULL COMMENT '提交时间' AFTER `submittedBy`;

ALTER TABLE `zt_project` ADD COLUMN `syncStory` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '阶段需求设置，0阶段手动关联需求，1自动同步项目需求' AFTER `storyType`;
