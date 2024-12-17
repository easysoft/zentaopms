CREATE INDEX `objectType` ON `zt_stakeholder` (`objectType`);

CREATE TABLE IF NOT EXISTS `zt_autocache` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(30) NOT NULL DEFAULT '',
  `fields` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE UNIQUE INDEX `cache` ON `zt_autocache` (`code`, `fields`);
INSERT INTO `zt_workflowaction` (`module`, `action`, `method`, `name`, `type`, `batchMode`, `extensionType`, `open`, `position`, `layout`, `show`, `order`, `buildin`, `role`, `virtual`, `conditions`, `verifications`, `hooks`, `linkages`, `js`, `css`, `toList`, `blocks`, `desc`, `status`, `vision`, `createdBy`, `createdDate`, `editedBy`, `editedDate`) VALUES
('product',	'requirement',	'requirement',	'用户需求列表',	'single',	'different',	'none',	'normal',	'browse',	'normal',	'direct',	0,	1,	'buildin',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	'',	NULL,	NULL,	'enable',	'rnd',	'admin',	'2024-12-16 11:22:30',	'',	NULL),
('product',	'epic',	'epic',	'业务需求列表',	'single',	'different',	'none',	'normal',	'browse',	'normal',	'direct',	0,	1,	'buildin',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	'',	NULL,	NULL,	'enable',	'rnd',	'admin',	'2024-12-16 11:22:30',	'',	NULL);

-- DROP TABLE IF EXISTS `zt_casespec`;
CREATE TABLE IF NOT EXISTS `zt_casespec` (
  `case` mediumint(9) NOT NULL DEFAULT '0',
  `version` smallint(6) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `precondition` text NULL,
  `files` text NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE UNIQUE INDEX `case` ON `zt_casespec`(`case`,`version`);
