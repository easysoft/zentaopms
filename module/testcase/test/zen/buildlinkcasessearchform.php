#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::buildLinkCasesSearchForm();
timeout=0
cid=0

- 步骤1：正常情况，product字段应被移除属性hasProductField @0
- 步骤2：project标签页，objectID为项目ID属性objectID @1
- 步骤3：execution标签页，objectID为执行ID属性objectID @1
- 步骤4：确认product字段被移除属性hasProductField @0
- 步骤5：验证actionURL格式属性actionURL @/testcase-linkCases-caseID=1&browseType=bySearch&queryID=myQueryID

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$caseTable = zenData('case');
$caseTable->id->range('1-5');
$caseTable->product->range('1-2');
$caseTable->project->range('1-3');
$caseTable->execution->range('1-3');
$caseTable->title->range('TestCase{1-5}');
$caseTable->status->range('normal{3},wait{2}');
$caseTable->type->range('feature{3},interface{2}');
$caseTable->gen(5);

$productTable = zenData('product');
$productTable->id->range('1-2');
$productTable->name->range('Product{1-2}');
$productTable->type->range('normal');
$productTable->status->range('normal');
$productTable->gen(2);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
$case = new stdclass();
$case->id = 1;
$case->product = 1;
$case->project = 1;
$case->execution = 1;

r($testcaseTest->buildLinkCasesSearchFormTest($case, 0)) && p('hasProductField') && e('0'); // 步骤1：正常情况，product字段应被移除

global $tester;
$tester->app->tab = 'project';
r($testcaseTest->buildLinkCasesSearchFormTest($case, 0)) && p('objectID') && e('1'); // 步骤2：project标签页，objectID为项目ID

$tester->app->tab = 'execution';
r($testcaseTest->buildLinkCasesSearchFormTest($case, 0)) && p('objectID') && e('1'); // 步骤3：execution标签页，objectID为执行ID

$tester->app->tab = '';
r($testcaseTest->buildLinkCasesSearchFormTest($case, 1)) && p('hasProductField') && e('0'); // 步骤4：确认product字段被移除

r($testcaseTest->buildLinkCasesSearchFormTest($case, 5)) && p('actionURL') && e('/testcase-linkCases-caseID=1&browseType=bySearch&queryID=myQueryID'); // 步骤5：验证actionURL格式