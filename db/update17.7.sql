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
ALTER TABLE `zt_vm` ADD `unit` enum('GB','TB') NOT NULL DEFAULT 'GB' AFTER `osDisk`;
update zt_kanban
set
  colWidth    = if(colWidth < 200, 200, colWidth),
  minColWidth = if(minColWidth < 200, 200, minColWidth),
  maxColWidth = if(maxColWidth <= 200 and minColWidth <= 200, 201, maxColWidth)
where colWidth < 200 or minColWidth < 200 or maxColWidth < 200;

update zt_project
set
  colWidth    = if(colWidth < 200, 200, colWidth),
  minColWidth = if(minColWidth < 200, 200, minColWidth),
  maxColWidth = if(maxColWidth <= 200 and minColWidth <= 200, 201, maxColWidth)
where colWidth < 200 or minColWidth < 200 or maxColWidth < 200;
ALTER TABLE `zt_kanban` ADD `minColWidth` smallint(4) NOT NULL DEFAULT '200' AFTER `colWidth`;
ALTER TABLE `zt_project` ADD `minColWidth` smallint(4) NOT NULL DEFAULT '200' AFTER `colWidth`;
