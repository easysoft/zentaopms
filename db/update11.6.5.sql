ALTER TABLE `zt_dept` CHANGE `order` `order` smallint(4) unsigned NOT NULL DEFAULT '0' AFTER `grade`;
CREATE TABLE `zt_dinguserid` (
  `webhook` mediumint(8) unsigned NOT NULL,
  `account` varchar(30) NOT NULL,
  `userid` varchar(255) NOT NULL,
  UNIQUE KEY `webhook_account` (`webhook`,`account`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
ALTER TABLE `zt_product` ADD INDEX acl (`acl`); 
ALTER TABLE `zt_project` ADD INDEX acl (`acl`); 
