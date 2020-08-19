update zt_story set `plan`='' where `plan`=0;

ALTER TABLE `zt_compile` ADD `times` tinyint unsigned NOT NULL DEFAULT '0' AFTER `tag`;
