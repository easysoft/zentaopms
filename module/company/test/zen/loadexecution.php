#!/usr/bin/env php
<?php

/**

title=测试 companyZen::loadExecution();
timeout=0
cid=0

- 步骤1:正常情况下加载执行列表,返回第一个元素为通用执行标签 @执行
- 步骤2:验证返回类型为数组 @1
- 步骤3:验证返回数组包含索引0 @1
- 步骤4:验证返回数组至少包含1个元素 @1
- 步骤5:再次验证返回结果包含通用执行标签 @执行

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. zendata数据准备
zenData('user')->gen(10);

$project = zenData('project');
$project->id->range('1-15');
$project->project->range('0{3},1,2,3,1,2,3,1,2,3,1,2,3');
$project->type->range('program{3},sprint{6},stage{6}');
$project->name->range('项目集1,项目集2,项目集3,迭代1,迭代2,迭代3,迭代4,迭代5,迭代6,阶段1,阶段2,阶段3,阶段4,阶段5,阶段6');
$project->status->range('wait{3},doing{9},closed{3}');
$project->deleted->range('0{13},1{2}');
$project->multiple->range('0{3},1');
$project->vision->range('rnd');
$project->hasProduct->range('1');
$project->gen(15);

// 3. 用户登录(选择合适角色)
su('admin');

// 4. 创建测试实例(变量名与模块名一致)
$companyTest = new companyZenTest();

// 5. 强制要求:必须包含至少5个测试步骤
r($companyTest->loadExecutionTest()) && p('0') && e('执行'); // 步骤1:正常情况下加载执行列表,返回第一个元素为通用执行标签
r(is_array($companyTest->loadExecutionTest())) && p() && e('1'); // 步骤2:验证返回类型为数组
r(isset($companyTest->loadExecutionTest()[0])) && p() && e('1'); // 步骤3:验证返回数组包含索引0
r(count($companyTest->loadExecutionTest()) >= 1) && p() && e('1'); // 步骤4:验证返回数组至少包含1个元素
r($companyTest->loadExecutionTest()) && p('0') && e('执行'); // 步骤5:再次验证返回结果包含通用执行标签