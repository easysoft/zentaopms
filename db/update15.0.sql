ALTER TABLE `zt_project` 
ADD `template` char(30) NOT NULL AFTER `type`,
ADD `category` char(30) NOT NULL AFTER `template`,
ADD `program` mediumint(8) NOT NULL DEFAULT '0' AFTER `category`,
ADD `budget` varchar(30) NOT NULL DEFAULT '0' AFTER `program`,
ADD `budgetUnit` char(30) NOT NULL  DEFAULT 'yuan' AFTER `budget`,
ADD `privway` char(30) NOT NULL AFTER `parent`,
ADD `realStarted` date NOT NULL AFTER `end`,
ADD `realFinished` date NOT NULL AFTER `realStarted`;

ALTER TABLE `zt_group` ADD `program` mediumint(8) NOT NULL AFTER `id`;
INSERT INTO `zt_group` (`name`, `role`, `desc`, `acl`, `developer`) VALUES ('项目管理员', 'prgadmin', '项目管理员可以维护项目的权限', NULL, '1');

ALTER TABLE `zt_usergroup` ADD `program` text NOT NULL;

ALTER TABLE `zt_userview` ADD `programs` mediumtext COLLATE 'utf8_general_ci' NOT NULL AFTER `account`;
