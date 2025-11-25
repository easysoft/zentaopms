ALTER TABLE `zt_extension` ADD `depends` VARCHAR( 100 ) NOT NULL AFTER `installedTime`;
ALTER TABLE `zt_extension` CHANGE `zentaoVersion` `zentaoCompatible` VARCHAR( 100 ) NOT NULL;
ALTER TABLE `zt_company` DROP `pms`;
ALTER TABLE `zt_bug` ADD `plan` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `project`;
