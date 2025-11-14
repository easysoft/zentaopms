#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::assignShowImportVars();
timeout=0
cid=0

- 步骤1:正常用例数据导入
 - 属性caseDataCount @2
 - 属性hasModules @1
 - 属性hasStories @1
- 步骤2:空用例数据处理 @noData
- 步骤3:小数据量不触发分页
 - 属性caseDataCount @3
 - 属性pagerID @1
 - 属性isEndPage @1
 - 属性allCount @3
- 步骤4:验证pagerID参数传递
 - 属性caseDataCount @3
 - 属性pagerID @2
- 步骤5:检查suhosin限制提示属性hasSuhosinInfo @0
- 步骤6:多分支产品导入
 - 属性caseDataCount @2
 - 属性hasBranches @1
- 步骤7:验证allCount和isEndPage
 - 属性caseDataCount @2
 - 属性allCount @2
 - 属性isEndPage @1

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. zendata数据准备(根据需要配置)
$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品1,产品2,产品3,产品4,产品5');
$product->type->range('normal{3},branch{2}');
$product->gen(5);

$branch = zenData('branch');
$branch->id->range('1-5');
$branch->product->range('4{3},5{2}');
$branch->name->range('分支1,分支2,分支3,分支4,分支5');
$branch->status->range('active{5}');
$branch->gen(5);

$module = zenData('module');
$module->id->range('1-10');
$module->root->range('1-5{2}');
$module->branch->range('0{6},1{2},2{2}');
$module->name->range('模块1,模块2,模块3,模块4,模块5,模块6,模块7,模块8,模块9,模块10');
$module->type->range('case{10}');
$module->parent->range('0{10}');
$module->grade->range('1{10}');
$module->path->range('`,1,`,`,2,`,`,3,`,`,4,`,`,5,`,`,6,`,`,7,`,`,8,`,`,9,`,`,10,`');
$module->deleted->range('0{10}');
$module->gen(10);

$story = zenData('story');
$story->id->range('1-10');
$story->product->range('1-5{2}');
$story->branch->range('0{6},1{2},2{2}');
$story->module->range('1-10');
$story->title->range('需求1,需求2,需求3,需求4,需求5,需求6,需求7,需求8,需求9,需求10');
$story->type->range('story{10}');
$story->status->range('active{10}');
$story->stage->range('wait{5},planned{5}');
$story->version->range('1{10}');
$story->gen(10);

// 3. 用户登录(选择合适角色)
su('admin');

// 4. 创建测试实例(变量名与模块名一致)
$testcaseTest = new testcaseZenTest();

// 5. 强制要求:必须包含至少5个测试步骤
r($testcaseTest->assignShowImportVarsTest(1, '0', array(array('id' => 1, 'title' => '测试用例1'), array('id' => 2, 'title' => '测试用例2')), 10, 1, 0)) && p('caseDataCount,hasModules,hasStories') && e('2,1,1'); // 步骤1:正常用例数据导入
r($testcaseTest->assignShowImportVarsTest(1, '0', array(), 0, 1, 0)) && p() && e('noData'); // 步骤2:空用例数据处理
r($testcaseTest->assignShowImportVarsTest(1, '0', array(array('id' => 1), array('id' => 2), array('id' => 3)), 0, 1, 2)) && p('caseDataCount,pagerID,isEndPage,allCount') && e('3,1,1,3'); // 步骤3:小数据量不触发分页
r($testcaseTest->assignShowImportVarsTest(1, '0', array(array('id' => 1), array('id' => 2), array('id' => 3)), 0, 2, 2)) && p('caseDataCount,pagerID') && e('3,2'); // 步骤4:验证pagerID参数传递
r($testcaseTest->assignShowImportVarsTest(1, '0', array(array('id' => 1), array('id' => 2)), 500, 1, 0)) && p('hasSuhosinInfo') && e('0'); // 步骤5:检查suhosin限制提示
r($testcaseTest->assignShowImportVarsTest(4, '1', array(array('id' => 1), array('id' => 2)), 0, 1, 0)) && p('caseDataCount,hasBranches') && e('2,1'); // 步骤6:多分支产品导入
r($testcaseTest->assignShowImportVarsTest(1, '0', array(array('id' => 1), array('id' => 2)), 0, 1, 1)) && p('caseDataCount,allCount,isEndPage') && e('2,2,1'); // 步骤7:验证allCount和isEndPage