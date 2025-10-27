#!/usr/bin/env php
<?php

/**

title=测试 testtaskZen::responseAfterRunCase();
timeout=0
cid=0

- 步骤1：fail结果处理有错误 @error
- 步骤2：pass结果无下一个用例返回null @0
- 步骤3：pass结果有下一个用例返回null @0
- 步骤4：my标签页处理返回null @0
- 步骤5：空对象处理返回null @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testtaskzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('testtask');
$table->id->range('1-10');
$table->name->range('测试单1,测试单2,测试单3');
$table->product->range('1-3');
$table->status->range('wait,doing,done');
$table->gen(3);

$runTable = zenData('testrun');
$runTable->id->range('1-10');
$runTable->task->range('1-3');
$runTable->case->range('1-10');
$runTable->version->range('1-3');
$runTable->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testtaskTest = new testtaskZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 步骤1：测试用例结果为fail时的处理
$preAndNext1 = new stdclass();
$preAndNext1->next = null;
$preAndNext1->pre = null;
$run1 = new stdclass();
$run1->id = 1;
$run1->task = 1;
$result1 = $testtaskTest->responseAfterRunCaseTest('fail', $preAndNext1, $run1, 1, 1);
r(strpos((string)$result1, 'error') !== false ? 'error' : $result1) && p() && e('error'); // 步骤1：fail结果处理有错误

// 步骤2：测试用例结果为pass且无下一个用例时的处理
$preAndNext2 = new stdclass();
$preAndNext2->next = null;
$preAndNext2->pre = null;
$run2 = new stdclass();
$run2->id = 2;
$run2->task = 0;
$result2 = $testtaskTest->responseAfterRunCaseTest('pass', $preAndNext2, $run2, 2, 1);
r($result2) && p() && e(0); // 步骤2：pass结果无下一个用例返回null

// 步骤3：测试用例结果为pass且有下一个用例且非my标签页时的处理
global $app;
$app->tab = 'qa';
$preAndNext3 = new stdclass();
$preAndNext3->next = new stdclass();
$preAndNext3->next->id = 3;
$preAndNext3->next->case = 3;
$preAndNext3->next->version = 1;
$preAndNext3->pre = null;
$run3 = new stdclass();
$run3->id = 3;
$run3->task = 0;
$result3 = $testtaskTest->responseAfterRunCaseTest('pass', $preAndNext3, $run3, 3, 1);
r($result3) && p() && e(0); // 步骤3：pass结果有下一个用例返回null

// 步骤4：测试用例结果为pass且有下一个用例但在my标签页时的处理
$app->tab = 'my';
$preAndNext4 = new stdclass();
$preAndNext4->next = new stdclass();
$preAndNext4->next->id = 4;
$preAndNext4->next->case = 4;
$preAndNext4->next->version = 1;
$preAndNext4->pre = null;
$run4 = new stdclass();
$run4->id = 4;
$run4->task = 0;
$result4 = $testtaskTest->responseAfterRunCaseTest('pass', $preAndNext4, $run4, 4, 1);
r($result4) && p() && e(0); // 步骤4：my标签页处理返回null

// 步骤5：测试空运行对象时的边界情况处理
$preAndNext5 = new stdclass();
$preAndNext5->next = null;
$preAndNext5->pre = null;
$run5 = new stdclass();
$run5->id = 0;
$run5->task = 0;
$result5 = $testtaskTest->responseAfterRunCaseTest('pass', $preAndNext5, $run5, 5, 1);
r($result5) && p() && e(0); // 步骤5：空对象处理返回null