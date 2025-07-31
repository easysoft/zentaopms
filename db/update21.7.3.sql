ALTER TABLE `zt_bug`
CHANGE `injection` `injection` varchar(30) NOT NULL DEFAULT '',
CHANGE `identify` `identify` varchar(30) NOT NULL DEFAULT '';

ALTER TABLE `zt_task` MODIFY `path` varchar(255) NOT NULL DEFAULT '';

CREATE INDEX `parent` ON `zt_story` (`parent`);
CREATE INDEX `path` ON `zt_task` (`path`);

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

INSERT INTO `zt_workflowaction` (`group`, `module`, `action`, `method`, `name`, `type`, `batchMode`, `extensionType`, `open`, `position`, `layout`, `show`, `order`, `buildin`, `role`, `virtual`, `conditions`, `verifications`, `hooks`, `linkages`, `js`, `css`, `toList`, `blocks`, `desc`, `status`, `vision`, `createdBy`, `createdDate`, `editedBy`, `editedDate`) VALUES
(0,	'project',	'execution',	'browse',	'执行列表',	'single',	'different',	'none',	'normal',	'browse',	'normal',	'direct',	0,	1,	'buildin',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	'',	NULL,	NULL,	'enable',	'rnd',	'admin',	'2025-01-08 09:41:02',	'',	NULL);
