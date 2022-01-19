ALTER TABLE `zt_kanbanspace` ADD `type` varchar(50) NOT NULL AFTER `name`;
UPDATE `zt_kanbanspace` SET `type` = 'cooperation';
ALTER TABLE `zt_kanban` ADD `performable` enum ('0', '1') NOT NULL DEFAULT '0' AFTER `archived`;
