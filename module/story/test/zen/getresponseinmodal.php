#!/usr/bin/env php
<?php

/**

title=测试 storyZen::getResponseInModal();
timeout=0
cid=0

- 步骤1：非弹窗环境 @0
- 步骤2：execution且kanban属性callback @refreshKanban()
- 步骤3：execution但非kanban属性load @1
- 步骤4：非execution的tab属性load @1
- 步骤5：没有execution ID属性load @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$executionTable = zenData('project');
$executionTable->id->range('1-10');
$executionTable->type->range('sprint{3},stage{3},kanban{4}');
$executionTable->name->range('Execution{10}');
$executionTable->model->range('scrum{10}');
$executionTable->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$storyTest = new storyTest();

// 5. 测试步骤：必须包含至少5个测试步骤
r($storyTest->getResponseInModalTest('', false, '', 0, 'sprint')) && p() && e('0'); // 步骤1：非弹窗环境
r($storyTest->getResponseInModalTest('Test Message', true, 'execution', 1, 'kanban')) && p('callback') && e('refreshKanban()'); // 步骤2：execution且kanban
r($storyTest->getResponseInModalTest('Test Message', true, 'execution', 2, 'sprint')) && p('load') && e('1'); // 步骤3：execution但非kanban
r($storyTest->getResponseInModalTest('Test Message', true, 'product', 3, 'sprint')) && p('load') && e('1'); // 步骤4：非execution的tab
r($storyTest->getResponseInModalTest('Test Message', true, 'execution', 0, 'sprint')) && p('load') && e('1'); // 步骤5：没有execution ID