#!/usr/bin/env php
<?php

/**

title=测试 testtaskZen::setDropMenu();
timeout=0
cid=19241

- 步骤1：qa tab下的正常产品和任务属性switcherParams @productID=1&branch=&taskID=1&module=testtask&method=browse&objectType=&objectID=
- 步骤2：project tab下的项目相关参数设置属性switcherParams @productID=1&branch=&taskID=1&module=testtask&method=browse&objectType=&objectID=
- 步骤3：execution tab下的执行相关参数设置属性switcherParams @productID=1&branch=&taskID=1&module=testtask&method=browse&objectType=&objectID=
- 步骤4：空任务对象的处理属性switcherText @Test Task
- 步骤5：多分支产品的情况下参数设置属性switcherObjectID @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testtaskzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('product');
$table->id->range('1-5');
$table->name->range('产品1,产品2,产品3,产品4,产品5');
$table->type->range('normal{3},branch{2}');
$table->gen(5);

$projectTable = zenData('project');
$projectTable->id->range('1-3');
$projectTable->name->range('项目1,项目2,项目3');
$projectTable->type->range('project');
$projectTable->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testtaskTest = new testtaskZenTest();

// 创建任务对象
$task = new stdclass();
$task->id = 1;
$task->name = '测试任务1';
$task->project = 1;
$task->execution = 1;

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testtaskTest->setDropMenuTest(1, $task, 'qa')) && p('switcherParams') && e('productID=1&branch=&taskID=1&module=testtask&method=browse&objectType=&objectID='); // 步骤1：qa tab下的正常产品和任务
r($testtaskTest->setDropMenuTest(1, $task, 'project')) && p('switcherParams') && e('productID=1&branch=&taskID=1&module=testtask&method=browse&objectType=&objectID='); // 步骤2：project tab下的项目相关参数设置
r($testtaskTest->setDropMenuTest(1, $task, 'execution')) && p('switcherParams') && e('productID=1&branch=&taskID=1&module=testtask&method=browse&objectType=&objectID='); // 步骤3：execution tab下的执行相关参数设置
r($testtaskTest->setDropMenuTest(2, null, 'qa')) && p('switcherText') && e('Test Task'); // 步骤4：空任务对象的处理
r($testtaskTest->setDropMenuTest(3, $task, 'qa')) && p('switcherObjectID') && e('1'); // 步骤5：多分支产品的情况下参数设置