#!/usr/bin/env php
<?php

/**

title=测试 biModel::getCorrectGroup();
timeout=0
cid=0

- 步骤1：测试存在的chart类型模块 @1
- 步骤2：测试存在的pivot类型模块 @9
- 步骤3：测试不存在的模块ID @
- 步骤4：测试多个模块ID @1,5

- 步骤5：测试空字符串和无效类型 @

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

// 2. zendata数据准备
$table = zenData('module');
$table->id->range('1-15');
$table->root->range('1{8},2{4},3{3}');
$table->name->range('产品,项目,测试,组织,需求,发布,项目,任务,产品,项目,测试,组织,产品,项目,测试');
$table->type->range('chart{8},pivot{4},chart{3}');
$table->grade->range('1{4},2{4},1{4},2{3}');
$table->gen(15);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$biTest = new biTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($biTest->getCorrectGroupTest('32', 'chart')) && p() && e('1'); // 步骤1：测试存在的chart类型模块
r($biTest->getCorrectGroupTest('59', 'pivot')) && p() && e('9'); // 步骤2：测试存在的pivot类型模块
r($biTest->getCorrectGroupTest('999', 'chart')) && p() && e(''); // 步骤3：测试不存在的模块ID
r($biTest->getCorrectGroupTest('32,36', 'chart')) && p() && e('1,5'); // 步骤4：测试多个模块ID
r($biTest->getCorrectGroupTest('', 'invalid')) && p() && e(''); // 步骤5：测试空字符串和无效类型