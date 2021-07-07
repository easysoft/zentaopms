UPDATE `zt_block` SET `source`='execution' WHERE `source`='project' and `block`='overview';

ALTER TABLE `zt_story` MODIFY COLUMN `reviewedDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00';
REPLACE INTO `zt_config` (`owner`, `module`, `section`, `key`, `value`) VALUES ('system', 'story', '', 'reviewRules', 'allpass');
ALTER TABLE `zt_storyestimate` MODIFY COLUMN `average` float NOT NULL;
