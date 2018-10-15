ALTER TABLE `zt_file`
CHANGE `title` `title` varchar(255) COLLATE 'utf8_general_ci' NOT NULL AFTER `pathname`;
