ALTER TABLE `zt_mr` CHANGE COLUMN `gitlabID` `hostID`  mediumint(8) UNSIGNED NOT NULL AFTER `id`;
ALTER TABLE `zt_mr` MODIFY COLUMN `sourceProject`  varchar(50) NOT NULL AFTER `hostID`;
ALTER TABLE `zt_mr` MODIFY COLUMN `targetProject`  varchar(50) NOT NULL AFTER `sourceBranch`;
UPDATE `zt_project` SET `status`='closed' WHERE `type` in ('sprint', 'stage', 'kanban') AND `model`='';
