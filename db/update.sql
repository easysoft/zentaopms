-- story优先级的默认值。
ALTER TABLE `zt_story` CHANGE `pri` `pri` TINYINT( 3 ) UNSIGNED NOT NULL DEFAULT '3'

-- 修改project code字段的长度。
ALTER TABLE `zt_project` CHANGE `code` `code` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL 

-- task 暂时增加left字段：
 ALTER TABLE `zt_task` ADD `left` tinyINT(3) NOT NULL AFTER `consumed`

-- 修改日期字段
ALTER TABLE `zt_bug` CHANGE `openedDate` `openedDate` DATETIME NOT NULL ,
CHANGE `assignedDate` `assignedDate` DATETIME NOT NULL ,
CHANGE `resolvedDate` `resolvedDate` DATETIME NOT NULL ,
CHANGE `closedDate` `closedDate` DATETIME NOT NULL ,
CHANGE `lastEditedDate` `lastEditedDate` DATETIME NOT NULL 

RENAME TABLE `zentao`.`zt_division` TO `zentao`.`zt_dept` ;
ALTER TABLE `zt_user` CHANGE `division` `dept` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT '0'


-- 0.2版本：
--
-- 修改task表name字段的长度。
-- 修改task表的时间的类型，可以是浮点数。
ALTER TABLE `zt_task` CHANGE `estimate` `estimate` FLOAT UNSIGNED NOT NULL ,
CHANGE `consumed` `consumed` FLOAT UNSIGNED NOT NULL ,
CHANGE `left` `left` FLOAT UNSIGNED NOT NULL

-- todo表

CREATE TABLE IF NOT EXISTS `zt_todo` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `account` char(30) NOT NULL,
  `date` date NOT NULL default '0000-00-00',
  `begin` smallint(4) unsigned zerofill NOT NULL,
  `end` smallint(4) unsigned zerofill NOT NULL,
  `type` char(10) NOT NULL,
  `idvalue` mediumint(8) unsigned NOT NULL default '0',
  `pri` tinyint(3) unsigned NOT NULL,
  `name` char(90) NOT NULL,
  `desc` char(255) NOT NULL default '',
  `status` enum('wait','doing','done') NOT NULL default 'wait',
  PRIMARY KEY  (`id`),
  KEY `user` (`account`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- 更新product, project表中的company字段：
update zt_product set company = 1;
update zt_project set company = 1;

 -- 更新story字段里面的estimate字段：
ALTER TABLE `zt_story` CHANGE `estimate` `estimate` FLOAT UNSIGNED NOT NULL

-- 还是使用datetime字段。
ALTER TABLE `zt_story` CHANGE `openedDate` `openedDate` DATETIME NOT NULL ,
CHANGE `assignedDate` `assignedDate` DATETIME NOT NULL ,
CHANGE `lastEditedDate` `lastEditedDate` DATETIME NOT NULL ,
CHANGE `closedDate` `closedDate` DATETIME NOT NULL

-- 增加diff字段。 
ALTER TABLE `zt_history` ADD `diff` TEXT NOT NULL 

-- 10.27
ALTER TABLE `zt_todo` CHANGE `desc` `desc` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
ALTER TABLE `zt_task` CHANGE `name` `name` VARCHAR( 90 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL

ALTER TABLE `zt_task` CHANGE `estimate` `estimate` FLOAT UNSIGNED NOT NULL ,
CHANGE `consumed` `consumed` FLOAT UNSIGNED NOT NULL ,
CHANGE `left` `left` FLOAT UNSIGNED NOT NULL 


-- 11.2 todo表增加private字段：
ALTER TABLE `zt_todo` ADD `private` BOOL NOT NULL

-- 11.4 增加消耗表。
CREATE TABLE IF NOT EXISTS `zt_burn` (
  `project` mediumint(8) unsigned NOT NULL,
  `date` date NOT NULL,
  `left` float NOT NULL,
  `consumed` float NOT NULL,
  PRIMARY KEY  (`project`,`date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 11.5 project status字段更改。
ALTER TABLE `zt_project` CHANGE `status` `status` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL

-- 11.10.

ALTER TABLE `zt_bug` CHANGE `openedDate` `openedDate` DATETIME NOT NULL ,
CHANGE `assignedDate` `assignedDate` DATETIME NOT NULL ,
CHANGE `resolvedDate` `resolvedDate` DATETIME NOT NULL ,
CHANGE `closedDate` `closedDate` DATETIME NOT NULL ,
CHANGE `lastEditedDate` `lastEditedDate` DATETIME NOT NULL

-- 11.12 

ALTER TABLE `zt_bug` ADD `duplicateBug` MEDIUMINT UNSIGNED NOT NULL AFTER `closedDate` ,
ADD `linkBug` VARCHAR( 255 ) NOT NULL AFTER `duplicateBug` ,
ADD `case` MEDIUMINT UNSIGNED NOT NULL AFTER `linkBug` ,
ADD `result` MEDIUMINT UNSIGNED NOT NULL AFTER `case`

-- 11.13
ALTER TABLE `zt_case` CHANGE `openedDate` `openedDate` DATETIME NOT NULL ,
CHANGE `lastEditedDate` `lastEditedDate` DATETIME NOT NULL

-- 11.16
ALTER TABLE `zt_file` CHANGE `addedDate` `addedDate` DATETIME NOT NULL;
ALTER TABLE `zt_file` ADD `title` CHAR( 90 ) NOT NULL AFTER `file`;
ALTER TABLE `zt_file` ADD `objectType` CHAR( 10 ) NOT NULL AFTER `size` ,
ADD `objectID` MEDIUMINT NOT NULL AFTER `objectType` ;

ALTER TABLE `zt_file` CHANGE `file` `pathname` CHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `zt_file` CHANGE `type` `extension` CHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE `zt_task` CHANGE `status` `status` ENUM( 'wait', 'doing', 'done', 'cancel' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'wait'

ALTER TABLE `zt_todo` CHANGE `name` `name` CHAR( 150 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL


-- 12.10 新增productPlan表。
CREATE TABLE `zentao`.`zt_productPlan` (
  `id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
  `product` MEDIUMINT UNSIGNED NOT NULL ,
  `title` VARCHAR( 90 ) NOT NULL ,
  `desc` VARCHAR( 255 ) NOT NULL ,
  `begin` DATE NOT NULL ,
  `end` DATE NOT NULL 
) ENGINE = MYISAM ;

-- 将zt_story中的release改为plan。 
ALTER TABLE `zt_story` CHANGE `replease` `plan` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT '0'

-- 12.11 修改case表。

ALTER TABLE `zt_case` CHANGE `type` `type` CHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '1',
CHANGE `status` `status` CHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '1',
CHANGE `openedDate` `openedDate` DATETIME NOT NULL ,
CHANGE `lastEditedDate` `lastEditedDate` DATETIME NOT NULL

ALTER TABLE `zt_case` CHANGE `title` `title` CHAR( 90 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
