#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printQaStatisticBlock();
timeout=0
cid=15280

- 步骤1：正常情况属性success @1
- 步骤2：包含无效字符的type属性success @1
- 步骤3：空type参数属性success @1
- 步骤4：包含count参数属性success @1
- 步骤5：完全空参数属性success @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('product');
$table->id->range('1-10');
$table->name->range('产品1,产品2,产品3,产品4,产品5{5}');
$table->status->range('normal{8},closed{2}');
$table->type->range('normal{9},branch{1}');
$table->shadow->range('0{8},1{2}');
$table->deleted->range('0');
$table->gen(10);

$projectTable = zenData('project');
$projectTable->id->range('1-5');
$projectTable->name->range('项目1,项目2,项目3,项目4,项目5');
$projectTable->type->range('project{5}');
$projectTable->status->range('doing{3},closed{2}');
$projectTable->deleted->range('0');
$projectTable->gen(5);

$projectProductTable = zenData('projectproduct');
$projectProductTable->project->range('1-5');
$projectProductTable->product->range('9,10,1,2,3');
$projectProductTable->gen(5);

$testtaskTable = zenData('testtask');
$testtaskTable->id->range('1-10');
$testtaskTable->product->range('1-10');
$testtaskTable->project->range('1-5:R');
$testtaskTable->name->range('测试任务1,测试任务2,测试任务3{8}');
$testtaskTable->status->range('wait{3},doing{3},done{4}');
$testtaskTable->deleted->range('0');
$testtaskTable->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$blockTest = new blockTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($blockTest->printQaStatisticBlockTest((object)array('params' => (object)array('type' => 'normal', 'count' => 5)))) && p('success') && e('1'); // 步骤1：正常情况
r($blockTest->printQaStatisticBlockTest((object)array('params' => (object)array('type' => 'invalid<script>', 'count' => 5)))) && p('success') && e('1'); // 步骤2：包含无效字符的type
r($blockTest->printQaStatisticBlockTest((object)array('params' => (object)array('type' => '', 'count' => 3)))) && p('success') && e('1'); // 步骤3：空type参数
r($blockTest->printQaStatisticBlockTest((object)array('params' => (object)array('type' => 'closed', 'count' => 10)))) && p('success') && e('1'); // 步骤4：包含count参数
r($blockTest->printQaStatisticBlockTest((object)array('params' => (object)array()))) && p('success') && e('1'); // 步骤5：完全空参数