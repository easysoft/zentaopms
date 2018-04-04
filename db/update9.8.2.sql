ALTER TABLE `zt_team` DROP PRIMARY KEY;
ALTER TABLE `zt_team` ADD `id` mediumint(8) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST;
ALTER TABLE `zt_project` CHANGE `team` `team` varchar(90) NOT NULL;
