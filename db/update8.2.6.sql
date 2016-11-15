ALTER TABLE `zt_doclib` ADD `product` mediumint(8) unsigned NOT NULL AFTER `id`,
ADD `project` mediumint(8) unsigned NOT NULL AFTER `product`,
ADD `groups` varchar(255) COLLATE 'utf8_general_ci' NOT NULL AFTER `name`,
ADD `users` text COLLATE 'utf8_general_ci' NOT NULL AFTER `groups`;

CREATE TABLE IF NOT EXISTS `zt_doccontent` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `doc` mediumint(8) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `digest` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `files` text NOT NULL,
  `type` varchar(10) NOT NULL,
  `version` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `doc_version` (`doc`,`version`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
ALTER TABLE `zt_doc` ADD `version` smallint unsigned NOT NULL DEFAULT '1' AFTER `editedDate`;
ALTER TABLE `zt_doc` ADD `groups` varchar(255) NOT NULL AFTER `editedDate`,
ADD `users` text NOT NULL AFTER `groups`;
ALTER TABLE `zt_doclib` ADD `acl` varchar(10) COLLATE 'utf8_general_ci' NOT NULL DEFAULT 'open' AFTER `name`;
ALTER TABLE `zt_doc` ADD `acl` varchar(10) NOT NULL DEFAULT 'open' AFTER `editedDate`;
ALTER TABLE `zt_doclib` ADD `main` enum('0','1') COLLATE 'utf8_general_ci' NOT NULL DEFAULT '0' AFTER `users`;
UPDATE `zt_doc` SET `type` = 'text' WHERE `type` = 'file';
UPDATE `zt_module` SET `type` = 'doc' WHERE `type` = 'customdoc';
