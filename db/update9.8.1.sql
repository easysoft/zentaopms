ALTER TABLE `zt_release` ADD `marker` enum('0','1') NOT NULL default '0' AFTER `name`;
ALTER TABLE `zt_doc` ADD `collector` text NOT NULL default '' AFTER `views`,
ADD `visitedDate` text NOT NULL default '' AFTER `editedDate`;
