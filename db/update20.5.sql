CREATE TABLE IF NOT EXISTS `zt_workflowui` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `module` varchar(30) NOT NULL,
  `action` varchar(50) NOT NULL,
  `name` varchar(30) NOT NULL,
  `conditions` text NOT NULL,
  PRIMARY KEY `id` (`id`),
  KEY `module` (`module`),
  KEY `action` (`action`),
  UNIQUE KEY `unique` (`module`, `action`, `name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `zt_workflowlayout` ADD `ui` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `action`;

ALTER TABLE `zt_approvalflow` CHANGE `type` `workflow` char(30) NOT NULL DEFAULT '';
UPDATE `zt_approvalflow` SET `workflow` = '';
