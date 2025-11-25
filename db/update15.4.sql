UPDATE `zt_action` SET `action` = 'reviewpassed' WHERE `action` = 'passreviewed';
UPDATE `zt_action` SET `action` = 'reviewrejected' WHERE `action` = 'reviewclosed';
UPDATE `zt_action` SET `action` = 'reviewclarified' WHERE `action` = 'clarifyreviewed';
DELETE FROM `zt_config` WHERE `key` = 'skipYoungBlueTheme' and `section` = 'global';
UPDATE `zt_config` SET `key` = 'skipYoungBlueTheme' WHERE `key` = 'skipThemeGuide' and `section` = 'global';
DELETE FROM `zt_score` WHERE `module` = 'tutorial' AND `method` = 'finish';
CREATE TABLE `zt_mr` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `gitlabID` mediumint(8) unsigned NOT NULL,
  `sourceProject` int unsigned NOT NULL,
  `sourceBranch` varchar(100) NOT NULL,
  `targetProject` int unsigned NOT NULL,
  `targetBranch` varchar(100) NOT NULL,
  `mriid` int unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `assignee` varchar(255) NOT NULL,
  `reviewer` varchar(255) NOT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `status` char(30) NOT NULL,
  `mergeStatus` char(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;
INSERT INTO `zt_cron` (`m`, `h`, `dom`, `mon`, `dow`, `command`, `remark`, `type`, `buildin`, `status`, `lastTime`) VALUES ('*/5', '*', '*', '*', '*', 'moduleName=mr&methodName=syncMR', '定时同步GitLab合并数据到禅道数据库', 'zentao', 1, 'normal', '0000-00-00 00:00:00');
