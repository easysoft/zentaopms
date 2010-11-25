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

-- task table.
ALTER TABLE `zt_task` ADD `openedBy` VARCHAR( 30 ) NOT NULL AFTER `desc` ,
ADD `openedDate` DATETIME NOT NULL AFTER `openedBy` ,
ADD `assignedTo` VARCHAR( 30 ) NOT NULL AFTER `openedDate` ,
ADD `assignedDate` DATETIME NOT NULL AFTER `assignedTo` ,
ADD `finishedBy` VARCHAR( 30 ) NOT NULL AFTER `assignedDate` ,
ADD `finishedDate` DATETIME NOT NULL AFTER `finishedBy` ,
ADD `canceledBy` VARCHAR( 30 ) NOT NULL AFTER `finishedDate` ,
ADD `canceledDate` DATETIME NOT NULL AFTER `canceledBy` ,
ADD `closedBy` VARCHAR( 30 ) NOT NULL AFTER `canceledDate` ,
ADD `closedDate` DATETIME NOT NULL AFTER `closedBy` ;
ALTER TABLE `zt_task` ADD `lastEditedBy` VARCHAR( 30 ) NOT NULL AFTER `closedDate` ,
ADD `lastEditedDate` DATETIME NOT NULL AFTER `lastEditedBy` ;
UPDATE zt_task SET assignedTo = owner ;
ALTER TABLE `zt_task` DROP `owner`;
ALTER TABLE `zt_task` CHANGE `status` `status` ENUM( 'wait', 'doing', 'done', 'cancel', 'closed' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'wait';
ALTER TABLE `zt_task` ADD `closedReason` VARCHAR( 30 ) NOT NULL AFTER `closedDate` ;
