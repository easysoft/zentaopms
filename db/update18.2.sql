ALTER TABLE `zt_lang` MODIFY COLUMN `section` varchar(50) NOT NULL;
DELETE FROM `zt_grouppriv` WHERE `module` = 'dev' AND `method` = 'editor';
REPLACE INTO `zt_config` (`owner`, `module`, `section`, `key`, `value`) VALUES ('system','common','','setPercent','1');
DROP TABLE IF EXISTS `zt_dimension`;

REPLACE INTO `zt_lang` (`lang`, `module`, `section`, `key`, `value`, `system`) VALUES
('zh-tw', 'custom', 'URSRList', '1', '{"SRName":"\\u8edf\\u4ef6\\u9700\\u6c42","URName":"\\u7528\\u6236\\u9700\\u6c42"}', '0'),
('zh-tw', 'custom', 'URSRList', '2', '{"SRName":"\\u7814\\u767c\\u9700\\u6c42","URName":"\\u7528\\u6236\\u9700\\u6c42"}', '0'),
('zh-tw', 'custom', 'URSRList', '3', '{"SRName":"\\u8edf\\u9700","URName":"\\u7528\\u9700"}', '0'),
('zh-tw', 'custom', 'URSRList', '4', '{"SRName":"\\u6545\\u4e8b","URName":"\\u53f2\\u8a69"}', '0'),
('zh-tw', 'custom', 'URSRList', '5', '{"SRName":"\\u9700\\u6c42","URName":"\\u7528\\u6236\\u9700\\u6c42"}', '0'),
('fr', 'custom', 'URSRList', '1', '{\"SRName\":\"Story\",\"URName\":\"Epic\"}', '0'),
('fr', 'custom', 'URSRList', '2', '{\"SRName\":\"Software Requirement\",\"URName\":\"User Requirement\"}', '0'),
('de', 'custom', 'URSRList', '1', '{\"SRName\":\"Story\",\"URName\":\"Epic\"}', '0'),
('de', 'custom', 'URSRList', '2', '{\"SRName\":\"Software Requirement\",\"URName\":\"User Requirement\"}', '0');
