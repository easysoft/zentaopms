ALTER TABLE `zt_bug` ADD `activatedDate` datetime NOT NULL AFTER `activatedCount`;
ALTER TABLE `zt_team` ADD `limitedUser` varchar(8) NOT NULL default 'no' AFTER `role`;
