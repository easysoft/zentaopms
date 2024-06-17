ALTER TABLE `zt_demandpool` ADD COLUMN `products` varchar(255) NOT NULL DEFAULT '';
ALTER TABLE `zt_project` ADD COLUMN `parallel` mediumint(9) NOT NULL DEFAULT '0';
ALTER TABLE `zt_demand` ADD COLUMN `feedback` mediumint(9) NOT NULL DEFAULT '0';
ALTER TABLE `zt_demand` ADD COLUMN `keywords` varchar(255) NOT NULL DEFAULT '';
ALTER TABLE `zt_demand` CHANGE `product` `product` varchar(255) NOT NULL DEFAULT '';
ALTER TABLE `zt_story` ADD COLUMN `retractedReason` ENUM('', 'omit', 'other') NOT NULL DEFAULT '';
ALTER TABLE `zt_story` ADD COLUMN `retractedBy` varchar(30) NOT NULL DEFAULT '';
ALTER TABLE `zt_story` ADD COLUMN `retractedDate` datetime;

ALTER table `zt_metric` ADD `dateType` varchar(50) NOT NULL DEFAULT '';
ALTER table `zt_metric` ADD `lastCalcRows` mediumint NOT NULL DEFAULT 0 AFTER `order`;
ALTER table `zt_metric` ADD `lastCalcTime` datetime DEFAULT NULL AFTER `lastCalcRows`;
