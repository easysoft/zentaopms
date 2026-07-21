#!/usr/bin/env php
<?php

/**

title=测试 spaceModel::migrateGroupPrivs();
timeout=0
cid=0

- 执行权限迁移并验证返回结果 @1
- 准备仓库权限数据并迁移后再验证返回结果 @1
- 验证迁移后space模块browse权限存在 @1
- 验证迁移后space模块create权限存在 @1
- 验证迁移后space模块delete权限存在 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(10);
zenData('group')->gen(5);

/* Insert grouppriv data with repo module to trigger migration */
global $tester;
$grouppriv = zenData('grouppriv');
$grouppriv->group->range('1,2,3');
$grouppriv->module->range('repo');
$grouppriv->method->range('createRepo,create,import,edit');
$grouppriv->gen(5);

su('admin');

$spaceTester = new spaceModelTest();

r($spaceTester->migrateGroupPrivsTest()) && p() && e('1'); // 执行权限迁移并验证返回结果

$privs = $tester->dao->select('*')->from(TABLE_GROUPPRIV)->where('module')->eq('space')->andWhere('method')->eq('browse')->fetchAll();
r(!empty($privs)) && p() && e('1'); // 验证迁移后space模块browse权限存在

$privs = $tester->dao->select('*')->from(TABLE_GROUPPRIV)->where('module')->eq('space')->andWhere('method')->eq('create')->fetchAll();
r(!empty($privs)) && p() && e('1'); // 验证迁移后space模块create权限存在

$privs = $tester->dao->select('*')->from(TABLE_GROUPPRIV)->where('module')->eq('space')->andWhere('method')->eq('delete')->fetchAll();
r(!empty($privs)) && p() && e('1'); // 验证迁移后space模块delete权限存在
