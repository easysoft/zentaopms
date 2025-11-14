#!/usr/bin/env php
<?php

/**

title=测试 testtaskZen::processRowspanForUnitCases();
timeout=0
cid=19240

- 期望返回空数组 @0
- 第一个用例rowspan=3，其他为0
 - 第0条的rowspan属性 @3
 - 第1条的rowspan属性 @0
 - 第2条的rowspan属性 @0
- 每个套件第一个用例有正确rowspan
 - 第0条的rowspan属性 @2
 - 第1条的rowspan属性 @0
 - 第2条的rowspan属性 @3
 - 第3条的rowspan属性 @0
 - 第4条的rowspan属性 @0
 - 第5条的rowspan属性 @1
- 每次遇到不同suite时都重新赋值rowspan
 - 第0条的rowspan属性 @3
 - 第1条的rowspan属性 @2
 - 第2条的rowspan属性 @3
 - 第3条的rowspan属性 @2
 - 第4条的rowspan属性 @3
- 空套件名也能正确分组，每次遇到空套件时重新赋值
 - 第0条的rowspan属性 @3
 - 第1条的rowspan属性 @0
 - 第2条的rowspan属性 @1
 - 第3条的rowspan属性 @3

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testtaskzen.unittest.class.php';

// 2. zendata数据准备（不需要数据库数据，直接创建测试对象）

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testtaskTest = new testtaskZenTest();

// 5. 测试步骤：必须包含至少5个测试步骤

// 测试步骤1：空数组输入情况
r($testtaskTest->processRowspanForUnitCasesTest(array())) && p() && e('0'); // 期望返回空数组

// 测试步骤2：单个套件多个用例
$runs1 = array();
for($i = 1; $i <= 3; $i++)
{
    $run = new stdclass();
    $run->id = $i;
    $run->suite = 'phpunit';
    $run->case = $i;
    $run->rowspan = 0;
    $runs1[] = $run;
}
r($testtaskTest->processRowspanForUnitCasesTest($runs1)) && p('0:rowspan;1:rowspan;2:rowspan') && e('3;0;0'); // 第一个用例rowspan=3，其他为0

// 测试步骤3：多个套件混合情况
$runs2 = array();
$suites = array('suite1' => 2, 'suite2' => 3, 'suite3' => 1);
$id = 1;
foreach($suites as $suiteName => $count)
{
    for($i = 0; $i < $count; $i++)
    {
        $run = new stdclass();
        $run->id = $id++;
        $run->suite = $suiteName;
        $run->case = $run->id;
        $run->rowspan = 0;
        $runs2[] = $run;
    }
}
r($testtaskTest->processRowspanForUnitCasesTest($runs2)) && p('0:rowspan;1:rowspan;2:rowspan;3:rowspan;4:rowspan;5:rowspan') && e('2;0;3;0;0;1'); // 每个套件第一个用例有正确rowspan

// 测试步骤4：相同套件分散分布情况
$runs3 = array();
$caseData = array(
    array('id' => 1, 'suite' => 'junit', 'case' => 1),
    array('id' => 2, 'suite' => 'pytest', 'case' => 2),
    array('id' => 3, 'suite' => 'junit', 'case' => 3),
    array('id' => 4, 'suite' => 'pytest', 'case' => 4),
    array('id' => 5, 'suite' => 'junit', 'case' => 5)
);
foreach($caseData as $data)
{
    $run = new stdclass();
    $run->id = $data['id'];
    $run->suite = $data['suite'];
    $run->case = $data['case'];
    $run->rowspan = 0;
    $runs3[] = $run;
}
r($testtaskTest->processRowspanForUnitCasesTest($runs3)) && p('0:rowspan;1:rowspan;2:rowspan;3:rowspan;4:rowspan') && e('3;2;3;2;3'); // 每次遇到不同suite时都重新赋值rowspan

// 测试步骤5：套件名称为空的边界情况
$runs4 = array();
$emptySuiteData = array(
    array('id' => 1, 'suite' => '', 'case' => 1),
    array('id' => 2, 'suite' => '', 'case' => 2),
    array('id' => 3, 'suite' => 'valid_suite', 'case' => 3),
    array('id' => 4, 'suite' => '', 'case' => 4)
);
foreach($emptySuiteData as $data)
{
    $run = new stdclass();
    $run->id = $data['id'];
    $run->suite = $data['suite'];
    $run->case = $data['case'];
    $run->rowspan = 0;
    $runs4[] = $run;
}
r($testtaskTest->processRowspanForUnitCasesTest($runs4)) && p('0:rowspan;1:rowspan;2:rowspan;3:rowspan') && e('3;0;1;3'); // 空套件名也能正确分组，每次遇到空套件时重新赋值