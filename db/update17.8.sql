ALTER TABLE `zt_chart` ADD `group` mediumint(8) unsigned NOT NULL default '0' AFTER `type`;
ALTER TABLE `zt_chart` MODIFY COLUMN `desc` text NOT NULL;
ALTER TABLE `zt_chart` ADD `editedBy` varchar(30) NOT NULL AFTER `createdDate`;
ALTER TABLE `zt_chart` ADD `editedDate` datetime NOT NULL AFTER `editedBy`;

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
INSERT INTO `zt_dimension` (`name`, `code`, `desc`, `createdBy`, `createdDate`, `editedBy`, `editedDate`) VALUES('default', 'default', '', 'system', NOW(), 'system', NOW());

ALTER TABLE `zt_report`    ADD `dimension` int(8) NOT NULL default 0 AFTER `name`;
ALTER TABLE `zt_chart`     ADD `dimension` int(8) NOT NULL default 0 AFTER `name`;
ALTER TABLE `zt_dashboard` ADD `dimension` int(8) NOT NULL default 0 AFTER `name`;

UPDATE `zt_chart`     SET `dimension` = 1 WHERE `dimension` = 0;
UPDATE `zt_report`    SET `dimension` = 1 WHERE `dimension` = 0;
UPDATE `zt_dashboard` SET `dimension` = 1 WHERE `dimension` = 0;
UPDATE `zt_module`    SET `root` = 1      WHERE `root` = 0 AND `type` in ('chart', 'dashboard');
