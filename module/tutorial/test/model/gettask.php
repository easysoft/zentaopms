#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getTask();
timeout=0
cid=19484

- 步骤1：测试获取默认任务对象的基本属性
 - 属性id @1
 - 属性name @Test task
- 步骤2：测试任务对象的项目和执行信息
 - 属性project @2
 - 属性execution @3
- 步骤3：测试任务对象的状态和类型信息
 - 属性status @wait
 - 属性type @devel
- 步骤4：测试任务对象的优先级和估算信息
 - 属性pri @3
 - 属性estimate @0
- 步骤5：测试任务对象的用户分配信息属性openedBy @admin

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tutorial.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$tutorialTest = new tutorialTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($tutorialTest->getTaskTest()) && p('id,name') && e('1,Test task'); // 步骤1：测试获取默认任务对象的基本属性
r($tutorialTest->getTaskTest()) && p('project,execution') && e('2,3'); // 步骤2：测试任务对象的项目和执行信息
r($tutorialTest->getTaskTest()) && p('status,type') && e('wait,devel'); // 步骤3：测试任务对象的状态和类型信息
r($tutorialTest->getTaskTest()) && p('pri,estimate') && e('3,0'); // 步骤4：测试任务对象的优先级和估算信息
r($tutorialTest->getTaskTest()) && p('openedBy') && e('admin'); // 步骤5：测试任务对象的用户分配信息