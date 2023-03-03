ALTER TABLE `zt_lang` MODIFY COLUMN `section` varchar(50) NOT NULL;
DELETE FROM `zt_grouppriv` WHERE `module` = 'dev' AND `method` = 'editor';
