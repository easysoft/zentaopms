#!/usr/bin/env php
<?php

/**

title=测试 myZen::showWorkCount();
timeout=0
cid=0

- 步骤1：正常情况，检查任务数量属性task @5
- 步骤2：自定义分页参数属性story @3
- 步骤3：检查bug数量属性bug @2
- 步骤4：检查用例数量属性case @0
- 步骤5：检查测试任务数量属性testtask @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/my.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$task = zenData('task');
$task->id->range('1-10');
$task->name->range('Task{1-10}');
$task->assignedTo->range('admin{5},user1{3},user2{2}');
$task->status->range('wait{3},doing{4},done{3}');
$task->deleted->range('0{10}');
$task->gen(10);

$story = zenData('story');
$story->id->range('1-5');
$story->title->range('Story{1-5}');
$story->assignedTo->range('admin{3},user1{2}');
$story->status->range('active{3},reviewing{2}');
$story->deleted->range('0{5}');
$story->gen(5);

$bug = zenData('bug');
$bug->id->range('1-3');
$bug->title->range('Bug{1-3}');
$bug->assignedTo->range('admin{2},user1{1}');
$bug->status->range('active{2},resolved{1}');
$bug->deleted->range('0{3}');
$bug->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$myTest = new myTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($myTest->showWorkCountTest()) && p('task') && e('5'); // 步骤1：正常情况，检查任务数量
r($myTest->showWorkCountTest(100, 10, 1)) && p('story') && e('3'); // 步骤2：自定义分页参数
r($myTest->showWorkCountTest(0, 10, 1)) && p('bug') && e('2'); // 步骤3：检查bug数量
r($myTest->showWorkCountTest(50, 20, 2)) && p('case') && e('0'); // 步骤4：检查用例数量
r($myTest->showWorkCountTest(0, 20, 1)) && p('testtask') && e('0'); // 步骤5：检查测试任务数量