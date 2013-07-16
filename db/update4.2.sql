ALTER TABLE `zt_extension` ADD `depends` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `installedTime` ;
ALTER TABLE `zt_extension` CHANGE `zentaoVersion` `zentaoCompatible` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
