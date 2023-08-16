-- DROP TABLE IF EXISTS `zt_space`;
CREATE TABLE `zt_space` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `k8space` char(64) NOT NULL,
  `owner` char(30) NOT NULL,
  `default` tinyint(1) NOT NULL DEFAULT 0,
  `createdAt` datetime NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_instance`;
CREATE TABLE IF NOT EXISTS `zt_instance` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `space` mediumint(8) unsigned NOT NULL DEFAULT 0,
  `solution` mediumint(8) unsigned NOT NULL DEFAULT 0,
  `name` char(50) DEFAULT '',
  `appID` mediumint(8) unsigned NOT NULL DEFAULT 0,
  `appName` char(50) NOT NULL DEFAULT '',
  `appVersion` char(20) NOT NULL DEFAULT '',
  `chart` char(50) NOT NULL DEFAULT '',
  `logo` varchar(255) DEFAULT '',
  `version` char(50) NOT NULL DEFAULT '',
  `desc` text,
  `introduction` varchar(500) DEFAULT '',
  `source` char(20) NOT NULL DEFAULT '',
  `channel` char(20) DEFAULT '',
  `k8name` char(64) NOT NULL DEFAULT '',
  `status` char(20) NOT NULL DEFAULT '',
  `pinned` enum('0', '1') NOT NULL DEFAULT '0',
  `domain` char(255) NOT NULL DEFAULT '',
  `smtpSnippetName` char(30) NULL DEFAULT '',
  `ldapSnippetName` char(30) NULL DEFAULT '',
  `ldapSettings` text,
  `dbSettings` text,
  `autoBackup` tinyint(1) NOT NULL DEFAULT 0,
  `backupKeepDays` int unsigned NOT NULL DEFAULT 1,
  `autoRestore` tinyint(1) NOT NULL DEFAULT 0,
  `env` text,
  `createdBy` char(30) NOT NULL DEFAULT '',
  `createdAt` datetime NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `space` (`space`),
  KEY `k8name` (`k8name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_solution`;
CREATE TABLE IF NOT EXISTS `zt_solution` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(50),
  `appID` mediumint(8) unsigned NOT NULL,
  `appName` char(50) NOT NULL,
  `appVersion` char(20) NOT NULL,
  `version` char(50) NOT NULL,
  `chart` char(50) NOT NULL,
  `cover` varchar(255),
  `desc` text,
  `introduction` varchar(500),
  `source` char(20) NOT NULL,
  `channel` char(20),
  `components` text,
  `status` char(20) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `createdBy` char(30) NOT NULL,
  `createdAt` datetime NOT NULL,
  `updatedDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `zt_privmanager` (`parent`, `code`, `type`, `edition`, `vision`, `order`) VALUES (515, 'application', 'package', ',open,biz,max,ipd,', ',rnd,', 5);
INSERT INTO `zt_priv` (`module`, `method`, `parent`, `edition`, `vision`, `system`, `order`) VALUES ('space', 'browse', 651, ',open,biz,max,ipd,', ',rnd,', '1', 10),('instance', 'view', 651, ',open,biz,max,ipd,', ',rnd,', '1', 20),('space', 'getStoreAppInfo', 651, ',open,biz,max,ipd,', ',rnd,', '1', 30),('instance', 'install', 651, ',open,biz,max,ipd,', ',rnd,', '1', 40),('instance', 'visit', 651, ',open,biz,max,ipd,', ',rnd,', '1', 50),('instance', 'ajaxStatus', 651, ',open,biz,max,ipd,', ',rnd,', '1', 60),('instance', 'ajaxStart', 651, ',open,biz,max,ipd,', ',rnd,', '1', 70),('instance', 'ajaxStop', 651, ',open,biz,max,ipd,', ',rnd,', '1', 80),('instance', 'ajaxUninstall', 651, ',open,biz,max,ipd,', ',rnd,', '1', 90),('instance', 'upgrade', 651, ',open,biz,max,ipd,', ',rnd,', '1', 100);

INSERT INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (651, 'manager', 'zh-cn', '', '应用', ''),(651, 'manager', 'zh-tw', '', '應用', ''),(651, 'manager', 'de', '', 'Applications', ''),(651, 'manager', 'en', '', 'Applications', ''),(651, 'manager', 'fr', '', 'Applications', ''),(2109, 'priv', 'zh-cn', 'space-browse', '', ''),(2109, 'priv', 'zh-tw', 'space-browse', '', ''),(2109, 'priv', 'de', 'space-browse', '', ''),(2109, 'priv', 'en', 'space-browse', '', ''),(2109, 'priv', 'fr', 'space-browse', '', ''),(2110, 'priv', 'zh-cn', 'instance-view', '', ''),(2110, 'priv', 'zh-tw', 'instance-view', '', ''),(2110, 'priv', 'de', 'instance-view', '', ''),(2110, 'priv', 'en', 'instance-view', '', ''),(2110, 'priv', 'fr', 'instance-view', '', ''),(2111, 'priv', 'zh-cn', 'space-getStoreAppInfo', '', ''),(2111, 'priv', 'zh-tw', 'space-getStoreAppInfo', '', ''),(2111, 'priv', 'de', 'space-getStoreAppInfo', '', ''), (2111, 'priv', 'en', 'space-getStoreAppInfo', '', ''),(2111, 'priv', 'fr', 'space-getStoreAppInfo', '', ''),(2112, 'priv', 'zh-cn', 'instance-install', '', ''),(2112, 'priv', 'zh-tw', 'instance-install', '', ''),(2112, 'priv', 'de', 'instance-install', '', ''), (2112, 'priv', 'en', 'instance-install', '', ''),(2112, 'priv', 'fr', 'instance-install', '', ''),(2113, 'priv', 'zh-cn', 'instance-visit', '', ''),(2113, 'priv', 'zh-tw', 'instance-visit', '', ''),(2113, 'priv', 'de', 'instance-visit', '', ''), (2113, 'priv', 'en', 'instance-visit', '', ''),(2113, 'priv', 'fr', 'instance-visit', '', ''),(2114, 'priv', 'zh-cn', 'instance-ajaxStatus', '', ''),(2114, 'priv', 'zh-tw', 'instance-ajaxStatus', '', ''),(2114, 'priv', 'de', 'instance-ajaxStatus', '', ''), (2114, 'priv', 'en', 'instance-ajaxStatus', '', ''),(2114, 'priv', 'fr', 'instance-ajaxStatus', '', ''),(2115, 'priv', 'zh-cn', 'instance-ajaxStart', '', ''),(2115, 'priv', 'zh-tw', 'instance-ajaxStart', '', ''),(2115, 'priv', 'de', 'instance-ajaxStart', '', ''), (2115, 'priv', 'en', 'instance-ajaxStart', '', ''),(2115, 'priv', 'fr', 'instance-ajaxStart', '', ''),(2116, 'priv', 'zh-cn', 'instance-ajaxStop', '', ''),(2116, 'priv', 'zh-tw', 'instance-ajaxStop', '', ''),(2116, 'priv', 'de', 'instance-ajaxStop', '', ''), (2116, 'priv', 'en', 'instance-ajaxStop', '', ''),(2116, 'priv', 'fr', 'instance-ajaxStop', '', ''),(2117, 'priv', 'zh-cn', 'instance-ajaxUninstall', '', ''),(2117, 'priv', 'zh-tw', 'instance-ajaxUninstall', '', ''),(2117, 'priv', 'de', 'instance-ajaxUninstall', '', ''), (2117, 'priv', 'en', 'instance-ajaxUninstall', '', ''),(2117, 'priv', 'fr', 'instance-ajaxUninstall', '', ''),(2118, 'priv', 'zh-cn', 'instance-upgrade', '', ''),(2118, 'priv', 'zh-tw', 'instance-upgrade', '', ''),(2118, 'priv', 'de', 'instance-upgrade', '', ''), (2118, 'priv', 'en', 'instance-upgrade', '', ''),(2118, 'priv', 'fr', 'instance-upgrade', '', '');

INSERT INTO `zt_privmanager` (`parent`, `code`, `type`, `edition`, `vision`, `order`) VALUES (515, 'store', 'package', ',open,biz,max,ipd,', ',rnd,', 5);
INSERT INTO `zt_priv` (`module`, `method`, `parent`, `edition`, `vision`, `system`, `order`) VALUES ('store', 'browse', 652, ',open,biz,max,ipd,', ',rnd,', '1', 10),('store', 'appView', 652, ',open,biz,max,ipd,', ',rnd,', '1', 20);

INSERT INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (652, 'manager', 'zh-cn', '', '应用市场', ''),(652, 'manager', 'zh-tw', '', '應用市場', ''),(652, 'manager', 'de', '', 'Store', ''),(652, 'manager', 'en', '', 'Store', ''),(652, 'manager', 'fr', '', 'Store', '');

REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2119, 'priv', 'de', 'store-browse', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2119, 'priv', 'en', 'store-browse', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2119, 'priv', 'fr', 'store-browse', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2119, 'priv', 'zh-cn', 'store-browse', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2119, 'priv', 'zh-tw', 'store-browse', '', '');

REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2120, 'priv', 'de', 'store-appView', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2120, 'priv', 'en', 'store-appView', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2120, 'priv', 'fr', 'store-appView', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2120, 'priv', 'zh-cn', 'store-appView', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2120, 'priv', 'zh-tw', 'store-appView', '', '');

UPDATE `zt_privmanager` SET `parent`=502 WHERE `parent`=556;
UPDATE `zt_privmanager` SET `parent`=517 WHERE `parent`=557;
UPDATE `zt_privmanager` SET `edition`=',open,biz,max,ipd,' WHERE `id` IN (558,559,560);
UPDATE `zt_priv` SET `edition`=',open,biz,max,ipd,' WHERE `module` IN ('host','account','ops','serverroom');

REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (1286, 'priv', 'de', 'ops-provider', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (1286, 'priv', 'en', 'ops-provider', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (1286, 'priv', 'fr', 'ops-provider', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (1286, 'priv', 'zh-cn', 'ops-provider', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (1286, 'priv', 'zh-tw', 'ops-provider', '', '');

REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (1285, 'priv', 'de', 'ops-city', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (1285, 'priv', 'en', 'ops-city', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (1285, 'priv', 'fr', 'ops-city', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (1285, 'priv', 'zh-cn', 'ops-city', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (1285, 'priv', 'zh-tw', 'ops-city', '', '');

UPDATE `zt_priv` SET `method`='city' WHERE `id`=1285;
UPDATE `zt_priv` SET `method`='provider' WHERE `id`=1286;
INSERT INTO `zt_priv` (`module`, `method`, `parent`, `edition`, `vision`, `system`, `order`) VALUES ('ops', 'cpuBrand', 126, ',open,biz,max,ipd,', ',rnd,', '1', 15),('ops', 'os', 126, ',open,biz,max,ipd,', ',rnd,', '1', 20);

REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2121, 'priv', 'de', 'ops-cpuBrand', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2121, 'priv', 'en', 'ops-cpuBrand', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2121, 'priv', 'fr', 'ops-cpuBrand', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2121, 'priv', 'zh-cn', 'ops-cpuBrand', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2121, 'priv', 'zh-tw', 'ops-cpuBrand', '', '');

REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2122, 'priv', 'de', 'ops-os', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2122, 'priv', 'en', 'ops-os', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2122, 'priv', 'fr', 'ops-os', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2122, 'priv', 'zh-cn', 'ops-os', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2122, 'priv', 'zh-tw', 'ops-os', '', '');
