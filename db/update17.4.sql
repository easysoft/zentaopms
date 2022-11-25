ALTER TABLE `zt_task` ADD `order` mediumint(8) NOT NULL DEFAULT 0 AFTER `activatedDate`;
ALTER TABLE `zt_task` ADD INDEX `order` (`order`);
ALTER TABLE `zt_story` ADD COLUMN `linkRequirements` varchar(255) NOT NULL AFTER `linkStories`;

ALTER TABLE `zt_productplan` ADD `createdBy` varchar(30) NOT NULL AFTER `closedReason`;
ALTER TABLE `zt_productplan` ADD `createdDate` datetime NOT NULL AFTER `createdBy`;

ALTER TABLE `zt_release` ADD `createdBy` varchar(30) NOT NULL AFTER `subStatus`;
ALTER TABLE `zt_release` ADD `createdDate` datetime NOT NULL AFTER `createdBy`;

ALTER TABLE `zt_testtask` ADD `createdBy` varchar(30) NOT NULL AFTER `subStatus`;
ALTER TABLE `zt_testtask` ADD `createdDate` datetime NOT NULL AFTER `createdBy`;

ALTER TABLE `zt_build` ADD `createdBy` varchar(30) NOT NULL AFTER `desc`;
ALTER TABLE `zt_build` ADD `createdDate` datetime NOT NULL AFTER `createdBy`;

UPDATE `zt_workflowfield` SET `options`=(SELECT id FROM `zt_workflowdatasource` WHERE `code`='feedbackType' ORDER BY `id` DESC LIMIT 1) WHERE `module`='feedback' AND `field`='type';
UPDATE `zt_workflowfield` SET `options`=(SELECT id FROM `zt_workflowdatasource` WHERE `code`='feedbackSolution' ORDER BY `id` DESC LIMIT 1) WHERE `module`='feedback' AND `field`='solution';
UPDATE `zt_workflowfield` SET `options`=(SELECT id FROM `zt_workflowdatasource` WHERE `code`='feedbackclosedReason' ORDER BY `id` DESC LIMIT 1), `control`='select' WHERE `module`='feedback' AND `field`='closedReason';

UPDATE `zt_project` SET `closedDate`='' AND `closedBy`='' WHERE `status` != 'closed';
UPDATE `zt_grouppriv` SET `method`='exportTemplate' WHERE `method` = 'exportTemplet';

REPLACE INTO `zt_grouppriv` (SELECT `group`,`module`,'reply' FROM `zt_grouppriv` WHERE `module` = 'feedback' AND `method` = 'comment');
REPLACE INTO `zt_grouppriv` (SELECT `group`,`module`,'ask' FROM `zt_grouppriv` WHERE `module` = 'feedback' AND `method` = 'comment');

CREATE TABLE `zt_chart` (
  `id` mediumint NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type` varchar(30) NOT NULL,
  `dataset` varchar(30) NOT NULL,
  `desc` mediumtext NOT NULL,
  `settings` mediumtext NOT NULL,
  `filters` mediumtext NOT NULL,
  `createdBy` char(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `zt_dashboard` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `module` mediumint NOT NULL,
  `desc` mediumtext NOT NULL,
  `layout` mediumtext NOT NULL,
  `filters` mediumtext NOT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `zt_dataset` (
  `id` mediumint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(155) NOT NULL,
  `sql` text NOT NULL,
  `fields` mediumtext NOT NULL,
  `objects` mediumtext NOT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
