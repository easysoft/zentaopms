-- 2011-2-16 change the length of the desc field.
ALTER TABLE `zt_build` CHANGE `desc` `desc` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
-- 2011-3-4  fix the error of bug's browse: 
UPDATE zt_bug set browser='firefox3' where browser='firefx3';
