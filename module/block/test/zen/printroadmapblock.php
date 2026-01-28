#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printRoadmapBlock();
timeout=0
cid=15285

- 步骤1：正常情况第product条的name属性 @正常产品1
- 步骤2：不存在产品第product条的name属性 @0
- 步骤3：多分支产品第product条的type属性 @normal
- 步骤4：普通产品第product条的type属性 @normal
- 步骤5：分支数量验证属性branchCount @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. zendata数据准备（根据需要配置）
zendata('product')->loadYaml('product_printroadmapblock', false, 2)->gen(5);
zendata('productplan')->loadYaml('productplan_printroadmapblock', false, 2)->gen(15);
zendata('branch')->loadYaml('branch_printroadmapblock', false, 2)->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$blockTest = new blockZenTest();

// 5. 构造测试用的block对象
$normalProductBlock = new stdclass();
$normalProductBlock->id = 1;
$normalProductBlock->dashboard = 'my';

$nonExistProductBlock = new stdclass();
$nonExistProductBlock->id = 2;
$nonExistProductBlock->dashboard = 'my';

$branchProductBlock = new stdclass();
$branchProductBlock->id = 3;
$branchProductBlock->dashboard = 'my';

$emptyProductBlock = new stdclass();
$emptyProductBlock->id = 4;
$emptyProductBlock->dashboard = 'my';

$completenessTestBlock = new stdclass();
$completenessTestBlock->id = 5;
$completenessTestBlock->dashboard = 'my';

// 6. 强制要求：必须包含至少5个测试步骤

// 步骤1：测试正常产品查看路线图区块
global $tester;
$tester->app->session->product = 1;
r($blockTest->printRoadmapBlockTest($normalProductBlock)) && p('product:name') && e('正常产品1'); // 步骤1：正常情况

// 步骤2：测试不存在产品查看路线图区块（修复空值处理）
$tester->app->session->product = 999;
r($blockTest->printRoadmapBlockTest($nonExistProductBlock)) && p('product:name') && e('0'); // 步骤2：不存在产品

// 步骤3：测试多分支产品查看路线图区块
$tester->app->session->product = 2;
r($blockTest->printRoadmapBlockTest($branchProductBlock)) && p('product:type') && e('normal'); // 步骤3：多分支产品

// 步骤4：测试普通产品查看路线图区块
$tester->app->session->product = 1;
r($blockTest->printRoadmapBlockTest($emptyProductBlock)) && p('product:type') && e('normal'); // 步骤4：普通产品

// 步骤5：验证分支数量处理逻辑
$tester->app->session->product = 1;
r($blockTest->printRoadmapBlockTest($completenessTestBlock)) && p('branchCount') && e('1'); // 步骤5：分支数量验证