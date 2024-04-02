UPDATE `zt_workflowfield` SET `default` = 0 WHERE `field` = 'approva' AND `role` = 'approval' AND `default` = '';
UPDATE `zt_workflowfield` SET `default` = 'wait' WHERE `field` = 'reviewStatus' AND `role` = 'approval' AND `default` = '';

ALTER TABLE `zt_project` ADD COLUMN `enabled` enum('0','1') NOT NULL DEFAULT '1' AFTER `parallel`;
ALTER TABLE `zt_object`  ADD COLUMN `enabled` enum('0','1') NOT NULL DEFAULT '1' AFTER `type`;
