ALTER TABLE `zt_user` CHANGE `gendar` `gender` ENUM( 'f', 'm' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'f' ;
ALTER TABLE `zt_user` ADD `commiter` VARCHAR( 100 ) NOT NULL AFTER `nickname` ;

UPDATE `zt_action` SET `objectType` = 'product'     WHERE `objectType` = '`product`';
UPDATE `zt_action` SET `objectType` = 'productplan' WHERE `objectType` = '`productPlan`';
UPDATE `zt_action` SET `objectType` = 'project'     WHERE `objectType` = '`project`';
UPDATE `zt_action` SET `objectType` = 'story'       WHERE `objectType` = '`story`';
UPDATE `zt_action` SET `objectType` = 'task'        WHERE `objectType` = '`task`';
UPDATE `zt_action` SET `objectType` = 'bug'         WHERE `objectType` = '`bug`';
UPDATE `zt_action` SET `objectType` = 'case'        WHERE `objectType` = '`case`';
UPDATE `zt_action` SET `objectType` = 'build'       WHERE `objectType` = '`build`';
UPDATE `zt_action` SET `objectType` = 'release'     WHERE `objectType` = '`release`';
UPDATE `zt_action` SET `objectType` = 'user'        WHERE `objectType` = '`user`';
UPDATE `zt_action` SET `objectType` = 'doc'         WHERE `objectType` = '`doc`';
UPDATE `zt_action` SET `objectType` = 'doclib'      WHERE `objectType` = '`doclib`';
UPDATE `zt_action` SET `objectType` = 'testtask'    WHERE `objectType` = '`testtask`';
UPDATE `zt_action` SET `objectType` = 'todo'        WHERE `objectType` = '`todo`';
UPDATE `zt_bug` SET `os` = 'andriod' WHERE `os` = 'adriod';
