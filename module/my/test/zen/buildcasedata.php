#!/usr/bin/env php
<?php

/**

title=测试 myZen::buildCaseData();
timeout=0
cid=0

- 步骤1：空用例数组处理 @0
- 步骤2：正常用例处理 @1
- 步骤3：失败结果保持 @fail
- 步骤4：空执行结果处理 @未执行
- 步骤5：不同type参数处理 @1
- 步骤6：阻塞结果统计验证 @blocked
- 步骤7：基本属性保持验证 @测试用例6

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/my.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('case');
$table->id->range('1-10');
$table->product->range('1-3');
$table->title->range('测试用例{1-10}');
$table->status->range('normal{5},wait{3},blocked{2}');
$table->lastRunResult->range('pass{3},fail{2},blocked{1},""4');
$table->version->range('1-5');
$table->fromCaseVersion->range('1{5},6{5}');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$myTest = new myTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($myTest->buildCaseDataTest(array(), 'assigntome')) && p() && e('0'); // 步骤1：空用例数组处理
r(count($myTest->buildCaseDataTest(array((object)array('id' => 1, 'title' => '测试用例1', 'lastRunResult' => 'pass', 'version' => 1, 'fromCaseVersion' => 1)), 'assigntome'))) && p() && e('1'); // 步骤2：正常用例处理
r($myTest->buildCaseDataTest(array((object)array('id' => 2, 'title' => '测试用例2', 'lastRunResult' => 'fail', 'version' => 1, 'fromCaseVersion' => 1)), 'assigntome')[0]->lastRunResult) && p() && e('fail'); // 步骤3：失败结果保持
r($myTest->buildCaseDataTest(array((object)array('id' => 3, 'title' => '测试用例3', 'lastRunResult' => '', 'version' => 1, 'fromCaseVersion' => 1)), 'assigntome')[0]->lastRunResult) && p() && e('未执行'); // 步骤4：空执行结果处理
r(count($myTest->buildCaseDataTest(array((object)array('id' => 4, 'title' => '测试用例4', 'lastRunResult' => 'pass', 'version' => 1, 'fromCaseVersion' => 1)), 'openedbyme'))) && p() && e('1'); // 步骤5：不同type参数处理
r($myTest->buildCaseDataTest(array((object)array('id' => 5, 'title' => '测试用例5', 'lastRunResult' => 'blocked', 'version' => 1, 'fromCaseVersion' => 1)), 'assigntome')[0]->lastRunResult) && p() && e('blocked'); // 步骤6：阻塞结果统计验证
r($myTest->buildCaseDataTest(array((object)array('id' => 6, 'title' => '测试用例6', 'lastRunResult' => 'pass', 'version' => 1, 'fromCaseVersion' => 1)), 'assigntome')[0]->title) && p() && e('测试用例6'); // 步骤7：基本属性保持验证