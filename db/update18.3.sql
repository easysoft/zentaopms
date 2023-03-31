/* Upgrade 18.0.beta2 will execute sql, but the installation does not. */
CREATE TABLE IF NOT EXISTS `zt_dimension` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(90) NOT NULL,
  `code` varchar(45) NOT NULL,
  `desc` text NOT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `zt_chart_back` SELECT * FROM `zt_chart`;

ALTER TABLE `zt_chart` MODIFY `fields` mediumtext NOT NULL;
ALTER TABLE `zt_chart` MODIFY `group` varchar(255) NOT NULL;
ALTER TABLE `zt_chart` ADD `stage` enum('draft','published') NOT NULL DEFAULT 'draft' AFTER `sql`;
ALTER TABLE `zt_chart` ADD `langs` text NOT NULL AFTER `fields`;
ALTER TABLE `zt_chart` ADD `step` tinyint(1) unsigned NOT NULL AFTER `filters`;

ALTER TABLE `zt_dataview` ADD `langs` text NOT NULL AFTER `fields`;

ALTER TABLE `zt_screen` ADD `status` enum('draft','published') NOT NULL DEFAULT 'draft' AFTER `scheme`;
ALTER TABLE `zt_screen` ADD `builtin` enum('0', '1') NOT NULL DEFAULT '0' AFTER `status`;

UPDATE `zt_screen` SET `builtin` = '1', `status` = 'published';

UPDATE `zt_grouppriv` SET `module` = 'dataview' WHERE `module` = 'dataset' AND `method` IN ('create', 'browse', 'edit', 'delete');
UPDATE `zt_grouppriv` SET `module` = 'screen' WHERE `module` = 'dashboard';
UPDATE `zt_grouppriv` SET `module` = 'screen' WHERE `module` = 'report' AND `method` IN ('annualData','allAnnualData');
UPDATE `zt_grouppriv` SET `module` = 'pivot' WHERE `module` = 'report' AND `method` IN ('projectDeviation','productSummary', 'bugCreate', 'bugAssign', 'workload', 'showProduct', 'showProject');

REPLACE INTO `zt_grouppriv` (`group`, `module`, `method`) SELECT `group`, 'pivot', 'create' FROM `zt_grouppriv` WHERE `module` = 'report' AND `method` IN ('custom', 'saveReport');
REPLACE INTO `zt_grouppriv` (`group`, `module`, `method`) SELECT `group`, 'pivot', 'edit' FROM `zt_grouppriv` WHERE `module` = 'report' AND `method` = 'editReport';
REPLACE INTO `zt_grouppriv` (`group`, `module`, `method`) SELECT `group`, 'pivot', 'delete' FROM `zt_grouppriv` WHERE `module` = 'report' AND `method` = 'deleteReport';
REPLACE INTO `zt_grouppriv` (`group`, `module`, `method`) SELECT `group`, 'pivot', 'preview' FROM `zt_grouppriv` WHERE `module` = 'report' AND `method` = 'show';
REPLACE INTO `zt_grouppriv` (`group`, `module`, `method`) SELECT `group`, 'pivot', 'design' FROM `zt_grouppriv` WHERE `module` = 'report' AND `method` = 'useReport';
REPLACE INTO `zt_grouppriv` (`group`, `module`, `method`) SELECT `group`, 'pivot', 'export' FROM `zt_grouppriv` WHERE `module` = 'report' AND `method` = 'crystalExport';

DELETE FROM `zt_grouppriv` WHERE `module` = 'dataset' AND `method` = 'view';

CREATE TABLE `zt_pivot`  (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `dimension` mediumint(8) unsigned NOT NULL DEFAULT 0,
  `group` varchar(255) NOT NULL,
  `name` text NOT NULL,
  `desc` text NOT NULL,
  `sql` mediumtext NOT NULL,
  `fields` mediumtext NOT NULL,
  `langs` mediumtext NOT NULL,
  `vars` mediumtext NOT NULL,
  `objects` mediumtext NULL,
  `settings` mediumtext NOT NULL,
  `filters` mediumtext NOT NULL,
  `step` tinyint(1) unsigned NOT NULL,
  `stage` enum('draft','published') NOT NULL DEFAULT 'draft',
  `builtin` enum('0', '1') NOT NULL DEFAULT '0',
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `deleted` enum('0', '1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY(`dimension`),
  KEY(`group`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE zt_case ADD scene int(11) DEFAULT 0;
ALTER TABLE zt_case ADD sort int(11) DEFAULT 0;

DROP TABLE IF EXISTS `zt_scene`;
CREATE TABLE `zt_scene` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `product` mediumint(8) unsigned NOT NULL DEFAULT 0,
  `branch` mediumint(8) unsigned NOT NULL DEFAULT 0,
  `module` mediumint(8) unsigned NOT NULL DEFAULT 0,
  `title` varchar(255) NOT NULL,
  `sort` int(11) unsigned NOT NULL DEFAULT 0,
  `openedBy` char(30) NOT NULL DEFAULT '''''',
  `openedDate` datetime DEFAULT NULL,
  `lastEditedBy` char(30) NOT NULL DEFAULT '',
  `lastEditedDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `parent` int(11) DEFAULT NULL,
  `grade` tinyint(3) DEFAULT NULL,
  `path` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP VIEW IF EXISTS `ztv_scenecase`;
CREATE OR REPLACE VIEW `ztv_scenecase` AS SELECT
  `zt_case`.`id` AS `id`,
  `zt_case`.`title` AS `title`,
  `zt_case`.`project` AS `project`,
  `zt_case`.`product` AS `product`,
  `zt_case`.`execution` AS `execution`,
  `zt_case`.`branch` AS `branch`,
  `zt_case`.`lib` AS `lib`,
  `zt_case`.`module` AS `module`,
  `zt_case`.`story` AS `story`,
  `zt_case`.`storyVersion` AS `storyVersion`,
  `zt_case`.`precondition` AS `precondition`,
  `zt_case`.`keywords` AS `keywords`,
  `zt_case`.`pri` AS `pri`,
  `zt_case`.`type` AS `type`,
  `zt_case`.`auto` AS `auto`,
  `zt_case`.`frame` AS `frame`,
  `zt_case`.`stage` AS `stage`,
  `zt_case`.`howRun` AS `howRun`,
  `zt_case`.`scriptedBy` AS `scriptedBy`,
  `zt_case`.`scriptedDate` AS `scriptedDate`,
  `zt_case`.`scriptStatus` AS `scriptStatus`,
  `zt_case`.`scriptLocation` AS `scriptLocation`,
  `zt_case`.`status` AS `status`,
  `zt_case`.`subStatus` AS `subStatus`,
  `zt_case`.`color` AS `color`,
  `zt_case`.`frequency` AS `frequency`,
  IF(`zt_case`.`sort` = 0, `zt_case`.`id`, `zt_case`.`sort`) AS `sort`,
  `zt_case`.`openedBy` AS `openedBy`,
  `zt_case`.`openedDate` AS `openedDate`,
  `zt_case`.`reviewedBy` AS `reviewedBy`,
  `zt_case`.`reviewedDate` AS `reviewedDate`,
  `zt_case`.`lastEditedBy` AS `lastEditedBy`,
  `zt_case`.`lastEditedDate` AS `lastEditedDate`,
  `zt_case`.`version` AS `version`,
  `zt_case`.`linkCase` AS `linkCase`,
  `zt_case`.`fromBug` AS `fromBug`,
  `zt_case`.`fromCaseID` AS `fromCaseID`,
  `zt_case`.`fromCaseVersion` AS `fromCaseVersion`,
  `zt_case`.`deleted` AS `deleted`,
  `zt_case`.`lastRunner` AS `lastRunner`,
  `zt_case`.`lastRunDate` AS `lastRunDate`,
  `zt_case`.`lastRunResult` AS `lastRunResult`,
  `zt_case`.`scene` AS `parent`,
  `zt_case`.`scene` AS `scene`,
  ifnull(`zt_scene`.`grade` + 1 , 1) AS `grade`,
  ifnull(
    concat(
      `zt_scene`.`path`,
      `zt_case`.`id`,
      ','
    ),
    CONVERT(
      concat(',' , `zt_case`.`id` , ',') USING utf8
    )
  ) AS `path`,
  1 AS `isCase`
FROM (`zt_case` LEFT JOIN `zt_scene` ON( `zt_case`.`scene` = `zt_scene`.`id`+100000000))
UNION
  SELECT
    `zt_scene`.`id`+ 100000000 AS `id`,
    `zt_scene`.`title` AS `title`,
    0 AS `project`,
    `zt_scene`.`product` AS `product`,
    0 AS `execution`,
    `zt_scene`.`branch` AS `branch`,
    0 AS `lib`,
    `zt_scene`.`module` AS `module`,
    0 AS `story`,
    0 AS `storyVersion`,
    '' AS `precondition`,
    '' AS `keywords`,
    0 AS `pri`,
    '' AS `type`,
    '' AS `auto`,
    '' AS `frame`,
    '' AS `stage`,
    '' AS `howRun`,
    '' AS `scriptedBy`,
    '' AS `scriptedDate`,
    '' AS `scriptStatus`,
    '' AS `scriptLocation`,
    '' AS `status`,
    '' AS `subStatus`,
    '' AS `color`,
    '' AS `frequency`,
    `zt_scene`.`sort` AS `sort`,
    `zt_scene`.`openedBy` AS `openedBy`,
    `zt_scene`.`openedDate` AS `openedDate`,
    '' AS `reviewedBy`,
    '' AS `reviewedDate`,
    `zt_scene`.`lastEditedBy` AS `lastEditedBy`,
    `zt_scene`.`lastEditedDate` AS `lastEditedDate`,
    0 AS `version`,
    '' AS `linkCase`,
    0 AS `fromBug`,
    0 AS `fromCaseID`,
    '' AS `fromCaseVersion`,
    `zt_scene`.`deleted` AS `deleted`,
    '' AS `lastRunner`,
    '' AS `lastRunDate`,
    '' AS `lastRunResult`,
    `zt_scene`.`parent` AS `parent`,
    `zt_scene`.`parent` AS `scene`,
    `zt_scene`.`grade` AS `grade`,
    `zt_scene`.`path` AS `path`,
    2 AS `isCase`
  FROM `zt_scene`;