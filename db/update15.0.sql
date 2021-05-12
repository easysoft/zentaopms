ALTER TABLE `zt_project` CHANGE `lifetime` `lifetime` char(30) NOT NULL DEFAULT '';
ALTER TABLE `zt_story` ADD `category` varchar(30) NOT NULL DEFAULT 'feature' AFTER 'type';
