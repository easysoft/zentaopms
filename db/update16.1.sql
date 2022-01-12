ALTER TABLE `zt_project` ADD `vision` varchar(10) NOT NULL DEFAULT 'common' AFTER `order`;
ALTER TABLE `zt_task` ADD `vision` varchar(10) NOT NULL DEFAULT 'common' AFTER `v2`;
