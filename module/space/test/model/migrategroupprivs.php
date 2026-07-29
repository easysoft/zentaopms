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

r($spaceTester->getMigratedGroupPrivCountTest('browse')) && p() && e('3'); // 验证迁移后space模块browse权限存在
r($spaceTester->getMigratedGroupPrivCountTest('create')) && p() && e('3'); // 验证迁移后space模块create权限存在
r($spaceTester->getMigratedGroupPrivCountTest('delete')) && p() && e('3'); // 验证迁移后space模块delete权限存在
