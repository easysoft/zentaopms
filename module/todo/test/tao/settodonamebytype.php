#!/usr/bin/env php
<?php

/**

title=测试 todoTao::setTodoNameByType();
timeout=0
cid=19281

- 执行todoTest模块的setTodoNameByTypeTest方法，参数是1 属性name @Test Story Title
- 执行todoTest模块的setTodoNameByTypeTest方法，参数是2 属性name @Test Task Name
- 执行todoTest模块的setTodoNameByTypeTest方法，参数是3 属性name @Test Bug Title
- 执行todoTest模块的setTodoNameByTypeTest方法，参数是4 属性name @Test Testtask Name
- 执行todoTest模块的setTodoNameByTypeTest方法，参数是5 属性name @Original Todo Name
- 执行todoTest模块的setTodoNameByTypeTest方法，参数是6 属性name @Custom Todo Name
- 执行todoTest模块的setTodoNameByTypeTest方法，参数是7 属性name @Empty Object Todo Name

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todo.unittest.class.php';

// 2. zendata数据准备
zenData('todo')->loadYaml('settodonamebytype')->gen(7);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$todoTest = new todoTest();

// 5. 准备测试数据
global $tester;

/* 清理并创建测试数据 */
$tester->dao->exec('TRUNCATE TABLE ' . TABLE_STORY);
$tester->dao->exec('TRUNCATE TABLE ' . TABLE_TASK);
$tester->dao->exec('TRUNCATE TABLE ' . TABLE_BUG);
$tester->dao->exec('TRUNCATE TABLE ' . TABLE_TESTTASK);

/* 创建story测试数据 */
$story = new stdclass();
$story->id       = 1;
$story->title    = 'Test Story Title';
$story->estimate = '1';
$tester->dao->insert(TABLE_STORY)->data($story)->exec();

/* 创建task测试数据 */
$task = new stdclass();
$task->id       = 1;
$task->name     = 'Test Task Name';
$task->estimate = '1';
$task->left     = '1';
$tester->dao->insert(TABLE_TASK)->data($task)->exec();

/* 创建bug测试数据 */
$bug = new stdclass();
$bug->id    = 1;
$bug->title = 'Test Bug Title';
$tester->dao->insert(TABLE_BUG)->data($bug)->exec();

/* 创建testtask测试数据 */
$testtask = new stdclass();
$testtask->id   = 1;
$testtask->name = 'Test Testtask Name';
$tester->dao->insert(TABLE_TESTTASK)->data($testtask)->exec();

/* 创建空名称的测试数据 */
$emptyStory = new stdclass();
$emptyStory->id    = 2;
$emptyStory->title = '';
$tester->dao->insert(TABLE_STORY)->data($emptyStory)->exec();

// 测试步骤1：正常情况测试story类型待办名称设置
r($todoTest->setTodoNameByTypeTest(1)) && p('name') && e('Test Story Title');

// 测试步骤2：正常情况测试task类型待办名称设置
r($todoTest->setTodoNameByTypeTest(2)) && p('name') && e('Test Task Name');

// 测试步骤3：正常情况测试bug类型待办名称设置
r($todoTest->setTodoNameByTypeTest(3)) && p('name') && e('Test Bug Title');

// 测试步骤4：正常情况测试testtask类型待办名称设置
r($todoTest->setTodoNameByTypeTest(4)) && p('name') && e('Test Testtask Name');

// 测试步骤5：边界值测试不存在的objectID
r($todoTest->setTodoNameByTypeTest(5)) && p('name') && e('Original Todo Name');

// 测试步骤6：异常输入测试custom类型待办
r($todoTest->setTodoNameByTypeTest(6)) && p('name') && e('Custom Todo Name');

// 测试步骤7：业务规则测试当关联对象名称为空时的回退逻辑
r($todoTest->setTodoNameByTypeTest(7)) && p('name') && e('Empty Object Todo Name');