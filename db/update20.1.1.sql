REPLACE INTO `zt_lang` (`lang`, `module`, `section`, `key`, `value`, `system`, `vision`) VALUES
('zh-cn', 'custom', 'URSRList', '1', '{\"ERName\":\"\\u4e1a\\u52a1\\u9700\\u6c42\",\"SRName\":\"\\u8f6f\\u4ef6\\u9700\\u6c42\",\"URName\":\"\\u7528\\u6237\\u9700\\u6c42\"}', '0', 'rnd'),
('zh-cn', 'custom', 'URSRList', '2', '{\"ERName\":\"\\u4e1a\\u52a1\\u9700\\u6c42\",\"SRName\":\"\\u7814\\u53d1\\u9700\\u6c42\",\"URName\":\"\\u7528\\u6237\\u9700\\u6c42\"}', '0', 'rnd'),
('zh-cn', 'custom', 'URSRList', '3', '{\"ERName\":\"\\u4e1a\\u9700\",\"SRName\":\"\\u8f6f\\u9700\",\"URName\":\"\\u7528\\u9700\"}', '0', 'rnd'),
('zh-cn', 'custom', 'URSRList', '4', '{\"ERName\":\"\\u53f2\\u8bd7\",\"SRName\":\"\\u6545\\u4e8b\",\"URName\":\"\\u7279\\u6027\"}', '0', 'rnd'),
('zh-cn', 'custom', 'URSRList', '5', '{\"ERName\":\"\\u4e1a\\u52a1\\u9700\\u6c42\",\"SRName\":\"\\u9700\\u6c42\",\"URName\":\"\\u7528\\u6237\\u9700\\u6c42\"}', '0', 'rnd'),
('zh-cn', 'custom', 'URSRList', '6', '{\"ERName\":\"Epic\",\"SRName\":\"Story\",\"URName\":\"Feature\"}',     '0', 'rnd'),
('en',    'custom', 'URSRList', '1', '{\"ERName\":\"Epic\",\"SRName\":\"Story\",\"URName\":\"Feature\"}',     '0', 'rnd'),
('en',    'custom', 'URSRList', '2', '{\"ERName\":\"Epic\",\"SRName\":\"Story\",\"URName\":\"Requirement\"}', '0', 'rnd'),
('en',    'custom', 'URSRList', '3', '{\"ERName\":\"Epic\",\"SRName\":\"Story\",\"URName\":\"Feature\"}',     '0', 'rnd'),
('fr',    'custom', 'URSRList', '1', '{\"ERName\":\"Epic\",\"SRName\":\"Story\",\"URName\":\"Feature\"}',     '0', 'rnd'),
('fr',    'custom', 'URSRList', '2', '{\"ERName\":\"Epic\",\"SRName\":\"Story\",\"URName\":\"Requirement\"}', '0', 'rnd'),
('fr',    'custom', 'URSRList', '3', '{\"ERName\":\"Epic\",\"SRName\":\"Story\",\"URName\":\"Feature\"}',     '0', 'rnd'),
('de',    'custom', 'URSRList', '1', '{\"ERName\":\"Epic\",\"SRName\":\"Story\",\"URName\":\"Feature\"}',     '0', 'rnd'),
('de',    'custom', 'URSRList', '2', '{\"ERName\":\"Epic\",\"SRName\":\"Story\",\"URName\":\"Requirement\"}', '0', 'rnd'),
('de',    'custom', 'URSRList', '3', '{\"ERName\":\"Epic\",\"SRName\":\"Story\",\"URName\":\"Feature\"}',     '0', 'rnd'),
('zh-tw', 'custom', 'URSRList', '1', '{\"ERName\":\"\\u696d\\u52d9\\u9700\\u6c42\",\"SRName\":\"\\u8edf\\u4ef6\\u9700\\u6c42\",\"URName\":\"\\u7528\\u6236\\u9700\\u6c42\"}', '0', 'rnd'),
('zh-tw', 'custom', 'URSRList', '2', '{\"ERName\":\"\\u696d\\u52d9\\u9700\\u6c42\",\"SRName\":\"\\u7814\\u767c\\u9700\\u6c42\",\"URName\":\"\\u7528\\u6236\\u9700\\u6c42\"}', '0', 'rnd'),
('zh-tw', 'custom', 'URSRList', '3', '{\"ERName\":\"\\u696d\\u9700\",\"SRName\":\"\\u8edf\\u9700\",\"URName\":\"\\u7528\\u9700\"}', '0', 'rnd'),
('zh-tw', 'custom', 'URSRList', '4', '{\"ERName\":\"\\u53f2\\u8a69\",\"SRName\":\"\\u6545\\u4e8b\",\"URName\":\"\\u7279\\u6027\"}', '0', 'rnd'),
('zh-tw', 'custom', 'URSRList', '5', '{\"ERName\":\"\\u696d\\u52d9\\u9700\\u6c42\",\"SRName\":\"\\u9700\\u6c42\",\"URName\":\"\\u7528\\u6236\\u9700\\u6c42\"}', '0', 'rnd'),
('zh-tw', 'custom', 'URSRList', '6', '{\"ERName\":\"Epic\",\"SRName\":\"Story\",\"URName\":\"Feature\"}',     '0', 'rnd');

CREATE TABLE `zt_storygrade` (
  `type` enum('story','requirement','epic') NOT NULL,
  `grade` smallint NOT NULL,
  `name` char(30) NOT NULL,
  `status` char(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `zt_storygrade` (`type`, `grade`, `name`, `status`) VALUES
('requirement', 1,    'UR', 'enable'),
('epic',        1,    'BR', 'enable'),
('story',       1,    'SR', 'enable'),
('story',       2,    '子', 'enable');

ALTER TABLE `zt_story` ADD `isParent` enum('0','1') NOT NULL DEFAULT '0' AFTER `parent`;
ALTER TABLE `zt_story` ADD `root` mediumint NOT NULL DEFAULT '0' AFTER `isParent`;
ALTER TABLE `zt_story` ADD `path` text NULL AFTER `root`;
ALTER TABLE `zt_story` ADD `grade` smallint(6) NOT NULL DEFAULT '0' AFTER `path`;
ALTER TABLE `zt_story` ADD `parentVersion` smallint NOT NULL DEFAULT '0' AFTER `version`;
ALTER TABLE `zt_story` CHANGE `stage` `stage` enum('','wait','defining','planning','planned','projected','designing','designed','developing','developed','testing','tested','verified','rejected','delivering','pending','released','closed') NOT NULL DEFAULT 'wait';
ALTER TABLE `zt_story` DROP `childStories`;
CREATE INDEX `root` ON `zt_story` (`root`);
UPDATE `zt_story` SET stage = 'defining'   WHERE stage = 'wait' AND (parent = -1 OR type != 'story');
UPDATE `zt_story` SET stage = 'planning'   WHERE stage in ('planned', 'projected') AND (parent = -1 OR type != 'story');
UPDATE `zt_story` SET stage = 'developing' WHERE stage in ('developed', 'testing', 'tested') AND (parent = -1 OR type != 'story');
UPDATE `zt_story` SET stage = 'delivering' WHERE stage in ('verified', 'released') AND (parent = -1 OR type != 'story');
UPDATE `zt_story` SET isParent = '1' WHERE parent = -1;
UPDATE `zt_story` SET grade = 1, parent = 0, root = id, path = concat(',', id, ',') WHERE type != 'story';
UPDATE `zt_story` SET grade = 1, parent = 0, root = id, path = concat(',', id, ',') WHERE type = 'story' AND parent <= 0;
UPDATE `zt_story` SET grade = 2, root = parent, path = concat(',', parent, ',', id, ',') WHERE type = 'story' AND parent > 0;

INSERT INTO `zt_config` (`owner`, `module`, `section`, `key`, `value`) VALUES ('system', 'story', '', 'gradeRule', 'stepwise');
INSERT INTO `zt_config` (`owner`, `module`, `section`, `key`, `value`) VALUES ('system', 'requirement', '', 'gradeRule', 'stepwise');
INSERT INTO `zt_config` (`owner`, `module`, `section`, `key`, `value`) VALUES ('system', 'epic', '', 'gradeRule', 'stepwise');
INSERT INTO `zt_config` (`owner`, `module`, `section`, `key`, `value`) VALUES ('system', 'custom', '', 'enableER', '1');

ALTER TABLE `zt_product`
ADD `draftRequirements` mediumint(8) NOT NULL DEFAULT '0' AFTER `reviewer`,
ADD `activeRequirements` mediumint(8) NOT NULL DEFAULT '0' AFTER `draftRequirements`,
ADD `changingRequirements` mediumint(8) NOT NULL DEFAULT '0' AFTER `activeRequirements`,
ADD `reviewingRequirements` mediumint(8) NOT NULL DEFAULT '0' AFTER `changingRequirements`,
ADD `finishedRequirements` mediumint NOT NULL DEFAULT '0' AFTER `reviewingRequirements`,
ADD `closedRequirements` mediumint(8) NOT NULL DEFAULT '0' AFTER `finishedRequirements`,
ADD `totalRequirements` mediumint(8) NOT NULL DEFAULT '0' AFTER `closedRequirements`;

ALTER TABLE `zt_product`
ADD `draftEpics` mediumint(8) NOT NULL DEFAULT '0' AFTER `reviewer`,
ADD `activeEpics` mediumint(8) NOT NULL DEFAULT '0' AFTER `draftEpics`,
ADD `changingEpics` mediumint(8) NOT NULL DEFAULT '0' AFTER `activeEpics`,
ADD `reviewingEpics` mediumint(8) NOT NULL DEFAULT '0' AFTER `changingEpics`,
ADD `finishedEpics` mediumint NOT NULL DEFAULT '0' AFTER `reviewingEpics`,
ADD `closedEpics` mediumint(8) NOT NULL DEFAULT '0' AFTER `finishedEpics`,
ADD `totalEpics` mediumint(8) NOT NULL DEFAULT '0' AFTER `closedEpics`;

ALTER TABLE `zt_project` ADD `storyType` char(30) NULL DEFAULT 'story' AFTER `auth`;
UPDATE `zt_project` SET storyType = 'story' WHERE storyType = '';

DROP VIEW IF EXISTS `view_datasource_2`;
DROP VIEW IF EXISTS `view_datasource_3`;
DROP VIEW IF EXISTS `view_datasource_4`;

CREATE VIEW `view_datasource_2` AS select `id`,`title` from `zt_story` where `deleted` = '0' and type = 'epic';
CREATE VIEW `view_datasource_3` AS select `id`,`title` from `zt_story` where `deleted` = '0' and type = 'requirement';
CREATE VIEW `view_datasource_4` AS select `id`,`title` from `zt_story` where `deleted` = '0' and type = 'story';

INSERT INTO `zt_workflowdatasource` (`type`, `name`, `code`, `buildin`, `vision`, `createdBy`, `createdDate`, `datasource`, `view`, `keyField`, `valueField`) VALUES
('sql', '用户需求', 'requirements', '1', 'rnd', 'admin', '1970-01-01 00:00:01', 'select id,title from zt_story where deleted=\"0\" and type=\"requirement\"',    'view_datasource_3',    'id',   'title'),
('sql', '业务需求', 'epics',        '1', 'rnd', 'admin', '1970-01-01 00:00:01', 'select id,title from zt_story where deleted=\"0\" type=\"epic\"',    'view_datasource_2',    'id',   'title');

UPDATE `zt_workflowdatasource` SET `datasource` = 'select id,title from zt_story where deleted=\"0\" and type=\"story\"' WHERE `code` = 'stories';
