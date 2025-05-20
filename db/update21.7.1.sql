ALTER TABLE `zt_project`
ADD `isTpl` tinyint(1) unsigned NOT NULL DEFAULT '0' AFTER `project`;

ALTER TABLE `zt_task`
ADD `isTpl` tinyint(1) unsigned NOT NULL DEFAULT '0' AFTER `isParent`;