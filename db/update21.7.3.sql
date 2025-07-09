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
JOIN `zt_object` AS `object` ON `object`.`category` = 'UM'
WHERE `bug`.`identify` != 0 AND `object`.`category` = 'UM';

UPDATE `zt_bug` AS `bug`
JOIN `zt_review` AS `review` ON `bug`.`identify` = `review`.`id`
JOIN `zt_object` AS `object` ON `review`.`object` = `object`.`id`
SET `bug`.`identify` = 'requirement'
WHERE `bug`.`identify` != 0 AND `object`.`category` NOT IN ('HLDS', 'DDS', 'DBDS', 'ADS', 'QAP', 'CMP', 'ITP', 'ITTC', 'STP', 'STTC', 'UM');

UPDATE `zt_bug` SET `injection` = '' WHERE `injection` = '0';
UPDATE `zt_bug` SET `identify`  = '' WHERE `identify`  = '0';