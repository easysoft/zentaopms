UPDATE `zt_block` SET `source`='execution' WHERE `source`='project' and `block`='overview';

CREATE TABLE IF NOT EXISTS `zt_storyestimate` (
  `story` mediumint(9) NOT NULL,
  `round` smallint(6) NOT NULL,
  `estimate` text NOT NULL,
  `average` float NOT NULL,
  `openedBy` varchar(30) NOT NULL,
  `openedDate` datetime NOT NULL,
  UNIQUE KEY `story` (`story`,`round`)
) ENGINE=MyISAM;
CREATE TABLE IF NOT EXISTS `zt_storyreview` (
  `story` mediumint(9) NOT NULL,
  `version` smallint(6) NOT NULL,
  `reviewer` varchar(30) NOT NULL,
  `result` varchar(30) NOT NULL,
  `reviewDate` datetime NOT NULL,
  UNIQUE KEY `story` (`story`,`version`,`reviewer`)
) ENGINE=MyISAM;

ALTER TABLE `zt_story` MODIFY COLUMN `reviewedDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00';
REPLACE INTO `zt_config` (`owner`, `module`, `section`, `key`, `value`) VALUES ('system', 'story', '', 'reviewRules', 'allpass');

ALTER TABLE `zt_jenkins` ADD COLUMN `type` char(30) NOT NULL AFTER `id`;
ALTER TABLE `zt_jenkins` ADD COLUMN `private` char(32) NOT NULL AFTER `token`;
UPDATE `zt_jenkins` SET `type`='jenkins';
RENAME TABLE `zt_jenkins` TO `zt_pipeline`;

ALTER TABLE `zt_relation` DROP INDEX `relation`;
ALTER TABLE `zt_relation` ADD UNIQUE INDEX `relation`(`product`, `relation`, `AType`, `BType`, `AID`, `BID`);
