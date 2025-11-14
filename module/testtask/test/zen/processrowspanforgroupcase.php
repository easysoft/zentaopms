#!/usr/bin/env php
<?php

/**

title=测试 testtaskZen::processRowspanForGroupCase();
timeout=0
cid=19239

- 步骤1：空用例数组测试 @0
- 步骤2：多个需求正常处理，第一个需求的第一个用例rowspan为2第0条的rowspan属性 @2
- 步骤3：单个需求单个用例，rowspan为1第0条的rowspan属性 @1
- 步骤4：带构建ID的正常处理第0条的rowspan属性 @1
- 步骤5：需求ID为0的边界处理第0条的rowspan属性 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testtaskzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$storyTable = zenData('story');
$storyTable->id->range('1-10');
$storyTable->title->range('需求1,需求2,需求3,需求4,需求5,需求6,需求7,需求8,需求9,需求10');
$storyTable->deleted->range('0');
$storyTable->gen(10);

$buildTable = zenData('build');
$buildTable->id->range('1-5');
$buildTable->stories->range('1,2,3|2,3,4|3,4,5|4,5,6|5,6,7');
$buildTable->gen(5);

$caseTable = zenData('case');
$caseTable->id->range('1-20');
$caseTable->story->range('1{5},2{3},3{4},4{2},5{1}');
$caseTable->title->range('测试用例1,测试用例2,测试用例3,测试用例4,测试用例5,测试用例6,测试用例7,测试用例8,测试用例9,测试用例10{10}');
$caseTable->gen(15);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testtaskTest = new testtaskZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testtaskTest->processRowspanForGroupCaseTest(array(), '')) && p() && e('0'); // 步骤1：空用例数组测试

// 构造测试数据：多个需求的用例
$cases = array();
$case1 = new stdclass();
$case1->id = 1;
$case1->story = 1;
$case1->title = '测试用例1';
$case1->rowspan = 0;
$cases[] = $case1;

$case2 = new stdclass();
$case2->id = 2;
$case2->story = 1;
$case2->title = '测试用例2';
$case2->rowspan = 0;
$cases[] = $case2;

$case3 = new stdclass();
$case3->id = 3;
$case3->story = 2;
$case3->title = '测试用例3';
$case3->rowspan = 0;
$cases[] = $case3;

r($testtaskTest->processRowspanForGroupCaseTest($cases, '')) && p('0:rowspan') && e('2'); // 步骤2：多个需求正常处理，第一个需求的第一个用例rowspan为2

// 构造单个需求单个用例的测试数据
$singleCases = array();
$singleCase = new stdclass();
$singleCase->id = 1;
$singleCase->story = 1;
$singleCase->title = '单个测试用例';
$singleCase->rowspan = 0;
$singleCases[] = $singleCase;

r($testtaskTest->processRowspanForGroupCaseTest($singleCases, '')) && p('0:rowspan') && e('1'); // 步骤3：单个需求单个用例，rowspan为1

// 测试带构建ID的情况
$casesWithBuild = array();
$buildCase1 = new stdclass();
$buildCase1->id = 1;
$buildCase1->story = 1;
$buildCase1->title = '构建测试用例1';
$buildCase1->rowspan = 0;
$casesWithBuild[] = $buildCase1;

$buildCase2 = new stdclass();
$buildCase2->id = 2;
$buildCase2->story = 2;
$buildCase2->title = '构建测试用例2';
$buildCase2->rowspan = 0;
$casesWithBuild[] = $buildCase2;

r($testtaskTest->processRowspanForGroupCaseTest($casesWithBuild, '1')) && p('0:rowspan') && e('1'); // 步骤4：带构建ID的正常处理

// 测试需求ID为0的边界情况
$zeroCases = array();
$zeroCase = new stdclass();
$zeroCase->id = 1;
$zeroCase->story = 0;
$zeroCase->title = '无需求用例';
$zeroCase->rowspan = 0;
$zeroCases[] = $zeroCase;

r($testtaskTest->processRowspanForGroupCaseTest($zeroCases, '')) && p('0:rowspan') && e('1'); // 步骤5：需求ID为0的边界处理