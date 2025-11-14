#!/usr/bin/env php
<?php

/**

title=测试 executionZen::processPrintKanbanData();
timeout=0
cid=16438

- 步骤1：有历史数据时过滤重复项目
 - 属性wait @3
 - 属性doing @2
- 步骤2：无历史数据时返回原始数据
 - 属性wait @2
 - 属性doing @1
- 步骤3：空数据列表返回0 @0
- 步骤4：历史数据存在但无重复时返回原数据
 - 属性wait @2
 - 属性doing @1
- 步骤5：多类型数据重复被全部移除
 - 属性story @2
 - 属性bug @2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

// 2. 手动数据准备（设置历史看板数据）
global $tester;
$settingModel = $tester->loadModel('setting');
$settingModel->setItem('owner=null&module=execution&section=kanban&key=execution1', '{"wait":["1","2"],"doing":["3"],"done":["4","5"]}');
$settingModel->setItem('owner=null&module=execution&section=kanban&key=execution2', '{"story":["101","102"],"bug":["201","202"]}');
$settingModel->setItem('owner=null&module=execution&section=kanban&key=execution3', '{"task":["301","302","303"],"story":["104"]}');
$settingModel->setItem('owner=null&module=execution&section=kanban&key=execution4', '{"wait":["6","7","8"],"doing":["9"]}');

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$executionTest = new executionZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($executionTest->processPrintKanbanDataTest(1, array('wait' => array('1' => 'task1', '2' => 'task2', '10' => 'task10'), 'doing' => array('3' => 'task3', '11' => 'task11')))) && p('wait,doing') && e('3,2'); // 步骤1：有历史数据时过滤重复项目
r($executionTest->processPrintKanbanDataTest(999, array('wait' => array('1' => 'task1', '2' => 'task2'), 'doing' => array('3' => 'task3')))) && p('wait,doing') && e('2,1'); // 步骤2：无历史数据时返回原始数据
r($executionTest->processPrintKanbanDataTest(1, array())) && p() && e('0'); // 步骤3：空数据列表返回0
r($executionTest->processPrintKanbanDataTest(1, array('wait' => array('20' => 'task20', '21' => 'task21'), 'doing' => array('22' => 'task22')))) && p('wait,doing') && e('2,1'); // 步骤4：历史数据存在但无重复时返回原数据
r($executionTest->processPrintKanbanDataTest(2, array('story' => array('101' => 'story101', '103' => 'story103'), 'bug' => array('201' => 'bug201', '203' => 'bug203')))) && p('story,bug') && e('2,2'); // 步骤5：多类型数据重复被全部移除