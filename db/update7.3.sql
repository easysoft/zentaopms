ALTER TABLE `zt_action` CHANGE `extra` `extra` text COLLATE 'utf8_general_ci' NOT NULL AFTER `comment`;
ALTER TABLE `zt_release` ADD `leftBugs` text COLLATE 'utf8_general_ci' NOT NULL AFTER `bugs`;
