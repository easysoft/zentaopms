#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('dept')->gen(10);

/**

title=测试 deptModel::createMemberLink();
timeout=0
cid=15964

- 步骤1：正常部门ID生成成员链接 @index.php?m=company&f=browse&browseType=inside&dept=1
- 步骤2：部门ID为2的链接生成 @index.php?m=company&f=browse&browseType=inside&dept=2
- 步骤3：部门ID为3的链接生成 @index.php?m=company&f=browse&browseType=inside&dept=3
- 步骤4：链接格式验证包含company模块 @1
- 步骤5：链接格式验证包含browse方法 @1

*/

$deptTest = new deptModelTest();

r($deptTest->createMemberLinkTest(1)) && p() && e('index.php?m=company&f=browse&browseType=inside&dept=1'); // 步骤1：正常部门ID生成成员链接
r($deptTest->createMemberLinkTest(2)) && p() && e('index.php?m=company&f=browse&browseType=inside&dept=2'); // 步骤2：部门ID为2的链接生成
r($deptTest->createMemberLinkTest(3)) && p() && e('index.php?m=company&f=browse&browseType=inside&dept=3'); // 步骤3：部门ID为3的链接生成
r(strpos($deptTest->createMemberLinkTest(4), 'm=company') !== false) && p() && e('1'); // 步骤4：链接格式验证包含company模块
r(strpos($deptTest->createMemberLinkTest(5), 'f=browse') !== false) && p() && e('1'); // 步骤5：链接格式验证包含browse方法