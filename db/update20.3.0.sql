ALTER TABLE `zt_approvalnode` CHANGE `date` `date` datetime NULL;

INSERT INTO `zt_workflowdatasource` (`type`, `name`, `code`, `datasource`, `view`, `keyField`, `valueField`, `buildin`, `vision`) VALUES
('lang', '反馈优先级', 'feedbackPri', 'feedbackPri', '', '', '', 1, 'rnd'),
('lang', '反馈优先级', 'litefeedbackclosedPri', 'feedbackPri', '', '', '', 1, 'lite');

ALTER TABLE `zt_charter` MODIFY `product` text NULL;
ALTER TABLE `zt_charter` MODIFY `roadmap` text NULL;

ALTER TABLE `zt_release` ADD `releasedDate` date NULL AFTER `date`;
UPDATE `zt_release` SET `releasedDate` = `date`;

ALTER TABLE `zt_pivotdrill` ADD `status` enum('design', 'published') NOT NULL DEFAULT 'published';
ALTER TABLE `zt_pivotdrill` ADD `account` varchar(30) NOT NULL DEFAULT '';

REPLACE INTO `zt_grouppriv` (`group`, `module`, `method`) SELECT `group`, 'repo', 'browseTag' FROM `zt_grouppriv` WHERE `module` = 'repo' AND `method` = 'browse';
REPLACE INTO `zt_grouppriv` (`group`, `module`, `method`) SELECT `group`, 'repo', 'browseBranch' FROM `zt_grouppriv` WHERE `module` = 'repo' AND `method` = 'browse';

ALTER TABLE `zt_projectproduct` ADD `roadmap` varchar(255) NOT NULL DEFAULT '';
