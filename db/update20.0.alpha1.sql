DELETE FROM `zt_block` WHERE `type` IN ('news', 'patch', 'plugin', 'puglicclass');

ALTER TABLE `zt_block` ADD `dashboard` varchar(20) NOT NULL DEFAULT '' AFTER `account`;
ALTER TABLE `zt_block` CHANGE `block` `code` varchar(30) NOT NULL DEFAULT '' AFTER `module`;
ALTER TABLE `zt_block` ADD `width` enum ('1', '2', '3') NOT NULL DEFAULT '1' AFTER `code`;
ALTER TABLE `zt_block` MODIFY `height` smallint(5) UNSIGNED NOT NULL DEFAULT 3 AFTER `width`;
ALTER TABLE `zt_block` ADD `left` enum('0', '1', '2') NOT NULL DEFAULT '0' AFTER `height`;
ALTER TABLE `zt_block` ADD `top` smallint(5) UNSIGNED NOT NULL DEFAULT 0 AFTER `left`;
ALTER TABLE `zt_block` MODIFY `vision` varchar(10) NOT NULL DEFAULT 'rnd' AFTER `hidden`;

DROP INDEX account_vision_module_type_order ON `zt_block`;
UPDATE `zt_block` SET `dashboard` = CONCAT(`module`, `type`);
UPDATE `zt_block` SET `module` = IF(`source` != '', `source`, `code`);
UPDATE `zt_block` SET `width` = IF(`grid` > 4, '2', '1');
UPDATE `zt_block` SET `params` = '{"count":"20"}' WHERE `module` = 'assigntome' AND `code` = 'assigntome';

ALTER TABLE `zt_block` DROP COLUMN `source`;
ALTER TABLE `zt_block` DROP COLUMN `type`;
ALTER TABLE `zt_block` DROP COLUMN `grid`;
ALTER TABLE `zt_block` DROP COLUMN `order`;

DELETE FROM `zt_block` where `module` = 'todo' and `code` = 'list';
DELETE FROM `zt_block` where `module` = 'contribute' and `code` = 'contribute';
DELETE FROM `zt_block` where `module` = 'project' and `code` = 'projectteam';
DELETE FROM `zt_block` where `module` = 'execution' and `code` = 'execution';

ALTER TABLE `zt_todo`  CHANGE `idvalue` `objectID` mediumint(8) unsigned default '0' NOT NULL AFTER `type`;
ALTER TABLE `zt_todo` CHANGE `config` `config` VARCHAR(1000) NOT NULL  DEFAULT '';

ALTER TABLE `zt_project` ADD `stageBy` enum('project', 'product') NOT NULL DEFAULT 'project' AFTER `division`;
UPDATE `zt_project` SET `stageBy` = 'project' WHERE `division` = '0';
UPDATE `zt_project` SET `stageBy` = 'product' WHERE `division` = '1';
ALTER TABLE `zt_project` DROP `division`;
ALTER TABLE `zt_project` CHANGE `minColWidth` `minColWidth` smallint NOT NULL DEFAULT '264' AFTER `colWidth`;

ALTER TABLE `zt_bug` CHANGE `linkBug` `relatedBug` varchar(255) NOT NULL DEFAULT '';

ALTER TABLE `zt_product` ADD COLUMN `groups` text NULL AFTER `acl`;

ALTER TABLE `zt_usercontact` ADD `public` tinyint(1) NOT NULL DEFAULT 0;
UPDATE `zt_usercontact` AS t1, `zt_config` AS t2 SET t1.public = 1 WHERE t2.module = 'my' AND t2.section = 'global' AND t2.key = 'globalContacts' AND FIND_IN_SET(t1.id, t2.value); -- Change it for compatible with dameng.
DELETE FROM `zt_config` WHERE `module` = 'my' AND `section` = 'global' AND `key` = 'globalContacts';

ALTER TABLE `zt_testtask` ADD `realBegan` date NULL AFTER `end`;

UPDATE `zt_config` SET `module` = 'bug', `section` = 'browse' WHERE `module` = 'datatable' AND `section` = 'bugBrowse' AND `key` = 'showModule';
UPDATE `zt_config` SET `module` = 'caselib', `section` = 'browse' WHERE `module` = 'datatable' AND `section` = 'caselibBrowse' AND `key` = 'showModule';
UPDATE `zt_config` SET `module` = 'execution', `section` = 'bug' WHERE `module` = 'datatable' AND `section` = 'executionBug' AND `key` = 'showModule';
UPDATE `zt_config` SET `module` = 'execution', `section` = 'story' WHERE `module` = 'datatable' AND `section` = 'executionStory' AND `key` = 'showModule';
UPDATE `zt_config` SET `module` = 'execution', `section` = 'task' WHERE `module` = 'datatable' AND `section` = 'executionTask' AND `key` = 'showModule';
UPDATE `zt_config` SET `module` = 'feedback', `section` = 'admin' WHERE `module` = 'datatable' AND `section` = 'feedbackAdmin' AND `key` = 'showModule';
UPDATE `zt_config` SET `module` = 'product', `section` = 'browse' WHERE `module` = 'datatable' AND `section` = 'productBrowse' AND `key` = 'showModule';
UPDATE `zt_config` SET `module` = 'project', `section` = 'bug' WHERE `module` = 'datatable' AND `section` = 'projectBug' AND `key` = 'showModule';
UPDATE `zt_config` SET `module` = 'testcase', `section` = 'browse' WHERE `module` = 'datatable' AND `section` = 'testcaseBrowse' AND `key` = 'showModule';

INSERT IGNORE INTO `zt_config` (`owner`, `module`, `key`, `value`) VALUES ('system', 'execution', 'defaultWorkhours', '7');

CREATE TABLE IF NOT EXISTS `zt_session` (
    `id` varchar(32) NOT NULL,
    `data` mediumtext,
    `timestamp` int(10) unsigned DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `zt_action` MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
ALTER TABLE `zt_actionrecent` MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;

ALTER TABLE `zt_history`
CHANGE `old` `old` text NULL AFTER `field`,
CHANGE `new` `new` text NULL AFTER `old`,
CHANGE `diff` `diff` mediumtext NULL AFTER `new`;
