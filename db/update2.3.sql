ALTER TABLE `zt_bug` ADD `toStory` MEDIUMINT( 8 ) NOT NULL DEFAULT '0' AFTER `toTask` ;
ALTER TABLE `zt_testResult` ADD `runAccount` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `stepResults` ;
ALTER TABLE `zt_testRun` ADD `runAccount` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `assignedTo` ;
ALTER TABLE `zt_case` ADD `runAccount` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `deleted` ;
