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
