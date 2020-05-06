ALTER TABLE `zt_project` 
CHANGE `type` `type` varchar(20) COLLATE 'utf8_general_ci' NOT NULL DEFAULT 'waterfall' AFTER `catID`,
ADD `program` mediumint(8) NOT NULL DEFAULT '0' AFTER `type`,
ADD `budget` varchar(30) NOT NULL DEFAULT '0' AFTER `program`,
ADD `budgetUnit` char(30) NOT NULL  DEFAULT 'yuan' AFTER `budget`;
