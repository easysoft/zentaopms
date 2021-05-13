ALTER TABLE `zt_project` CHANGE `lifetime` `lifetime` char(30) NOT NULL DEFAULT '';
ALTER TABLE `zt_story` ADD `category` varchar(30) NOT NULL DEFAULT 'feature' AFTER `type`;
ALTER TABLE `zt_testtask` ADD `type` varchar(255) NOT NULL DEFAULT '' AFTER `build`;
