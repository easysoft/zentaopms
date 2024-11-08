ALTER TABLE `zt_charter` ADD `reviewStatus` varchar(30) NOT NULL DEFAULT 'wait' AFTER `reviewedDate`;
ALTER TABLE `zt_charter` ADD `completionFiles` text NULL AFTER `charterFiles`;
ALTER TABLE `zt_approvalobject` ADD `reviewers` text DEFAULT NULL AFTER `objectID`;
ALTER TABLE `zt_approvalobject` ADD `opinion` text DEFAULT NULL AFTER `reviewers`;
ALTER TABLE `zt_approvalobject` ADD `result` varchar(10) NOT NULL DEFAULT '' AFTER `opinion`;
ALTER TABLE `zt_approvalobject` ADD `status` varchar(30) NOT NULL DEFAULT '' AFTER `result`;
ALTER TABLE `zt_approvalobject` ADD `appliedBy` char(30) NOT NULL DEFAULT '' AFTER `status`;
ALTER TABLE `zt_approvalobject` ADD `appliedDate` datetime NULL AFTER `appliedBy`;
ALTER TABLE `zt_approvalobject` ADD `desc` text NULL AFTER `appliedDate`;
