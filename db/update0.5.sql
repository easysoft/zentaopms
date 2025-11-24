ALTER TABLE `zt_task` ADD `deadline` DATE NOT NULL AFTER `left`;
ALTER TABLE `zt_bug` CHANGE `openedBuild` `openedBuild` VARCHAR( 255 ) NOT NULL;
