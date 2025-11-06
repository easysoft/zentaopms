ALTER TABLE `zt_demand` CHANGE `version` `version` smallint unsigned NOT NULL DEFAULT '1';
ALTER TABLE `zt_demand` CHANGE `pri` `pri` tinyint unsigned NOT NULL DEFAULT 3;
ALTER TABLE `zt_issue` CHANGE `pri` `pri` tinyint unsigned NOT NULL DEFAULT 3;
ALTER TABLE `zt_risk` CHANGE `pri` `pri` tinyint unsigned NOT NULL DEFAULT 3;
ALTER TABLE `zt_opportunity` CHANGE `pri` `pri` tinyint unsigned NOT NULL DEFAULT 3;
ALTER TABLE `zt_design` CHANGE `project` `project` int unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_design` CHANGE `product` `product` int unsigned NOT NULL DEFAULT 0;