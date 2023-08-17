ALTER TABLE `zt_doc` ADD `editedList` text NULL AFTER `editingDate`;

ALTER TABLE `zt_case` ADD INDEX `scene` (`scene`);
UPDATE `zt_case` SET `scene` = `scene` - 100000000 WHERE `scene` > 100000000;
UPDATE `zt_scene` SET `sort` = `sort` - 100000000 WHERE `sort` > 100000000;
UPDATE `zt_scene` SET `parent` = `parent` - 100000000 WHERE `parent` > 100000000;
UPDATE `zt_scene` SET `path` = REPLACE(`path`, ',10000000', ','), `path` = REPLACE(`path`, ',1000000', ','), `path` = REPLACE(`path`, ',100000', ','), `path` = REPLACE(`path`, ',10000', ',');

-- DROP TABLE IF EXISTS `zt_actionlatest`;
CREATE TABLE IF NOT EXISTS `zt_actionlatest` (
  `id` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `objectType` varchar(30) NOT NULL DEFAULT '',
  `objectID` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `product` text NULL,
  `project` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `execution` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `actor` varchar(100) NOT NULL DEFAULT '',
  `action` varchar(80) NOT NULL DEFAULT '',
  `date` datetime NULL,
  `comment` text NULL,
  `extra` text NULL,
  `read` enum('0','1') NOT NULL DEFAULT '0',
  `vision` varchar(10) NOT NULL DEFAULT 'rnd',
  `efforted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE INDEX `date`     ON `zt_actionlatest`(`date`);
CREATE INDEX `actor`    ON `zt_actionlatest`(`actor`);
CREATE INDEX `project`  ON `zt_actionlatest`(`project`);
CREATE INDEX `action`   ON `zt_actionlatest`(`action`);
CREATE INDEX `objectID` ON `zt_actionlatest`(`objectID`);

INSERT INTO `zt_actionlatest`(`objectType`,`objectID`,`product`,`project`,`execution`,`actor`,`action`,`date`,`comment`,`extra`,`read`,`vision`,`efforted`)
SELECT `objectType`,`objectID`,`product`,`project`,`execution`,`actor`,`action`,`date`,`comment`,`extra`,`read`,`vision`,`efforted` FROM `zt_action`
WHERE `date` >= DATE(DATE_SUB(NOW(), INTERVAL 1 MONTH));

INSERT INTO `zt_cron` (`m`, `h`, `dom`, `mon`, `dow`, `command`, `remark`, `type`, `buildin`, `status`) VALUES ('15', '0', '*', '*', '*', 'moduleName=action&methodName=cleanActions', '清除超过一个月的动态',    'zentao', 1, 'normal');


ALTER TABLE `zt_notify`
MODIFY COLUMN `toList` text NOT NULL AFTER `action`,
MODIFY COLUMN `subject` text NOT NULL AFTER `ccList`,
DROP INDEX `objectType_toList_status`,
ADD INDEX `objectType`(`objectType` ASC),
ADD INDEX `status`(`status` ASC);

CREATE INDEX deleted ON zt_bug (deleted);
CREATE INDEX project ON zt_bug (project);
CREATE INDEX product_status_deleted ON zt_bug (product,status,deleted);

UPDATE `zt_cron` SET `m` = '*/1' WHERE `command` in ('moduleName=mail&methodName=asyncSend', 'moduleName=webhook&methodName=asyncSend') and `type` = 'zentao';

ALTER TABLE `zt_project` ADD INDEX `type_order` (`type`, `order`);

ALTER TABLE `zt_case`
ADD `bugs` MEDIUMINT NOT NULL DEFAULT '0' AFTER `sort`,
ADD `steps` MEDIUMINT NOT NULL DEFAULT '0' AFTER `bugs`,
ADD `executions` MEDIUMINT NOT NULL DEFAULT '0' AFTER `steps`,
ADD `fails` MEDIUMINT NOT NULL DEFAULT '0' AFTER `executions`;

ALTER TABLE `zt_testrun`
ADD `taskBugs` MEDIUMINT NOT NULL DEFAULT '0' AFTER `status`,
ADD `taskSteps` MEDIUMINT NOT NULL DEFAULT '0' AFTER `taskBugs`,
ADD `taskExecutions` MEDIUMINT NOT NULL DEFAULT '0' AFTER `taskSteps`,
ADD `taskFails` MEDIUMINT NOT NULL DEFAULT '0' AFTER `taskExecutions`;

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
  `zt_case`.`bugs` AS `bugs`,
  `zt_case`.`steps` AS `steps`,
  `zt_case`.`executions` AS `executions`,
  `zt_case`.`fails` AS `fails`,
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
    0 AS `bugs`,
    0 AS `steps`,
    0 AS `executions`,
    0 AS `fails`,
    `zt_scene`.`parent` AS `parent`,
    `zt_scene`.`parent` AS `scene`,
    `zt_scene`.`grade` AS `grade`,
    `zt_scene`.`path` AS `path`,
    2 AS `isCase`
  FROM `zt_scene`;
