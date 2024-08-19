<?php
$lang->bi->binNotExists        = 'DuckDB 二进制文件不存在。';
$lang->bi->tmpPermissionDenied = 'DuckDB tmp 目录没有权限, 需要修改目录 "%s" 的权限。<br />命令为：<br />chmod 755 -R %s。';

$lang->bi->acl = '访问控制';
$lang->bi->aclList['open']    = '公开（所有人均可访问，有维度的视图权限可访问并管理）';
$lang->bi->aclList['private'] = '私有（仅创建者和白名单用户可访问）';

$lang->bi->driver = '数据库类型';
$lang->bi->driverList = array();
$lang->bi->driverList['mysql'] = 'MySQL';

$lang->bi->sqlQuery   = 'SQL语句查询';
$lang->bi->sqlBuilder = 'SQL构建器';

$lang->bi->toggleSqlText    = '手写SQL语句';
$lang->bi->toggleSqlBuilder = 'SQL构建器';

$lang->bi->builderStepList = array();
$lang->bi->builderStepList['table'] = '查询数据表';
$lang->bi->builderStepList['field'] = '选择查询字段';
$lang->bi->builderStepList['func']  = '新增函数字段';
$lang->bi->builderStepList['where'] = '添加查询条件';
$lang->bi->builderStepList['query'] = '添加查询筛选器';
$lang->bi->builderStepList['group'] = '设置分组并聚合';
