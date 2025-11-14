ALTER TABLE `zt_demand` CHANGE `version` `version` smallint unsigned NOT NULL DEFAULT '1';
ALTER TABLE `zt_demand` CHANGE `pri` `pri` tinyint unsigned NOT NULL DEFAULT 3;
ALTER TABLE `zt_issue` CHANGE `pri` `pri` tinyint unsigned NOT NULL DEFAULT 3;
ALTER TABLE `zt_risk` CHANGE `pri` `pri` tinyint unsigned NOT NULL DEFAULT 3;
ALTER TABLE `zt_opportunity` CHANGE `pri` `pri` tinyint unsigned NOT NULL DEFAULT 3;
ALTER TABLE `zt_design` CHANGE `project` `project` int unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_design` CHANGE `product` `product` int unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_acl` CHANGE `id` `id` int unsigned NOT NULL AUTO_INCREMENT FIRST;
ALTER TABLE `zt_user` CHANGE `mobile` `mobile` varchar(20) NOT NULL DEFAULT '';

ALTER TABLE `zt_doc` ADD `reportModule` varchar(20) NOT NULL DEFAULT '0' AFTER `module`;
UPDATE `zt_doc` SET `reportModule` = `module` WHERE `templateType` = 'projectReport';
UPDATE `zt_doc` SET `module` = 0 WHERE `templateType` = 'projectReport' OR `module` = '';
ALTER TABLE `zt_doc` MODIFY `module` int unsigned NOT NULL DEFAULT 0;
