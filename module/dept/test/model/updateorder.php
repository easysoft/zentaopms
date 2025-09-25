#!/usr/bin/env php
<?php

/**

title=测试 deptModel::updateOrder();
timeout=0
cid=0

- 测试步骤1：正常多部门排序更新
 - 第3条的order属性 @1
 - 第3条的1:order属性 @2
 - 第3条的5:order属性 @3
 - 第3条的2:order属性 @4
 - 第3条的4:order属性 @5
- 测试步骤2：单个部门排序更新第10条的order属性 @1
- 测试步骤3：空数组输入测试 @1
- 测试步骤4：不存在部门ID测试 @1
- 测试步骤5：大量部门排序测试
 - 第6条的order属性 @1
 - 第6条的16:order属性 @10

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/dept.unittest.class.php';

$table = zenData('dept');
$table->id->range('1-20');
$table->name->range('部门1,部门2,部门3,部门4,部门5,部门6,部门7,部门8,部门9,部门10,部门11,部门12,部门13,部门14,部门15,部门16,部门17,部门18,部门19,部门20');
$table->parent->range('0{5},1{5},2{5},3{5}');
$table->path->range(',1,,2,,3,,4,,5,,1,1,,1,2,,1,3,,1,4,,1,5,');
$table->grade->range('1{5},2{15}');
$table->order->range('1-20');
$table->position->range('');
$table->function->range('');
$table->manager->range('');
$table->gen(20);

su('admin');

$deptTest = new deptTest();

r($deptTest->updateOrderTest(array('3', '1', '5', '2', '4'))) && p('3:order,1:order,5:order,2:order,4:order') && e('1,2,3,4,5'); // 测试步骤1：正常多部门排序更新
r($deptTest->updateOrderTest(array('10'))) && p('10:order') && e('1'); // 测试步骤2：单个部门排序更新
r($deptTest->updateOrderTest(array())) && p() && e('1'); // 测试步骤3：空数组输入测试
r($deptTest->updateOrderTest(array('999', '888'))) && p() && e('1'); // 测试步骤4：不存在部门ID测试
r($deptTest->updateOrderTest(array('6', '7', '8', '9', '11', '12', '13', '14', '15', '16'))) && p('6:order,16:order') && e('1,10'); // 测试步骤5：大量部门排序测试