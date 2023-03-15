ALTER TABLE `zt_lang` MODIFY COLUMN `section` varchar(50) NOT NULL;
DELETE FROM `zt_grouppriv` WHERE `module` = 'dev' AND `method` = 'editor';
REPLACE INTO `zt_config` (`owner`, `module`, `section`, `key`, `value`) VALUES ('system','common','','setPercent','1');
DROP TABLE IF EXISTS `zt_dimension`;
