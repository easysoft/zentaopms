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
UPDATE `zt_project` SET `status` = 'doing' WHERE `status` = '';
ALTER TABLE  `zt_testTask` ADD  `report` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER  `desc`;
ALTER TABLE  `zt_project` CHANGE  `type`  `type` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  'sprint';

-- 2013-1-21 change the priv of todo mark method instead finish method.
UPDATE `zt_groupPriv` SET method='finish'  WHERE module='todo' AND method='mark';

ALTER TABLE `zt_taskEstimate` CHANGE `date` `date` DATE NOT NULL;

DELETE FROM `zt_groupPriv` WHERE `module` = 'webapp' and `method` = 'index';
INSERT INTO `zt_groupPriv` (`company` , `group` , `module` , `method` ) VALUES
('1', '1', 'webapp', 'index'),
('1', '2', 'webapp', 'index'),
('1', '3', 'webapp', 'index'),
('1', '4', 'webapp', 'index'),
('1', '5', 'webapp', 'index'),
('1', '6', 'webapp', 'index'),
('1', '7', 'webapp', 'index'),
('1', '8', 'webapp', 'index'),
('1', '9', 'webapp', 'index'),
('1', '10', 'webapp', 'index'),
('1', '11', 'webapp', 'index');

ALTER TABLE  `zt_webapp` ADD  `abstract` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER  `size` ;
ALTER TABLE `zt_webapp` CHANGE `url` `url` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

UPDATE `zt_groupPriv` set method='finish' where module='todo' and method='mark';
ALTER TABLE  `zt_taskEstimate` ADD  `work` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
