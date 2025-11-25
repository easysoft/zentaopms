ALTER TABLE `zt_webhook` CHANGE `projects` `executions` text NOT NULL;
ALTER TABLE `zt_webhook` CHANGE `executions` `executions` text NOT NULL;
ALTER TABLE `zt_projectcase` ADD `count` mediumint(8) unsigned NOT NULL DEFAULT '1' AFTER `case`;

ALTER TABLE `zt_repo` ADD `extra` char(30) NOT NULL AFTER `desc`;

UPDATE `zt_config` set `value`='program-browse' where `key`='programLink' and `value`='program-pgmbrowse';
UPDATE `zt_config` set `value`='project-browse' where `key`='projectLink' and `value`='program-prjbrowse';
