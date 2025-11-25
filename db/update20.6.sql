ALTER TABLE `zt_workflow` ADD `belong` varchar(50) NOT NULL DEFAULT '' AFTER `buildin`;

ALTER TABLE `zt_workflow` ADD `icon` varchar(30) DEFAULT 'flow' NOT NULL AFTER `name`;
UPDATE `zt_workflow` SET `icon` = 'flow' WHERE `icon` = '';

ALTER TABLE `zt_pivot` ADD `mode` enum('text', 'builder') not NULL default 'builder' AFTER `driver`;
UPDATE `zt_pivot` SET `mode` = 'text';

ALTER TABLE zt_dimension ADD `acl` enum('open','private') NOT NULL DEFAULT 'open' AFTER `desc`;
ALTER TABLE zt_dimension ADD `whitelist` text NULL AFTER `acl`;

ALTER TABLE zt_chart ADD `acl` enum('open','private') NOT NULL DEFAULT 'open' AFTER `desc`;
ALTER TABLE zt_chart ADD `whitelist` text NULL AFTER `acl`;

ALTER TABLE zt_pivot ADD `acl` enum('open','private') NOT NULL DEFAULT 'open' AFTER `desc`;
ALTER TABLE zt_pivot ADD `whitelist` text NULL AFTER `acl`;

ALTER TABLE zt_screen ADD `acl` enum('open','private') NOT NULL DEFAULT 'open' AFTER `desc`;
ALTER TABLE zt_screen ADD `whitelist` text NULL AFTER `acl`;

CREATE TABLE IF NOT EXISTS `zt_sqlbuilder` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `objectID`   mediumint(8)  NOT NULL,
  `objectType` varchar(50)   NOT NULL,
  `sql`        text          NULL,
  `setting`    text          NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

DELETE t1
FROM `zt_workflowlayout` t1
LEFT JOIN `zt_workflowfield` t2 ON t1.field = t2.field AND t1.module = t2.module
LEFT JOIN `zt_workflowaction` t3 ON t1.module = t3.module AND t1.action = t3.action
WHERE t2.buildin = '1' AND t2.role = 'buildin' AND t3.buildin = '1';

ALTER TABLE `zt_dataview` ADD `mode` enum('text', 'builder') not NULL default 'builder' AFTER `code`;
UPDATE `zt_dataview` SET `mode` = 'text';
ALTER TABLE `zt_chart` ADD `mode` enum('text', 'builder') not NULL default 'builder' AFTER `driver`;
UPDATE `zt_chart` SET `mode` = 'text';
