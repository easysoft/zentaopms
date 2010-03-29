-- 2010-03-20 adjust bug table.
ALTER TABLE `zt_bug` ADD `pri` TINYINT UNSIGNED NOT NULL AFTER `severity` ;
ALTER TABLE `zt_bug` ADD `keywords` VARCHAR( 255 ) NOT NULL AFTER `title` ;
ALTER TABLE `zt_case` ADD `keywords` VARCHAR( 255 ) NOT NULL AFTER `title` ;
ALTER TABLE `zt_case` ADD `stage` VARCHAR( 255 ) NOT NULL AFTER `type` ;
ALTER TABLE `zt_case` ADD `howRun` VARCHAR( 30 ) NOT NULL AFTER `stage` ,
ADD `scriptedBy` VARCHAR( 30 ) NOT NULL AFTER `howRun` ,
ADD `scriptedDate` DATE NOT NULL AFTER `scriptedBy` ,
ADD `scriptStatus` VARCHAR( 30 ) NOT NULL AFTER `scriptedDate` ,
ADD `scriptLocation` VARCHAR( 255 ) NOT NULL AFTER `scriptStatus`, 
ADD `linkCase` VARCHAR( 255 ) NOT NULL AFTER `version` ;

-- 2010-03-27 fix bug 43
update zt_projectStory,zt_story set zt_projectStory.product=zt_story.product where zt_projectStory.story=zt_story.id;

-- 2010-03-27 add keywords to story.
ALTER TABLE `zt_story` ADD `keywords` VARCHAR( 255 ) NOT NULL AFTER `title` ;
