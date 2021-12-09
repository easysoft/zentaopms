ALTER TABLE `zt_task` ADD `repo` mediumint unsigned NOT NULL AFTER `activatedDate`;
ALTER TABLE `zt_task` ADD `entry` varchar(255) NOT NULL AFTER `repo`;
ALTER TABLE `zt_task` ADD `lines` varchar(10) NOT NULL AFTER `entry`;
ALTER TABLE `zt_task` ADD `v1` varchar(40) NOT NULL AFTER `lines`;
ALTER TABLE `zt_task` ADD `v2` varchar(40) NOT NULL AFTER `v1`;
ALTER TABLE `zt_task` ADD `mr` mediumint(8) unsigned NOT NULL AFTER `repo`;
ALTER TABLE `zt_bug` ADD `mr` mediumint(8) unsigned NOT NULL AFTER `repo`;
