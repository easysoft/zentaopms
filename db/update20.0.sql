REPLACE INTO `zt_lang` (`lang`, `module`, `section`, `key`, `value`, `system`, `vision`) VALUES
('zh-cn', 'custom', 'URSRList', '1', '{\"ERName\":\"\\u4e1a\\u52a1\\u9700\\u6c42\",\"SRName\":\"\\u8f6f\\u4ef6\\u9700\\u6c42\",\"URName\":\"\\u7528\\u6237\\u9700\\u6c42\"}', '0', 'rnd'),
('zh-cn', 'custom', 'URSRList', '2', '{\"ERName\":\"\\u4e1a\\u52a1\\u9700\\u6c42\",\"SRName\":\"\\u7814\\u53d1\\u9700\\u6c42\",\"URName\":\"\\u7528\\u6237\\u9700\\u6c42\"}', '0', 'rnd'),
('zh-cn', 'custom', 'URSRList', '3', '{\"ERName\":\"\\u4e1a\\u9700\",\"SRName\":\"\\u8f6f\\u9700\",\"URName\":\"\\u7528\\u9700\"}', '0', 'rnd'),
('zh-cn', 'custom', 'URSRList', '4', '{\"ERName\":\"\\u53f2\\u8bd7\",\"SRName\":\"\\u6545\\u4e8b\",\"URName\":\"\\u7279\\u6027\"}', '0', 'rnd'),
('zh-cn', 'custom', 'URSRList', '5', '{\"ERName\":\"\\u4e1a\\u52a1\\u9700\\u6c42\",\"SRName\":\"\\u9700\\u6c42\",\"URName\":\"\\u7528\\u6237\\u9700\\u6c42\"}', '0', 'rnd'),
('en',    'custom', 'URSRList', '1', '{\"ERName\":\"Epic\",\"SRName\":\"Story\",\"URName\":\"Feature\"}',     '0', 'rnd'),
('en',    'custom', 'URSRList', '2', '{\"ERName\":\"Epic\",\"SRName\":\"Story\",\"URName\":\"Requirement\"}', '0', 'rnd'),
('fr',    'custom', 'URSRList', '1', '{\"ERName\":\"Epic\",\"SRName\":\"Story\",\"URName\":\"Feature\"}',     '0', 'rnd'),
('fr',    'custom', 'URSRList', '2', '{\"ERName\":\"Epic\",\"SRName\":\"Story\",\"URName\":\"Requirement\"}', '0', 'rnd'),
('de',    'custom', 'URSRList', '1', '{\"ERName\":\"Epic\",\"SRName\":\"Story\",\"URName\":\"Feature\"}',     '0', 'rnd'),
('de',    'custom', 'URSRList', '2', '{\"ERName\":\"Epic\",\"SRName\":\"Story\",\"URName\":\"Requirement\"}', '0', 'rnd'),
('zh-tw', 'custom', 'URSRList', '1', '{\"ERName\":\"\\u696d\\u52d9\\u9700\\u6c42\",\"SRName\":\"\\u8edf\\u4ef6\\u9700\\u6c42\",\"URName\":\"\\u7528\\u6236\\u9700\\u6c42\"}', '0', 'rnd'),
('zh-tw', 'custom', 'URSRList', '2', '{\"ERName\":\"\\u696d\\u52d9\\u9700\\u6c42\",\"SRName\":\"\\u7814\\u767c\\u9700\\u6c42\",\"URName\":\"\\u7528\\u6236\\u9700\\u6c42\"}', '0', 'rnd'),
('zh-tw', 'custom', 'URSRList', '3', '{\"ERName\":\"\\u696d\\u9700\",\"SRName\":\"\\u8edf\\u9700\",\"URName\":\"\\u7528\\u9700\"}', '0', 'rnd'),
('zh-tw', 'custom', 'URSRList', '4', '{\"ERName\":\"\\u53f2\\u8a69\",\"SRName\":\"\\u6545\\u4e8b\",\"URName\":\"\\u7279\\u6027\"}', '0', 'rnd'),
('zh-tw', 'custom', 'URSRList', '5', '{\"ERName\":\"\\u696d\\u52d9\\u9700\\u6c42\",\"SRName\":\"\\u9700\\u6c42\",\"URName\":\"\\u7528\\u6236\\u9700\\u6c42\"}', '0', 'rnd');

CREATE TABLE `zt_storygrade` (
  `type` enum('story','requirement','epic') NOT NULL,
  `grade` smallint NOT NULL,
  `name` char(30) NOT NULL,
  `status` char(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `zt_story` ADD `isParent` enum('0','1') NOT NULL DEFAULT '0' AFTER `parent`;
ALTER TABLE `zt_story` ADD `root` mediumint NOT NULL DEFAULT '0' AFTER `isParent`;
ALTER TABLE `zt_story` ADD `path` text NULL AFTER `root`;
ALTER TABLE `zt_story` ADD `grade` smallint(6) NOT NULL AFTER `path`;
ALTER TABLE `zt_story` ADD `parentVersion` smallint NOT NULL DEFAULT '0' AFTER `version`;
ALTER TABLE `zt_story` CHANGE `stage` `stage` enum('','wait','defining','planning','planned','projected','designing','designed','developing','developed','testing','tested','verified','rejected','delivering','pending','released','closed') NOT NULL DEFAULT 'wait';
ALTER TABLE `zt_story` DROP `childStories`;
UPDATE `zt_story` SET isParent = 1 WHERE parent = -1;
UPDATE `zt_story` SET grade = 1, parent = 0, root = id, path = concat(',', id, ',') WHERE type != 'story';
UPDATE `zt_story` SET grade = 1, parent = 0, root = id, path = concat(',', id, ',') WHERE type = 'story' AND parent <= 0;
UPDATE `zt_story` SET grade = 2, root = parent, path = concat(',', parent, ',', id, ',') WHERE type = 'story' AND parent > 0;

INSERT INTO `zt_config` (`owner`, `module`, `section`, `key`, `value`) VALUES ('system', 'story', '', 'gradeRule', 'stepwise');
INSERT INTO `zt_config` (`owner`, `module`, `section`, `key`, `value`) VALUES ('system', 'requirement', '', 'gradeRule', 'stepwise');
INSERT INTO `zt_config` (`owner`, `module`, `section`, `key`, `value`) VALUES ('system', 'epic', '', 'gradeRule', 'stepwise');
INSERT INTO `zt_config` (`owner`, `module`, `section`, `key`, `value`) VALUES ('system', 'custom', '', 'enableER', '0');

UPDATE `zt_config` SET `value` = CONCAT(`value`, ',productER') WHERE `key` = 'closedFeatures' AND module = 'common';
