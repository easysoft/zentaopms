ALTER TABLE `zt_project`ADD `isTpl` tinyint(1) unsigned NOT NULL DEFAULT '0' AFTER `project`;
ALTER TABLE `zt_task` ADD `isTpl` tinyint(1) unsigned NOT NULL DEFAULT '0' AFTER `isParent`;

ALTER TABLE `zt_oauth` MODIFY `openID` varchar(100) NOT NULL DEFAULT '';
ALTER TABLE `zt_doclib` MODIFY `id` mediumint(8) unsigned NOT NULL auto_increment;
ALTER TABLE `zt_doclib` MODIFY `order` mediumint(8) unsigned NOT NULL DEFAULT '0';

ALTER TABLE `zt_repobranch` DROP INDEX `repo_revision_branch`;
CREATE UNIQUE INDEX `repo_revision` ON `zt_repobranch`(`repo`, `revision`);

UPDATE `zt_workflowgroup` SET `name` = '瀑布式产品研发' WHERE `code` = 'waterfallproduct';
UPDATE `zt_workflowgroup` SET `name` = '瀑布式项目研发' WHERE `code` = 'waterfallproject';
UPDATE `zt_workflowgroup` SET `name` = '敏捷式产品研发' WHERE `code` = 'scrumproduct';
UPDATE `zt_workflowgroup` SET `name` = '敏捷式项目研发' WHERE `code` = 'scrumproject';
