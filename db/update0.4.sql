-- 20100128 修改user表中ip字段的默认值，解决install失败的问题。
ALTER TABLE `zt_user` CHANGE `ip` `ip` CHAR( 15 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''
