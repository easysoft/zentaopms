#!/usr/bin/env php
<?php

/**

title=测试 deptModel::createManageProjectAdminLink();
timeout=0
cid=15963

- 测试步骤1：正常部门和分组ID生成链接 @index.php?m=group&f=manageProjectAdmin&groupID=5&deptID=1
- 测试步骤2：边界值部门ID(1)和分组ID测试 @index.php?m=group&f=manageProjectAdmin&groupID=1&deptID=1
- 测试步骤3：零值分组ID生成链接测试 @index.php?m=group&f=manageProjectAdmin&groupID=0&deptID=2
- 测试步骤4：大ID值的链接生成测试 @index.php?m=group&f=manageProjectAdmin&groupID=999&deptID=10
- 测试步骤5：链接格式完整性验证测试 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/dept.unittest.class.php';

su('admin');

$deptTest = new deptTest();

r($deptTest->createManageProjectAdminLinkTest(1, 5)) && p() && e('index.php?m=group&f=manageProjectAdmin&groupID=5&deptID=1'); // 测试步骤1：正常部门和分组ID生成链接
r($deptTest->createManageProjectAdminLinkTest(1, 1)) && p() && e('index.php?m=group&f=manageProjectAdmin&groupID=1&deptID=1'); // 测试步骤2：边界值部门ID(1)和分组ID测试
r($deptTest->createManageProjectAdminLinkTest(2, 0)) && p() && e('index.php?m=group&f=manageProjectAdmin&groupID=0&deptID=2'); // 测试步骤3：零值分组ID生成链接测试
r($deptTest->createManageProjectAdminLinkTest(10, 999)) && p() && e('index.php?m=group&f=manageProjectAdmin&groupID=999&deptID=10'); // 测试步骤4：大ID值的链接生成测试
r(strpos($deptTest->createManageProjectAdminLinkTest(3, 7), 'manageProjectAdmin') !== false) && p() && e('1'); // 测试步骤5：链接格式完整性验证测试