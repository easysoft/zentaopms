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

CREATE TABLE `zt_testtaskproduct` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  `product` int unsigned NOT NULL default 0 COMMENT '所属产品',
  `build` int unsigned NOT NULL default 0 COMMENT '所属构建',
  `task` int unsigned NOT NULL default 0 COMMENT '所属测试单',
  `execution` int unsigned NOT NULL default 0 COMMENT '所属执行',
  `project` int unsigned NOT NULL default 0 COMMENT '所属项目',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;
CREATE UNIQUE INDEX `uk_productbuild` ON `zt_testtaskproduct` (`product`,`build`,`task`);

ALTER TABLE `zt_testtask` ADD `joint` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否为联调测试单' AFTER `build`;

UPDATE `zt_testtask` SET `build` = 0 WHERE `build` = 'trunk' OR `build` = '' OR `build` IS NULL;

ALTER TABLE `zt_testtask` MODIFY `build` int unsigned NOT NULL DEFAULT 0;
