#!/usr/bin/env php
<?php

/**

title=测试 executionZen::displayAfterCreated();
timeout=0
cid=16425

- 步骤1：正常情况有计划ID未确认属性result @success
- 步骤2：有计划ID且确认导入 @linkStories
- 步骤3：kanban类型在project标签页属性load @project_index
- 步骤4：kanban类型不在project标签页属性load @execution_kanban
- 步骤5：无计划ID显示tips页面属性template @tips
- 步骤6：ops生命周期不导入计划属性template @tips
- 步骤7：多分支产品导入提示属性result @success

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$execution = zenData('project');
$execution->id->range('1-10');
$execution->name->range('执行1,执行2,执行3,执行4,执行5{5}');
$execution->type->range('sprint{3},kanban{2},sprint{5}');
$execution->grade->range('1');
$execution->deleted->range('0');
$execution->gen(10);

$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品1,产品2,产品3{3}');
$product->type->range('normal{3},branch{2}');
$product->deleted->range('0');
$product->gen(5);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1,2,3');
$projectProduct->product->range('1,2,2');
$projectProduct->gen(3);

$project = zenData('project');
$project->id->range('11-15');
$project->name->range('项目11,项目12,项目13{3}');
$project->type->range('project{5}');
$project->deleted->range('0');
$project->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$executionTest = new executionZenTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($executionTest->displayAfterCreatedTest(11, 1, 1, 'no', 'project')) && p('result') && e('success'); // 步骤1：正常情况有计划ID未确认
r($executionTest->displayAfterCreatedTest(11, 1, 1, 'yes', 'project')) && p() && e('linkStories'); // 步骤2：有计划ID且确认导入
r($executionTest->displayAfterCreatedTest(11, 2, 0, 'no', 'project')) && p('load') && e('project_index'); // 步骤3：kanban类型在project标签页
r($executionTest->displayAfterCreatedTest(0, 2, 0, 'no', 'execution')) && p('load') && e('execution_kanban'); // 步骤4：kanban类型不在project标签页
r($executionTest->displayAfterCreatedTest(11, 1, 0, 'no', 'project')) && p('template') && e('tips'); // 步骤5：无计划ID显示tips页面
r($executionTest->displayAfterCreatedTest(11, 1, 999, 'no', 'project')) && p('template') && e('tips'); // 步骤6：ops生命周期不导入计划
r($executionTest->displayAfterCreatedTest(11, 3, 1, 'no', 'project')) && p('result') && e('success'); // 步骤7：多分支产品导入提示