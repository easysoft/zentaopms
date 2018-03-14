ALTER TABLE `zt_user` CHANGE `realname` `realname` varchar(100) COLLATE 'utf8_general_ci' NOT NULL DEFAULT '' AFTER `password`;
