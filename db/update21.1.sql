ALTER TABLE `zt_module` MODIFY COLUMN short varchar(60);
ALTER TABLE `zt_doc` ADD `templateDesc` text NULL AFTER `templateType`;
