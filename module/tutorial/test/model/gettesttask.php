#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getTesttask();
timeout=0
cid=19490

- 步骤1：正常获取测试单对象
 - 属性id @1
 - 属性name @Test testtask
- 步骤2：验证项目和产品关联
 - 属性project @2
 - 属性product @1
- 步骤3：验证状态和优先级
 - 属性status @wait
 - 属性pri @3
- 步骤4：验证执行和版本关联
 - 属性execution @3
 - 属性build @1
- 步骤5：验证删除标记属性deleted @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tutorial.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$tutorialTest = new tutorialTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($tutorialTest->getTesttaskTest()) && p('id,name') && e('1,Test testtask'); // 步骤1：正常获取测试单对象
r($tutorialTest->getTesttaskTest()) && p('project,product') && e('2,1'); // 步骤2：验证项目和产品关联
r($tutorialTest->getTesttaskTest()) && p('status,pri') && e('wait,3'); // 步骤3：验证状态和优先级
r($tutorialTest->getTesttaskTest()) && p('execution,build') && e('3,1'); // 步骤4：验证执行和版本关联
r($tutorialTest->getTesttaskTest()) && p('deleted') && e('0'); // 步骤5：验证删除标记