#!/usr/bin/env php
<?php

/**

title=测试 repoZen::buildCreateRepoForm();
timeout=0
cid=0

- 步骤1：正常项目ID属性objectID @1
- 步骤2：零值项目ID属性objectID @0
- 步骤3：大数值项目ID属性objectID @999
- 步骤4：其他项目ID属性objectID @5
- 步骤5：另一个项目ID属性objectID @100

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('project');
$table->id->range('1-100');
$table->name->range('项目{1-100}');
$table->status->range('wait{30},doing{40},done{30}');
$table->type->range('project{80},sprint{20}');
$table->gen(10);

$productTable = zenData('product');
$productTable->id->range('1-50');
$productTable->name->range('产品{1-50}');
$productTable->status->range('normal{40},closed{10}');
$productTable->gen(5);

$groupTable = zenData('group');
$groupTable->id->range('1-10');
$groupTable->name->range('开发组{1-5},测试组{6-10}');
$groupTable->gen(3);

zenData('user');  // 使用默认用户数据，避免重复账号问题

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$repoTest = new repoZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($repoTest->buildCreateRepoFormTest(1)) && p('objectID') && e('1'); // 步骤1：正常项目ID
r($repoTest->buildCreateRepoFormTest(0)) && p('objectID') && e('0'); // 步骤2：零值项目ID
r($repoTest->buildCreateRepoFormTest(999)) && p('objectID') && e('999'); // 步骤3：大数值项目ID
r($repoTest->buildCreateRepoFormTest(5)) && p('objectID') && e('5'); // 步骤4：其他项目ID
r($repoTest->buildCreateRepoFormTest(100)) && p('objectID') && e('100'); // 步骤5：另一个项目ID