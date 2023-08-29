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

-- DROP TABLE IF EXISTS `zt_artifactrepo`;
CREATE TABLE `zt_artifactrepo` (
  `id` smallint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(45) CHARACTER SET utf8 NOT NULL,
  `products` varchar(1000) CHARACTER SET utf8 NOT NULL,
  `serverID` smallint(8) NOT NULL,
  `repoName` varchar(45) CHARACTER SET utf8 NOT NULL,
  `format` varchar(10) CHARACTER SET utf8 NOT NULL,
  `type` char(7) CHARACTER SET utf8 NOT NULL,
  `status` varchar(10) CHARACTER SET utf8 NOT NULL,
  `createdBy` varchar(30) CHARACTER SET utf8 NOT NULL,
  `createdDate` datetime DEFAULT CURRENT_TIMESTAMP,
  `editedBy` varchar(30) CHARACTER SET utf8 NOT NULL,
  `editedDate` datetime DEFAULT CURRENT_TIMESTAMP,
  `deleted` tinyint(4) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `zt_build` ADD `artifactRepoID` MEDIUMINT(8) UNSIGNED NOT NULL AFTER `bugs`;

ALTER TABLE `zt_host` ADD COLUMN `product` varchar(255) NOT NULL DEFAULT '' AFTER `hostType`,ADD COLUMN `testType` varchar(10) NOT NULL DEFAULT '' AFTER `product`;
UPDATE `zt_host` SET `testType`='kvm',`type`='normal' WHERE `type`='zahost';
UPDATE `zt_host` SET `testType`='node',`type`='normal',`hostType`='physical' WHERE `type`='node' AND `parent`=0;

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

CREATE TABLE IF NOT EXISTS `zt_prompt` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `desc` text DEFAULT NULL,
  `model` mediumint(8) unsigned DEFAULT NULL,
  `module` varchar(30) DEFAULT NULL,
  `source` text DEFAULT NULL,
  `targetForm` varchar(30) DEFAULT NULL,
  `purpose` text DEFAULT NULL,
  `elaboration` text DEFAULT NULL,
  `role` text DEFAULT NULL,
  `characterization` text DEFAULT NULL,
  `status` enum('draft','active','replaced') NOT NULL DEFAULT 'draft',
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) DEFAULT NULL,
  `editedDate` datetime DEFAULT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `zt_promptrole` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT NULL,
  `desc` text DEFAULT NULL,
  `model` mediumint(8) unsigned DEFAULT NULL,
  `role` text DEFAULT NULL,
  `characterization` text DEFAULT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

REPLACE INTO
    `zt_priv` (`id`, `module`, `method`, `parent`, `edition`, `vision`, `system`, `order`)
VALUES
    (2117, 'ai', 'models', 653, ',open,biz,max,', ',rnd,', '1', 5),
    (2118, 'ai', 'editModel', 653, ',open,biz,max,', ',rnd,', '1', 10),
    (2119, 'ai', 'testConnection', 653, ',open,biz,max,', ',rnd,', '1', 15),
    (2120, 'ai', 'createPrompt', 655, ',biz,max,', ',rnd,', '1', 20),
    (2121, 'ai', 'promptEdit', 655, ',biz,max,', ',rnd,', '1', 25),
    (2122, 'ai', 'promptDelete', 657, ',biz,max,', ',rnd,', '1', 30),
    (2123, 'ai', 'promptAssignRole', 655, ',biz,max,', ',rnd,', '1', 35),
    (2124, 'ai', 'promptSelectDataSource', 655, ',biz,max,', ',rnd,', '1', 40),
    (2125, 'ai', 'promptSetPurpose', 655, ',biz,max,', ',rnd,', '1', 45),
    (2126, 'ai', 'promptSetTargetForm', 655, ',biz,max,', ',rnd,', '1', 50),
    (2127, 'ai', 'promptFinalize', 655, ',biz,max,', ',rnd,', '1', 55),
    (2128, 'ai', 'promptAudit', 655, ',biz,max,', ',rnd,', '1', 60),
    (2129, 'ai', 'promptPublish', 656, ',open,biz,max,', ',rnd,', '1', 65),
    (2130, 'ai', 'promptUnpublish', 656, ',open,biz,max,', ',rnd,', '1', 70),
    (2131, 'ai', 'prompts', 654, ',open,biz,max,', ',rnd,', '1', 75),
    (2132, 'ai', 'promptView', 654, ',open,biz,max,', ',rnd,', '1', 80),
    (2133, 'ai', 'promptExecute', 652, ',open,biz,max,', ',rnd,', '1', 85),
    (2134, 'ai', 'roleTemplates', 655, ',biz,max,', ',rnd,', '1', 90),
    (2135, 'ai', 'promptExecutionReset', 652, ',open,biz,max,', ',rnd,', '1', 95);

REPLACE INTO
    `zt_privmanager` (`id`, `parent`, `code`, `type`, `edition`, `vision`, `order`)
VALUES
    (651, 457, 'ai', 'module', ',open,biz,max,', ',rnd,', 2020),
    (652, 651, '', 'package', ',open,biz,max,', ',rnd,', 2040),
    (653, 651, '', 'package', ',open,biz,max,', ',rnd,', 2060),
    (654, 651, '', 'package', ',open,biz,max,', ',rnd,', 2080),
    (655, 651, '', 'package', ',biz,max,', ',rnd,', 2100),
    (656, 651, '', 'package', ',open,biz,max,', ',rnd,', 2120),
    (657, 651, '', 'package', ',biz,max,', ',rnd,', 2140);

REPLACE INTO
    `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`)
VALUES
    (651, 'manager', 'zh-cn', '', 'AI', ''),
    (652, 'manager', 'zh-cn', '', '执行提词', ''),
    (653, 'manager', 'zh-cn', '', '语言模型管理', ''),
    (654, 'manager', 'zh-cn', '', '浏览提词', ''),
    (655, 'manager', 'zh-cn', '', '维护和设计提词', ''),
    (656, 'manager', 'zh-cn', '', '提词上下架', ''),
    (657, 'manager', 'zh-cn', '', '删除提词', ''),
    (651, 'manager', 'zh-tw', '', 'AI', ''),
    (652, 'manager', 'zh-tw', '', '執行提詞', ''),
    (653, 'manager', 'zh-tw', '', '語言模型管理', ''),
    (654, 'manager', 'zh-tw', '', '瀏覽提詞', ''),
    (655, 'manager', 'zh-tw', '', '維護和設計提詞', ''),
    (656, 'manager', 'zh-tw', '', '提詞上下架', ''),
    (657, 'manager', 'zh-tw', '', '刪除提詞', ''),
    (651, 'manager', 'en', '', 'AI', ''),
    (652, 'manager', 'en', '', 'Execute Prompts', ''),
    (653, 'manager', 'en', '', 'Manage Models', ''),
    (654, 'manager', 'en', '', 'Browse Prompts', ''),
    (655, 'manager', 'en', '', 'Manage and Design Prompts', ''),
    (656, 'manager', 'en', '', 'Publish and Unpublish Prompts', ''),
    (657, 'manager', 'en', '', 'Delete Prompts', ''),
    (651, 'manager', 'de', '', 'AI', ''),
    (652, 'manager', 'de', '', 'Execute Prompts', ''),
    (653, 'manager', 'de', '', 'Manage Models', ''),
    (654, 'manager', 'de', '', 'Browse Prompts', ''),
    (655, 'manager', 'de', '', 'Manage and Design Prompts', ''),
    (656, 'manager', 'de', '', 'Publish and Unpublish Prompts', ''),
    (657, 'manager', 'de', '', 'Delete Prompts', ''),
    (651, 'manager', 'fr', '', 'AI', ''),
    (652, 'manager', 'fr', '', 'Execute Prompts', ''),
    (653, 'manager', 'fr', '', 'Manage Models', ''),
    (654, 'manager', 'fr', '', 'Browse Prompts', ''),
    (655, 'manager', 'fr', '', 'Manage and Design Prompts', ''),
    (656, 'manager', 'fr', '', 'Publish and Unpublish Prompts', ''),
    (657, 'manager', 'fr', '', 'Delete Prompts', ''),
    (2117, 'priv', 'zh-cn', 'ai-modelBrowse', '', ''),
    (2118, 'priv', 'zh-cn', 'ai-modelEdit', '', ''),
    (2119, 'priv', 'zh-cn', 'ai-modelTestConnection', '', ''),
    (2120, 'priv', 'zh-cn', 'ai-promptCreate', '', ''),
    (2121, 'priv', 'zh-cn', 'ai-promptEdit', '', ''),
    (2122, 'priv', 'zh-cn', 'ai-promptDelete', '', ''),
    (2123, 'priv', 'zh-cn', 'ai-promptAssignRole', '', ''),
    (2124, 'priv', 'zh-cn', 'ai-promptSelectDataSource', '', ''),
    (2125, 'priv', 'zh-cn', 'ai-promptSetPurpose', '', ''),
    (2126, 'priv', 'zh-cn', 'ai-promptSetTargetForm', '', ''),
    (2127, 'priv', 'zh-cn', 'ai-promptFinalize', '', ''),
    (2128, 'priv', 'zh-cn', 'ai-promptAudit', '', ''),
    (2129, 'priv', 'zh-cn', 'ai-promptPublish', '', ''),
    (2130, 'priv', 'zh-cn', 'ai-promptUnpublish', '', ''),
    (2131, 'priv', 'zh-cn', 'ai-promptBrowse', '', ''),
    (2132, 'priv', 'zh-cn', 'ai-promptView', '', ''),
    (2133, 'priv', 'zh-cn', 'ai-promptExecute', '', ''),
    (2134, 'priv', 'zh-cn', 'ai-roleTemplates', '', ''),
    (2135, 'priv', 'zh-cn', 'ai-promptExecutionReset', '', ''),
    (2117, 'priv', 'zh-tw', 'ai-modelBrowse', '', ''),
    (2118, 'priv', 'zh-tw', 'ai-modelEdit', '', ''),
    (2119, 'priv', 'zh-tw', 'ai-modelTestConnection', '', ''),
    (2120, 'priv', 'zh-tw', 'ai-promptCreate', '', ''),
    (2121, 'priv', 'zh-tw', 'ai-promptEdit', '', ''),
    (2122, 'priv', 'zh-tw', 'ai-promptDelete', '', ''),
    (2123, 'priv', 'zh-tw', 'ai-promptAssignRole', '', ''),
    (2124, 'priv', 'zh-tw', 'ai-promptSelectDataSource', '', ''),
    (2125, 'priv', 'zh-tw', 'ai-promptSetPurpose', '', ''),
    (2126, 'priv', 'zh-tw', 'ai-promptSetTargetForm', '', ''),
    (2127, 'priv', 'zh-tw', 'ai-promptFinalize', '', ''),
    (2128, 'priv', 'zh-tw', 'ai-promptAudit', '', ''),
    (2129, 'priv', 'zh-tw', 'ai-promptPublish', '', ''),
    (2130, 'priv', 'zh-tw', 'ai-promptUnpublish', '', ''),
    (2131, 'priv', 'zh-tw', 'ai-promptBrowse', '', ''),
    (2132, 'priv', 'zh-tw', 'ai-promptView', '', ''),
    (2133, 'priv', 'zh-tw', 'ai-promptExecute', '', ''),
    (2134, 'priv', 'zh-tw', 'ai-roleTemplates', '', ''),
    (2135, 'priv', 'zh-tw', 'ai-promptExecutionReset', '', ''),
    (2117, 'priv', 'en', 'ai-modelBrowse', '', ''),
    (2118, 'priv', 'en', 'ai-modelEdit', '', ''),
    (2119, 'priv', 'en', 'ai-modelTestConnection', '', ''),
    (2120, 'priv', 'en', 'ai-promptCreate', '', ''),
    (2121, 'priv', 'en', 'ai-promptEdit', '', ''),
    (2122, 'priv', 'en', 'ai-promptDelete', '', ''),
    (2123, 'priv', 'en', 'ai-promptAssignRole', '', ''),
    (2124, 'priv', 'en', 'ai-promptSelectDataSource', '', ''),
    (2125, 'priv', 'en', 'ai-promptSetPurpose', '', ''),
    (2126, 'priv', 'en', 'ai-promptSetTargetForm', '', ''),
    (2127, 'priv', 'en', 'ai-promptFinalize', '', ''),
    (2128, 'priv', 'en', 'ai-promptAudit', '', ''),
    (2129, 'priv', 'en', 'ai-promptPublish', '', ''),
    (2130, 'priv', 'en', 'ai-promptUnpublish', '', ''),
    (2131, 'priv', 'en', 'ai-promptBrowse', '', ''),
    (2132, 'priv', 'en', 'ai-promptView', '', ''),
    (2133, 'priv', 'en', 'ai-promptExecute', '', ''),
    (2134, 'priv', 'en', 'ai-roleTemplates', '', ''),
    (2135, 'priv', 'en', 'ai-promptExecutionReset', '', ''),
    (2117, 'priv', 'de', 'ai-modelBrowse', '', ''),
    (2118, 'priv', 'de', 'ai-modelEdit', '', ''),
    (2119, 'priv', 'de', 'ai-modelTestConnection', '', ''),
    (2120, 'priv', 'de', 'ai-promptCreate', '', ''),
    (2121, 'priv', 'de', 'ai-promptEdit', '', ''),
    (2122, 'priv', 'de', 'ai-promptDelete', '', ''),
    (2123, 'priv', 'de', 'ai-promptAssignRole', '', ''),
    (2124, 'priv', 'de', 'ai-promptSelectDataSource', '', ''),
    (2125, 'priv', 'de', 'ai-promptSetPurpose', '', ''),
    (2126, 'priv', 'de', 'ai-promptSetTargetForm', '', ''),
    (2127, 'priv', 'de', 'ai-promptFinalize', '', ''),
    (2128, 'priv', 'de', 'ai-promptAudit', '', ''),
    (2129, 'priv', 'de', 'ai-promptPublish', '', ''),
    (2130, 'priv', 'de', 'ai-promptUnpublish', '', ''),
    (2131, 'priv', 'de', 'ai-promptBrowse', '', ''),
    (2132, 'priv', 'de', 'ai-promptView', '', ''),
    (2133, 'priv', 'de', 'ai-promptExecute', '', ''),
    (2134, 'priv', 'de', 'ai-roleTemplates', '', ''),
    (2135, 'priv', 'de', 'ai-promptExecutionReset', '', ''),
    (2117, 'priv', 'fr', 'ai-modelBrowse', '', ''),
    (2118, 'priv', 'fr', 'ai-modelEdit', '', ''),
    (2119, 'priv', 'fr', 'ai-modelTestConnection', '', ''),
    (2120, 'priv', 'fr', 'ai-promptCreate', '', ''),
    (2121, 'priv', 'fr', 'ai-promptEdit', '', ''),
    (2122, 'priv', 'fr', 'ai-promptDelete', '', ''),
    (2123, 'priv', 'fr', 'ai-promptAssignRole', '', ''),
    (2124, 'priv', 'fr', 'ai-promptSelectDataSource', '', ''),
    (2125, 'priv', 'fr', 'ai-promptSetPurpose', '', ''),
    (2126, 'priv', 'fr', 'ai-promptSetTargetForm', '', ''),
    (2127, 'priv', 'fr', 'ai-promptFinalize', '', ''),
    (2128, 'priv', 'fr', 'ai-promptAudit', '', ''),
    (2129, 'priv', 'fr', 'ai-promptPublish', '', ''),
    (2130, 'priv', 'fr', 'ai-promptUnpublish', '', ''),
    (2131, 'priv', 'fr', 'ai-promptBrowse', '', ''),
    (2132, 'priv', 'fr', 'ai-promptView', '', ''),
    (2133, 'priv', 'fr', 'ai-promptExecute', '', ''),
    (2134, 'priv', 'fr', 'ai-roleTemplates', '', ''),
    (2135, 'priv', 'zh-cn', 'ai-promptExecutionReset', '', '');

REPLACE INTO
    `zt_privrelation` (`priv`, `type`, `relationPriv`)
VALUES
    ('ai-editModel', 'depend', 'ai-models'), ('ai-editModel', 'depend', 'ai-testConnection'),
    ('ai-testConnection', 'depend', 'ai-models'), ('ai-testConnection', 'depend', 'ai-editModel'),
    ('ai-promptView', 'depend', 'ai-prompts'),
    ('ai-createPrompt', 'depend', 'ai-prompts'), ('ai-createPrompt', 'depend', 'ai-promptView'),
    ('ai-promptEdit', 'depend', 'ai-prompts'), ('ai-promptEdit', 'depend', 'ai-promptView'),
    ('ai-promptDelete', 'depend', 'ai-prompts'), ('ai-promptDelete', 'depend', 'ai-promptView'),
    ('ai-promptAssignRole', 'depend', 'ai-prompts'), ('ai-promptAssignRole', 'depend', 'ai-promptView'),
    ('ai-promptSelectDataSource', 'depend', 'ai-prompts'), ('ai-promptSelectDataSource', 'depend', 'ai-promptView'),
    ('ai-promptSetPurpose', 'depend', 'ai-prompts'), ('ai-promptSetPurpose', 'depend', 'ai-promptView'),
    ('ai-promptSetTargetForm', 'depend', 'ai-prompts'), ('ai-promptSetTargetForm', 'depend', 'ai-promptView'),
    ('ai-promptFinalize', 'depend', 'ai-prompts'), ('ai-promptFinalize', 'depend', 'ai-promptView'),
    ('ai-promptAudit', 'depend', 'ai-prompts'), ('ai-promptAudit', 'depend', 'ai-promptView'),
    ('ai-promptPublish', 'depend', 'ai-prompts'), ('ai-promptPublish', 'depend', 'ai-promptView'),
    ('ai-promptUnpublish', 'depend', 'ai-prompts'), ('ai-promptUnpublish', 'depend', 'ai-promptView'),
    ('ai-promptEdit', 'depend', 'ai-createPrompt'), ('ai-promptAssignRole', 'depend', 'ai-createPrompt'), ('ai-promptSelectDataSource', 'depend', 'ai-createPrompt'), ('ai-promptSetPurpose', 'depend', 'ai-createPrompt'), ('ai-promptSetTargetForm', 'depend', 'ai-createPrompt'), ('ai-promptFinalize', 'depend', 'ai-createPrompt'), ('ai-promptAudit', 'depend', 'ai-createPrompt'),
    ('ai-createPrompt', 'recommend', 'ai-promptEdit'),
    ('ai-createPrompt', 'recommend', 'ai-promptDelete'),
    ('ai-createPrompt', 'recommend', 'ai-promptAssignRole'), ('ai-createPrompt', 'recommend', 'ai-promptSelectDataSource'), ('ai-createPrompt', 'recommend', 'ai-promptSetPurpose'), ('ai-createPrompt', 'recommend', 'ai-promptSetTargetForm'), ('ai-createPrompt', 'recommend', 'ai-promptFinalize'), ('ai-createPrompt', 'recommend', 'ai-promptAudit'),
    ('ai-promptAssignRole', 'depend', 'ai-promptSelectDataSource'), ('ai-promptAssignRole', 'depend', 'ai-promptSetPurpose'), ('ai-promptAssignRole', 'depend', 'ai-promptSetTargetForm'), ('ai-promptAssignRole', 'depend', 'ai-promptFinalize'), ('ai-promptAssignRole', 'depend', 'ai-promptAudit'), ('ai-promptAssignRole', 'depend', 'ai-promptExecute'),
    ('ai-promptSelectDataSource', 'depend', 'ai-promptAssignRole'), ('ai-promptSelectDataSource', 'depend', 'ai-promptSetPurpose'), ('ai-promptSelectDataSource', 'depend', 'ai-promptSetTargetForm'), ('ai-promptSelectDataSource', 'depend', 'ai-promptFinalize'), ('ai-promptSelectDataSource', 'depend', 'ai-promptAudit'), ('ai-promptSelectDataSource', 'depend', 'ai-promptExecute'),
    ('ai-promptSetPurpose', 'depend', 'ai-promptAssignRole'), ('ai-promptSetPurpose', 'depend', 'ai-promptSelectDataSource'), ('ai-promptSetPurpose', 'depend', 'ai-promptSetTargetForm'), ('ai-promptSetPurpose', 'depend', 'ai-promptFinalize'), ('ai-promptSetPurpose', 'depend', 'ai-promptAudit'), ('ai-promptSetPurpose', 'depend', 'ai-promptExecute'),
    ('ai-promptSetTargetForm', 'depend', 'ai-promptAssignRole'), ('ai-promptSetTargetForm', 'depend', 'ai-promptSelectDataSource'), ('ai-promptSetTargetForm', 'depend', 'ai-promptSetPurpose'), ('ai-promptSetTargetForm', 'depend', 'ai-promptFinalize'), ('ai-promptSetTargetForm', 'depend', 'ai-promptAudit'), ('ai-promptSetTargetForm', 'depend', 'ai-promptExecute'),
    ('ai-promptFinalize', 'depend', 'ai-promptAssignRole'), ('ai-promptFinalize', 'depend', 'ai-promptSelectDataSource'), ('ai-promptFinalize', 'depend', 'ai-promptSetPurpose'), ('ai-promptFinalize', 'depend', 'ai-promptSetTargetForm'), ('ai-promptFinalize', 'depend', 'ai-promptAudit'), ('ai-promptFinalize', 'depend', 'ai-promptExecute'),
    ('ai-promptAudit', 'depend', 'ai-promptAssignRole'), ('ai-promptAudit', 'depend', 'ai-promptSelectDataSource'), ('ai-promptAudit', 'depend', 'ai-promptSetPurpose'), ('ai-promptAudit', 'depend', 'ai-promptSetTargetForm'), ('ai-promptAudit', 'depend', 'ai-promptFinalize'), ('ai-promptAudit', 'depend', 'ai-promptExecute'),
    ('ai-roleTemplates', 'depend', 'ai-promptAssignRole'), ('ai-promptAssignRole', 'depend', 'ai-roleTemplates'),
    ('ai-promptExecutionReset', 'depend', 'ai-promptExecute'), ('ai-promptExecute', 'depend', 'ai-promptExecutionReset');

INSERT INTO `zt_promptrole` (`role`, `characterization`) VALUES ('请你扮演一名资深的产品经理。', '负责产品战略、设计、开发、数据分析、用户体验、团队管理、沟通协调等方面，需要具备多种技能和能力，以实现产品目标和公司战略。');
INSERT INTO `zt_promptrole` (`role`, `characterization`) VALUES ('你是一名经验丰富的开发工程师。', '精通多种编程语言和框架、熟悉前后端技术和架构、擅长性能优化和安全防护、熟悉云计算和容器化技术、能够协调多人协作和项目管理。');
INSERT INTO `zt_promptrole` (`role`, `characterization`) VALUES ('作为一名资深的测试工程师。', '测试工程师应该是专业且严谨的。熟悉测试流程和方法，精通自动化测试和性能测试，能够设计和编写测试用例和测试脚本，擅长问题诊断和分析，熟悉敏捷开发和持续集成，能够协调多部门合作和项目管理。');
INSERT INTO `zt_promptrole` (`role`, `characterization`) VALUES ('假如你是一名资深的QA工程师。', '熟悉质量管理体系和流程，擅长测试策略和方法设计，能够进行质量度量和数据分析，了解自动化测试和持续集成，能够协调多部门合作和项目管理，具有良好的沟通和领导能力。');
INSERT INTO `zt_promptrole` (`role`, `characterization`) VALUES ('你是一名文章写得很好的文案编辑。', '文笔流畅、条理清晰。精通广告文案写作和编辑，擅长创意思维和品牌策略，能够进行市场调研和竞品分析，具有敏锐的审美和语言表达能力，能够协调多部门合作和项目管理，具有良好的沟通和协调能力。');
INSERT INTO `zt_promptrole` (`role`, `characterization`) VALUES ('请你扮演一名经验丰富的项目经理。', '具备项目计划制定、进度管理、成本控制、团队管理、沟通协调、风险管理、质量控制、敏捷开发、互联网技术和数据分析等多方面的技能和能力。');
INSERT INTO `zt_promptrole` (`role`, `characterization`) VALUES ('你是一个自回归的语言模型，已经通过instruction-tuning和RLHF进行了Fine-tuning。', '你仔细地提供准确、事实、深思熟虑、细致入微的答案，并在推理方面表现出色。如果你认为可能没有正确的答案，你会直接说出来。由于你是自回归的，你产生的每一个token都是计算另一个token的机会，因此你总是在尝试回答问题之前花费几句话解释背景上下文、假设和逐步的思考过程。您的用户是AI和伦理学的专家，所以他们已经知道您是一个语言模型以及您的能力和局限性，所以不需要再提醒他们。他们一般都熟悉伦理问题，所以您也不需要再提醒他们。在回答时不要啰嗦，但在可能有助于解释的地方提供详细信息和示例。');

INSERT INTO `zt_prompt` (`name`, `model`, `module`, `source`, `targetForm`, `purpose`, `elaboration`, `role`, `characterization`, `createdBy`, `createdDate`) VALUES ('需求润色', 0, 'story', ',story.title,story.spec,story.verify,story.product,story.module,story.pri,story.category,story.estimate,', 'story.change', '帮忙优化其中各字段的表述，使表述清晰准确。必要时可以修改需求使其更加合理。', '需求描述格式建议使用：作为一名<某种类型的用户>，我希望<达成某些目的>，这样可以<开发的价值>。验收标准建议列举多条。', '请你扮演一名资深的产品经理。', '负责产品战略、设计、开发、数据分析、用户体验、团队管理、沟通协调等方面，需要具备多种技能和能力，以实现产品目标和公司战略。', 'system', '2023-08-10 13:24:14');
INSERT INTO `zt_prompt` (`name`, `model`, `module`, `source`, `targetForm`, `purpose`, `elaboration`, `role`, `characterization`, `createdBy`, `createdDate`) VALUES ('一键拆用例', 0, 'story', ',story.title,story.spec,story.verify,story.product,story.module,story.pri,story.category,story.estimate,', 'story.testcasecreate', '为这个需求生成一个或多个对应的测试用例。', '', '作为一名资深的测试工程师。', '熟悉测试流程和方法，精通自动化测试和性能测试，能够设计和编写测试用例和测试脚本，擅长问题诊断和分析，熟悉敏捷开发和持续集成，能够协调多部门合作和项目管理。开发工程师应该是专业且严谨的。', 'system', '2023-08-10 13:24:14');
INSERT INTO `zt_prompt` (`name`, `model`, `module`, `source`, `targetForm`, `purpose`, `elaboration`, `role`, `characterization`, `createdBy`, `createdDate`) VALUES ('任务润色', 0, 'task', ',task.name,task.desc,task.pri,task.status,task.estimate,task.consumed,task.left,task.progress,task.estStarted,task.realStarted,', 'task.edit', '优化其中各字段的表述，使表述清晰准确，明确任务目标。', '必要时指出任务的风险点。', '你是一名经验丰富的开发工程师。', '精通多种编程语言和框架、熟悉前后端技术和架构、擅长性能优化和安全防护、熟悉云计算和容器化技术、能够协调多人协作和项目管理。', 'system', '2023-08-10 13:24:14');
INSERT INTO `zt_prompt` (`name`, `model`, `module`, `source`, `targetForm`, `purpose`, `elaboration`, `role`, `characterization`, `createdBy`, `createdDate`) VALUES ('需求转任务', 0, 'story', ',story.title,story.spec,story.verify,story.product,story.module,story.pri,story.category,story.estimate,', 'story.totask', '将需求转化为对应的开发任务要求。', '', '请你扮演一名资深的产品经理。', '负责产品战略、设计、开发、数据分析、用户体验、团队管理、沟通协调等方面，需要具备多种技能和能力，以实现产品目标和公司战略。同时精通多种编程语言和框架、熟悉前后端技术。', 'system', '2023-08-10 13:24:14');
INSERT INTO `zt_prompt` (`name`, `model`, `module`, `source`, `targetForm`, `purpose`, `elaboration`, `role`, `characterization`, `createdBy`, `createdDate`) VALUES ('Bug转需求', 0, 'bug', ',bug.title,bug.steps,bug.severity,bug.pri,bug.status,bug.confirmed,bug.type,', 'bug.story/create', '将bug转换为产品需求，表述清晰准确。', '需求描述格式建议使用：作为一名<某种类型的用户>，我希望<达成某些目的>，这样可以<开发的价值>。验收标准建议列举多条。', '请你扮演一名资深的产品经理。', '负责产品战略、设计、开发、数据分析、用户体验、团队管理、沟通协调等方面，需要具备多种技能和能力，以实现产品目标和公司战略。', 'system', '2023-08-10 13:24:14');
INSERT INTO `zt_prompt` (`name`, `model`, `module`, `source`, `targetForm`, `purpose`, `elaboration`, `role`, `characterization`, `createdBy`, `createdDate`) VALUES ('Bug润色', 0, 'bug', ',bug.title,bug.steps,bug.severity,bug.pri,bug.status,bug.confirmed,bug.type,', 'bug.edit', '优化其中各字段的表述，使表述清晰准确。', 'Bug描述格式建议使用：[步骤]<一步一步复现Bug的步骤>[结果]<Bug导致的结果描述>[期望]<Bug修复后期望的描述>', '作为一名资深的测试工程师。', '熟悉测试流程和方法，精通自动化测试和性能测试，能够设计和编写测试用例和测试脚本，擅长问题诊断和分析，熟悉敏捷开发和持续集成，能够协调多部门合作和项目管理。开发工程师应该是专业且严谨的。', 'system', '2023-08-10 13:24:14');

ALTER TABLE `zt_ticket` ADD `subStatus` varchar(30) NOT NULL DEFAULT '';
