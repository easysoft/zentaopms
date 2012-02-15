UPDATE `zt_story` SET `source` = 'customer' WHERE `source` = 'custom';
ALTER TABLE `zt_action` CHANGE `product` `product` VARCHAR( 255 ) NOT NULL ;
