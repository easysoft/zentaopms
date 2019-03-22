ALTER TABLE `zt_case` ADD `fromCaseVersion` mediumint(8) unsigned NOT NULL AFTER `fromCaseID`;
ALTER TABLE `zt_case` ADD `ignoreCaseVersion` mediumint(8) unsigned NOT NULL AFTER `fromCaseVersion`;
