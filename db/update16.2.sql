ALTER TABLE `zt_kanbanspace` ADD `type` varchar(50) NOT NULL AFTER `name`;
UPDATE `zt_kanbanspace` SET `type` = 'cooperation' WHERE `type` = '';
UPDATE `zt_kanban` SET `team` = CONCAT(team, ',', whitelist) where `whitelist` != '';

ALTER TABLE `zt_kanban` ADD `object` varchar(255) NOT NULL AFTER `displayCards`;
ALTER TABLE `zt_kanban` ADD `performable` enum ('0', '1') NOT NULL DEFAULT '0' AFTER `archived`;
ALTER TABLE `zt_kanban` ADD `fluidBoard` enum('0','1') NOT NULL DEFAULT '0' AFTER `displayCards`;
ALTER TABLE `zt_kanbancard` ADD `status` varchar(30) NOT NULL DEFAULT 'doing' AFTER `name`;

ALTER TABLE `zt_kanbancard` ADD `fromID` mediumint(8) unsigned NOT NULL AFTER `group`;
ALTER TABLE `zt_kanbancard` ADD `fromType` varchar(30) NOT NULL AFTER `fromID`;

ALTER TABLE `zt_job` ADD `sonarqubeServer` mediumint(8) unsigned NOT NULL AFTER `triggerType`;
ALTER TABLE `zt_job` ADD `projectKey` varchar(255) NOT NULL AFTER `sonarqubeServer`;

ALTER TABLE `zt_project` ADD `fluidBoard` enum('0','1') NOT NULL DEFAULT '0' AFTER `displayCards`;
