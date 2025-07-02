INSERT INTO `zt_workflowdatasource` (`type`, `name`, `code`, `buildin`, `vision`, `createdBy`, `createdDate`, `datasource`, `view`, `keyField`, `valueField`)
SELECT 'lang', '立项级别',     'charterLevel',        '1', 'rnd',  'admin', '1970-01-01 00:00:01', 'charterLevel', '', '', '' FROM DUAL WHERE NOT EXISTS ( SELECT 1 FROM `zt_workflowdatasource` WHERE `type` = 'lang' AND `code` = 'charterLevel');
INSERT INTO `zt_workflowdatasource` (`type`, `name`, `code`, `buildin`, `vision`, `createdBy`, `createdDate`, `datasource`, `view`, `keyField`, `valueField`)
SELECT 'lang', '立项类型',     'charterCategory',     '1', 'rnd',  'admin', '1970-01-01 00:00:01', 'charterCategory', '', '', '' FROM DUAL WHERE NOT EXISTS ( SELECT 1 FROM `zt_workflowdatasource` WHERE `type` = 'lang' AND `code` = 'charterCategory');
INSERT INTO `zt_workflowdatasource` (`type`, `name`, `code`, `buildin`, `vision`, `createdBy`, `createdDate`, `datasource`, `view`, `keyField`, `valueField`)
SELECT 'lang', '立项适用市场', 'charterMarket',       '1', 'rnd',  'admin', '1970-01-01 00:00:01', 'charterMarket', '', '', '' FROM DUAL WHERE NOT EXISTS ( SELECT 1 FROM `zt_workflowdatasource` WHERE `type` = 'lang' AND `code` = 'charterMarket');
INSERT INTO `zt_workflowdatasource` (`type`, `name`, `code`, `buildin`, `vision`, `createdBy`, `createdDate`, `datasource`, `view`, `keyField`, `valueField`)
SELECT 'lang', '立项状态',     'charterStatus',       '1', 'rnd',  'admin', '1970-01-01 00:00:01', 'charterStatus', '', '', '' FROM DUAL WHERE NOT EXISTS ( SELECT 1 FROM `zt_workflowdatasource` WHERE `type` = 'lang' AND `code` = 'charterStatus');
INSERT INTO `zt_workflowdatasource` (`type`, `name`, `code`, `buildin`, `vision`, `createdBy`, `createdDate`, `datasource`, `view`, `keyField`, `valueField`)
SELECT 'lang', '立项关闭原因', 'charterCloseReason',  '1', 'rnd',  'admin', '1970-01-01 00:00:01', 'charterCloseReason', '', '', '' FROM DUAL WHERE NOT EXISTS ( SELECT 1 FROM `zt_workflowdatasource` WHERE `type` = 'lang' AND `code` = 'charterCloseReason');
INSERT INTO `zt_workflowdatasource` (`type`, `name`, `code`, `buildin`, `vision`, `createdBy`, `createdDate`, `datasource`, `view`, `keyField`, `valueField`)
SELECT 'lang', '立项审批结果', 'charterReviewResult', '1', 'rnd',  'admin', '1970-01-01 00:00:01', 'charterReviewResult', '', '', '' FROM DUAL WHERE NOT EXISTS ( SELECT 1 FROM `zt_workflowdatasource` WHERE `type` = 'lang' AND `code` = 'charterReviewResult');
INSERT INTO `zt_workflowdatasource` (`type`, `name`, `code`, `buildin`, `vision`, `createdBy`, `createdDate`, `datasource`, `view`, `keyField`, `valueField`)
SELECT 'lang', '立项审批状态', 'charterReviewStatus', '1', 'rnd',  'admin', '1970-01-01 00:00:01', 'charterReviewStatus', '', '', '' FROM DUAL WHERE NOT EXISTS ( SELECT 1 FROM `zt_workflowdatasource` WHERE `type` = 'lang' AND `code` = 'charterReviewStatus');

UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'charterLevel' LIMIT 1) WHERE `module` = 'charter' AND `field` = 'level';
UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'charterCategory' LIMIT 1) WHERE `module` = 'charter' AND `field` = 'category';
UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'charterMarket' LIMIT 1) WHERE `module` = 'charter' AND `field` = 'market';
UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'charterStatus' LIMIT 1) WHERE `module` = 'charter' AND `field` = 'status';
UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'charterCloseReason' LIMIT 1) WHERE `module` = 'charter' AND `field` = 'closedReason';
UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'charterReviewResult' LIMIT 1) WHERE `module` = 'charter' AND `field` = 'reviewedResult';
UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'charterReviewStatus' LIMIT 1) WHERE `module` = 'charter' AND `field` = 'reviewStatus';
