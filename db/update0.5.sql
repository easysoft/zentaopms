ALTER TABLE `zt_task` ADD `deadline` DATE NOT NULL AFTER `left` ;
ALTER TABLE `zt_bug` CHANGE `openedBuild` `openedBuild` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
