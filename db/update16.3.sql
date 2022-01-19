ALTER TABLE `zt_project` ADD `vision` varchar(10) NOT NULL DEFAULT 'rnd' AFTER `order`;
ALTER TABLE `zt_product` ADD `vision` varchar(10) NOT NULL DEFAULT 'rnd' AFTER `order`;
ALTER TABLE `zt_doclib` ADD `vision` varchar(10) NOT NULL DEFAULT 'rnd' AFTER `order`;
ALTER TABLE `zt_task` ADD `vision` varchar(10) NOT NULL DEFAULT 'rnd' AFTER `deleted`;
ALTER TABLE `zt_group` ADD `vision` varchar(10) NOT NULL DEFAULT 'rnd' AFTER `project`;
