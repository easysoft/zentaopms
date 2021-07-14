UPDATE `zt_block` SET `source`='execution' WHERE `source`='project' and `block`='overview';

ALTER TABLE `zt_story` MODIFY COLUMN `reviewedDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00';
REPLACE INTO `zt_config` (`owner`, `module`, `section`, `key`, `value`) VALUES ('system', 'story', '', 'reviewRules', 'allpass');
ALTER TABLE `zt_storyestimate` MODIFY COLUMN `average` float NOT NULL;

ALTER TABLE `zt_jenkins` ADD COLUMN `type` char(30) NOT NULL AFTER `id`;
ALTER TABLE `zt_jenkins` ADD COLUMN `private` char(32) NOT NULL AFTER `token`;
RENAME TABLE `zt_jenkins` TO `zt_pipeline`;

ALTER TABLE `zt_relation` DROP INDEX `relation`, ADD UNIQUE INDEX `relation`(`product`, `relation`, `AType`, `BType`, `AID`, `BID`) USING BTREE;

