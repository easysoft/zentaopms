ALTER TABLE `zt_build` CHANGE `desc` `desc` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `zt_productPlan` CHANGE `desc` `desc` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

 -- 2011-9-17 change length of name field in zt_module
ALTER TABLE `zt_module` CHANGE `name` `name` CHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '';
