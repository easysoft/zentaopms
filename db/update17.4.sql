ALTER TABLE `zt_productplan` ADD `createdBy` varchar(30) NOT NULL AFTER `closedReason`;
ALTER TABLE `zt_productplan` ADD `createdDate` datetime NOT NULL AFTER `createdBy`;

ALTER TABLE `zt_release` ADD `createdBy` varchar(30) NOT NULL AFTER `subStatus`;
ALTER TABLE `zt_release` ADD `createdDate` datetime NOT NULL AFTER `createdBy`;

ALTER TABLE `zt_testtask` ADD `createdBy` varchar(30) NOT NULL AFTER `subStatus`;
ALTER TABLE `zt_testtask` ADD `createdDate` datetime NOT NULL AFTER `createdBy`;
