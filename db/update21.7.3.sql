ALTER TABLE `zt_workflowgroup` CHANGE `projectModel` `projectModel` varchar(30) NOT NULL DEFAULT '';
INSERT INTO `zt_workflowgroup` (`type`, `projectModel`, `projectType`, `name`, `code`, `status`, `vision`, `main`) VALUES
('project',	'agileplus',	  'product',	'融合敏捷式产品研发',	'agileplusproduct',	    'normal',	'rnd',	'1'),
('project',	'agileplus',	  'project',	'融合敏捷式项目研发',	'agileplusproject',	    'normal',	'rnd',	'1'),
('project',	'waterfallplus',  'product',	'融合瀑布式产品研发',	'waterfallplusproduct',	'normal',	'rnd',	'1'),
('project',	'waterfallplus',  'project',	'融合瀑布式项目研发',	'waterfallplusproject',	'normal',	'rnd',	'1'),
('project',	'kanban',	      'product',	'看板式产品研发',	    'kanbanproduct',	    'normal',	'rnd',	'1'),
('project',	'kanban',	      'project',	'看板式项目研发',	    'kanbanproject',	    'normal',	'rnd',	'1'),
('project',	'ipd',	          'ipd',	    'IPD集成产品研发',	    'ipdproduct',	        'normal',	'rnd',	'1'),
('project',	'ipd',	          'tpd',	    'IPD预研产品研发',	    'tpdproduct',	        'normal',	'rnd',	'1'),
('project',	'ipd',	          'cbb',	    'IPD平台产品研发',	    'cbbproduct',	        'normal',	'rnd',	'1'),
('project',	'ipd',	          'cpdproduct',	'IPD定制产品研发',	    'cpdproduct',	        'normal',	'rnd',	'1'),
('project',	'ipd',	          'cpdproject',	'IPD定制项目研发',	    'cpdproject',	        'normal',	'rnd',	'1');

ALTER TABLE `zt_bug`
CHANGE `injection` `injection` varchar(30) NOT NULL DEFAULT '',
CHANGE `identify` `identify` varchar(30) NOT NULL DEFAULT '';

UPDATE `zt_project` SET `workflowGroup` = (SELECT `id` FROM `zt_workflowgroup` WHERE `code` = 'kanbanproduct' AND `main` = '1' LIMIT 1) WHERE `type` = 'project' AND `model` = 'kanban' AND `hasProduct` = '1';
UPDATE `zt_project` SET `workflowGroup` = (SELECT `id` FROM `zt_workflowgroup` WHERE `code` = 'kanbanproject' AND `main` = '1' LIMIT 1) WHERE `type` = 'project' AND `model` = 'kanban' AND `hasProduct` = '0';

UPDATE `zt_bug` AS `bug`
JOIN `zt_review` AS `review` ON `bug`.`injection` = `review`.`id`
JOIN `zt_object` AS `object` ON `review`.`object` = `object`.`id`
SET `bug`.`injection` = 'request'
WHERE `bug`.`injection` != 0 AND `object`.`category` IN ('PP', 'QAP', 'CMP', 'ERS', 'URS', 'SRS');

UPDATE `zt_bug` AS `bug`
JOIN `zt_review` AS `review` ON `bug`.`injection` = `review`.`id`
JOIN `zt_object` AS `object` ON `review`.`object` = `object`.`id`
SET `bug`.`injection` = 'code'
WHERE `bug`.`injection` != 0 AND `object`.`category` IN ('Code', 'ITP', 'ITTC', 'STP', 'STTC');

UPDATE `zt_bug` AS `bug`
JOIN `zt_review` AS `review` ON `bug`.`injection` = `review`.`id`
JOIN `zt_object` AS `object` ON `review`.`object` = `object`.`id`
SET `bug`.`injection` = 'design'
WHERE `bug`.`injection` != 0 AND `object`.`category` NOT IN ('PP', 'QAP', 'CMP', 'ERS', 'URS', 'SRS', 'Code', 'ITP', 'ITTC', 'STP', 'STTC');

UPDATE `zt_bug` AS `bug`
JOIN `zt_review` AS `review` ON `bug`.`identify` = `review`.`id`
JOIN `zt_object` AS `object` ON `review`.`object` = `object`.`id`
SET `bug`.`identify` = 'design'
WHERE `bug`.`identify` != 0 AND `object`.`category` IN ('HLDS', 'DDS', 'DBDS', 'ADS', 'QAP', 'CMP');

UPDATE `zt_bug` AS `bug`
JOIN `zt_review` AS `review` ON `bug`.`identify` = `review`.`id`
JOIN `zt_object` AS `object` ON `review`.`object` = `object`.`id`
SET `bug`.`identify` = 'integration'
WHERE `bug`.`identify` != 0 AND `object`.`category` IN ('ITP', 'ITTC');

UPDATE `zt_bug` AS `bug`
JOIN `zt_review` AS `review` ON `bug`.`identify` = `review`.`id`
JOIN `zt_object` AS `object` ON `review`.`object` = `object`.`id`
SET `bug`.`identify` = 'system'
WHERE `bug`.`identify` != 0 AND `object`.`category` IN ('STP', 'STTC');

UPDATE `zt_bug` AS `bug`
JOIN `zt_review` AS `review` ON `bug`.`identify` = `review`.`id`
JOIN `zt_object` AS `object` ON `review`.`object` = `object`.`id`
SET `bug`.`identify` = 'production'
WHERE `bug`.`identify` != 0 AND `object`.`category` = 'UM';

UPDATE `zt_bug` AS `bug`
JOIN `zt_review` AS `review` ON `bug`.`identify` = `review`.`id`
JOIN `zt_object` AS `object` ON `review`.`object` = `object`.`id`
SET `bug`.`identify` = 'requirement'
WHERE `bug`.`identify` != 0 AND `object`.`category` NOT IN ('HLDS', 'DDS', 'DBDS', 'ADS', 'QAP', 'CMP', 'ITP', 'ITTC', 'STP', 'STTC', 'UM');

UPDATE `zt_bug` SET `injection` = '' WHERE `injection` = '0';
UPDATE `zt_bug` SET `identify`  = '' WHERE `identify`  = '0';

ALTER TABLE `zt_doc` ADD `isDeliverable` char(10) NOT NULL DEFAULT '0' AFTER `acl`;
ALTER TABLE `zt_deliverable` CHANGE `module` `module` mediumint(8) unsigned NOT NULL DEFAULT '0';
ALTER TABLE `zt_deliverable` ADD `workflowGroup` int(8) NOT NULL DEFAULT '0' AFTER `id`;
ALTER TABLE `zt_deliverable` ADD `activity` int(8) unsigned NOT NULL DEFAULT '0' AFTER `name`;
ALTER TABLE `zt_deliverable` ADD `trimmable` char(30) NOT NULL DEFAULT '0' AFTER `activity`;
ALTER TABLE `zt_deliverable` ADD `trimRule` varchar(255) NOT NULL AFTER `trimmable`;
ALTER TABLE `zt_deliverable` ADD `template` text NOT NULL AFTER `trimRule`;
ALTER TABLE `zt_deliverable` DROP `method`, DROP `model`, DROP `type`, DROP `files`;

CREATE TABLE IF NOT EXISTS `zt_deliverablestage` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `deliverable` int(8) unsigned NOT NULL DEFAULT 0,
  `stage` varchar(30) NOT NULL,
  `required` varchar(30) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE UNIQUE INDEX `unique` ON `zt_deliverablestage`(`deliverable`,`stage`);
