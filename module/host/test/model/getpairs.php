#!/usr/bin/env php
<?php

/**

title=测试 hostModel::getPairs();
timeout=0
cid=16757

- 步骤1：不带参数获取所有主机 @10
- 步骤2：根据模块ID为1获取主机 @3
- 步骤3：根据多个模块ID获取主机 @6
- 步骤4：根据状态获取在线主机 @5
- 步骤5：同时根据模块和状态过滤 @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$table = zenData('host');
$table->id->range('1-10');
$table->name->range('主机1,主机2,主机3,主机4,主机5,主机6,主机7,主机8,主机9,主机10');
$table->type->range('normal');
$table->status->range('online{5},offline{5}');
$table->group->range('1{3},2{3},3{2},0{2}');
$table->deleted->range('0');
$table->gen(10);

$moduleTable = zenData('module');
$moduleTable->id->range('1-5');
$moduleTable->name->range('模块1,模块2,模块3,模块4,模块5');
$moduleTable->type->range('host');
$moduleTable->parent->range('0{2},1,2{2}');
$moduleTable->gen(5);

su('admin');

$hostTest = new hostModelTest();

r(count($hostTest->getPairsTest())) && p() && e('10'); // 步骤1：不带参数获取所有主机
r(count($hostTest->getPairsTest('1'))) && p() && e('3'); // 步骤2：根据模块ID为1获取主机
r(count($hostTest->getPairsTest('1,2'))) && p() && e('6'); // 步骤3：根据多个模块ID获取主机
r(count($hostTest->getPairsTest('', 'online'))) && p() && e('5'); // 步骤4：根据状态获取在线主机
r(count($hostTest->getPairsTest('1', 'online'))) && p() && e('3'); // 步骤5：同时根据模块和状态过滤