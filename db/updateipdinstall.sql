CREATE TABLE `zt_demandpool` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `desc` mediumtext NULL,
  `status` char(30) NOT NULL DEFAULT '',
  `createdBy` char(30) NOT NULL DEFAULT '',
  `createdDate` date NULL,
  `owner` text NULL,
  `reviewer` text NULL,
  `acl` char(30) NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE `zt_demand` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `pool` int(8) NOT NULL DEFAULT '0',
  `module` int(8) NOT NULL DEFAULT '0',
  `product` mediumint(8) NOT NULL DEFAULT '0',
  `parent` mediumint(8) NOT NULL DEFAULT '0',
  `pri` char(30) NOT NULL DEFAULT '',
  `category` char(30) NOT NULL DEFAULT '',
  `source` char(30) NOT NULL DEFAULT '',
  `sourceNote` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `feedbackedBy` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `assignedTo` char(30) NOT NULL DEFAULT '',
  `assignedDate` datetime NULL,
  `reviewedBy` text NULL,
  `reviewedDate` datetime NULL,
  `status` char(30) NOT NULL DEFAULT '',
  `duration` char(30) NOT NULL DEFAULT '',
  `BSA` char(30) NOT NULL DEFAULT '',
  `story` mediumint(8) NOT NULL DEFAULT '0',
  `roadmap` mediumint(8) NOT NULL DEFAULT '0',
  `createdBy` char(30) NOT NULL DEFAULT '',
  `createdDate` datetime NULL,
  `mailto` text NULL,
  `duplicateDemand` mediumint(8) NULL,
  `childDemands` varchar(255) NOT NULL DEFAULT '',
  `version` varchar(255) NOT NULL DEFAULT '',
  `vision` varchar(255) NOT NULL DEFAULT 'or',
  `color` varchar(255) NOT NULL DEFAULT '',
  `changedBy` char(30) NOT NULL DEFAULT '',
  `changedDate` datetime NULL,
  `closedBy` char(30) NOT NULL DEFAULT '',
  `closedDate` datetime NULL,
  `closedReason` varchar(30) NOT NULL DEFAULT '',
  `submitedBy` varchar(30) NOT NULL DEFAULT '',
  `lastEditedBy` varchar(30) NOT NULL DEFAULT '',
  `lastEditedDate` datetime NULL,
  `activatedDate` datetime NULL,
  `distributedBy` varchar(30) NOT NULL DEFAULT '',
  `distributedDate` datetime NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE `zt_demandspec` (
  `demand` mediumint(9) NOT NULL DEFAULT '0',
  `version` smallint(6) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `spec` mediumtext NULL,
  `verify` mediumtext NULL,
  `files` text NULL
) ENGINE=InnoDB;
CREATE UNIQUE INDEX `demand` ON `zt_demandspec`(`demand`,`version`);

CREATE TABLE `zt_demandreview` (
  `demand` mediumint(9) NOT NULL DEFAULT '0',
  `version` smallint(6) NOT NULL DEFAULT '0',
  `reviewer` varchar(30) NOT NULL DEFAULT '',
  `result` varchar(30) NOT NULL DEFAULT '',
  `reviewDate` datetime NULL
) ENGINE=InnoDB;
CREATE UNIQUE INDEX `demand` ON `zt_demandreview`(`demand`,`version`,`reviewer`);

CREATE TABLE `zt_charter` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `level` int(8) NOT NULL DEFAULT '0',
  `category` char(30) NOT NULL DEFAULT '',
  `market` varchar(30) NOT NULL DEFAULT '',
  `check` enum('0','1') NOT NULL DEFAULT '0',
  `appliedBy` char(30) NOT NULL DEFAULT '',
  `appliedDate` datetime NULL,
  `budget` char(30) NOT NULL DEFAULT '',
  `budgetUnit` char(30) NOT NULL DEFAULT '',
  `product` mediumint(8) NOT NULL DEFAULT '0',
  `roadmap` mediumint(8) NOT NULL DEFAULT '0',
  `spec` mediumtext NULL,
  `status` char(30) NOT NULL DEFAULT '',
  `createdBy` char(30) NOT NULL DEFAULT '',
  `createdDate` datetime NULL,
  `charterFiles` text NULL,
  `closedBy` char(30) NOT NULL DEFAULT '',
  `closedDate` datetime NULL,
  `closedReason` varchar(255) NOT NULL DEFAULT '',
  `activatedBy` char(30) NOT NULL DEFAULT '',
  `activatedDate` datetime NULL,
  `reviewedBy` varchar(255) NOT NULL DEFAULT '',
  `reviewedResult` char(30) NOT NULL DEFAULT '',
  `reviewedDate` datetime NULL,
  `meetingDate` date NULL,
  `meetingLocation` varchar(255) NOT NULL DEFAULT '',
  `meetingMinutes` mediumtext NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

REPLACE INTO `zt_config` (`vision`, `owner`, `module`, `section`, `key`, `value`) VALUES ('or', 'system', 'demand', '', 'reviewRules', 'allpass');
REPLACE INTO `zt_config` (`vision`, `owner`, `module`, `section`, `key`, `value`) VALUES ('or', 'system', 'demand', '', 'needReview', 1);

ALTER TABLE `zt_story` ADD `BSA` char(30) NOT NULL AFTER `notifyEmail`;
ALTER TABLE `zt_story` ADD `duration` char(30) NOT NULL AFTER `notifyEmail`;
ALTER TABLE `zt_story` ADD `demand` mediumint(8)  NOT NULL AFTER `notifyEmail`;
ALTER TABLE `zt_product` ADD `PMT` text NOT NULL AFTER `reviewer`;
ALTER TABLE `zt_story` ADD `submitedBy` varchar(30) NOT NULL AFTER `changedDate`;
ALTER TABLE `zt_story` ADD `roadmap` VARCHAR(255)  NOT NULL  DEFAULT ''  AFTER `plan`;

CREATE TABLE `zt_roadmap` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `product` mediumint(8) NOT NULL DEFAULT '0',
  `branch` mediumint(8) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `status` char(30) NOT NULL DEFAULT '',
  `begin` date NULL,
  `end` date NULL,
  `desc` longtext NULL,
  `createdBy` char(30) NOT NULL DEFAULT '',
  `createdDate` date NULL,
  `closedBy` char(30) NOT NULL DEFAULT '',
  `closedDate` datetime NULL,
  `closedReason` enum('done','canceled') DEFAULT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE `zt_roadmapstory` (
  `roadmap` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `story` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `order` MEDIUMINT  UNSIGNED  NOT NULL
) ENGINE=InnoDB;
CREATE UNIQUE INDEX `roadmap_story` ON `zt_roadmapstory`(`roadmap`,`story`);

ALTER TABLE `zt_story` MODIFY `status` enum('','changing','active','draft','closed','reviewing','launched','developing') NOT NULL DEFAULT '';

ALTER TABLE `zt_project` ADD `charter` mediumint(8) NOT NULL DEFAULT 0 AFTER `project`;
ALTER TABLE `zt_project` ADD `category` char(30) NOT NULL DEFAULT '' AFTER `type`;

DELETE FROM `zt_stage` WHERE `projectType` = 'ipd';
REPLACE INTO `zt_stage` (`name`, `percent`, `type`, `projectType`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `deleted`) VALUES
('概念',        '10',   'concept',   'ipd', 'admin', '2020-02-08 21:08:30',  'admin', '2020-02-12 13:50:27',  '0'),
('计划',        '10',   'plan',      'ipd', 'admin', '2020-02-08 21:08:30',  'admin', '2020-02-12 13:50:27',  '0'),
('开发',        '50',   'develop',   'ipd', 'admin', '2020-02-08 21:08:30',  'admin', '2020-02-12 13:50:27',  '0'),
('验证',        '15',   'qualify',   'ipd', 'admin', '2020-02-08 21:08:30',  'admin', '2020-02-12 13:50:27',  '0'),
('发布',        '10',   'launch',    'ipd', 'admin', '2020-02-08 21:08:30',  'admin', '2020-02-12 13:50:27',  '0'),
('生命周期',    '5',    'lifecycle', 'ipd', 'admin', '2020-02-08 21:08:45',  'admin', '2020-02-12 13:50:27',  '0');

ALTER TABLE `zt_review` ADD `begin` date NULL AFTER `createdDate`;
ALTER TABLE `zt_review` MODIFY `doc` varchar(255);
ALTER TABLE `zt_review` MODIFY `docVersion` varchar(255);

ALTER TABLE `zt_object` ADD `end` date NULL AFTER `designEst`;

UPDATE `zt_priv` SET `vision` = ',rnd,or,' WHERE `module` = 'requirement' AND `method` = 'import';
UPDATE `zt_priv` SET `vision` = ',rnd,or,' WHERE `module` = 'requirement' AND `method` = 'exportTemplate';

REPLACE INTO `zt_priv` (`id`, `module`, `method`, `parent`, `edition`, `vision`, `system`, `order`) VALUES (2109, 'demand', 'export', '643', ',ipd,', ',or,', '1', '210');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2109, 'priv', 'de',    'demand-export', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2109, 'priv', 'en',    'demand-export', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2109, 'priv', 'fr',    'demand-export', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2109, 'priv', 'zh-cn', 'demand-export', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2109, 'priv', 'zh-tw', 'demand-export', '', '');

REPLACE INTO `zt_priv` (`id`, `module`, `method`, `parent`, `edition`, `vision`, `system`, `order`) VALUES (2110, 'demand', 'exportTemplate', '643', ',ipd,', ',or,', '1', '220');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2110, 'priv', 'de',    'demand-exportTemplate', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2110, 'priv', 'en',    'demand-exportTemplate', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2110, 'priv', 'fr',    'demand-exportTemplate', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2110, 'priv', 'zh-cn', 'demand-exportTemplate', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2110, 'priv', 'zh-tw', 'demand-exportTemplate', '', '');

REPLACE INTO `zt_priv` (`id`, `module`, `method`, `parent`, `edition`, `vision`, `system`, `order`) VALUES (2111, 'demand', 'import', '643', ',ipd,', ',or,', '1', '230');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2111, 'priv', 'de',    'demand-import', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2111, 'priv', 'en',    'demand-import', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2111, 'priv', 'fr',    'demand-import', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2111, 'priv', 'zh-cn', 'demand-import', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2111, 'priv', 'zh-tw', 'demand-import', '', '');

REPLACE INTO `zt_privrelation` (priv, `type`, relationPriv) VALUES(2109, 'depend',    2075);
REPLACE INTO `zt_privrelation` (priv, `type`, relationPriv) VALUES(2109, 'recommend', 2110);
REPLACE INTO `zt_privrelation` (priv, `type`, relationPriv) VALUES(2109, 'recommend', 2111);

REPLACE INTO `zt_privrelation` (priv, `type`, relationPriv) VALUES(2110, 'depend',    2075);
REPLACE INTO `zt_privrelation` (priv, `type`, relationPriv) VALUES(2110, 'recommend', 2109);

REPLACE INTO `zt_privrelation` (priv, `type`, relationPriv) VALUES(2111, 'depend',    2075);
REPLACE INTO `zt_privrelation` (priv, `type`, relationPriv) VALUES(2111, 'depend',    2110);
REPLACE INTO `zt_privrelation` (priv, `type`, relationPriv) VALUES(2111, 'recommend', 2109);
REPLACE INTO `zt_privrelation` (priv, `type`, relationPriv) VALUES(2111, 'recommend', 2076);
REPLACE INTO `zt_privrelation` (priv, `type`, relationPriv) VALUES(2111, 'recommend', 2077);

REPLACE INTO `zt_priv` (`id`, `module`, `method`, `parent`, `edition`, `vision`, `system`, `order`) VALUES (2112, 'requirement', 'batchChangeRoadmap', '32', ',ipd,', ',or,', '1', '125');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2112, 'priv', 'de',    'requirement-batchChangeRoadmap', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2112, 'priv', 'en',    'requirement-batchChangeRoadmap', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2112, 'priv', 'fr',    'requirement-batchChangeRoadmap', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2112, 'priv', 'zh-cn', 'requirement-batchChangeRoadmap', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2112, 'priv', 'zh-tw', 'requirement-batchChangeRoadmap', '', '');

REPLACE INTO `zt_privrelation` (priv, `type`, relationPriv) VALUES(2112, 'depend',    65);
REPLACE INTO `zt_privrelation` (priv, `type`, relationPriv) VALUES(2112, 'recommend', 121);
REPLACE INTO `zt_privrelation` (priv, `type`, relationPriv) VALUES(2112, 'recommend', 122);
REPLACE INTO `zt_privrelation` (priv, `type`, relationPriv) VALUES(2112, 'recommend', 123);

UPDATE `zt_privlang` SET `value` = '创建维护立项' WHERE `objectID` = 638 and `objectType` = 'manager';

REPLACE INTO `zt_priv` (`id`, `module`, `method`, `parent`, `edition`, `vision`, `system`, `order`) VALUES (2113, 'charter', 'close', '638', ',ipd,', ',or,', '1', '80');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2113, 'priv', 'de',    'charter-close', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2113, 'priv', 'en',    'charter-close', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2113, 'priv', 'fr',    'charter-close', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2113, 'priv', 'zh-cn', 'charter-close', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2113, 'priv', 'zh-tw', 'charter-close', '', '');

REPLACE INTO `zt_priv` (`id`, `module`, `method`, `parent`, `edition`, `vision`, `system`, `order`) VALUES (2114, 'charter', 'activate', '638', ',ipd,', ',or,', '1', '90');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2114, 'priv', 'de',    'charter-activate', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2114, 'priv', 'en',    'charter-activate', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2114, 'priv', 'fr',    'charter-activate', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2114, 'priv', 'zh-cn', 'charter-activate', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2114, 'priv', 'zh-tw', 'charter-activate', '', '');

REPLACE INTO `zt_privrelation` (priv, `type`, relationPriv) VALUES(2113, 'depend',    2061);
REPLACE INTO `zt_privrelation` (priv, `type`, relationPriv) VALUES(2113, 'depend',    2064);
REPLACE INTO `zt_privrelation` (priv, `type`, relationPriv) VALUES(2113, 'recommend', 2062);
REPLACE INTO `zt_privrelation` (priv, `type`, relationPriv) VALUES(2113, 'recommend', 2063);
REPLACE INTO `zt_privrelation` (priv, `type`, relationPriv) VALUES(2113, 'recommend', 2114);

REPLACE INTO `zt_privrelation` (priv, `type`, relationPriv) VALUES(2114, 'depend',    2061);
REPLACE INTO `zt_privrelation` (priv, `type`, relationPriv) VALUES(2114, 'depend',    2064);
REPLACE INTO `zt_privrelation` (priv, `type`, relationPriv) VALUES(2114, 'recommend', 2062);
REPLACE INTO `zt_privrelation` (priv, `type`, relationPriv) VALUES(2114, 'recommend', 2063);
REPLACE INTO `zt_privrelation` (priv, `type`, relationPriv) VALUES(2114, 'recommend', 2113);

REPLACE INTO `zt_priv` (`id`, `module`, `method`, `parent`, `edition`, `vision`, `system`, `order`) VALUES (2115, 'requirement', 'relation', '32', ',max,ipd,', ',rnd,', '1', '130');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2115, 'priv', 'de',    'requirement-relation', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2115, 'priv', 'en',    'requirement-relation', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2115, 'priv', 'fr',    'requirement-relation', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2115, 'priv', 'zh-cn', 'requirement-relation', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2115, 'priv', 'zh-tw', 'requirement-relation', '', '');

UPDATE `zt_priv` SET `vision` = ',rnd,or,' WHERE `module` = 'user' AND `method` = 'export';
UPDATE `zt_priv` SET `vision` = ',rnd,or,' WHERE `module` = 'user' AND `method` = 'exportTemplate';
UPDATE `zt_priv` SET `vision` = ',rnd,or,' WHERE `module` = 'user' AND `method` = 'import';
UPDATE `zt_priv` SET `vision` = ',rnd,or,' WHERE `module` = 'user' AND `method` = 'importldap';
