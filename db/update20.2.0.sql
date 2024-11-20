UPDATE zt_demand SET status = 'active' WHERE status in ('launched','distributed','pass');
UPDATE zt_story  SET status = 'active' WHERE status in ('launched','developing');

ALTER TABLE `zt_demand` ADD `parentVersion` smallint NOT NULL DEFAULT 0 AFTER `version`;
ALTER TABLE `zt_story`  ADD `demandVersion` smallint NOT NULL DEFAULT 0 AFTER `parentVersion`;
UPDATE `zt_demand` AS t1 JOIN `zt_demand` AS t2 ON t1.parent = t2.id SET t1.parentVersion = t2.version WHERE t1.parent > 0;
UPDATE `zt_story`  AS t1 JOIN `zt_demand` AS t2 ON t1.demand = t2.id SET t1.demandVersion = t2.version WHERE t1.demand > 0;

ALTER TABLE `zt_approvalnode` ADD `forwardBy` char(30) NOT NULL DEFAULT '' AFTER `extra`;
ALTER TABLE `zt_approvalnode` ADD `revertTo`  char(30) NOT NULL DEFAULT '' AFTER `extra`;

UPDATE `zt_workflowaction` SET conditions = '' WHERE `action` = 'approvalreview' AND `conditions` = '[{"conditionType":"data","fields":[{"field":"deleted","operator":"equal","param":"0","logicalOperator":"and"},{"field":"reviewStatus","operator":"equal","param":"doing","logicalOperator":"and"},{"field":"reviewers","operator":"include","param":"currentUser"}]}]';
UPDATE `zt_workflowaction` SET conditions = '' WHERE `action` = 'approvalcancel' AND `conditions` = '[{"conditionType":"data","fields":[{"field":"deleted","operator":"equal","param":"0","logicalOperator":"and"},{"field":"reviewStatus","operator":"equal","param":"doing","logicalOperator":"and"},{"field":"openedBy","operator":"equal","param":"currentUser"}]}]';
UPDATE `zt_workflowaction` SET conditions = '' WHERE `action` = 'approvalsubmit' AND `conditions` = '[{"conditionType":"data","fields":[{"field":"deleted","operator":"equal","param":"0","logicalOperator":"and"},{"field":"reviewStatus","operator":"equal","param":"wait","logicalOperator":"and"},{"field":"openedBy","operator":"equal","param":"currentUser"}]},{"conditionType":"data","fields":[{"field":"deleted","operator":"equal","param":"0","logicalOperator":"and"},{"field":"reviewStatus","operator":"equal","param":"reject","logicalOperator":"and"},{"field":"openedBy","operator":"equal","param":"currentUser"}]}]';

CREATE INDEX `idx_repo_deleted` ON `zt_job` (`repo`,`deleted`);

UPDATE `zt_workflowfield` SET options = '{"wait":"\\u5f85\\u5ba1\\u6279","doing":"\\u5ba1\\u6279\\u4e2d","pass":"\\u901a\\u8fc7","reject":"\\u4e0d\\u901a\\u8fc7","reverting":"\\u56de\\u9000\\u4e2d"}' WHERE field = 'reviewStatus';

UPDATE `zt_workflowaction` SET `role` = 'buildin' WHERE `buildin` = '1' AND `module` IN ('requirement', 'epic');

CREATE TABLE `zt_pivotspec` (
  `pivot` mediumint(8) NOT NULL,
  `version` varchar(10) NOT NULL,
  `driver` enum('mysql', 'duckdb') NOT NULL default 'mysql',
  `mode` varchar(10) NOT NULL default 'builder',
  `name` text NULL,
  `desc` text NULL,
  `sql` text NULL,
  `fields` text NULL,
  `langs` text NULL,
  `vars` text NULL,
  `objects` text NULL,
  `settings` text NULL,
  `filters` text NULL,
  `createdDate` datetime NULL,
  UNIQUE KEY `pivot` (`pivot`, `version`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `zt_pivot` ADD `version` varchar(10) NOT NULL AFTER `builtin`;
ALTER TABLE `zt_pivot` CHANGE `mode` `mode` varch(10) NOT NULL DEFAULT 'builder';
ALTER TABLE `zt_pivot` CHANGE `sql` `sql` text NULL;
ALTER TABLE `zt_pivot` CHANGE `fields` `fields` text NULL;
ALTER TABLE `zt_pivot` CHANGE `langs` `langs` text NULL;
ALTER TABLE `zt_pivot` CHANGE `vars` `vars` text NULL;
ALTER TABLE `zt_pivot` CHANGE `objects` `objects` text NULL;
ALTER TABLE `zt_pivot` CHANGE `settings` `settings` text NULL;
ALTER TABLE `zt_pivot` CHANGE `filters` `filters` text NULL;
