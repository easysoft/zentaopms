ALTER TABLE  `zt_build` CHANGE  `desc`  `desc` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE  `zt_group` ADD  `role` CHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
DELETE FROM  `zt_group` WHERE `id` = 6;
UPDATE  `zentaopms`.`zt_group` SET  `role` =  'po,pd' WHERE  `zt_group`.`id` = 2;
UPDATE  `zentaopms`.`zt_group` SET  `role` =  'dev' WHERE  `zt_group`.`id` = 3;
UPDATE  `zentaopms`.`zt_group` SET  `role` =  'qa,qd' WHERE  `zt_group`.`id` = 4;
UPDATE  `zentaopms`.`zt_group` SET  `role` =  'pm,td' WHERE  `zt_group`.`id` = 5;
INSERT INTO `zt_group`(`id`, `company`, `name`, `desc`, `role`) VALUES (6, 1, 'TOP', 'for top managers.', 'top');
INSERT INTO `zt_group`(`id`, `company`, `name`, `desc`, `role`) VALUES (7, 1, 'guest', 'for guest.', '');
