<?php
$lang->bi->binNotExists        = 'DuckDB 二进制文件不存在。';
$lang->bi->tmpPermissionDenied = 'DuckDB tmp 目录没有权限, 需要修改目录 "%s" 的权限。<br />命令为：<br />chmod 755 -R %s。';

$lang->bi->acl = '访问控制';
$lang->bi->aclList['open']    = '公开（所有人均可访问，有维度的视图权限可访问并管理）';
$lang->bi->aclList['private'] = '私有（仅创建者和白名单用户可访问）';

$lang->bi->driver = '数据库类型';
$lang->bi->driverList = array();
$lang->bi->driverList['mysql'] = 'MySQL';
