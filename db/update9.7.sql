ALTER TABLE `zt_team` DROP PRIMARY KEY;
ALTER TABLE `zt_team` CHANGE `project` `root` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `zt_team` DROP `task`;
ALTER TABLE `zt_team` ADD `type` ENUM('project', 'task') NOT NULL DEFAULT 'project' AFTER `root`;
