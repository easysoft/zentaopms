ALTER TABLE `zt_task` ADD `order` mediumint(8) NOT NULL DEFAULT '0' AFTER `activatedDate`;
ALTER TABLE `zt_task` ADD INDEX `order` (`order`);
