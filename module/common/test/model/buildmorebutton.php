#!/usr/bin/env php
<?php

/**

title=测试 commonModel::buildMoreButton();
timeout=0
cid=15647

- 步骤1：不存在的executionID @0
- 步骤2：无效ID为0 @0
- 步骤3：负数ID @0
- 步骤4：Tutorial模式或正常模式 @0
- 步骤5：执行记录但同项目无其他执行 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/common.unittest.class.php';

zenData('user')->gen(5);

// 准备项目和执行数据
$project = zenData('project');
$project->id->range('1-10');
$project->name->range('项目{1-5},执行{6-10}');
$project->type->range('project{5},sprint{5}');
$project->parent->range('0{5},0{5}');
$project->project->range('0{5},1-5'); // 执行的project字段指向父项目
$project->grade->range('1{5},2{5}');
$project->deleted->range('0{10}');
$project->gen(10);

su('admin');

// 创建测试实例
$commonTest = new commonTest();

// 模拟Tutorial模式进行一个测试
$_SESSION['tutorialMode'] = true;

// 测试用例（5个测试步骤）
r($commonTest->buildMoreButtonTest(999, false)) && p() && e('0'); // 步骤1：不存在的executionID
r($commonTest->buildMoreButtonTest(0, false))   && p() && e('0');   // 步骤2：无效ID为0
r($commonTest->buildMoreButtonTest(-1, false))  && p() && e('0');  // 步骤3：负数ID
r($commonTest->buildMoreButtonTest(1, false))   && p() && e('0');   // 步骤4：Tutorial模式或正常模式
r($commonTest->buildMoreButtonTest(6, false))   && p() && e('0');   // 步骤5：执行记录但同项目无其他执行
