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

UPDATE `zt_bug` as `bug`
JOIN `zt_review` as `review` ON `bug`.`injection` = `review`.`id`
JOIN `zt_object` as `object` ON `review`.`object` = `object`.`id`
SET `bug`.`injection` = 'request'
WHERE `bug`.`injection` != 0 AND `object`.`category` IN ('PP', 'QAP', 'CMP', 'ERS', 'URS', 'SRS');

UPDATE `zt_bug` as `bug`
JOIN `zt_review` as `review` ON `bug`.`injection` = `review`.`id`
JOIN `zt_object` as `object` ON `review`.`object` = `object`.`id`
SET `bug`.`injection` = 'code'
WHERE `bug`.`injection` != 0 AND `object`.`category` IN ('Code', 'ITP', 'ITTC', 'STP', 'STTC');

UPDATE `zt_bug` as `bug`
JOIN `zt_review` as `review` ON `bug`.`injection` = `review`.`id`
JOIN `zt_object` as `object` ON `review`.`object` = `object`.`id`
SET `bug`.`injection` = 'design'
WHERE `bug`.`injection` != 0 AND `object`.`category` NOT IN ('PP', 'QAP', 'CMP', 'ERS', 'URS', 'SRS', 'Code', 'ITP', 'ITTC', 'STP', 'STTC');

UPDATE `zt_bug` as `bug`
JOIN `zt_review` as `review` ON `bug`.`identify` = `review`.`id`
JOIN `zt_object` as `object` ON `review`.`object` = `object`.`id`
SET `bug`.`identify` = 'design'
WHERE `bug`.`identify` != 0 AND `object`.`category` IN ('HLDS', 'DDS', 'DBDS', 'ADS', 'QAP', 'CMP');

UPDATE `zt_bug` as `bug`
JOIN `zt_review` as `review` ON `bug`.`identify` = `review`.`id`
JOIN `zt_object` as `object` ON `review`.`object` = `object`.`id`
SET `bug`.`identify` = 'integration'
WHERE `bug`.`identify` != 0 AND `object`.`category` IN ('ITP', 'ITTC');

UPDATE `zt_bug` as `bug`
JOIN `zt_review` as `review` ON `bug`.`identify` = `review`.`id`
JOIN `zt_object` as `object` ON `review`.`object` = `object`.`id`
SET `bug`.`identify` = 'system'
WHERE `bug`.`identify` != 0 AND `object`.`category` IN ('STP', 'STTC');

UPDATE `zt_bug` as `bug`
JOIN `zt_review` as `review` ON `bug`.`identify` = `review`.`id`
JOIN `zt_object` as `object` ON `review`.`object` = `object`.`id`
SET `bug`.`identify` = 'production'
WHERE `bug`.`identify` != 0 AND `object`.`category` = 'UM';

UPDATE `zt_bug` as `bug`
JOIN `zt_review` as `review` ON `bug`.`identify` = `review`.`id`
JOIN `zt_object` as `object` ON `review`.`object` = `object`.`id`
SET `bug`.`identify` = 'requirement'
WHERE `bug`.`identify` != 0 AND `object`.`category` NOT IN ('HLDS', 'DDS', 'DBDS', 'ADS', 'QAP', 'CMP', 'ITP', 'ITTC', 'STP', 'STTC', 'UM');

UPDATE `zt_bug` SET `injection` = '' WHERE `injection` = '0';
UPDATE `zt_bug` SET `identify`  = '' WHERE `identify`  = '0';