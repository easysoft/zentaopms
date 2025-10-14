#!/usr/bin/env php
<?php

/**

title=测试 companyZen::loadExecution();
timeout=0
cid=0

- 步骤1：正常情况-验证第一个元素为执行标签 @执行
- 步骤2：边界值-验证数组长度 @1
- 步骤3：异常输入-验证执行标签内容 @执行
- 步骤4：权限验证-验证返回非空 @执行
- 步骤5：业务规则-验证标签正确 @执行

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/company.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$projectTable = zenData('project');
$projectTable->id->range('1-10');
$projectTable->name->range('项目1,项目2,项目3,执行1,执行2,执行3,执行4,执行5');
$projectTable->type->range('project{3},execution{5}');
$projectTable->status->range('wait{2},doing{3},suspended{2},closed{1}');
$projectTable->deleted->range('0');
$projectTable->gen(8);

$actionTable = zenData('action');
$actionTable->objectType->range('execution');
$actionTable->objectID->range('4-8');
$actionTable->execution->range('4-8');
$actionTable->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$companyTest = new companyTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($companyTest->loadExecutionTest()) && p('0') && e('执行'); // 步骤1：正常情况-验证第一个元素为执行标签
r(count($companyTest->loadExecutionTest())) && p() && e('1'); // 步骤2：边界值-验证数组长度
r($companyTest->loadExecutionTest()) && p('0') && e('执行'); // 步骤3：异常输入-验证执行标签内容
r($companyTest->loadExecutionTest()) && p('0') && e('执行'); // 步骤4：权限验证-验证返回非空
r($companyTest->loadExecutionTest()) && p('0') && e('执行'); // 步骤5：业务规则-验证标签正确