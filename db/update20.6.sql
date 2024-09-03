ALTER TABLE `zt_workflow` ADD `belong` varchar(50) NOT NULL DEFAULT '' AFTER `buildin`;

ALTER TABLE `zt_pivot` ADD `mode` enum('text', 'builder') not NULL default 'builder' AFTER `driver`;
ALTER TABLE `zt_pivot` ADD `builder` mediumtext AFTER `sql`;
UPDATE `zt_pivot` SET `mode` = 'text';

ALTER TABLE zt_dimension ADD `acl` enum('open','private') NOT NULL DEFAULT 'open' AFTER `desc`;
ALTER TABLE zt_dimension ADD `whitelist` text NULL AFTER `acl`;

ALTER TABLE zt_chart ADD `acl` enum('open','private') NOT NULL DEFAULT 'open' AFTER `desc`;
ALTER TABLE zt_chart ADD `whitelist` text NULL AFTER `acl`;

ALTER TABLE zt_pivot ADD `acl` enum('open','private') NOT NULL DEFAULT 'open' AFTER `desc`;
ALTER TABLE zt_pivot ADD `whitelist` text NULL AFTER `acl`;

ALTER TABLE zt_screen ADD `acl` enum('open','private') NOT NULL DEFAULT 'open' AFTER `desc`;
ALTER TABLE zt_screen ADD `whitelist` text NULL AFTER `acl`;
