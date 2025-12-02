#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::processKanbanDatas();
timeout=0
cid=17421

- 步骤1：story对象，包含看板项目第0条的isModal属性 @1
- 步骤2：story对象，不包含看板项目第0条的isModal属性 @~~
- 步骤3：task对象，包含看板项目第0条的isModal属性 @1
- 步骤4：task对象，不包含看板项目第0条的isModal属性 @~~
- 步骤5：空数据处理 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. 数据准备（使用mock数据，在测试类中处理）

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotTest();

// 创建测试数据对象
$storyData1 = new stdClass();
$storyData1->id = 1;  // 这个故事关联项目1（看板项目）
$storyData1->title = '故事1';

$storyData2 = new stdClass();
$storyData2->id = 2;  // 这个故事关联项目1（看板项目）
$storyData2->title = '故事2';

$storyData3 = new stdClass();
$storyData3->id = 6;  // 这个故事没有关联项目
$storyData3->title = '故事6';

$taskData1 = new stdClass();
$taskData1->id = 1;
$taskData1->name = '任务1';
$taskData1->execution = 1;  // 执行项目1是看板项目

$taskData2 = new stdClass();
$taskData2->id = 2;
$taskData2->name = '任务2';
$taskData2->execution = 2;  // 执行项目2是普通项目

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($pivotTest->processKanbanDatasTest('story', array($storyData1, $storyData2))) && p('0:isModal') && e('1'); // 步骤1：story对象，包含看板项目
r($pivotTest->processKanbanDatasTest('story', array($storyData3))) && p('0:isModal') && e('~~'); // 步骤2：story对象，不包含看板项目
r($pivotTest->processKanbanDatasTest('task', array($taskData1))) && p('0:isModal') && e('1'); // 步骤3：task对象，包含看板项目
r($pivotTest->processKanbanDatasTest('task', array($taskData2))) && p('0:isModal') && e('~~'); // 步骤4：task对象，不包含看板项目
r($pivotTest->processKanbanDatasTest('story', array())) && p() && e('0'); // 步骤5：空数据处理