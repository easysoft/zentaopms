ALTER TABLE `zt_host` ADD `type` varchar(30) NOT NULL DEFAULT 'normal' AFTER `admin`;
ALTER TABLE `zt_host` ADD `secret` varchar(50) NOT NULL DEFAULT '' AFTER `type`;
ALTER TABLE `zt_host` ADD `token` varchar(50) NOT NULL DEFAULT '' AFTER `secret`;
ALTER TABLE `zt_host` ADD `expiredDate` datetime NOT NULL AFTER `token`;
ALTER TABLE `zt_host` ADD `virtualSoftware` varchar(30) NOT NULL DEFAULT '' AFTER `expiredDate`;
ALTER TABLE `zt_asset` ADD `registerDate`  datetime NOT NULL AFTER `editedDate`;
ALTER TABLE `zt_vmtemplate` ADD `type` varchar(30) NOT NULL DEFAULT 'normal' AFTER `hostID`;
ALTER TABLE `zt_vmtemplate` ADD `imageName` varchar(50) NOT NULL;
ALTER TABLE `zt_vmtemplate` ADD `createdBy` varchar(30) NOT NULL;
ALTER TABLE `zt_vmtemplate` ADD `createdDate` datetime NOT NULL;
ALTER TABLE `zt_vmtemplate` ADD `editedBy` varchar(30) NOT NULL;
ALTER TABLE `zt_vmtemplate` ADD `editedDate` datetime NOT NULL;
ALTER TABLE `zt_vm` ADD `osVersion` varchar(50) NOT NULL DEFAULT '' AFTER `osCategory`;

UPDATE `zt_workflowlabel` SET `label` = '全部' where `label` = '所有' AND `action` = 'browse';
