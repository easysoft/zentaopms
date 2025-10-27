#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printSprintBlock();
timeout=0
cid=0

- 步骤1：测试正常block对象输入情况属性type @success
- 步骤2：测试空block对象输入情况属性type @empty
- 步骤3：测试包含项目ID的session情况属性hasProjectFilter @1
- 步骤4：测试不包含项目ID的session情况属性hasProjectFilter @0
- 步骤5：测试生成的groups数据结构属性groupCount @2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('block');
$table->id->range('1-10');
$table->account->range('admin,user1,user2');
$table->dashboard->range('my,project,product');
$table->module->range('my,project,execution');
$table->title->range('迭代概况,项目概况,产品概况');
$table->block->range('sprint,project,product');
$table->code->range('sprint,executionoverview,product');
$table->gen(10);

$projectTable = zenData('project');
$projectTable->id->range('1-5');
$projectTable->name->range('项目1,项目2,项目3,项目4,项目5');
$projectTable->status->range('wait,doing,suspended,done,closed');
$projectTable->type->range('project,sprint,stage,kanban');
$projectTable->model->range('scrum,waterfall,kanban');
$projectTable->gen(5);

$executionTable = zenData('project');
$executionTable->id->range('6-15');
$executionTable->name->range('迭代1,迭代2,迭代3,迭代4,迭代5,迭代6,迭代7,迭代8,迭代9,迭代10');
$executionTable->status->range('wait{3},doing{4},suspended{2},done{1}');
$executionTable->type->range('sprint{8},stage{2}');
$executionTable->parent->range('1{3},2{3},3{4}');
$executionTable->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$blockTest = new blockTest();

// 5. 强制要求：必须包含至少5个测试步骤
$block = new stdclass();
$block->dashboard = 'my';
$block->module = 'my';
$block->params = new stdclass();
$block->params->count = 10;

r($blockTest->printSprintBlockTest($block)) && p('type') && e('success'); // 步骤1：测试正常block对象输入情况
r($blockTest->printSprintBlockTest(new stdclass())) && p('type') && e('empty'); // 步骤2：测试空block对象输入情况

$block->dashboard = 'project';
r($blockTest->printSprintBlockTest($block)) && p('hasProjectFilter') && e(1); // 步骤3：测试包含项目ID的session情况

$block->dashboard = 'my';
r($blockTest->printSprintBlockTest($block)) && p('hasProjectFilter') && e(0); // 步骤4：测试不包含项目ID的session情况

r($blockTest->printSprintBlockTest($block)) && p('groupCount') && e(2); // 步骤5：测试生成的groups数据结构