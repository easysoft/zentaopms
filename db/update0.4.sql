-- 20100128 修改user表中ip字段的默认值，解决install失败的问题。
ALTER TABLE `zt_user` CHANGE `ip` `ip` CHAR( 15 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '';

-- 20100128: 调整casestep表。
ALTER TABLE `zt_caseStep` CHANGE `caseVersion` `version` SMALLINT( 3 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `zt_caseStep` CHANGE `step` `desc` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '';
ALTER TABLE `zt_caseStep` CHANGE `expect` `expect` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '';
ALTER TABLE `zt_caseStep` ADD INDEX ( `case` , `version` ) ;

-- 20100128 转换case中的step字段
update zt_case set version = 1 where version = 0;
insert into zt_caseStep select '', id,version,steps, '' from zt_case;
ALTER TABLE `zt_case` DROP `steps`;
