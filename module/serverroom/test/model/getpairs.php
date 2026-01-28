#!/usr/bin/env php
<?php

/**

title=测试 serverroomModel::getPairs();
timeout=0
cid=0

- 步骤1：正常情况，检查记录数 @9
- 步骤2：检查键值结构
 -  @~~
 - 属性10 @北京 - 青云 - 机房10
- 步骤3：检查ID为1的机房名称格式属性1 @北京 - 阿里云 - 机房1
- 步骤4：空数据情况，检查返回空数组 @0
- 步骤5：已删除数据过滤，期望空结果 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 测试步骤1：正常数据测试
zenData('serverroom')->loadYaml('serverroom')->gen(10);
su('admin');

$serverroomTest = new serverroomModelTest();

r(count($serverroomTest->getPairsTest())) && p() && e('9'); // 步骤1：正常情况，检查记录数

// 测试步骤2：检查数组键值结构
r($serverroomTest->getPairsTest()) && p('0,10') && e('~~,北京 - 青云 - 机房10'); // 步骤2：检查键值结构

// 测试步骤3：检查特定机房的名称格式化
$pairs = $serverroomTest->getPairsTest();
r($pairs) && p('1') && e('北京 - 阿里云 - 机房1'); // 步骤3：检查ID为1的机房名称格式

// 测试步骤4：测试空数据情况
zenData('serverroom')->gen(0);
r(count($serverroomTest->getPairsTest())) && p() && e('0'); // 步骤4：空数据情况，检查返回空数组

// 测试步骤5：测试已删除数据过滤
zenData('serverroom')->loadYaml('serverroom');
$table = zenData('serverroom');
$table->deleted->range('1'); // 设置所有数据为已删除
$table->gen(5);
r(count($serverroomTest->getPairsTest())) && p() && e('0'); // 步骤5：已删除数据过滤，期望空结果