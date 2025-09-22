#!/usr/bin/env php
<?php

/**

title=测试 deptModel::createManageProjectAdminLink();
timeout=0
cid=0

- 测试步骤1：正常部门和分组ID生成链接 @group-manageProjectAdmin-5-1.html
- 测试步骤2：边界值部门ID(1)和分组ID测试 @group-manageProjectAdmin-1-1.html
- 测试步骤3：零值分组ID生成链接测试 @group-manageProjectAdmin-0-2.html
- 测试步骤4：大ID值的链接生成测试 @group-manageProjectAdmin-999-10.html
- 测试步骤5：链接格式完整性验证测试 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/dept.unittest.class.php';

zenData('dept')->loadYaml('dept_createmanageprojectadminlink', false, 2)->gen(10);
su('admin');

$deptTest = new deptTest();

r($deptTest->createManageProjectAdminLinkTest(1, 5)) && p() && e('group-manageProjectAdmin-5-1.html'); // 测试步骤1：正常部门和分组ID生成链接
r($deptTest->createManageProjectAdminLinkTest(1, 1)) && p() && e('group-manageProjectAdmin-1-1.html'); // 测试步骤2：边界值部门ID(1)和分组ID测试
r($deptTest->createManageProjectAdminLinkTest(2, 0)) && p() && e('group-manageProjectAdmin-0-2.html'); // 测试步骤3：零值分组ID生成链接测试
r($deptTest->createManageProjectAdminLinkTest(10, 999)) && p() && e('group-manageProjectAdmin-999-10.html'); // 测试步骤4：大ID值的链接生成测试
r(strpos($deptTest->createManageProjectAdminLinkTest(3, 7), 'manageProjectAdmin') !== false) && p() && e('1'); // 测试步骤5：链接格式完整性验证测试