#!/usr/bin/env php
<?php
/**

title=测试 spaceModel->createDefaultSpace();
cid=18392

- 创建默认空间返回成功 @1
- 创建默认空间后空间名称正确 @默认空间
- 创建默认空间后空间编码正确 @default
- 创建默认空间后鉴权方式正确 @extend
- 创建默认空间后管理员数量正确 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(5);
zenData('ops_space')->gen(0);
zenData('company')->gen(1);
zenData('ops_spaceuser')->gen(0);

global $tester;
$tester->dao->update(TABLE_COMPANY)->set('admins')->eq(',admin,')->where('id')->eq(1)->exec();

su('admin');

$spaceTester = new spaceModelTest();

r($spaceTester->createDefaultSpaceTest()) && p() && e('1');                            // 创建默认空间返回成功
r($spaceTester->createDefaultSpaceAndGetFieldTest('name')) && p() && e('默认空间');    // 创建默认空间后空间名称正确
r($spaceTester->createDefaultSpaceAndGetFieldTest('code')) && p() && e('default');    // 创建默认空间后空间编码正确
r($spaceTester->createDefaultSpaceAndGetFieldTest('auth')) && p() && e('extend');     // 创建默认空间后鉴权方式正确
r($spaceTester->createDefaultSpaceAndGetManagerCountTest()) && p() && e('1');         // 创建默认空间后管理员数量正确
