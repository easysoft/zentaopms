#!/usr/bin/env php
<?php

/**

title=测试 testtaskZen::setSearchParamsForCases();
timeout=0
cid=19243

- 步骤1：qa应用下正常产品和测试单设置搜索参数 @1
- 步骤2：project应用下设置搜索参数 @1
- 步骤3：execution应用下设置搜索参数 @1
- 步骤4：影子产品设置搜索参数 @1
- 步骤5：多分支产品设置搜索参数 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testtaskzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品1,产品2,产品3,影子产品,多分支产品');
$product->type->range('normal{3},normal,branch');
$product->shadow->range('0{4},1');
$product->gen(5);

$testtask = zenData('testtask');
$testtask->id->range('1-3');
$testtask->name->range('测试单1,测试单2,测试单3');
$testtask->project->range('1-3');
$testtask->execution->range('1-3');
$testtask->gen(3);

$module = zenData('module');
$module->id->range('1-10');
$module->root->range('1-5');
$module->name->range('模块1,模块2,模块3,模块4,模块5,模块6,模块7,模块8,模块9,模块10');
$module->type->range('case{10}');
$module->gen(10);

$story = zenData('story');
$story->id->range('1-10');
$story->title->range('需求1,需求2,需求3,需求4,需求5,需求6,需求7,需求8,需求9,需求10');
$story->product->range('1-5');
$story->status->range('active{10}');
$story->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testtaskTest = new testtaskZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
$result1 = $testtaskTest->setSearchParamsForCasesTest();
r(!dao::isError()) && p() && e('1'); // 步骤1：qa应用下正常产品和测试单设置搜索参数

// 构建project应用下的测试对象
$product2 = new stdclass();
$product2->id = 2;
$product2->name = '产品2';
$product2->shadow = 0;
$product2->type = 'normal';
$testtask2 = new stdclass();
$testtask2->id = 2;
$testtask2->project = 2;
$testtask2->execution = 2;
$result2 = $testtaskTest->setSearchParamsForCasesTest($product2, 1, $testtask2, 0, 'project');
r(!dao::isError()) && p() && e('1'); // 步骤2：project应用下设置搜索参数

// 构建execution应用下的测试对象
$product3 = new stdclass();
$product3->id = 3;
$product3->name = '产品3';
$product3->shadow = 0;
$product3->type = 'normal';
$testtask3 = new stdclass();
$testtask3->id = 3;
$testtask3->project = 3;
$testtask3->execution = 3;
$result3 = $testtaskTest->setSearchParamsForCasesTest($product3, 2, $testtask3, 0, 'execution');
r(!dao::isError()) && p() && e('1'); // 步骤3：execution应用下设置搜索参数

// 构建影子产品测试对象
$shadowProduct = new stdclass();
$shadowProduct->id = 4;
$shadowProduct->name = '影子产品';
$shadowProduct->shadow = 1;
$shadowProduct->type = 'normal';
$testtask4 = new stdclass();
$testtask4->id = 1;
$testtask4->project = 1;
$testtask4->execution = 1;
$result4 = $testtaskTest->setSearchParamsForCasesTest($shadowProduct, 0, $testtask4, 0);
r(!dao::isError()) && p() && e('1'); // 步骤4：影子产品设置搜索参数

// 构建多分支产品测试对象
$branchProduct = new stdclass();
$branchProduct->id = 5;
$branchProduct->name = '多分支产品';
$branchProduct->shadow = 0;
$branchProduct->type = 'branch';
$testtask5 = new stdclass();
$testtask5->id = 1;
$testtask5->project = 1;
$testtask5->execution = 1;
$result5 = $testtaskTest->setSearchParamsForCasesTest($branchProduct, 3, $testtask5, 1);
r(!dao::isError()) && p() && e('1'); // 步骤5：多分支产品设置搜索参数