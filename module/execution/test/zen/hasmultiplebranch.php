#!/usr/bin/env php
<?php

/**

title=测试 executionZen::hasMultipleBranch();
timeout=0
cid=16434

- 步骤1：指定产品ID，产品类型为normal @0
- 步骤2：指定产品ID，产品类型为branch @1
- 步骤3：不指定产品ID，执行关联普通产品 @0
- 步骤4：不指定产品ID，执行关联多分支产品 @1
- 步骤5：边界情况，产品ID和执行ID都为0 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品1,产品2,产品3,产品4,产品5');
$product->type->range('normal,normal,branch,platform,normal');
$product->status->range('normal{5}');
$product->deleted->range('0{5}');
$product->gen(5);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1,2,2');
$projectProduct->product->range('1,3,4');
$projectProduct->branch->range('0{3}');
$projectProduct->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$executionTest = new executionZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($executionTest->hasMultipleBranchTest(1, 1)) && p() && e('0'); // 步骤1：指定产品ID，产品类型为normal
r($executionTest->hasMultipleBranchTest(3, 2)) && p() && e('1'); // 步骤2：指定产品ID，产品类型为branch
r($executionTest->hasMultipleBranchTest(0, 1)) && p() && e('0'); // 步骤3：不指定产品ID，执行关联普通产品
r($executionTest->hasMultipleBranchTest(0, 2)) && p() && e('1'); // 步骤4：不指定产品ID，执行关联多分支产品
r($executionTest->hasMultipleBranchTest(0, 0)) && p() && e('1'); // 步骤5：边界情况，产品ID和执行ID都为0