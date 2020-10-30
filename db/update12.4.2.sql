update zt_story set `plan`='' where `plan`=0;

ALTER TABLE `zt_compile` ADD `times` tinyint unsigned NOT NULL DEFAULT '0' AFTER `tag`;
CREATE TABLE IF NOT EXISTS `zt_planstory` (
  `plan` mediumint(8) unsigned NOT NULL,
  `story` mediumint(8) unsigned NOT NULL,
  `order` mediumint(9) NOT NULL,
  UNIQUE KEY `plan_story` (`plan`,`story`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
ALTER TABLE `zt_case` change `fromCaseVersion` `fromCaseVersion` mediumint(8) unsigned NOT NULL default '1' AFTER `fromCaseID`;
