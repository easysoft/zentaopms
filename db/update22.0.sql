ALTER TABLE `zt_doc` ADD `isDeliverable` tinyint(1) NOT NULL DEFAULT 0 AFTER `acl`;
ALTER TABLE `zt_deliverable` ADD `workflowGroup` int(8) NOT NULL DEFAULT '0' AFTER `id`;
ALTER TABLE `zt_deliverable` ADD `activity` int(8) unsigned NOT NULL DEFAULT '0' AFTER `name`;
ALTER TABLE `zt_deliverable` ADD `trimmable` char(30) NOT NULL DEFAULT '0' AFTER `activity`;
ALTER TABLE `zt_deliverable` ADD `trimRule` varchar(255) NOT NULL AFTER `trimmable`;
ALTER TABLE `zt_deliverable` ADD `template` text NOT NULL AFTER `trimRule`;
ALTER TABLE `zt_deliverable` ADD `status` varchar(30) NOT NULL DEFAULT 'enabled' AFTER `name`;

CREATE TABLE IF NOT EXISTS `zt_deliverablestage` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `deliverable` int(8) unsigned NOT NULL DEFAULT 0,
  `stage` varchar(30) NOT NULL,
  `required` varchar(30) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE UNIQUE INDEX `unique` ON `zt_deliverablestage`(`deliverable`,`stage`);
ALTER TABLE `zt_module` ADD `extra` varchar(30) NOT NULL DEFAULT '';

ALTER TABLE `zt_process` ADD `workflowGroup` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `id`;
ALTER TABLE `zt_process` ADD `module` int(8) unsigned NOT NULL DEFAULT '0' AFTER `name`;
ALTER TABLE `zt_activity` ADD `workflowGroup` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `process`;
