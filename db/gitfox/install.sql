-- DevOps独立版初始化 SQL。
-- GitFox独立版复用开源版的默认权限分组，仅调整 vision，升级回正常版本后可继续复用分组权限。;
UPDATE `zt_group` SET `vision` = 'gitfox' WHERE `vision` = 'rnd' AND `role` IN ('admin', 'DEVOPSADMIN', 'DEVOPSINSPECTOR', 'DEVOPSUSER');
