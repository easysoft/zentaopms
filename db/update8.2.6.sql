ALTER TABLE `zt_doclib` ADD `product` mediumint(8) unsigned NOT NULL AFTER `id`,
ADD `project` mediumint(8) unsigned NOT NULL AFTER `product`,
ADD `groups` varchar(255) COLLATE 'utf8_general_ci' NOT NULL AFTER `name`,
ADD `users` text COLLATE 'utf8_general_ci' NOT NULL AFTER `groups`;
