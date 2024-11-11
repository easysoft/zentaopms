ALTER TABLE zt_module MODIFY COLUMN short varchar(60);
ALTER TABLE `zt_host` DROP COLUMN `serverModel`;
ALTER TABLE `zt_host` DROP COLUMN `hardwareType`;
ALTER TABLE `zt_host` DROP COLUMN `cpuBrand`;
ALTER TABLE `zt_host` DROP COLUMN `cpuModel`;
ALTER TABLE `zt_host` DROP COLUMN `provider`;
ALTER TABLE `zt_host` ADD `CD` varchar(32) NOT NULL DEFAULT 'manual' AFTER `name`;
ALTER TABLE `zt_host` ADD `sshPort` mediumint NOT NULL DEFAULT 0 AFTER `ssh`;

ALTER TABLE `zt_deploystep` DROP COLUMN `begin`;
ALTER TABLE `zt_deploystep` DROP COLUMN `end`;
ALTER TABLE `zt_deploystep` ADD `parent` mediumint(8) unsigned NOT NULL DEFAULT 0 AFTER `deploy`;

DROP TABLE IF EXISTS `zt_deployscope`;

ALTER TABLE `zt_deploy` ADD `host` varchar(255) NOT NULL DEFAULT '' AFTER `name`;

ALTER TABLE `zt_deployproduct` DROP COLUMN `package`;

UPDATE `zt_deploy` SET `status` = 'success' WHERE `status` = 'done';

REPLACE INTO `zt_lang` (`lang`, `module`, `section`, `key`, `value`, `system`, `vision`) VALUES
('zh-cn', 'custom', 'relationList', '1', '{\"relation\":\"\\u76f8\\u5173\",\"relativeRelation\":\"\\u76f8\\u5173\"}', '0', 'all'),
('zh-cn', 'custom', 'relationList', '2', '{\"relation\":\"\\u4f9d\\u8d56\",\"relativeRelation\":\"\\u88ab\\u4f9d\\u8d56\"}', '0', 'all'),
('zh-cn', 'custom', 'relationList', '3', '{\"relation\":\"\\u91cd\\u590d\",\"relativeRelation\":\"\\u91cd\\u590d\"}', '0', 'all'),
('zh-cn', 'custom', 'relationList', '4', '{\"relation\":\"\\u5f15\\u7528\",\"relativeRelation\":\"\\u88ab\\u5f15\\u7528\"}', '0', 'all'),
('en', 'custom', 'relationList', '1', '{\"relation\":\"Relate\",\"relativeRelation\":\"Relate\"}', '0', 'all'),
('en', 'custom', 'relationList', '2', '{\"relation\":\"Dependence\",\"relativeRelation\":\"Depended On\"}', '0', 'all'),
('en', 'custom', 'relationList', '3', '{\"relation\":\"Repetition\",\"relativeRelation\":\"Repetition\"}', '0', 'all'),
('en', 'custom', 'relationList', '4', '{\"relation\":\"Quote\",\"relativeRelation\":\"Quoted\"}', '0', 'all'),
('de', 'custom', 'relationList', '1', '{\"relation\":\"Relate\",\"relativeRelation\":\"Relate\"}', '0', 'all'),
('de', 'custom', 'relationList', '2', '{\"relation\":\"Dependence\",\"relativeRelation\":\"Depended On\"}', '0', 'all'),
('de', 'custom', 'relationList', '3', '{\"relation\":\"Repetition\",\"relativeRelation\":\"Repetition\"}', '0', 'all'),
('de', 'custom', 'relationList', '4', '{\"relation\":\"Quote\",\"relativeRelation\":\"Quoted\"}', '0', 'all'),
('fr', 'custom', 'relationList', '1', '{\"relation\":\"Relate\",\"relativeRelation\":\"Relate\"}', '0', 'all'),
('fr', 'custom', 'relationList', '2', '{\"relation\":\"Dependence\",\"relativeRelation\":\"Depended On\"}', '0', 'all'),
('fr', 'custom', 'relationList', '3', '{\"relation\":\"Repetition\",\"relativeRelation\":\"Repetition\"}', '0', 'all'),
('fr', 'custom', 'relationList', '4', '{\"relation\":\"Quote\",\"relativeRelation\":\"Quoted\"}', '0', 'all'),
('zh-tw', 'custom', 'relationList', '1', '{\"relation\":\"\\u76f8\\u95dc\",\"relativeRelation\":\"\\u76f8\\u95dc\"}', '0', 'all'),
('zh-tw', 'custom', 'relationList', '2', '{\"relation\":\"\\u4f9d\\u8cf4\",\"relativeRelation\":\"\\u88ab\\u4f9d\\u8cf4\"}', '0', 'all'),
('zh-tw', 'custom', 'relationList', '3', '{\"relation\":\"\\u91cd\\u8907\",\"relativeRelation\":\"\\u91cd\\u8907\"}', '0', 'all'),
('zh-tw', 'custom', 'relationList', '4', '{\"relation\":\"\\u5f15\\u7528\",\"relativeRelation\":\"\\u88ab\\u5f15\\u7528\"}', '0', 'all');
