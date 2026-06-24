UPDATE `zt_workflowfield` SET `control` = 'multi-select' WHERE `control` = 'multi-selec';

DELETE FROM `zt_workflowaction` WHERE `action` IN ('link', 'unlink');
DELETE FROM `zt_workflowlayout` WHERE `module` = 'caselib' AND `action` = 'browse';

ALTER TABLE `zt_workflowlabel`
ADD `type` enum('data', 'sql') NOT NULL DEFAULT 'data' AFTER `label`,
ADD `sql` text NULL AFTER `params`;
ALTER TABLE `zt_workflowlayout` ADD `ditto` tinyint unsigned NOT NULL DEFAULT 0 AFTER `position`;

ALTER TABLE `zt_project` MODIFY COLUMN `budget` decimal(14,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '预算';
