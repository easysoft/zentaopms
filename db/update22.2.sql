UPDATE `zt_workflowfield` SET `control` = 'multi-select' WHERE `control` = 'multi-selec';
DELETE FROM `zt_workflowaction` WHERE `action` IN ('link', 'unlink');
ALTER TABLE `zt_workflowlabel`
ADD `type` enum('data', 'sql') NOT NULL DEFAULT 'data' AFTER `label`,
ADD `sql` text NULL AFTER `params`;
