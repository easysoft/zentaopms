ALTER TABLE `zt_story` ADD `source` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `plan` ;
ALTER TABLE `zt_bug` ADD `activatedCount` SMALLINT( 6 ) NOT NULL AFTER `status` ;
ALTER TABLE `zt_bug` CHANGE `status` `status` ENUM( 'active', 'reactivated', 'resolved', 'closed' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'active' ;
