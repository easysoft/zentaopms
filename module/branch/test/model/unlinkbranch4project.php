#!/usr/bin/env php
<?php

/**

title=测试 branchModel::unlinkBranch4Project();
timeout=0
cid=15339

- 步骤1：测试单个产品解除分支关联 @0
- 步骤2：测试多个产品批量解除分支关联 @0
- 步骤3：测试空产品列表异常情况 @0
- 步骤4：测试不存在的产品ID @0
- 步骤5：测试无分支关联的产品 @0

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/branch.unittest.class.php';

// 2. zendata数据准备
// 创建包含分支的项目产品关联数据，确保主键(project,product,branch)唯一
$projectproductTable = zenData('projectproduct');
$projectproductTable->project->range('11,12,13,14,15,16,17,18,19,20,21,22,23,24,25');
$projectproductTable->product->range('6,7,8,6,7,8,1,1,1,999,6,7,8,1,999');
$projectproductTable->branch->range('1,2,3,0,1,2,0,0,0,0,2,3,4,0,0');
$projectproductTable->plan->range('1-15');
$projectproductTable->gen(15);

zenData('user')->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$branchTester = new branchTest();

// 5. 强制要求：包含至少5个测试步骤
r($branchTester->unlinkBranch4ProjectTest(array(6))) && p() && e('0'); // 步骤1：测试单个产品解除分支关联
r($branchTester->unlinkBranch4ProjectTest(array(7, 8))) && p() && e('0'); // 步骤2：测试多个产品批量解除分支关联
r($branchTester->unlinkBranch4ProjectTest(array())) && p() && e('0'); // 步骤3：测试空产品列表异常情况
r($branchTester->unlinkBranch4ProjectTest(array(999))) && p() && e('0'); // 步骤4：测试不存在的产品ID
r($branchTester->unlinkBranch4ProjectTest(array(1))) && p() && e('0'); // 步骤5：测试无分支关联的产品