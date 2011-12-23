ALTER TABLE `zt_user` CHANGE `gendar` `gender` ENUM( 'f', 'm' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'f' ;
ALTER TABLE `zt_user` ADD `commiter` VARCHAR( 100 ) NOT NULL AFTER `nickname` ;
