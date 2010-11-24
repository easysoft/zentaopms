-- 2010-11-13 project table.
ALTER TABLE `zt_project` ADD `RM` varchar(30) NOT NULL DEFAULT '' AFTER `QM`;
ALTER TABLE `zt_user` DROP `birthyear`;

-- 2010-11-24 product table.
ALTER TABLE `zt_product` CHANGE `productOwner` `PO` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `bugOwner` `QM` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `zt_product` ADD `RM` VARCHAR( 30 ) NOT NULL AFTER `QM` ;
ALTER TABLE `zt_product` ADD `createdBy` VARCHAR( 30 ) NOT NULL AFTER `whitelist` ,
ADD `createdDate` DATETIME NOT NULL AFTER `createdBy` ;

-- fileds length.
ALTER TABLE `zt_bug` CHANGE `title` `title` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `zt_build` CHANGE `name` `name` CHAR( 150 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `zt_case` CHANGE `title` `title` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `zt_dept` CHANGE `name` `name` CHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `zt_doc` CHANGE `title` `title` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `zt_story` CHANGE `title` `title` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `zt_task` CHANGE `name` `name` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
