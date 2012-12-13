ALTER TABLE  `zt_testtask` ADD  `pri` TINYINT( 3 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `owner`;
ALTER TABLE `zt_user` ADD `role` CHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `password`;
