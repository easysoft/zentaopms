UPDATE zt_demand SET status = 'active' WHERE status in ('launched','distributed','pass');
UPDATE zt_story  SET status = 'active' WHERE status in ('launched','developing');

ALTER TABLE `zt_demand` ADD `parentVersion` smallint NOT NULL DEFAULT '0' AFTER `version`;
UPDATE `zt_demand` AS t1 JOIN `zt_demand` AS t2 ON t1.parent = t2.id SET t1.parentVersion = t2.version WHERE t1.parent > 0;

ALTER TABLE `zt_approvalnode` ADD `forwardBy` char(30) NOT NULL DEFAULT '' AFTER `extra`;
ALTER TABLE `zt_approvalnode` ADD `revertTo` char(30) NOT NULL DEFAULT '' AFTER `extra`;

UPDATE `zt_workflowaction` SET conditions = '' WHERE `action` IN ('approvalreview','approvalcancel','approvalsubmit');
