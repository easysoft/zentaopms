ALTER TABLE `zt_workflowgroup` MODIFY `name` varchar(255) NOT NULL DEFAULT '';
ALTER TABLE `zt_workflowgroup` ADD COLUMN `exclusive` enum('0','1') NOT NULL DEFAULT '0' AFTER `main`;
ALTER TABLE `zt_doclib` ADD COLUMN `archived` enum('0','1') NOT NULL DEFAULT '0';
