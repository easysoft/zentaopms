ALTER TABLE `zt_mr` CHANGE COLUMN `gitlabID` `hostID`  mediumint(8) UNSIGNED NOT NULL AFTER `id`;
ALTER TABLE `zt_mr` MODIFY COLUMN `sourceProject`  varchar(50) NOT NULL AFTER `hostID`;
ALTER TABLE `zt_mr` MODIFY COLUMN `targetProject`  varchar(50) NOT NULL AFTER `sourceBranch`;
UPDATE `zt_project` SET `status`='closed' WHERE `type` IN ('sprint', 'stage', 'kanban') AND `model`='' AND `status` = 'done';
ALTER TABLE `zt_repo` ADD COLUMN `serviceHost`  int(10) NOT NULL AFTER `client`;
ALTER TABLE `zt_repo` ADD COLUMN `serviceProject`  varchar(100) NOT NULL AFTER `serviceHost`;
UPDATE `zt_repo` SET `serviceHost` = `client`, `serviceProject` = `path` WHERE `SCM` = 'Gitlab';
UPDATE `zt_repo` SET `client` = '', `path` = '' WHERE `SCM` = 'Gitlab';
