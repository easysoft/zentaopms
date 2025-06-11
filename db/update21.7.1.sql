ALTER TABLE `zt_project`
ADD `isTpl` tinyint(1) unsigned NOT NULL DEFAULT '0' AFTER `project`;

ALTER TABLE `zt_task`
ADD `isTpl` tinyint(1) unsigned NOT NULL DEFAULT '0' AFTER `isParent`;

UPDATE `zt_workflowgroup` SET `name` = '瀑布式产品研发' WHERE `code` = 'waterfallproduct';
UPDATE `zt_workflowgroup` SET `name` = '瀑布式项目研发' WHERE `code` = 'waterfallproject';
UPDATE `zt_workflowgroup` SET `name` = '敏捷式产品研发' WHERE `code` = 'scrumproduct';
UPDATE `zt_workflowgroup` SET `name` = '敏捷式项目研发' WHERE `code` = 'scrumproject';