-- 2010-11-13 project table.
ALTER TABLE `zt_project` ADD `RM` varchar(30) NOT NULL DEFAULT '' AFTER `QM`;
ALTER TABLE `zt_user` DROP `birthyear`;

-- 2010-11-24 product table.
ALTER TABLE `zt_product` CHANGE `productOwner` `PO` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `bugOwner` `QM` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `zt_product` ADD `RM` VARCHAR( 30 ) NOT NULL AFTER `QM` ;
ALTER TABLE `zt_product` ADD `createdBy` VARCHAR( 30 ) NOT NULL AFTER `whitelist` ,
ADD `createdDate` DATETIME NOT NULL AFTER `createdBy` ;
