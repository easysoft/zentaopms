ALTER TABLE `zt_charter` MODIFY `level` varchar(255) NOT NULL DEFAULT '';
ALTER TABLE `zt_charter` ADD `reviewStatus` varchar(30) NOT NULL DEFAULT 'wait' AFTER `reviewedDate`;
ALTER TABLE `zt_charter` ADD `appliedReviewer` text NULL DEFAULT NULL AFTER `appliedDate`;
ALTER TABLE `zt_charter` ADD `complatedBy` varchar(30) NOT NULL DEFAULT '' AFTER `reviewStatus`;
ALTER TABLE `zt_charter` ADD `complatedDate` datetime NULL DEFAULT NULL AFTER `complatedBy`;
ALTER TABLE `zt_charter` ADD `complatedReviewer` text NULL DEFAULT NULL AFTER `complatedDate`;
ALTER TABLE `zt_charter` ADD `canceledBy` varchar(30) NOT NULL DEFAULT '' AFTER `complatedReviewer`;
ALTER TABLE `zt_charter` ADD `canceledDate` datetime NULL DEFAULT NULL AFTER `canceledBy`;
ALTER TABLE `zt_charter` ADD `canceledReviewer` text NULL DEFAULT NULL AFTER `canceledDate`;
ALTER TABLE `zt_charter` ADD `activatedReviewer` text NULL DEFAULT NULL AFTER `activatedDate`;
ALTER TABLE `zt_charter` ADD `completionFiles` text NULL AFTER `charterFiles`;
ALTER TABLE `zt_charter` ADD `canceledFiles` text NULL AFTER `completionFiles`;
ALTER TABLE `zt_charter` ADD `beforeCanceled` varchar(30) NOT NULL DEFAULT '' AFTER `canceledFiles`;
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
