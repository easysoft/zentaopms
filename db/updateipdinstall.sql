CREATE TABLE `zt_demandpool` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `desc` mediumtext NOT NULL,
  `status` char(30) NOT NULL,
  `createdBy` char(30) NOT NULL,
  `createdDate` date NOT NULL,
  `owner` text NOT NULL,
  `reviewer` text NOT NULL,
  `acl` char(30) NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `zt_demand` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `pool` int(8) NOT NULL,
  `module` int(8) NOT NULL,
  `product` mediumint(8) NOT NULL,
  `parent` mediumint(8) NOT NULL,
  `pri` char(30) NOT NULL,
  `category` char(30) NOT NULL,
  `source` char(30) NOT NULL,
  `sourceNote` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `feedbackedBy` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `assignedTo` char(30) NOT NULL,
  `assignedDate` datetime NOT NULL,
  `reviewedBy` text NOT NULL,
  `reviewedDate` datetime NOT NULL,
  `status` char(30) NOT NULL,
  `duration` char(30) NOT NULL,
  `BSA` char(30) NOT NULL,
  `story` mediumint(8) NOT NULL,
  `roadmap` mediumint(8) NOT NULL,
  `createdBy` char(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `mailto` text NOT NULL,
  `duplicateDemand` mediumint(8) NOT NULL,
  `childDemands` varchar(255) NOT NULL,
  `version` varchar(255) NOT NULL,
  `vision` varchar(255) NOT NULL DEFAULT 'or',
  `color` varchar(255) NOT NULL,
  `changedBy` char(30) NOT NULL,
  `changedDate` datetime NOT NULL,
  `closedBy` char(30) NOT NULL,
  `closedDate` datetime NOT NULL,
  `closedReason` varchar(30) NOT NULL,
  `submitedBy` varchar(30) NOT NULL,
  `lastEditedBy` varchar(30) NOT NULL DEFAULT '',
  `lastEditedDate` datetime NOT NULL,
  `activatedDate` datetime NOT NULL,
  `distributedBy` varchar(30) NOT NULL DEFAULT '',
  `distributedDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `zt_demandspec` (
  `demand` mediumint(9) NOT NULL,
  `version` smallint(6) NOT NULL,
  `title` varchar(255) NOT NULL,
  `spec` mediumtext NOT NULL,
  `verify` mediumtext NOT NULL,
  `files` text NOT NULL,
  UNIQUE KEY `demand` (`demand`,`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `zt_demandreview` (
  `demand` mediumint(9) NOT NULL,
  `version` smallint(6) NOT NULL,
  `reviewer` varchar(30) NOT NULL,
  `result` varchar(30) NOT NULL,
  `reviewDate` datetime NOT NULL,
  UNIQUE KEY `demand` (`demand`,`version`,`reviewer`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `zt_charter` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `level` int(8) NOT NULL,
  `category` char(30) NOT NULL,
  `market` varchar(30) NOT NULL,
  `appliedBy` char(30) NOT NULL,
  `appliedDate` datetime NOT NULL,
  `budget` char(30) NOT NULL,
  `budgetUnit` char(30) NOT NULL,
  `product` mediumint(8) NOT NULL,
  `roadmap` mediumint(8) NOT NULL,
  `spec` mediumtext NOT NULL,
  `status` char(30) NOT NULL,
  `createdBy` char(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `charterFiles` text NOT NULL,
  `reviewedBy` char(30) NOT NULL,
  `reviewedResult` char(30) NOT NULL,
  `reviewedDate` datetime NOT NULL,
  `meetingDate` date NOT NULL,
  `meetingLocation` varchar(255) NOT NULL,
  `meetingMinutes` mediumtext NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

REPLACE INTO `zt_config` (`vision`, `owner`, `module`, `section`, `key`, `value`) VALUES ('or', 'system', 'demand', '', 'reviewRules', 'allpass');
REPLACE INTO `zt_config` (`vision`, `owner`, `module`, `section`, `key`, `value`) VALUES ('or', 'system', 'demand', '', 'needReview', 1);

ALTER TABLE `zt_story` ADD `BSA` char(30) NOT NULL AFTER `notifyEmail`;
ALTER TABLE `zt_story` ADD `duration` char(30) NOT NULL AFTER `notifyEmail`;
ALTER TABLE `zt_story` ADD `demand` mediumint(8)  NOT NULL AFTER `notifyEmail`;
ALTER TABLE `zt_product` ADD `PMT` text COLLATE 'utf8_general_ci' NOT NULL AFTER `reviewer`;
ALTER TABLE `zt_story` ADD `submitedBy` varchar(30) NOT NULL AFTER `changedDate`;
ALTER TABLE `zt_story` ADD `roadmap` VARCHAR(255)  NOT NULL  DEFAULT ''  AFTER `plan`;
ALTER TABLE `zt_charter` ADD `check` enum('0','1') NOT NULL DEFAULT '0';
ALTER TABLE `zt_charter` modify column reviewedBy varchar(255);

CREATE TABLE `zt_roadmap` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `product` mediumint(8) NOT NULL,
  `branch` mediumint(8) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` char(30) NOT NULL,
  `begin` date NOT NULL,
  `end` date NOT NULL,
  `desc` longtext NOT NULL,
  `createdBy` char(30) NOT NULL,
  `createdDate` date NOT NULL,
  `closedBy` char(30) NOT NULL,
  `closedDate` datetime NOT NULL,
  `closedReason` enum('done','canceled') DEFAULT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `zt_roadmapstory` (
  `roadmap` mediumint(8) unsigned NOT NULL,
  `story` mediumint(8) unsigned NOT NULL,
  `order` MEDIUMINT  UNSIGNED  NOT NULL,
  UNIQUE KEY `roadmap_story` (`roadmap`,`story`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `zt_story` MODIFY `status` enum('','changing','active','draft','closed','reviewing','launched','developing') NOT NULL DEFAULT '' AFTER `estimate`;

ALTER TABLE `zt_project`
ADD `charter` mediumint(8) NOT NULL DEFAULT 0 AFTER `project`,
ADD `category` char(30) COLLATE 'utf8_general_ci' NOT NULL AFTER `type`;

REPLACE INTO `zt_stage` (`name`, `percent`, `type`, `projectType`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `deleted`) VALUES
('概念',        '10',   'concept',   'ipd', 'admin', '2020-02-08 21:08:30',  'admin', '2020-02-12 13:50:27',  '0'),
('计划',        '10',   'plan',      'ipd', 'admin', '2020-02-08 21:08:30',  'admin', '2020-02-12 13:50:27',  '0'),
('开发',        '50',   'develop',   'ipd', 'admin', '2020-02-08 21:08:30',  'admin', '2020-02-12 13:50:27',  '0'),
('验证',        '15',   'qualify',   'ipd', 'admin', '2020-02-08 21:08:30',  'admin', '2020-02-12 13:50:27',  '0'),
('发布',        '10',   'launch',    'ipd', 'admin', '2020-02-08 21:08:30',  'admin', '2020-02-12 13:50:27',  '0'),
('全生命周期',  '5',    'lifecycle', 'ipd', 'admin', '2020-02-08 21:08:45',  'admin', '2020-02-12 13:50:27',  '0');

ALTER TABLE `zt_review` ADD `begin` date NULL AFTER `createdDate`;
ALTER TABLE `zt_review` MODIFY `doc` varchar(255);
ALTER TABLE `zt_review` MODIFY `docVersion` varchar(255);

ALTER TABLE `zt_object` ADD `end` date NULL AFTER `designEst`;

ALTER TABLE `zt_charter` ADD `closedBy`      char(30) NULL;
ALTER TABLE `zt_charter` ADD `closedDate`    datetime NULL;
ALTER TABLE `zt_charter` ADD `closedReason`  varchar(255) NULL;
ALTER TABLE `zt_charter` ADD `activatedBy`   char(30) NULL;
ALTER TABLE `zt_charter` ADD `activatedDate` datetime NULL;

UPDATE `zt_priv` SET `vision` = ',rnd,or,' WHERE `module` = 'requirement' AND `method` = 'import';
UPDATE `zt_priv` SET `vision` = ',rnd,or,' WHERE `module` = 'requirement' AND `method` = 'exportTemplate';
