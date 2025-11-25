ALTER TABLE `zt_task` ADD `isParent` tinyint(1) NOT NULL DEFAULT 0 after `parent`;
ALTER TABLE `zt_task` ADD `path` text NULL after `isParent`;

UPDATE `zt_task` SET `path` = concat(',', id, ',') WHERE `parent` <= 0;
UPDATE `zt_task` SET `path` = concat(',', parent, ',', id, ',') WHERE `parent` > 0;

UPDATE `zt_task` SET `isParent` = 1 WHERE `parent` = -1;
UPDATE `zt_task` SET `parent`   = 0 WHERE `parent` = -1;

ALTER TABLE `zt_workflowfield` MODIFY `placeholder` VARCHAR(255) NOT NULL DEFAULT '';

ALTER TABLE zt_dataview MODIFY `fields` text NULL;
ALTER TABLE zt_dataview MODIFY `objects` text NULL;
ALTER TABLE zt_dataview MODIFY `mode` varchar(50) NOT NULL DEFAULT 'builder';
ALTER TABLE `zt_doccontent` ADD `html` longtext DEFAULT NULL AFTER `content`;

ALTER TABLE `zt_file` ADD `gid` CHAR(48) NOT NULL DEFAULT '' AFTER `objectID`;
CREATE INDEX `gid` ON `zt_file`(`gid`);

ALTER TABLE `zt_charter` MODIFY `level` varchar(255) NOT NULL DEFAULT '';
ALTER TABLE `zt_charter` ADD `reviewStatus` varchar(30) NOT NULL DEFAULT 'wait' AFTER `reviewedDate`;
ALTER TABLE `zt_charter` ADD `appliedReviewer` text NULL DEFAULT NULL AFTER `appliedDate`;
ALTER TABLE `zt_charter` ADD `completedBy` varchar(30) NOT NULL DEFAULT '' AFTER `reviewStatus`;
ALTER TABLE `zt_charter` ADD `completedDate` datetime NULL DEFAULT NULL AFTER `completedBy`;
ALTER TABLE `zt_charter` ADD `completedReviewer` text NULL DEFAULT NULL AFTER `completedDate`;
ALTER TABLE `zt_charter` ADD `canceledBy` varchar(30) NOT NULL DEFAULT '' AFTER `completedReviewer`;
ALTER TABLE `zt_charter` ADD `canceledDate` datetime NULL DEFAULT NULL AFTER `canceledBy`;
ALTER TABLE `zt_charter` ADD `canceledReviewer` text NULL DEFAULT NULL AFTER `canceledDate`;
ALTER TABLE `zt_charter` ADD `activatedReviewer` text NULL DEFAULT NULL AFTER `activatedDate`;
ALTER TABLE `zt_charter` ADD `completionFiles` text NULL AFTER `charterFiles`;
ALTER TABLE `zt_charter` ADD `canceledFiles` text NULL AFTER `completionFiles`;
ALTER TABLE `zt_charter` ADD `prevCanceledStatus` varchar(30) NOT NULL DEFAULT '' AFTER `canceledFiles`;
ALTER TABLE `zt_charter` ADD `plan` text NULL AFTER `roadmap`;
ALTER TABLE `zt_charter` ADD `type` varchar(30) NOT NULL DEFAULT 'roadmap' AFTER `plan`;
ALTER TABLE `zt_charter` ADD `filesConfig` text NULL AFTER `type`;
ALTER TABLE `zt_approvalobject` ADD `reviewers` text DEFAULT NULL AFTER `objectID`;
ALTER TABLE `zt_approvalobject` ADD `opinion` text DEFAULT NULL AFTER `reviewers`;
ALTER TABLE `zt_approvalobject` ADD `result` varchar(10) NOT NULL DEFAULT '' AFTER `opinion`;
ALTER TABLE `zt_approvalobject` ADD `status` varchar(30) NOT NULL DEFAULT '' AFTER `result`;
ALTER TABLE `zt_approvalobject` ADD `appliedBy` char(30) NOT NULL DEFAULT '' AFTER `status`;
ALTER TABLE `zt_approvalobject` ADD `appliedDate` datetime NULL AFTER `appliedBy`;
ALTER TABLE `zt_approvalobject` ADD `desc` text NULL AFTER `appliedDate`;
ALTER TABLE `zt_project` ADD `linkType` varchar(30) NOT NULL DEFAULT 'plan' AFTER `enabled`;

ALTER TABLE `zt_approval` ADD `extra` text NULL AFTER `result`;

INSERT INTO `zt_workflowdatasource` (`type`, `name`, `code`, `buildin`, `vision`, `createdBy`, `createdDate`, `datasource`, `view`, `keyField`, `valueField`) VALUES
('lang', '立项级别',     'charterLevel',        '1', 'rnd', 'admin', '1970-01-01 00:00:01', 'charterLevel', '', '', ''),
('lang', '立项类型',     'charterCategory',     '1', 'rnd', 'admin', '1970-01-01 00:00:01', 'charterCategory', '', '', ''),
('lang', '立项适用市场', 'charterMarket',       '1', 'rnd', 'admin', '1970-01-01 00:00:01', 'charterMarket', '', '', ''),
('lang', '立项状态',     'charterStatus',       '1', 'rnd', 'admin', '1970-01-01 00:00:01', 'charterStatus', '', '', ''),
('lang', '立项关闭原因', 'charterCloseReason',  '1', 'rnd', 'admin', '1970-01-01 00:00:01', 'charterCloseReason', '', '', ''),
('lang', '立项审批结果', 'charterReviewResult', '1', 'rnd', 'admin', '1970-01-01 00:00:01', 'charterReviewResult', '', '', ''),
('lang', '立项审批状态', 'charterReviewStatus', '1', 'rnd', 'admin', '1970-01-01 00:00:01', 'charterReviewStatus', '', '', '');

UPDATE `zt_grouppriv` SET `method`='activateProjectApproval' WHERE `module`='charter' AND `method`='activate';

UPDATE `zt_project` SET `linkType` = 'roadmap' WHERE `charter` != '0';

CREATE INDEX `idx_repo` ON `zt_bug`(`repo`);
CREATE INDEX `idx_created_status` ON `zt_compile`(`createdDate`, `status`, `deleted`);

DELETE FROM `zt_workflowaction` WHERE `module` = 'feedback' AND `action` = 'view' AND `vision` = 'rnd';
DELETE FROM `zt_workflowaction` WHERE `module` = 'feedback' AND `action` = 'adminView' AND `vision` = 'lite';

ALTER TABLE `zt_deploy` ADD `estimate` datetime NULL AFTER `end`;
UPDATE `zt_deploy` SET `estimate` = `begin` WHERE `estimate` IS NULL;
UPDATE `zt_deploy` SET `begin` = NULL, `end` = NULL WHERE `status` NOT IN ('success', 'fail');

CREATE TABLE IF NOT EXISTS `zt_docblock` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `doc` mediumint(8) unsigned NOT NULL DEFAULT 0,
  `type` varchar(50) NOT NULL DEFAULT '',
  `settings` text NULL,
  `content` text NULL,
  `extra` varchar(255) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;
CREATE INDEX `idx_doc` ON `zt_docblock` (`doc`);

INSERT INTO `zt_cron` (`m`, `h`, `dom`, `mon`, `dow`, `command`, `remark`, `type`, `buildin`, `status`) VALUES ('0', '2', '*', '*', '*', 'moduleName=system&methodName=initSystem', '初始化产品下应用数据', 'zentao', 1, 'normal');
INSERT INTO `zt_cron` (`m`, `h`, `dom`, `mon`, `dow`, `command`, `remark`, `type`, `buildin`, `status`) VALUES ('0', '2', '*', '*', '*', 'moduleName=upgrade&methodName=ajaxInitTaskRelation', '更新任务关联关系', 'zentao', 1, 'normal');
