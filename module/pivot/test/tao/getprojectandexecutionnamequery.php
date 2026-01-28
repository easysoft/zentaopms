#!/usr/bin/env php
<?php

/**

title=测试 pivotTao::getProjectAndExecutionNameQuery();
timeout=0
cid=17448

- 步骤1：验证返回数组类型 @1
- 步骤2：验证返回记录数量 @7
- 步骤3：验证第一条记录id字段第0条的id属性 @1
- 步骤4：验证第一条记录name字段第0条的name属性 @执行1
- 步骤5：验证status字段存在 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. zendata数据准备
$project = zenData('project');
$project->id->range('1-10');
$project->name->range('执行1,执行2,执行3,项目1,项目2,执行4,执行5,项目3,执行6,执行7');
$project->type->range('stage{3},sprint{4},project{3}');
$project->status->range('wait{5},doing{3},closed{2}');
$project->deleted->range('0{8},1{2}');
$project->multiple->range('1{7},0{3}');
$project->project->range('0{3},1,1,2,2,3,3,3');
$project->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$pivotTest = new pivotTaoTest();

// 5. 执行测试步骤
r(is_array($pivotTest->getProjectAndExecutionNameQueryTest())) && p() && e('1'); // 步骤1：验证返回数组类型
r(count($pivotTest->getProjectAndExecutionNameQueryTest())) && p() && e('7'); // 步骤2：验证返回记录数量
r($pivotTest->getProjectAndExecutionNameQueryTest()) && p('0:id') && e('1'); // 步骤3：验证第一条记录id字段
r($pivotTest->getProjectAndExecutionNameQueryTest()) && p('0:name') && e('执行1'); // 步骤4：验证第一条记录name字段
r(isset($pivotTest->getProjectAndExecutionNameQueryTest()[0]->status)) && p() && e('1'); // 步骤5：验证status字段存在