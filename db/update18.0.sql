ALTER TABLE `zt_image` ADD `localName` VARCHAR(64) NOT NULL AFTER `name`;
ALTER TABLE `zt_image` ADD `restoreDate` datetime NOT NULL AFTER `createdDate`;

ALTER TABLE `zt_ticket` MODIFY `resolution` text NOT NULL;

ALTER TABLE `zt_chart` CHANGE `fields` `fields` mediumtext NULL AFTER `filters`;
REPLACE INTO `zt_chart` (`id`, `name`, `dimension`, `type`, `group`, `dataset`, `desc`, `settings`, `filters`, `fields`, `sql`, `builtin`, `objects`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `deleted`) VALUES
(1105,	'年度排行-个人-创建需求条目榜',	1,	'bar',	0,	'',	'',	'{\"xaxis\":[{\"field\":\"realname\",\"name\":\"用户\"}],\"yaxis\":[{\"type\":\"value\",\"field\":\"count\",\"agg\":\"value\",\"name\":\"创建需求条目\",\"valOrAgg\":\"value\"}]}',	'',	NULL,	'SELECT YEAR(t1.openedDate) AS `year`,t2.realname,count(1) AS count\r\nFROM zt_story AS t1\r\nLEFT JOIN zt_user AS t2 ON t1.openedBy=t2.account\r\nWHERE t1.deleted = \'0\' AND t2.id IS NOT NULL\r\nGROUP BY `year`,t2.account ORDER BY `year`,count DESC',	1,	'',	'',	'0000-00-00 00:00:00',	'',	'0000-00-00 00:00:00',	0);
