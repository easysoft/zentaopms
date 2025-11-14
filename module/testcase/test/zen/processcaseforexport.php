#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::processCaseForExport();
timeout=0
cid=0

- 测试产品字段格式化属性product @产品A(#1)
- 测试模块字段格式化属性module @模块A(#1)
- 测试需求字段格式化属性story @需求A(#1)
- 测试带分支的用例导出数据格式化属性branch @分支A(#1)
- 测试带场景的用例导出数据格式化属性scene @场景A(#1)
- 测试日期格式化属性openedDate @2024-01-01

*/

// 1. 导入依赖（路径固定,不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('case');
$table->id->range('1-10');
$table->product->range('1-3');
$table->branch->range('0{3},1{3},2{4}');
$table->module->range('1-3');
$table->lib->range('0');
$table->story->range('0,1{3},2{3},3{3}');
$table->scene->range('0{5},1{3},2{2}');
$table->title->range('测试用例1,测试用例2,测试用例3,测试用例4,测试用例5');
$table->type->range('feature{5},performance{3},config{2}');
$table->pri->range('1-4');
$table->status->range('normal{8},blocked{2}');
$table->stage->range('unittest{3},feature{4},integrate{3}');
$table->openedBy->range('admin{5},user1{3},tester{2}');
$table->openedDate->range('`2024-01-01 10:00:00`');
$table->lastEditedBy->range('admin{3},user1{4},tester{3}');
$table->lastEditedDate->range('`2024-01-15 15:30:00`');
$table->lastRunner->range('admin{4},tester{3},user1{3}');
$table->lastRunDate->range('`2024-02-01 09:00:00`');
$table->lastRunResult->range('pass{4},fail{3},blocked{3}');
$table->deleted->range('0');
$table->gen(10);

$productTable = zenData('product');
$productTable->id->range('1-5');
$productTable->name->range('产品A,产品B,产品C,产品D,产品E');
$productTable->type->range('normal{2},branch{2},platform{1}');
$productTable->status->range('normal');
$productTable->deleted->range('0');
$productTable->gen(5);

$branchTable = zenData('branch');
$branchTable->id->range('1-5');
$branchTable->product->range('2{3},3{2}');
$branchTable->name->range('分支A,分支B,分支C,分支D,分支E');
$branchTable->status->range('active');
$branchTable->deleted->range('0');
$branchTable->gen(5);

$moduleTable = zenData('module');
$moduleTable->id->range('1-10');
$moduleTable->root->range('1{5},2{3},3{2}');
$moduleTable->name->range('模块A,模块B,模块C,模块D,模块E');
$moduleTable->type->range('case');
$moduleTable->parent->range('0');
$moduleTable->grade->range('1');
$moduleTable->deleted->range('0');
$moduleTable->gen(10);

$storyTable = zenData('story');
$storyTable->id->range('1-5');
$storyTable->product->range('1-3');
$storyTable->title->range('需求A,需求B,需求C,需求D,需求E');
$storyTable->type->range('story');
$storyTable->status->range('active');
$storyTable->deleted->range('0');
$storyTable->gen(5);

$sceneTable = zenData('scene');
$sceneTable->id->range('1-5');
$sceneTable->product->range('1-3');
$sceneTable->title->range('场景A,场景B,场景C,场景D,场景E');
$sceneTable->deleted->range('0');
$sceneTable->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseZenTest();

// 准备测试数据
$case1 = new stdClass();
$case1->id = 1;
$case1->product = 1;
$case1->branch = 0;
$case1->module = 1;
$case1->story = 1;
$case1->scene = 0;
$case1->pri = 1;
$case1->type = 'feature';
$case1->status = 'normal';
$case1->openedBy = 'admin';
$case1->openedDate = '2024-01-01 10:00:00';
$case1->lastEditedBy = 'admin';
$case1->lastEditedDate = '2024-01-15 15:30:00';
$case1->lastRunner = 'admin';
$case1->lastRunDate = '2024-02-01 09:00:00';
$case1->lastRunResult = 'pass';
$case1->stage = 'unittest,feature';
$case1->bugs = 1;
$case1->results = 2;
$case1->stepNumber = 3;
$case1->caseFails = 0;
$case1->linkCase = '';

$case2 = clone $case1;
$case2->id = 2;
$case2->product = 2;
$case2->branch = 1;
$case2->module = 2;

$case3 = clone $case1;
$case3->id = 3;
$case3->product = 3;
$case3->scene = 1;
$case3->module = 3;

$products = array(1 => '产品A', 2 => '产品B', 3 => '产品C');
$branches = array(1 => '分支A', 2 => '分支B');
$users = array('admin' => '管理员', 'user1' => '普通用户', 'tester' => '测试用户');
$results = array();
$relatedModules = array(1 => '模块A', 2 => '模块B', 3 => '模块C');
$relatedStories = array(1 => '需求A', 2 => '需求B', 3 => '需求C');
$relatedCases = array();
$relatedSteps = array();
$relatedFiles = array();
$relatedScenes = array(1 => '场景A', 2 => '场景B');

// 5. 🔴 强制要求:必须包含至少5个测试步骤
r($testcaseTest->processCaseForExportTest($case1, $products, $branches, $users, $results, $relatedModules, $relatedStories, $relatedCases, $relatedSteps, $relatedFiles, $relatedScenes)) && p('product') && e('产品A(#1)'); // 测试产品字段格式化
r($testcaseTest->processCaseForExportTest($case1, $products, $branches, $users, $results, $relatedModules, $relatedStories, $relatedCases, $relatedSteps, $relatedFiles, $relatedScenes)) && p('module') && e('模块A(#1)'); // 测试模块字段格式化
r($testcaseTest->processCaseForExportTest($case1, $products, $branches, $users, $results, $relatedModules, $relatedStories, $relatedCases, $relatedSteps, $relatedFiles, $relatedScenes)) && p('story') && e('需求A(#1)'); // 测试需求字段格式化
r($testcaseTest->processCaseForExportTest($case2, $products, $branches, $users, $results, $relatedModules, $relatedStories, $relatedCases, $relatedSteps, $relatedFiles, $relatedScenes)) && p('branch') && e('分支A(#1)'); // 测试带分支的用例导出数据格式化
r($testcaseTest->processCaseForExportTest($case3, $products, $branches, $users, $results, $relatedModules, $relatedStories, $relatedCases, $relatedSteps, $relatedFiles, $relatedScenes)) && p('scene') && e('场景A(#1)'); // 测试带场景的用例导出数据格式化
r($testcaseTest->processCaseForExportTest($case1, $products, $branches, $users, $results, $relatedModules, $relatedStories, $relatedCases, $relatedSteps, $relatedFiles, $relatedScenes)) && p('openedDate') && e('2024-01-01'); // 测试日期格式化