ALTER TABLE `zt_project` ADD `vision` varchar(10) NOT NULL DEFAULT 'rnd' AFTER `order`;
ALTER TABLE `zt_product` ADD `vision` varchar(10) NOT NULL DEFAULT 'rnd' AFTER `order`;
ALTER TABLE `zt_doclib` ADD `vision` varchar(10) NOT NULL DEFAULT 'rnd' AFTER `order`;
ALTER TABLE `zt_task` ADD `vision` varchar(10) NOT NULL DEFAULT 'rnd' AFTER `deleted`;
ALTER TABLE `zt_group` ADD `vision` varchar(10) NOT NULL DEFAULT 'rnd' AFTER `project`;
ALTER TABLE `zt_lang` ADD `vision` varchar(10) NOT NULL DEFAULT 'rnd' AFTER `system`;
ALTER TABLE `zt_lang` DROP INDEX `lang`, ADD UNIQUE `lang` (`lang`,`module`,`section`,`key`,`vision`);
ALTER TABLE `zt_doc` ADD `vision` varchar(10) NOT NULL DEFAULT 'rnd' AFTER `id`;
ALTER TABLE `zt_doclib` ADD `vision` varchar(10) NOT NULL DEFAULT 'rnd' AFTER `type`;
