ALTER TABLE `zt_kanbanspace` ADD `type` varchar(50) NOT NULL AFTER `name`;
UPDATE `zt_kanbanspace` SET `type` = 'cooperation';
