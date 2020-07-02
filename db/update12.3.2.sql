UPDATE `zt_story` SET `plan` = '' WHERE `parent` = '-1' AND `plan` != '0' AND `plan` != '';

DELETE FROM `zt_grouppriv` WHERE `module` = 'api' AND `method` = 'getModel';
DELETE FROM `zt_grouppriv` WHERE `module` = 'api' AND `method` = 'sql';

ALTER TABLE `zt_jenkins` CHANGE `password` `password` varchar(255) COLLATE 'utf8_general_ci' NOT NULL AFTER `account`;

ALTER TABLE `zt_group` ADD `program` mediumint(8) unsigned NOT NULL DEFAULT 0 AFTER `id`;
