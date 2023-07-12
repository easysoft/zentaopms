DELETE FROM `zt_block` WHERE `type` IN ('news', 'patch', 'plugin', 'puglicclass');

ALTER TABLE `zt_block` ADD `dashboard` varchar(20) NOT NULL DEFAULT '' AFTER `account`;
ALTER TABLE `zt_block` CHANGE `block` `code` varchar(30) NOT NULL DEFAULT '' AFTER `module`;
ALTER TABLE `zt_block` ADD `width` enum ('1', '2', '3') NOT NULL DEFAULT '1' AFTER `code`;
ALTER TABLE `zt_block` MODIFY `height` smallint(5) UNSIGNED NOT NULL DEFAULT 3 AFTER `width`;
ALTER TABLE `zt_block` ADD `left` enum('0', '1', '2') NOT NULL DEFAULT '0' AFTER `height`;
ALTER TABLE `zt_block` ADD `top` smallint(5) UNSIGNED NOT NULL DEFAULT 0 AFTER `left`;
ALTER TABLE `zt_block` MODIFY `vision` varchar(10) NOT NULL DEFAULT 'rnd' AFTER `hidden`;

UPDATE `zt_block` SET `dashboard` = CONCAT(`module`, `type`);
UPDATE `zt_block` SET `module` = IF(`source` != '', `source`, `code`);
DROP INDEX account_vision_module_type_order ON `zt_block`;

ALTER TABLE `zt_block` DROP COLUMN `source`;
ALTER TABLE `zt_block` DROP COLUMN `type`;
ALTER TABLE `zt_block` DROP COLUMN `grid`;
ALTER TABLE `zt_block` DROP COLUMN `order`;

ALTER TABLE `zt_todo`  CHANGE `idvalue` `objectID` mediumint(8) unsigned default '0' NOT NULL AFTER `type`;
ALTER TABLE `zt_todo` CHANGE `config` `config` VARCHAR(1000) NOT NULL  DEFAULT '';

ALTER TABLE `zt_project` ADD `stageBy` enum('project', 'product') NOT NULL DEFAULT 'product' AFTER `division`;
UPDATE `zt_project` SET `stageBy` = 'project' WHERE `division` = '0';
UPDATE `zt_project` SET `stageBy` = 'product' WHERE `division` = '1';
ALTER TABLE `zt_project` DROP `division`;

ALTER TABLE `zt_bug` CHANGE `linkBug` `relatedBug` varchar(255) NOT NULL DEFAULT '';

ALTER TABLE `zt_product` ADD COLUMN `groups` text NULL AFTER `acl`;

ALTER TABLE `zt_usercontact` ADD `public` tinyint(1) NOT NULL DEFAULT 0;
UPDATE `zt_usercontact` AS t1, `zt_config` AS t2 SET t1.public = 1 WHERE t2.module = 'my' AND t2.section = 'global' AND t2.key = 'globalContacts' AND FIND_IN_SET(t1.id, t2.value); -- Change it for compatible with dameng.
DELETE FROM `zt_config` WHERE `module` = 'my' AND `section` = 'global' AND `key` = 'globalContacts';

ALTER TABLE `zt_testtask` ADD `realBegan` date NULL;

-- DROP TABLE IF EXISTS `zt_space`;
CREATE TABLE `zt_space` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `k8space` char(64) NOT NULL,
  `owner` char(30) NOT NULL,
  `default` tinyint(1) NOT NULL DEFAULT 0,
  `createdAt` datetime NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_instance`;
CREATE TABLE IF NOT EXISTS `zt_instance` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `space` mediumint(8) unsigned NOT NULL,
  `solution` mediumint(8) unsigned NOT NULL,
  `name` char(50),
  `appID` mediumint(8) unsigned NOT NULL,
  `appName` char(50) NOT NULL,
  `appVersion` char(20) NOT NULL,
  `chart` char(50) NOT NULL,
  `logo` varchar(255),
  `version` char(50) NOT NULL,
  `desc` text,
  `introduction` varchar(500),
  `source` char(20) NOT NULL,
  `channel` char(20),
  `k8name` char(64) NOT NULL,
  `status` char(20) NOT NULL,
  `pinned` enum('0', '1') NOT NULL DEFAULT '0',
  `domain` char(255) NOT NULL,
  `smtpSnippetName` char(30) NULL,
  `ldapSnippetName` char(30) NULL,
  `ldapSettings` text,
  `dbSettings` text,
  `autoBackup` tinyint(1) NOT NULL DEFAULT 0,
  `backupKeepDays` int unsigned NOT NULL DEFAULT 1,
  `autoRestore` tinyint(1) NOT NULL DEFAULT 0,
  `env` text,
  `createdBy` char(30) NOT NULL,
  `createdAt` datetime NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `space` (`space`),
  KEY `k8name` (`k8name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_solution`;
CREATE TABLE IF NOT EXISTS `zt_solution` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(50),
  `appID` mediumint(8) unsigned NOT NULL,
  `appName` char(50) NOT NULL,
  `appVersion` char(20) NOT NULL,
  `version` char(50) NOT NULL,
  `chart` char(50) NOT NULL,
  `cover` varchar(255),
  `desc` text,
  `introduction` varchar(500),
  `source` char(20) NOT NULL,
  `channel` char(20),
  `components` text,
  `status` char(20) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `createdBy` char(30) NOT NULL,
  `createdAt` datetime NOT NULL,
  `updatedDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `zt_session` (
    `id` varchar(32) NOT NULL,
    `data` mediumtext,
    `timestamp` int(10) unsigned DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `zt_metric` (
  `id` mediumint unsigned NOT NULL AUTO_INCREMENT,
  `purpose` varchar(50) NOT NULL DEFAULT '',
  `scope` char(30) NOT NULL DEFAULT '',
  `object` char(30) NOT NULL DEFAULT '',
  `name` varchar(90) NOT NULL DEFAULT '',
  `code` varchar(90) NOT NULL DEFAULT '',
  `unit` varchar(10) NOT NULL DEFAULT '',
  `desc` text,
  `definition` text,
  `when` varchar(30) NOT NULL DEFAULT '',
  `event` varchar(30) NOT NULL DEFAULT '',
  `cronCFG` varchar(30) NOT NULL DEFAULT '',
  `time` varchar(30) NOT NULL DEFAULT '',
  `createdBy` varchar(30) NOT NULL DEFAULT '',
  `createdDate` datetime DEFAULT NULL,
  `editedBy` varchar(30) NOT NULL DEFAULT '',
  `editedDate` datetime DEFAULT NULL,
  `order` mediumint unsigned NOT NULL DEFAULT '0',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `zt_metric`(`purpose`, `scope`, `object`, `name`, `code`, `unit`, `definition`, `desc`, `when`, `event`, `cronCFG`, `time`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `order`, `deleted`) VALUES ('scale', 'prod', 'productplan', '按产品统计的计划总数', 'count_of_plan_in_product', '个', '产品的计划总数，反映产品活跃程度以及产品经理对产品开发节奏的把握。', '产品中计划的个数求和\n过滤已删除的计划\n过滤已删除的产品', 'realtime', '', '', '', '', NULL, '', NULL, '0', '0');
INSERT INTO `zt_metric`(`purpose`, `scope`, `object`, `name`, `code`, `unit`, `definition`, `desc`, `when`, `event`, `cronCFG`, `time`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `order`, `deleted`) VALUES ('scale', 'prod', 'productplan', '按产品统计的年度新增计划数', 'count_of_annual_created_plan_in_product', '个', '产品年度新增的计划数，反映产品年度活跃情况。', '产品中创建时间为某年的计划个数求和\n过滤已删除的计划\n过滤已删除的产品', 'realtime', '', '', '', '', NULL, '', NULL, '0', '0');
INSERT INTO `zt_metric`(`purpose`, `scope`, `object`, `name`, `code`, `unit`, `definition`, `desc`, `when`, `event`, `cronCFG`, `time`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `order`, `deleted`) VALUES ('scale', 'prod', 'productplan', '按产品统计的年度完成计划数', 'count_of_annual_finished_plan_in_product', '个', '产品年新增完成计划数', '产品中完成时间为某年的计划个数求和\n过滤已删除的计划\n过滤已删除的产品', 'realtime', '', '', '', '', NULL, '', NULL, '0', '0');
INSERT INTO `zt_metric`(`purpose`, `scope`, `object`, `name`, `code`, `unit`, `definition`, `desc`, `when`, `event`, `cronCFG`, `time`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `order`, `deleted`) VALUES ('scale', 'prod', 'release', '按产品统计的发布总数', 'count_of_release_in_product', '个', '产品的发布总数', '产品中发布的个数求和\n过滤已删除的发布\n过滤已删除的产品', 'realtime', '', '', '', '', NULL, '', NULL, '0', '0');
INSERT INTO `zt_metric`(`purpose`, `scope`, `object`, `name`, `code`, `unit`, `definition`, `desc`, `when`, `event`, `cronCFG`, `time`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `order`, `deleted`) VALUES ('scale', 'prod', 'release', '按产品统计的年度新增发布数', 'count_of_annual_created_release_in_product', '个', '产品年新增发布数', '产品中发布时间为某年的发布个数求和\n过滤已删除的发布\n过滤已删除的产品\n过滤无效时间', 'realtime', '', '', '', '', NULL, '', NULL, '0', '0');
INSERT INTO `zt_metric`(`purpose`, `scope`, `object`, `name`, `code`, `unit`, `definition`, `desc`, `when`, `event`, `cronCFG`, `time`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `order`, `deleted`) VALUES ('qc', 'prod', 'release', '按产品统计的发布上线后的Bug数', 'count_of_released_bug_in_product', '个', '产品发布上线后产生的与本次发布相关的Bug数', '产品中Bug创建时间为某个发布上线后且关联到这个发布版本的Bug个数求和\n过滤已删除的计划\n过滤已删除的产品', 'realtime', '', '', '', '', NULL, '', NULL, '0', '0');
INSERT INTO `zt_metric`(`purpose`, `scope`, `object`, `name`, `code`, `unit`, `definition`, `desc`, `when`, `event`, `cronCFG`, `time`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `order`, `deleted`) VALUES ('scale', 'prod', 'story', '按产品统计的研发需求总数', 'count_of_story_in_product', '个', '产品的研发需求总数', '产品中研发需求的个数求和\n过滤已删除的研发需求\n过滤已删除的产品', 'realtime', '', '', '', '', NULL, '', NULL, '0', '0');
INSERT INTO `zt_metric`(`purpose`, `scope`, `object`, `name`, `code`, `unit`, `definition`, `desc`, `when`, `event`, `cronCFG`, `time`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `order`, `deleted`) VALUES ('scale', 'prod', 'story', '按产品统计的已完成研发需求数', 'count_of_finished_story_in_product', '个', '产品的所处阶段为已关闭且关闭原因为已完成的研发需求数', '产品中所处阶段为已关闭且关闭原因为已完成的研发需求个数求和\n过滤已删除的研发需求\n过滤已删除的产品', 'realtime', '', '', '', '', NULL, '', NULL, '0', '0');
INSERT INTO `zt_metric`(`purpose`, `scope`, `object`, `name`, `code`, `unit`, `definition`, `desc`, `when`, `event`, `cronCFG`, `time`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `order`, `deleted`) VALUES ('scale', 'prod', 'story', '按产品统计的已关闭研发需求数', 'count_of_closed_story_in_product', '个', '产品的已关闭的研发需求数', '产品中阶段为已关闭研发需求的个数求和\n过滤已删除的研发需求\n过滤已删除的产品', 'realtime', '', '', '', '', NULL, '', NULL, '0', '0');
INSERT INTO `zt_metric`(`purpose`, `scope`, `object`, `name`, `code`, `unit`, `definition`, `desc`, `when`, `event`, `cronCFG`, `time`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `order`, `deleted`) VALUES ('scale', 'prod', 'story', '按产品统计的未关闭研发需求数', 'count_of_unclosed_story_in_product', '个', '产品的研发需求总数与已关闭研发需求个数的差值', '复用：\n按产品统计的研发需求总数\n按产品统计的已关闭研发需求数\n按产品统计的关闭研发需求总数=按产品统计的研发需求总数-按产品统计的已关闭研发需求数', 'realtime', '', '', '', '', NULL, '', NULL, '0', '0');
INSERT INTO `zt_metric`(`purpose`, `scope`, `object`, `name`, `code`, `unit`, `definition`, `desc`, `when`, `event`, `cronCFG`, `time`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `order`, `deleted`) VALUES ('scale', 'prod', 'story', '按产品统计的已交付研发需求数', 'count_of_delivered_story_in_product', '个', '产品的所处阶段为已发布或关闭原因为已完成的研发需求数', '产品中所处阶段为已发布或关闭原因为已完成的研发需求个数求和\n过滤已删除的研发需求\n过滤已删除的产品', 'realtime', '', '', '', '', NULL, '', NULL, '0', '0');
INSERT INTO `zt_metric`(`purpose`, `scope`, `object`, `name`, `code`, `unit`, `definition`, `desc`, `when`, `event`, `cronCFG`, `time`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `order`, `deleted`) VALUES ('scale', 'prod', 'story', '按产品统计的无效研发需求数', 'count_of_invalid_story_in_product', '个', '产品的关闭原因为重复、不做、设计如此和已取消的研发需求数', '产品中关闭原因为重复、不做、设计如此和已取消的研发需求个数求和\n过滤已删除的研发需求\n过滤已删除的产品', 'realtime', '', '', '', '', NULL, '', NULL, '0', '0');
INSERT INTO `zt_metric`(`purpose`, `scope`, `object`, `name`, `code`, `unit`, `definition`, `desc`, `when`, `event`, `cronCFG`, `time`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `order`, `deleted`) VALUES ('scale', 'prod', 'story', '按产品统计的研发完成的研发需求数', 'count_of_developed_story_in_product', '个', '产品的所处阶段为研发完毕、测试中、测试完毕、已验收、已发布和关闭原因为已完成的研发需求数', '产品中所处阶段为研发完毕、测试中、测试完毕、已验收、已发布和关闭原因为已完成的研发需求个数求和\n过滤已删除的研发需求\n过滤已删除的产品', 'realtime', '', '', '', '', NULL, '', NULL, '0', '0');
INSERT INTO `zt_metric`(`purpose`, `scope`, `object`, `name`, `code`, `unit`, `definition`, `desc`, `when`, `event`, `cronCFG`, `time`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `order`, `deleted`) VALUES ('scale', 'prod', 'story', '按产品统计的研发需求用例覆盖率', 'testcase_coverage_of_story_in_product', '个', '产品的有用例的研发需求相对于研发需求总数的比例', '复用：\n按产品统计的研发需求总数\n公式：\n按产品统计的研发需求用例覆盖率=按产品统计的的有用例研发需求数/按产品统计的研发需求总数\n过滤已删除的研发需求\n过滤已删除的产品', 'realtime', '', '', '', '', NULL, '', NULL, '0', '0');
INSERT INTO `zt_metric`(`purpose`, `scope`, `object`, `name`, `code`, `unit`, `definition`, `desc`, `when`, `event`, `cronCFG`, `time`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `order`, `deleted`) VALUES ('scale', 'prod', 'story', '按产品统计的年度新增研发需求数', 'count_of_annual_created_story_in_product', '个', '产品年度新增研发需求数', '产品中创建时间为某年的研发需求的个数求和\n过滤已删除的研发需求\n过滤已删除的产品', 'realtime', '', '', '', '', NULL, '', NULL, '0', '0');
INSERT INTO `zt_metric`(`purpose`, `scope`, `object`, `name`, `code`, `unit`, `definition`, `desc`, `when`, `event`, `cronCFG`, `time`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `order`, `deleted`) VALUES ('scale', 'prod', 'story', '按产品统计的年度完成研发需求数', 'count_of_annual_finished_story_in_product', '个', '产品年度关闭的且关闭原因为已完成的研发需求数', '产品中关闭时间在某年且关闭原因为已完成的研发需求的个数求和\n过滤已删除的研发需求\n过滤已删除的产品', 'realtime', '', '', '', '', NULL, '', NULL, '0', '0');
INSERT INTO `zt_metric`(`purpose`, `scope`, `object`, `name`, `code`, `unit`, `definition`, `desc`, `when`, `event`, `cronCFG`, `time`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `order`, `deleted`) VALUES ('scale', 'prod', 'story', '按产品统计的年度已交付研发需求数', 'count_of_annual_delivered_story_in_product', '个', '产品年度发布或完成的研发需求数', '产品中所处阶段为已发布且发布时间为某年或关闭原因为已完成且关闭时间为某年的研发需求个数求和\n过滤已删除的研发需求\n过滤已删除的产品', 'realtime', '', '', '', '', NULL, '', NULL, '0', '0');
INSERT INTO `zt_metric`(`purpose`, `scope`, `object`, `name`, `code`, `unit`, `definition`, `desc`, `when`, `event`, `cronCFG`, `time`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `order`, `deleted`) VALUES ('scale', 'prod', 'story', '按产品统计的年度关闭研发需求数', 'count_of_annual_closed_story_in_product', '个', '产品年度关闭的研发需求数', '产品中关闭时间在某年的研发需求的个数求和\n过滤已删除的研发需求\n过滤已删除的产品', 'realtime', '', '', '', '', NULL, '', NULL, '0', '0');
INSERT INTO `zt_metric`(`purpose`, `scope`, `object`, `name`, `code`, `unit`, `definition`, `desc`, `when`, `event`, `cronCFG`, `time`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `order`, `deleted`) VALUES ('scale', 'prod', 'story', '按产品统计的月度完成研发需求数', 'count_of_monthly_finished_story_in_product', '个', '产品月度关闭的且关闭原因为已完成的研发需求总数', '产品中关闭时间为某年某月且关闭原因为已完成的研发需求的个数求和\n过滤已删除的研发需求\n过滤已删除的产品', 'realtime', '', '', '', '', NULL, '', NULL, '0', '0');
INSERT INTO `zt_metric`(`purpose`, `scope`, `object`, `name`, `code`, `unit`, `definition`, `desc`, `when`, `event`, `cronCFG`, `time`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `order`, `deleted`) VALUES ('scale', 'prod', 'story', '按产品统计的月度已交付研发需求数', 'count_of_monthly_delivered_story_in_product', '个', '产品月度发布或完成的研发需求数', '产品中所处阶段为已发布且发布时间为某年某月或关闭原因为已完成且关闭时间为某年某月的研发需求个数求和\n过滤已删除的研发需求\n过滤已删除的产品', 'realtime', '', '', '', '', NULL, '', NULL, '0', '0');
INSERT INTO `zt_metric`(`purpose`, `scope`, `object`, `name`, `code`, `unit`, `definition`, `desc`, `when`, `event`, `cronCFG`, `time`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `order`, `deleted`) VALUES ('scale', 'prod', 'story', '按产品统计的月度关闭研发需求数', 'count_of_monthly_closed_story_in_product', '个', '产品月度关闭的研发需求数', '产品中关闭时间为某年某月的研发需求的个数求和\n过滤已删除的研发需求\n过滤已删除的产品', 'realtime', '', '', '', '', NULL, '', NULL, '0', '0');
INSERT INTO `zt_metric`(`purpose`, `scope`, `object`, `name`, `code`, `unit`, `definition`, `desc`, `when`, `event`, `cronCFG`, `time`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `order`, `deleted`) VALUES ('rate', 'prod', 'story', '按产品统计的研发需求完成率', 'rate_of_finish_story_in_product', '%', '产品已完成研发需求数相对于研发需求总数与无效研发需求数差值的比例', '复用：\n按产品统计的已完成研发需求数\n按产品统计的无效研发需求数\n按产品统计的研发需求总数\n公式：\n按产品统计的研发需求完成率=按产品统计的已完成研发需求数/（按产品统计的研发需求总数-按产品统计的无效研发需求数）*100%', 'realtime', '', '', '', '', NULL, '', NULL, '0', '0');
INSERT INTO `zt_metric`(`purpose`, `scope`, `object`, `name`, `code`, `unit`, `definition`, `desc`, `when`, `event`, `cronCFG`, `time`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `order`, `deleted`) VALUES ('rate', 'prod', 'story', '按产品统计的研发需求交付率', 'rate_of_delivery_story_in_product', '%', '产品已交付研发需求数相对于研发需求总数与无效研发需求数差值的比例', '复用：\n按产品统计的已交付研发需求数\n按产品统计的无效研发需求数\n按产品统计的研发需求总数\n公式：\n按产品统计的研发需求完成率=按产品统计的已交付研发需求数/（按产品统计的研发需求总数-按产品统计的无效研发需求数）*100%', 'realtime', '', '', '', '', NULL, '', NULL, '0', '0');
INSERT INTO `zt_metric`(`purpose`, `scope`, `object`, `name`, `code`, `unit`, `definition`, `desc`, `when`, `event`, `cronCFG`, `time`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `order`, `deleted`) VALUES ('scale', 'prod', 'story', '按产品统计的月度新增研发需求数', 'count_of_monthly_created_story_in_product', '个', '产品月度新增研发需求数', '产品中创建时间在某年某月的研发需求的个数求和\n过滤已删除的研发需求\n过滤已删除的产品', 'realtime', '', '', '', '', NULL, '', NULL, '0', '0');
INSERT INTO `zt_metric`(`purpose`, `scope`, `object`, `name`, `code`, `unit`, `definition`, `desc`, `when`, `event`, `cronCFG`, `time`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `order`, `deleted`) VALUES ('scale', 'prod', 'story', '按产品统计的研发需求规模数', 'scale_of_story_in_product', 'sp/工时/功能点', '产品的研发需求规模数', '产品中研发需求的规模数求和\n过滤已删除的研发需求\n过滤已删除的产品', 'realtime', '', '', '', '', NULL, '', NULL, '0', '0');
INSERT INTO `zt_metric`(`purpose`, `scope`, `object`, `name`, `code`, `unit`, `definition`, `desc`, `when`, `event`, `cronCFG`, `time`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `order`, `deleted`) VALUES ('scale', 'prod', 'story', '按产品统计的年度已完成研发需求规模数', 'scale_of_annual_finished_story_in_product', 'sp/工时/功能点', '产品年度关闭的且关闭原因为已完成的研发需求规模数', '产品中关闭时间在某年且关闭原因为已完成的研发需求的规模数求和\n过滤已删除的研发需求\n过滤已删除的产品', 'realtime', '', '', '', '', NULL, '', NULL, '0', '0');
INSERT INTO `zt_metric`(`purpose`, `scope`, `object`, `name`, `code`, `unit`, `definition`, `desc`, `when`, `event`, `cronCFG`, `time`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `order`, `deleted`) VALUES ('scale', 'prod', 'story', '按产品统计的年度已交付研发需求规模数', 'scale_of_annual_delivered_story_in_product', 'sp/工时', '产品月度发布或完成的研发需求规模数', '产品中所处阶段为已发布且发布时间为某年某月或关闭原因为已完成且关闭时间为某年某月的研发需求规模数求和\n过滤已删除的研发需求\n过滤已删除的产品', 'realtime', '', '', '', '', NULL, '', NULL, '0', '0');
INSERT INTO `zt_metric`(`purpose`, `scope`, `object`, `name`, `code`, `unit`, `definition`, `desc`, `when`, `event`, `cronCFG`, `time`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `order`, `deleted`) VALUES ('scale', 'prod', 'story', '按产品统计的年度关闭研发需求规模数', 'scale_of_annual_closed_story_in_product', 'sp/工时', '产品年度关闭的研发需求规模数', '产品中关闭时间在某年的研发需求的规模数求和\n过滤已删除的研发需求\n过滤已删除的产品', 'realtime', '', '', '', '', NULL, '', NULL, '0', '0');
INSERT INTO `zt_metric`(`purpose`, `scope`, `object`, `name`, `code`, `unit`, `definition`, `desc`, `when`, `event`, `cronCFG`, `time`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `order`, `deleted`) VALUES ('qc', 'prod', 'story', '按产品统计的研发需求评审通过率', 'rate_of_approved_story_in_product', '%', '产品中不需要评审的与评审通过的研发需求数相对于不需要评审的与有评审结果的需求数的比例', '按产品统计的所有研发需求评审通过率=（按产品统计的不需要评审的研发需求数+评审结果确认通过的研发需求数）/（按产品统计的不需要评审的研发需求数+有评审结果的研发需求数）\n过滤已删除的研发需求\n过滤已删除的产品', 'realtime', '', '', '', '', NULL, '', NULL, '0', '0');
INSERT INTO `zt_metric`(`purpose`, `scope`, `object`, `name`, `code`, `unit`, `definition`, `desc`, `when`, `event`, `cronCFG`, `time`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `order`, `deleted`) VALUES ('scale', 'prod', 'bug', '按产品统计的研发完成需求的Bug密度', 'bug_concentration_of_developed_story_in_product', '个', '产品中有效的Bug的个数相对于产品中研发完成的研发需求数的比例', '复用：\n按产品统计的有效Bug数\n按产品统计的研发完成的研发需求规模数\n公式：\n按产品统计的研发完成需求的Bug密度=按产品统计的有效Bug数/按产品统计的研发完成的研发需求规模数', 'realtime', '', '', '', '', NULL, '', NULL, '0', '0');
INSERT INTO `zt_metric`(`purpose`, `scope`, `object`, `name`, `code`, `unit`, `definition`, `desc`, `when`, `event`, `cronCFG`, `time`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `order`, `deleted`) VALUES ('scale', 'prod', 'requirement', '按产品统计的用户需求总数', 'count_of_requirement_in_product', '个', '产品的用户需求总数', '产品中用户需求的个数求和\n过滤已删除的用户需求\n过滤已删除的产品', 'realtime', '', '', '', '', NULL, '', NULL, '0', '0');
INSERT INTO `zt_metric`(`purpose`, `scope`, `object`, `name`, `code`, `unit`, `definition`, `desc`, `when`, `event`, `cronCFG`, `time`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `order`, `deleted`) VALUES ('scale', 'prod', 'requirement', '按产品统计的年度新增用户需求数', 'count_of_annual_created_requirement_in_product', '个', '产品年度新增用户需求总数', '产品中创建时间为某年的用户需求的个数求和\n过滤已删除的用户需求\n过滤已删除的产品', 'realtime', '', '', '', '', NULL, '', NULL, '0', '0');
INSERT INTO `zt_metric`(`purpose`, `scope`, `object`, `name`, `code`, `unit`, `definition`, `desc`, `when`, `event`, `cronCFG`, `time`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `order`, `deleted`) VALUES ('scale', 'prod', 'feedback', '按产品统计的反馈总数', 'count_of_feedback_in_product', '个', '产品的反馈总数', '产品中反馈的个数求和\n过滤已删除的反馈\n过滤已删除的产品', 'realtime', '', '', '', '', NULL, '', NULL, '0', '0');
INSERT INTO `zt_metric`(`purpose`, `scope`, `object`, `name`, `code`, `unit`, `definition`, `desc`, `when`, `event`, `cronCFG`, `time`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `order`, `deleted`) VALUES ('scale', 'prod', 'feedback', '按产品统计的年度新增反馈数', 'count_of_annual_created_feedback_in_product', '个', '产品年度新增反馈数', '产品中创建时间为某年的反馈的个数求和\n过滤已删除的反馈\n过滤已删除的产品', 'realtime', '', '', '', '', NULL, '', NULL, '0', '0');
INSERT INTO `zt_metric`(`purpose`, `scope`, `object`, `name`, `code`, `unit`, `definition`, `desc`, `when`, `event`, `cronCFG`, `time`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `order`, `deleted`) VALUES ('scale', 'prod', 'feedback', '按产品统计的年度关闭反馈数', 'count_of_annual_closed_feedback_in_product', '个', '产品年度关闭反馈数', '产品中关闭时间为某年的反馈的个数求和\n过滤已删除的反馈\n过滤已删除的产品', 'realtime', '', '', '', '', NULL, '', NULL, '0', '0');
INSERT INTO `zt_metric`(`purpose`, `scope`, `object`, `name`, `code`, `unit`, `definition`, `desc`, `when`, `event`, `cronCFG`, `time`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `order`, `deleted`) VALUES ('scale', 'prod', 'bug', '按产品统计的Bug总数', 'count_of_bug_in_product', '个', '产品的Bug总数', '产品中Bug的个数求和\n过滤已删除的Bug\n过滤已删除的产品', 'realtime', '', '', '', '', NULL, '', NULL, '0', '0');
INSERT INTO `zt_metric`(`purpose`, `scope`, `object`, `name`, `code`, `unit`, `definition`, `desc`, `when`, `event`, `cronCFG`, `time`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `order`, `deleted`) VALUES ('scale', 'prod', 'bug', '按产品统计的激活Bug数', 'count_of_activated_bug_in_product', '个', '产品的激活Bug总数', '产品中激活Bug的个数求和\n过滤已删除的Bug\n过滤已删除的产品', 'realtime', '', '', '', '', NULL, '', NULL, '0', '0');
INSERT INTO `zt_metric`(`purpose`, `scope`, `object`, `name`, `code`, `unit`, `definition`, `desc`, `when`, `event`, `cronCFG`, `time`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `order`, `deleted`) VALUES ('scale', 'prod', 'bug', '按产品统计的有效Bug数', 'count_of_effective_bug_in_product', '个', '产品的解决方案为已解决和延期处理的或者状态为激活的Bug数', '有效Bug数=方案为已解决的Bug数+方案为延期处理的Bug数+激活的Bug数\n过滤已删除的Bug\n过滤已删除的产品', 'realtime', '', '', '', '', NULL, '', NULL, '0', '0');
INSERT INTO `zt_metric`(`purpose`, `scope`, `object`, `name`, `code`, `unit`, `definition`, `desc`, `when`, `event`, `cronCFG`, `time`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `order`, `deleted`) VALUES ('scale', 'prod', 'bug', '按产品统计的已修复Bug数', 'count_of_restored_bug_in_product', '个', '产品的解决方案为已解决的Bug数', '产品中Bug的个数求和\n解决方案为已解决\n状态为已关闭\n过滤已删除的Bug\n过滤已删除的产品', 'realtime', '', '', '', '', NULL, '', NULL, '0', '0');
INSERT INTO `zt_metric`(`purpose`, `scope`, `object`, `name`, `code`, `unit`, `definition`, `desc`, `when`, `event`, `cronCFG`, `time`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `order`, `deleted`) VALUES ('scale', 'prod', 'bug', '按产品统计的严重程度为1级的Bug数', 'count_of_severity_1_bug_in_product', '个', '产品的严重程度为1级的Bug数', '产品的严重程度为1级的Bug数\n过滤已删除的Bug\n过滤已删除的产品', 'realtime', '', '', '', '', NULL, '', NULL, '0', '0');
INSERT INTO `zt_metric`(`purpose`, `scope`, `object`, `name`, `code`, `unit`, `definition`, `desc`, `when`, `event`, `cronCFG`, `time`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `order`, `deleted`) VALUES ('scale', 'prod', 'bug', '按产品统计的严重程度为2级的Bug数', 'count_of_severity_2_bug_in_product', '个', '产品的严重程度为2级的Bug数', '产品的严重程度为2级的Bug数\n过滤已删除的Bug\n过滤已删除的产品', 'realtime', '', '', '', '', NULL, '', NULL, '0', '0');
INSERT INTO `zt_metric`(`purpose`, `scope`, `object`, `name`, `code`, `unit`, `definition`, `desc`, `when`, `event`, `cronCFG`, `time`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `order`, `deleted`) VALUES ('scale', 'prod', 'Bug', '按产品统计的严重程度为1、2级的Bug数', 'count_of_severe_bug_in_product', '个', '产品的严重程度为1、2级的Bug数', '复用：\n按产品统计的严重程度为1级的Bug数\n按产品统计的严重程度为2级的Bug数\n公式：\n按产品统计的严重程度为1、2级的Bug数=按产品统计的严重程度为1级的Bug数+按产品统计的严重程度为2级的Bug数', 'realtime', '', '', '', '', NULL, '', NULL, '0', '0');
INSERT INTO `zt_metric`(`purpose`, `scope`, `object`, `name`, `code`, `unit`, `definition`, `desc`, `when`, `event`, `cronCFG`, `time`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `order`, `deleted`) VALUES ('scale', 'prod', 'Bug', '按产品统计的年度新增Bug数', 'count_of_annual_created_bug_in_product', '个', '产品年度新增Bug数', '产品中创建时间为某年的Bug的个数求和\n过滤已删除的Bug\n过滤已删除的产品', 'realtime', '', '', '', '', NULL, '', NULL, '0', '0');
INSERT INTO `zt_metric`(`purpose`, `scope`, `object`, `name`, `code`, `unit`, `definition`, `desc`, `when`, `event`, `cronCFG`, `time`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `order`, `deleted`) VALUES ('scale', 'prod', 'Bug', '按产品统计的年度新增有效Bug数', 'count_of_annual_created_effective_bug_in_product', '个', '产品年度新增有效Bug数', '产品中创建时间为某年的解决方案为已解决和延期处理的或者状态为激活的Bug的个数求和\n过滤已删除的Bug\n过滤已删除的产品', 'realtime', '', '', '', '', NULL, '', NULL, '0', '0');
INSERT INTO `zt_metric`(`purpose`, `scope`, `object`, `name`, `code`, `unit`, `definition`, `desc`, `when`, `event`, `cronCFG`, `time`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `order`, `deleted`) VALUES ('scale', 'prod', 'Bug', '按产品统计的年度修复Bug数', 'count_of_annual_restored_bug_in_product', '个', '产品年度修复Bug数', '产品中关闭时间为某年且解决方案为已解决的Bug的个数求和\n过滤已删除的Bug\n过滤已删除的产品', 'realtime', '', '', '', '', NULL, '', NULL, '0', '0');
INSERT INTO `zt_metric`(`purpose`, `scope`, `object`, `name`, `code`, `unit`, `definition`, `desc`, `when`, `event`, `cronCFG`, `time`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `order`, `deleted`) VALUES ('scale', 'prod', 'Bug', '按产品统计的每日新增Bug数', 'count_of_daily_created_bug_in_product', '个', '产品每日新增Bug数', '产品中每日创建的Bug数求和\n过滤已删除的Bug\n过滤已删除的产品', 'realtime', '', '', '', '', NULL, '', NULL, '0', '0');
INSERT INTO `zt_metric`(`purpose`, `scope`, `object`, `name`, `code`, `unit`, `definition`, `desc`, `when`, `event`, `cronCFG`, `time`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `order`, `deleted`) VALUES ('scale', 'prod', 'Bug', '按产品统计的每日解决Bug数', 'count_of_daily_resolved_bug_in_product', '个', '产品每日解决Bug数', '产品中每日解决的Bug数求和\n过滤已删除的Bug\n过滤已删除的产品', 'realtime', '', '', '', '', NULL, '', NULL, '0', '0');
INSERT INTO `zt_metric`(`purpose`, `scope`, `object`, `name`, `code`, `unit`, `definition`, `desc`, `when`, `event`, `cronCFG`, `time`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `order`, `deleted`) VALUES ('scale', 'prod', 'Bug', '按产品统计的每日关闭Bug数', 'count_of_daily_closed_bug_in_product', '个', '产品每日关闭Bug数', '产品中每日关闭的Bug数求和\n过滤已删除的Bug\n过滤已删除的产品', 'realtime', '', '', '', '', NULL, '', NULL, '0', '0');
INSERT INTO `zt_metric`(`purpose`, `scope`, `object`, `name`, `code`, `unit`, `definition`, `desc`, `when`, `event`, `cronCFG`, `time`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `order`, `deleted`) VALUES ('scale', 'prod', 'Bug', '按产品统计的Bug修复率', 'rate_of_restored_bug_in_product', '%', '产品中修复的Bug数相对于产品有效Bug数的比例', '复用：\n按产品统计的修复Bug数\n按产品统计的有效Bug数\n公式：\n按产品统计的Bug修复率=按产品统计的修复Bug数/按产品统计的有效Bug数', 'realtime', '', '', '', '', NULL, '', NULL, '0', '0');
INSERT INTO `zt_metric`(`purpose`, `scope`, `object`, `name`, `code`, `unit`, `definition`, `desc`, `when`, `event`, `cronCFG`, `time`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `order`, `deleted`) VALUES ('scale', 'prod', 'testcase', '按产品统计的用例总数', 'count_of_case_in_product', '个', '产品的用例总数', '产品中用例的个数求和\n过滤已删除的用例\n过滤已删除的产品', 'realtime', '', '', '', '', NULL, '', NULL, '0', '0');
INSERT INTO `zt_metric`(`purpose`, `scope`, `object`, `name`, `code`, `unit`, `definition`, `desc`, `when`, `event`, `cronCFG`, `time`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `order`, `deleted`) VALUES ('scale', 'prod', 'testcase', '按产品统计的年度新增用例数', 'count_of_annual_created_case_in_product', '个', '产品年度新增用例数', '产品中创建时间为某年的用例的个数求和\n过滤已删除的用例\n过滤已删除的产品', 'realtime', '', '', '', '', NULL, '', NULL, '0', '0');
