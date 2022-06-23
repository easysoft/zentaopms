ALTER TABLE `zt_user` ADD `resetToken` varchar(50) NOT NULL AFTER `scoreLevel`;
CREATE TABLE `zt_riskissue` (
  `risk` mediumint(8) unsigned NOT NULL,
  `issue` mediumint(8) unsigned NOT NULL,
  UNIQUE KEY `risk_issue` (`risk`,`issue`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `zt_kanban`
ADD `showWIP` enum('0','1') NOT NULL DEFAULT '1' AFTER `displayCards`;
