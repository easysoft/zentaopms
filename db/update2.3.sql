ALTER TABLE `zt_bug` ADD `toStory` MEDIUMINT( 8 ) NOT NULL DEFAULT '0' AFTER `toTask` ;
ALTER TABLE `zt_testResult` ADD `runAccount` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `stepResults` ;
ALTER TABLE `zt_testRun` ADD `runAccount` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `assignedTo` ;
ALTER TABLE `zt_case` ADD `runAccount` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `deleted` ;

-- adjust the working hours.
ALTER TABLE `zt_project` ADD `days` SMALLINT UNSIGNED NOT NULL AFTER `end`;
ALTER TABLE `zt_team` CHANGE `joinDate` `join` DATE NOT NULL DEFAULT '0000-00-00';
ALTER TABLE `zt_team` CHANGE `workingHour` `hours` TINYINT( 3 ) UNSIGNED NOT NULL DEFAULT '0'; 
ALTER TABLE `zt_team` ADD `days` SMALLINT UNSIGNED NOT NULL AFTER `join` ;
