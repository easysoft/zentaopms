#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::getGroupCases();
timeout=0
cid=0

- 步骤1:正常情况下按story分组获取用例
 - 第1条的id属性 @1
 - 第1条的rowspan属性 @1
 - 第6条的id属性 @6
 - 第6条的rowspan属性 @1
- 步骤2:使用空groupBy参数获取用例
 - 第1条的id属性 @1
 - 第1条的rowspan属性 @0
 - 第6条的id属性 @6
 - 第6条的rowspan属性 @0
- 步骤3:使用不存在的productID获取用例 @0
- 步骤4:使用空branch参数按story分组
 - 第1条的id属性 @1
 - 第1条的rowspan属性 @1
 - 第6条的id属性 @6
 - 第6条的rowspan属性 @1
- 步骤5:测试不同caseType参数获取用例
 - 第11条的id属性 @11
 - 第11条的rowspan属性 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 准备测试数据
zenData('product')->loadYaml('product', false, 2)->gen(5);
zenData('story')->loadYaml('story', false, 2)->gen(20);

// 准备用例数据
$case = zenData('case');
$case->id->range('1-20');
$case->product->range('1-5');
$case->story->range('1-20');
$case->module->range('1-10');
$case->title->range('测试用例1,测试用例2,测试用例3,测试用例4,测试用例5');
$case->type->range('feature{10},performance{5},config{3},install{2}');
$case->pri->range('1-4');
$case->status->range('normal');
$case->openedBy->range('admin');
$case->deleted->range('0');
$case->gen(20);

// 准备模块数据
zenData('module')->loadYaml('module', false, 2)->gen(10);

su('admin');

$testcaseTest = new testcaseZenTest();

r($testcaseTest->getGroupCasesTest(1, '0', 'story', 'feature', 'all')) && p('1:id,rowspan;6:id,rowspan') && e('1,1;6,1'); // 步骤1:正常情况下按story分组获取用例
r($testcaseTest->getGroupCasesTest(1, '0', '', 'feature', 'all')) && p('1:id,rowspan;6:id,rowspan') && e('1,0;6,0'); // 步骤2:使用空groupBy参数获取用例
r($testcaseTest->getGroupCasesTest(9999, '0', 'story', 'feature', 'all')) && p() && e('0'); // 步骤3:使用不存在的productID获取用例
r($testcaseTest->getGroupCasesTest(1, '', 'story', 'feature', 'all')) && p('1:id,rowspan;6:id,rowspan') && e('1,1;6,1'); // 步骤4:使用空branch参数按story分组
r($testcaseTest->getGroupCasesTest(1, '0', 'story', 'performance', 'all')) && p('11:id,rowspan') && e('11,1'); // 步骤5:测试不同caseType参数获取用例