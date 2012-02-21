UPDATE `zt_story` SET `source` = 'customer' WHERE `source` = 'custom';
ALTER TABLE `zt_action` CHANGE `product` `product` VARCHAR( 255 ) NOT NULL ;
ALTER TABLE `zt_history` CHANGE `diff` `diff` MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
ALTER TABLE `zt_project` ADD `order` TINYINT NOT NULL AFTER `whitelist`;
