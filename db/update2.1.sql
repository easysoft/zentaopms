ALTER TABLE `zt_build` CHANGE `desc` `desc` TEXT NOT NULL;
ALTER TABLE `zt_productPlan` CHANGE `desc` `desc` TEXT NOT NULL;

 -- 2011-9-17 change length of name field in zt_module
ALTER TABLE `zt_module` CHANGE `name` `name` CHAR( 60 ) NOT NULL DEFAULT '';
