UPDATE `zt_workflowfield` SET `control` = 'multi-select' WHERE `control` = 'multi-selec';

DELETE FROM `zt_workflowaction` WHERE `action` IN ('link', 'unlink');
DELETE FROM `zt_workflowlayout` WHERE `module` = 'caselib' AND `action` = 'browse';

ALTER TABLE `zt_workflowlabel`
ADD `type` enum('data', 'sql') NOT NULL DEFAULT 'data' AFTER `label`,
ADD `sql` text NULL AFTER `params`;
ALTER TABLE `zt_workflowlayout` ADD `ditto` tinyint unsigned NOT NULL DEFAULT 0 AFTER `position`;

ALTER TABLE `zt_project` MODIFY COLUMN `budget` decimal(14,2) unsigned NOT NULL DEFAULT 0.00 COMMENT '预算';
DROP VIEW IF EXISTS `ztv_projectnotpl`;
CREATE OR REPLACE VIEW `ztv_projectnotpl` AS SELECT * FROM `zt_project` WHERE `deleted` = '0' AND `isTpl` = 0;

ALTER TABLE `zt_workflowdatasource` MODIFY COLUMN `view` varchar(50) NOT NULL DEFAULT '' COMMENT '视图';

INSERT INTO `zt_workflowdatasource` (`type`, `name`, `code`, `buildin`, `vision`, `createdBy`, `createdDate`, `datasource`, `view`, `keyField`, `valueField`) VALUES
('sql', '需求池',     'demandPool',  '1', 'or',  'admin', '1970-01-01 00:00:01', 'SELECT `id`,`name` FROM zt_demandpool WHERE `deleted`=\'0\'', 'view_datasource_demandpool',  'id', 'name'),
('sql', '需求池需求', 'demand',      '1', 'or',  'admin', '1970-01-01 00:00:01', 'SELECT `id`,`title` FROM zt_demand WHERE `deleted`=\'0\'',    'view_datasource_demand',      'id', 'title'),
('sql', '路标',       'roadmap',     '1', 'or',  'admin', '1970-01-01 00:00:01', 'SELECT `id`,`name` FROM zt_roadmap WHERE `deleted`=\'0\'',    'view_datasource_roadmap',     'id', 'name'),
('sql', '分发需求',   'demandStory', '1', 'or',  'admin', '1970-01-01 00:00:01', 'SELECT `id`,`name` FROM zt_story WHERE `deleted`=\'0\'',      'view_datasource_demandstory', 'id', 'title');

DROP VIEW IF EXISTS `view_datasource_demandpool`;
DROP VIEW IF EXISTS `view_datasource_demand`;
DROP VIEW IF EXISTS `view_datasource_roadmap`;
DROP VIEW IF EXISTS `view_datasource_demandstory`;

CREATE VIEW `view_datasource_demandpool`  AS SELECT `id`,`name`  FROM `zt_demandpool`  WHERE `deleted` = '0';
CREATE VIEW `view_datasource_demand`      AS SELECT `id`,`title` FROM `zt_demand`      WHERE `deleted` = '0';
CREATE VIEW `view_datasource_roadmap`     AS SELECT `id`,`name`  FROM `zt_roadmap`     WHERE `deleted` = '0';
CREATE VIEW `view_datasource_demandstory` AS SELECT `id`,`title` FROM `zt_story`       WHERE `deleted` = '0';

UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'demandPool')     WHERE `module` = 'demand' AND `field` = 'pool';
UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'products')       WHERE `module` = 'demand' AND `field` = 'product';
UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'demand')         WHERE `module` = 'demand' AND `field` = 'parent';
UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'demandStory')    WHERE `module` = 'demand' AND `field` = 'story';
UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'roadmap')        WHERE `module` = 'demand' AND `field` = 'roadmap';
UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'demand')         WHERE `module` = 'demand' AND `field` = 'duplicateDemand';
UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'demand')         WHERE `module` = 'demand' AND `field` = 'childDemands';
UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'demand')         WHERE `module` = 'demand' AND `field` = 'duplicateDemand';
UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'demand')         WHERE `module` = 'demand' AND `field` = 'childDemands';
UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'demandSource')   WHERE `module` = 'demand' AND `field` = 'source';
UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'demandCategory') WHERE `module` = 'demand' AND `field` = 'category';

UPDATE `zt_pivotspec` SET `sql` = REPLACE(`sql`, "product=''", "product = '0'") WHERE `sql` LIKE "%product=''%";
UPDATE `zt_pivotspec` SET `sql` = REPLACE(`sql`, "project=''", "project = '0'") WHERE `sql` LIKE "%project=''%";
UPDATE `zt_pivotspec` SET `sql` = REPLACE(`sql`, "execution=''", "execution = '0'") WHERE `sql` LIKE "%execution=''%";

UPDATE `zt_workflowaction` SET `type` = 'batch', `batchMode` = 'different' WHERE `action` = 'showimport';

ALTER TABLE `zt_review` CHANGE `lastReviewedDate` `lastReviewedDate` datetime NULL AFTER `lastReviewedBy`;
ALTER TABLE `zt_review` CHANGE `lastEditedDate` `lastEditedDate` datetime NULL AFTER `lastEditedBy`;
