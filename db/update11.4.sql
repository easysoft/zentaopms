ALTER TABLE `zt_burn` ADD `task` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `project`;
ALTER TABLE `zt_burn` ADD PRIMARY KEY `project_date_task` (`project`, `date`, `task`), DROP INDEX `PRIMARY`;
