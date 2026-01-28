#!/usr/bin/env php
<?php

/**

title=测试 stakeholderModel::getProcess();
timeout=0
cid=0

- 测试步骤1：正常情况获取进度键值对属性1 @过程名称1
- 测试步骤2：验证返回数据数量 @10
- 测试步骤3：验证特定ID对应的名称属性5 @过程名称5
- 测试步骤4：验证只返回未删除的进度(ID11被删除)属性11 @~~
- 测试步骤5：验证返回数据结构(键为ID)
 -  @1
 - 属性1 @2
 - 属性2 @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$process = zenData('process');
$process->id->range('1-15');
$process->name->range('过程名称1,过程名称2,过程名称3,过程名称4,过程名称5,过程名称6,过程名称7,过程名称8,过程名称9,过程名称10,过程名称11,过程名称12,过程名称13,过程名称14,过程名称15');
$process->deleted->range('0{10},1{5}');
$process->model->range('waterfall{8},scrum{4},kanban{3}');
$process->type->range('type1{5},type2{5},type3{5}');
$process->status->range('active{10},inactive{5}');
$process->gen(15);

su('admin');

$stakeholderTester = new stakeholderModelTest();

r($stakeholderTester->getProcessTest()) && p('1') && e('过程名称1'); // 测试步骤1：正常情况获取进度键值对
r(count($stakeholderTester->getProcessTest())) && p() && e('10'); // 测试步骤2：验证返回数据数量
r($stakeholderTester->getProcessTest()) && p('5') && e('过程名称5'); // 测试步骤3：验证特定ID对应的名称
r($stakeholderTester->getProcessTest()) && p('11') && e('~~'); // 测试步骤4：验证只返回未删除的进度(ID11被删除)
r(array_keys($stakeholderTester->getProcessTest())) && p('0,1,2') && e('1,2,3'); // 测试步骤5：验证返回数据结构(键为ID)