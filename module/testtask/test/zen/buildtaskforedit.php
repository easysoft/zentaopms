#!/usr/bin/env php
<?php

/**

title=测试 testtaskZen::buildTaskForEdit();
timeout=0
cid=19229

- 步骤1：正常任务ID和产品ID
 - 属性id @1
 - 属性product @1
- 步骤2：无效任务ID
 - 属性id @999
 - 属性product @1
- 步骤3：存在execution的情况属性project @1
- 步骤4：不存在execution但有build的情况属性project @1
- 步骤5：members字段trim处理属性members @admin

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testtaskzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$testtask = zenData('testtask');
$testtask->id->range('1-10');
$testtask->name->range('测试单1,测试单2,测试单3,测试单4,测试单5{5}');
$testtask->product->range('1{10}');
$testtask->project->range('1{10}');
$testtask->execution->range('0{10}');
$testtask->build->range('1,2,3,4,5{5}');
$testtask->owner->range('admin{10}');
$testtask->status->range('wait{10}');
$testtask->begin->range('`2024-01-01`,`2024-01-02`,`2024-01-03`,`2024-01-04`,`2024-01-05`{5}');
$testtask->end->range('`2024-01-31`{10}');
$testtask->members->range(',admin,user1,,admin,user1,user2,,member1,member2,');
$testtask->desc->range('描述1{10}');
$testtask->gen(10);

$project = zenData('project');
$project->id->range('1-5');
$project->name->range('项目1,项目2,项目3,项目4,项目5');
$project->type->range('project{5}');
$project->status->range('wait{5}');
$project->gen(5);

$build = zenData('build');
$build->id->range('1-5');
$build->name->range('版本1,版本2,版本3,版本4,版本5');
$build->project->range('1,2,3,4,5');
$build->product->range('1{5}');
$build->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testtaskTest = new testtaskZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testtaskTest->buildTaskForEditTest(1, 1)) && p('id,product') && e('1,1'); // 步骤1：正常任务ID和产品ID
r($testtaskTest->buildTaskForEditTest(999, 1)) && p('id,product') && e('999,1'); // 步骤2：无效任务ID
r($testtaskTest->buildTaskForEditTest(1, 2)) && p('project') && e('1'); // 步骤3：存在execution的情况
r($testtaskTest->buildTaskForEditTest(4, 3)) && p('project') && e('1'); // 步骤4：不存在execution但有build的情况
r($testtaskTest->buildTaskForEditTest(3, 4)) && p('members') && e('admin'); // 步骤5：members字段trim处理