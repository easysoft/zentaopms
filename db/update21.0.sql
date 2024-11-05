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

ALTER TABLE `zt_deploy` ADD `host` mediumint(8) unsigned NOT NULL DEFAULT 0 AFTER `name`;

ALTER TABLE `zt_deployproduct` DROP COLUMN `package`;
