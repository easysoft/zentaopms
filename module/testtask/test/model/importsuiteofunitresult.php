#!/usr/bin/env php
<?php

/**

title=测试 testtaskModel::importSuiteOfUnitResult();
timeout=0
cid=19201

- 步骤1：正常情况 @4
- 步骤2：完整信息 @5
- 步骤3：空描述 @6
- 步骤4：最小信息 @7
- 步骤5：验证完整性 @8

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testtask.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('testsuite');
$table->id->range('1-10');
$table->project->range('0');
$table->product->range('1');
$table->name->range('TestSuite1,TestSuite2,TestSuite3');
$table->desc->range('Description1,Description2,');
$table->type->range('unit');
$table->order->range('0');
$table->addedBy->range('admin');
$table->addedDate->range('`2023-01-01 00:00:00`');
$table->lastEditedBy->range('');
$table->lastEditedDate->range('`0000-00-00 00:00:00`');
$table->deleted->range('0');
$table->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testtaskTest = new testtaskTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testtaskTest->importSuiteOfUnitResultTest((object)array('project' => 1, 'product' => 1, 'name' => 'UnitTestSuite1', 'desc' => 'Unit test suite description', 'type' => 'unit', 'order' => 1, 'addedBy' => 'admin', 'addedDate' => helper::now(), 'lastEditedBy' => '', 'lastEditedDate' => null, 'deleted' => '0'))) && p() && e('4'); // 步骤1：正常情况
r($testtaskTest->importSuiteOfUnitResultTest((object)array('project' => 2, 'product' => 2, 'name' => 'CompleteTestSuite', 'desc' => 'Complete suite with all fields', 'type' => 'unit', 'order' => 5, 'addedBy' => 'admin', 'addedDate' => helper::now(), 'lastEditedBy' => 'admin', 'lastEditedDate' => helper::now(), 'deleted' => '0'))) && p() && e('5'); // 步骤2：完整信息
r($testtaskTest->importSuiteOfUnitResultTest((object)array('project' => 0, 'product' => 1, 'name' => 'EmptyDescSuite', 'desc' => '', 'type' => 'unit', 'order' => 0, 'addedBy' => 'admin', 'addedDate' => helper::now(), 'lastEditedBy' => '', 'lastEditedDate' => null, 'deleted' => '0'))) && p() && e('6'); // 步骤3：空描述
r($testtaskTest->importSuiteOfUnitResultTest((object)array('project' => 0, 'product' => 1, 'name' => 'MinimalSuite', 'desc' => 'Minimal info', 'type' => 'unit', 'order' => 0, 'addedBy' => 'admin', 'addedDate' => helper::now(), 'lastEditedBy' => '', 'lastEditedDate' => null, 'deleted' => '0'))) && p() && e('7'); // 步骤4：最小信息
r($testtaskTest->importSuiteOfUnitResultTest((object)array('project' => 1, 'product' => 1, 'name' => 'VerificationSuite', 'desc' => 'For verification test', 'type' => 'unit', 'order' => 10, 'addedBy' => 'admin', 'addedDate' => helper::now(), 'lastEditedBy' => '', 'lastEditedDate' => null, 'deleted' => '0'))) && p() && e('8'); // 步骤5：验证完整性