-- DevOps独立版初始化 SQL。
-- GitFox独立版的默认权限分组。;
INSERT INTO `zt_group` (`vision`, `name`, `role`, `desc`) VALUES ('gitfox', 'ADMIN', 'admin', 'for administrator');
INSERT INTO `zt_group` (`vision`, `name`, `role`, `desc`) VALUES ('gitfox', 'DEVOPSADMIN', 'DEVOPSADMIN', '');
INSERT INTO `zt_group` (`vision`, `name`, `role`, `desc`) VALUES ('gitfox', 'DEVOPSINSPECTOR', 'DEVOPSINSPECTOR', '');
INSERT INTO `zt_group` (`vision`, `name`, `role`, `desc`) VALUES ('gitfox', 'DEVOPSUSER', 'DEVOPSUSER', '');

-- GitFox独立版默认权限分组的权限，与研发界面下的同名分组保持一致。;
INSERT INTO `zt_grouppriv` (`group`, `module`, `method`) SELECT (SELECT `id` FROM `zt_group` WHERE `vision` = 'gitfox' AND `role` = 'admin' LIMIT 1), `module`, `method` FROM `zt_grouppriv` WHERE `group` = (SELECT `id` FROM `zt_group` WHERE `vision` = 'rnd' AND `role` = 'admin' LIMIT 1);
INSERT INTO `zt_grouppriv` (`group`, `module`, `method`) SELECT (SELECT `id` FROM `zt_group` WHERE `vision` = 'gitfox' AND `role` = 'DEVOPSADMIN' LIMIT 1), `module`, `method` FROM `zt_grouppriv` WHERE `group` = (SELECT `id` FROM `zt_group` WHERE `vision` = 'rnd' AND `role` = 'DEVOPSADMIN' LIMIT 1);
INSERT INTO `zt_grouppriv` (`group`, `module`, `method`) SELECT (SELECT `id` FROM `zt_group` WHERE `vision` = 'gitfox' AND `role` = 'DEVOPSINSPECTOR' LIMIT 1), `module`, `method` FROM `zt_grouppriv` WHERE `group` = (SELECT `id` FROM `zt_group` WHERE `vision` = 'rnd' AND `role` = 'DEVOPSINSPECTOR' LIMIT 1);
INSERT INTO `zt_grouppriv` (`group`, `module`, `method`) SELECT (SELECT `id` FROM `zt_group` WHERE `vision` = 'gitfox' AND `role` = 'DEVOPSUSER' LIMIT 1), `module`, `method` FROM `zt_grouppriv` WHERE `group` = (SELECT `id` FROM `zt_group` WHERE `vision` = 'rnd' AND `role` = 'DEVOPSUSER' LIMIT 1);
