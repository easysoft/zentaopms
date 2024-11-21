ALTER TABLE `zt_charter` ADD `reviewStatus` varchar(30) NOT NULL DEFAULT 'wait' AFTER `reviewedDate`;
ALTER TABLE `zt_charter` ADD `completionFiles` text NULL AFTER `charterFiles`;
ALTER TABLE `zt_charter` ADD `canceledFiles` text NULL AFTER `completionFiles`;
ALTER TABLE `zt_charter` ADD `beforeCanceled` varchar(30) NOT NULL DEFAULT '' AFTER `canceledFiles`;
ALTER TABLE `zt_charter` ADD `plan` text NULL AFTER `roadmap`;
ALTER TABLE `zt_approvalobject` ADD `reviewers` text DEFAULT NULL AFTER `objectID`;
ALTER TABLE `zt_approvalobject` ADD `opinion` text DEFAULT NULL AFTER `reviewers`;
ALTER TABLE `zt_approvalobject` ADD `result` varchar(10) NOT NULL DEFAULT '' AFTER `opinion`;
ALTER TABLE `zt_approvalobject` ADD `status` varchar(30) NOT NULL DEFAULT '' AFTER `result`;
ALTER TABLE `zt_approvalobject` ADD `appliedBy` char(30) NOT NULL DEFAULT '' AFTER `status`;
ALTER TABLE `zt_approvalobject` ADD `appliedDate` datetime NULL AFTER `appliedBy`;
ALTER TABLE `zt_approvalobject` ADD `desc` text NULL AFTER `appliedDate`;

ALTER TABLE `zt_approval` ADD `extra` text NULL AFTER `result`;

INSERT INTO `zt_workflowdatasource` (`type`, `name`, `code`, `buildin`, `vision`, `createdBy`, `createdDate`, `datasource`, `view`, `keyField`, `valueField`) VALUES
('lang', '立项级别',     'charterLevel',        '1', 'rnd', 'admin', '1970-01-01 00:00:01', 'charterLevel', '', '', ''),
('lang', '立项类型',     'charterCategory',     '1', 'rnd', 'admin', '1970-01-01 00:00:01', 'charterCategory', '', '', ''),
('lang', '立项适用市场', 'charterMarket',       '1', 'rnd', 'admin', '1970-01-01 00:00:01', 'charterMarket', '', '', ''),
('lang', '立项状态',     'charterStatus',       '1', 'rnd', 'admin', '1970-01-01 00:00:01', 'charterStatus', '', '', ''),
('lang', '立项关闭原因', 'charterCloseReason',  '1', 'rnd', 'admin', '1970-01-01 00:00:01', 'charterCloseReason', '', '', ''),
('lang', '立项审批结果', 'charterReviewResult', '1', 'rnd', 'admin', '1970-01-01 00:00:01', 'charterReviewResult', '', '', ''),
('lang', '立项审批状态', 'charterReviewStatus', '1', 'rnd', 'admin', '1970-01-01 00:00:01', 'charterReviewStatus', '', '', '');
