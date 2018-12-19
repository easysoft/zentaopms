ALTER TABLE `zt_userview` CHANGE `products` `products` mediumtext COLLATE 'utf8_general_ci' NOT NULL AFTER `account`,
CHANGE `projects` `projects` mediumtext COLLATE 'utf8_general_ci' NOT NULL AFTER `products`;
ALTER TABLE `zt_task` ADD `finishedList` text NOT NULL AFTER `finishedDate`;
update zt_config set `value` = 'files,customFiles' where `module` = 'doc' and `section` = 'custom' and `key` = 'objectLibs';
ALTER TABLE `zt_file` CHANGE `title` `title` varchar(255) COLLATE 'utf8_general_ci' NOT NULL AFTER `pathname`;

ALTER TABLE `zt_doclib` ADD `type` varchar(30) COLLATE 'utf8_general_ci' NOT NULL AFTER `id`;
UPDATE `zt_doclib` SET `type` = 'product' WHERE `product` > 0;
UPDATE `zt_doclib` SET `type` = 'project' WHERE `project` > 0;
UPDATE `zt_doclib` SET `type` = 'custom'  WHERE `product` = 0 AND `project` = 0;
