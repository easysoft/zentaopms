ALTER TABLE `zt_story` ADD `source` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `plan` ;
ALTER TABLE `zt_bug` ADD `activatedCount` SMALLINT( 6 ) NOT NULL AFTER `status` ;
ALTER TABLE `zt_bug` ADD `confirm` BOOL NOT NULL DEFAULT '0' AFTER `activatedCount` ;
ALTER TABLE `zt_bug` ADD `confirmedBy` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `closedDate` , ADD `confirmedDate` DATETIME NOT NULL AFTER `confirmedBy` ;

 -- 2011-10-16 add precondition field in zt_case
ALTER TABLE `zt_case` ADD `precondition` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `title`;

 -- 2011-10-22 add lastRun and lastResult field in zt_case
ALTER TABLE `zt_case` ADD `lastRun` DATETIME NOT NULL;
ALTER TABLE `zt_case` ADD `lastResult` CHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE `zt_bug` ADD `toTask` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `task` ;
ALTER TABLE `zt_task` ADD `fromBug` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `storyVersion` ;
