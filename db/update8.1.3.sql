ALTER TABLE `zt_module` ADD `short` varchar(30) COLLATE 'utf8_general_ci' NOT NULL AFTER `owner`;
ALTER TABLE `zt_usertpl` ADD `public` enum('0','1') COLLATE 'utf8_general_ci' NOT NULL DEFAULT '0';
