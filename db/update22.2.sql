ALTER TABLE `zt_testrun` ADD COLUMN `caseVersion` smallint unsigned NOT NULL DEFAULT 0 COMMENT '用例版本' AFTER `case`;
ALTER TABLE `zt_suitecase` ADD COLUMN `caseVersion` smallint unsigned NOT NULL DEFAULT 0 COMMENT '用例版本' AFTER `case`;

ALTER TABLE `zt_object` ADD COLUMN `visible` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '甘特图中版本是否可见，默认0不可见' AFTER `enabled`;
