ALTER TABLE `zt_build` CHANGE `desc` `desc` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `zt_productPlan` CHANGE `desc` `desc` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

 -- 2011-9-17 change length of name field in zt_module
ALTER TABLE `zt_module` CHANGE `name` `name` CHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '';

ALTER TABLE `zt_story` ADD `source` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `plan` ;
ALTER TABLE `zt_bug` ADD `confirmed` BOOL NOT NULL DEFAULT '0' AFTER `status` ;
ALTER TABLE `zt_bug` ADD `activatedCount` SMALLINT( 6 ) NOT NULL AFTER `confirmed` ;
UPDATE `zt_bug` SET `confirmed` = 1 WHERE status in('closed', 'resolved');

 -- 2011-10-16 add precondition field in zt_case
ALTER TABLE `zt_case` ADD `precondition` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `title`;

 -- 2011-10-22 add lastRun and lastResult field in zt_case
ALTER TABLE `zt_case` ADD `lastRun` DATETIME NOT NULL;
ALTER TABLE `zt_case` ADD `lastResult` CHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE `zt_bug` ADD `toTask` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `task` ;
ALTER TABLE `zt_task` ADD `fromBug` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `storyVersion` ;

ALTER TABLE `zt_case` ADD `fromBug` MEDIUMINT UNSIGNED NOT NULL AFTER `linkCase`;
