#!/usr/bin/env php
<?php

/**

title=测试 deptModel::createGroupManageMemberLink();
timeout=0
cid=15962

- 步骤1：正常参数链接生成 @index.php?m=group&f=managemember&groupID=1&deptID=1
- 步骤2：边界值测试大权限组ID @index.php?m=group&f=managemember&groupID=999&deptID=1
- 步骤3：边界值测试大部门ID @index.php?m=group&f=managemember&groupID=5&deptID=20
- 步骤4：验证链接包含正确模块和方法名 @1
- 步骤5：验证参数传递准确性 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/dept.unittest.class.php';

$deptTable = zenData('dept');
$deptTable->id->range('1-20');
$deptTable->name->range('开发部,测试部,产品部,运维部,设计部');
$deptTable->parent->range('0');
$deptTable->grade->range('1');
$deptTable->order->range('1-20');
$deptTable->gen(20);

su('admin');

$deptTest = new deptTest();

r($deptTest->createGroupManageMemberLinkTest(1, 1)) && p() && e('index.php?m=group&f=managemember&groupID=1&deptID=1'); // 步骤1：正常参数链接生成
r($deptTest->createGroupManageMemberLinkTest(1, 999)) && p() && e('index.php?m=group&f=managemember&groupID=999&deptID=1'); // 步骤2：边界值测试大权限组ID
r($deptTest->createGroupManageMemberLinkTest(20, 5)) && p() && e('index.php?m=group&f=managemember&groupID=5&deptID=20'); // 步骤3：边界值测试大部门ID
r(strpos($deptTest->createGroupManageMemberLinkTest(2, 2), 'group') !== false && strpos($deptTest->createGroupManageMemberLinkTest(2, 2), 'managemember') !== false) && p() && e('1'); // 步骤4：验证链接包含正确模块和方法名
r(strpos($deptTest->createGroupManageMemberLinkTest(3, 7), 'groupID=7&deptID=3') !== false) && p() && e('1'); // 步骤5：验证参数传递准确性