#!/usr/bin/env php
<?php

/**

title=测试 docModel::getObjectIDByLib();
timeout=0
cid=16115

- 步骤1：product类型返回product字段 @15
- 步骤2：custom类型返回parent字段 @10
- 步骤3：mine类型返回parent字段 @8
- 步骤4：空参数返回0 @0
- 步骤5：指定libType为execution @3
- 步骤6：指定libType为product @5
- 步骤7：execution类型返回execution字段 @20

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 2. zendata数据准备
$table = zenData('doclib');
$table->loadYaml('doclib_getobjectidbylib', false, 2)->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$docTest = new docTest();

// 准备测试用的doclib对象
$productLib = new stdClass();
$productLib->id = 1;
$productLib->type = 'product';
$productLib->product = 15;
$productLib->project = 0;
$productLib->execution = 0;
$productLib->parent = 0;

$customLib = new stdClass();
$customLib->id = 2;
$customLib->type = 'custom';
$customLib->product = 0;
$customLib->project = 0;
$customLib->execution = 0;
$customLib->parent = 10;

$mineLib = new stdClass();
$mineLib->id = 3;
$mineLib->type = 'mine';
$mineLib->product = 0;
$mineLib->project = 0;
$mineLib->execution = 0;
$mineLib->parent = 8;

$projectLib = new stdClass();
$projectLib->id = 4;
$projectLib->type = 'project';
$projectLib->product = 5;
$projectLib->project = 12;
$projectLib->execution = 3;
$projectLib->parent = 0;

$executionLib = new stdClass();
$executionLib->id = 5;
$executionLib->type = 'execution';
$executionLib->product = 2;
$executionLib->project = 8;
$executionLib->execution = 20;
$executionLib->parent = 0;

// 5. 执行测试步骤
r($docTest->getObjectIDByLibTest($productLib)) && p() && e('15'); // 步骤1：product类型返回product字段
r($docTest->getObjectIDByLibTest($customLib)) && p() && e('10'); // 步骤2：custom类型返回parent字段
r($docTest->getObjectIDByLibTest($mineLib)) && p() && e('8'); // 步骤3：mine类型返回parent字段
r($docTest->getObjectIDByLibTest(null)) && p() && e('0'); // 步骤4：空参数返回0
r($docTest->getObjectIDByLibTest($projectLib, 'execution')) && p() && e('3'); // 步骤5：指定libType为execution
r($docTest->getObjectIDByLibTest($projectLib, 'product')) && p() && e('5'); // 步骤6：指定libType为product
r($docTest->getObjectIDByLibTest($executionLib)) && p() && e('20'); // 步骤7：execution类型返回execution字段