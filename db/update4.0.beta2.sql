ALTER TABLE  `zt_build` CHANGE  `desc`  `desc` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE  `zt_group` ADD  `role` CHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE  `zt_taskestimate` CHANGE  `estimater`  `account` CHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  '';
ALTER TABLE  `zt_taskestimate` ADD  `consumed` TINYINT( 3 ) UNSIGNED NOT NULL AFTER  `estimate`;
UPDATE `zt_group` SET `role` = 'guest' WHERE `name` = 'guest';
ALTER TABLE  `zt_taskestimate` CHANGE  `estimate`  `left` TINYINT( 3 ) UNSIGNED NOT NULL DEFAULT  '0';
ALTER TABLE  `zt_taskestimate` CHANGE  `date`  `date` DATETIME NOT NULL;
