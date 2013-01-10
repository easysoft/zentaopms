ALTER TABLE  `zt_build` CHANGE  `desc`  `desc` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE  `zt_group` ADD  `role` CHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE  `zt_taskEstimate` CHANGE  `estimater`  `account` CHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  '';
ALTER TABLE  `zt_taskEstimate` ADD  `consumed` TINYINT( 3 ) UNSIGNED NOT NULL AFTER  `estimate`;
UPDATE `zt_group` SET `role` = 'guest' WHERE `name` = 'guest';
ALTER TABLE  `zt_taskEstimate` CHANGE  `estimate`  `left` TINYINT( 3 ) UNSIGNED NOT NULL DEFAULT  '0';
ALTER TABLE  `zt_taskEstimate` CHANGE  `date`  `date` DATETIME NOT NULL;
ALTER TABLE  `zt_taskEstimate` CHANGE  `left`  `left` FLOAT UNSIGNED NOT NULL DEFAULT  '0', CHANGE  `consumed`  `consumed` FLOAT UNSIGNED NOT NULL;

UPDATE `zt_config` SET `company` = 0 WHERE `key` = 'version';
DELETE FROM `zt_config` WHERE `company` = 1 AND `key` = 'sn';
UPDATE `zt_config` SET `company` = 1 WHERE `key` = 'sn';
UPDATE `zt_config` SET `section` = 'global' WHERE `key` = 'flow';
