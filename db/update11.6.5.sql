ALTER TABLE `zt_dept` CHANGE `order` `order` smallint(4) unsigned NOT NULL DEFAULT '0' AFTER `grade`;
CREATE TABLE IF NOT EXISTS `zt_oauth` (
  `account` varchar(30) NOT NULL,
  `openID` varchar(255) NOT NULL,
  `providerType` varchar(30) NOT NULL,
  `providerID` mediumint(8) unsigned NOT NULL,
  KEY `account` (`account`),
  KEY `providerType` (`providerType`),
  KEY `providerID` (`providerID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
ALTER TABLE `zt_product` ADD INDEX acl (`acl`); 
ALTER TABLE `zt_project` ADD INDEX acl (`acl`); 
