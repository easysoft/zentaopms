-- 2010-03-20 adjust bug table.
ALTER TABLE `zt_bug` ADD `pri` TINYINT UNSIGNED NOT NULL AFTER `severity` ;
ALTER TABLE `zt_bug` ADD `keyword` VARCHAR( 255 ) NOT NULL ;
ALTER TABLE `zt_case` ADD `keyword` VARCHAR( 255 ) NOT NULL ;
ALTER TABLE `zt_case` ADD `executeType` VARCHAR( 30 ) NOT NULL AFTER `keyword` ,
ADD `scriptedBy` VARCHAR( 30 ) NOT NULL AFTER `executeType` ,
ADD `scriptedDate` DATE NOT NULL AFTER `scriptedBy` ,
ADD `scriptStatus` VARCHAR( 30 ) NOT NULL AFTER `scriptedDate` ,
ADD `scriptLocation` VARCHAR( 255 ) NOT NULL AFTER `scriptStatus` ,
ADD `linkCase` MEDIUMINT UNSIGNED NOT NULL AFTER `scriptLocation` ;
ALTER TABLE `zt_case` ADD `scope` VARCHAR( 30 ) NOT NULL ;
