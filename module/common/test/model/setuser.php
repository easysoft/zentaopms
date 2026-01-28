#!/usr/bin/env php
<?php

/**

title=测试 commonModel::setUser();
timeout=0
cid=15714

- 测试步骤1：无session用户且允许访客访问时创建guest用户
 - 属性account @guest
 - 属性realname @guest
- 测试步骤2：验证guest用户的基本属性设置
 - 属性id @0
 - 属性dept @0
- 测试步骤3：验证guest用户的权限和角色设置
 - 属性role @guest
 - 属性admin @~~
- 测试步骤4：session中有用户时设置为当前用户
 - 属性account @admin
 - 属性realname @admin
- 测试步骤5：验证admin用户权限属性admin @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 准备测试数据
$companyTable = zenData('company');
$companyTable->id->range('1');
$companyTable->name->range('禅道软件');
$companyTable->guest->range('1');
$companyTable->gen(1);

$commonTest = new commonModelTest();

r($commonTest->setUserTest('guest')) && p('account,realname') && e('guest,guest'); // 测试步骤1：无session用户且允许访客访问时创建guest用户
r($commonTest->setUserTest('guest')) && p('id,dept') && e('0,0'); // 测试步骤2：验证guest用户的基本属性设置
r($commonTest->setUserTest('guest')) && p('role,admin') && e('guest,~~'); // 测试步骤3：验证guest用户的权限和角色设置

su('admin');
r($commonTest->setUserTest()) && p('account,realname') && e('admin,admin'); // 测试步骤4：session中有用户时设置为当前用户
r($commonTest->setUserTest()) && p('admin') && e('1'); // 测试步骤5：验证admin用户权限