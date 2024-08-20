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

$lang->bi->stepTableTitle = '选择要查询的数据表';
$lang->bi->stepTableTip   = '请选择要查询的数据表，用于指定您想要从哪张表或哪些表中检索数据。';

$lang->bi->fromTable     = '主表';
$lang->bi->leftTable     = '左连接';
$lang->bi->joinCondition = '连接条件为';
$lang->bi->joinTable     = ' = %s的';
$lang->bi->of            = '的';

$lang->bi->stepFieldTitle = '选择查询表中的字段';
$lang->bi->stepFieldTip   = '选择查询表中的字段用于从已选择的查询表中获取所需的数据。';
$lang->bi->leftTableTip   = '在SQL中，左连接（Left join）是一种表与表之间的关联操作，它返回左表中所有记录以及与右表中匹配的记录。左连接根据指定的条件从两个表中组合数据，其中左表是查询的主表，而右表是要连接的表。具体请看联表查询常用方式：左连接。';
