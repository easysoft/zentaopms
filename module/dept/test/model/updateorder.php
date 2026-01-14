#!/usr/bin/env php
<?php

/**

title=测试 deptModel::updateOrder();
timeout=0
cid=15981

- 测试步骤1：正常多部门排序更新 @1
- 测试步骤2：单个部门排序更新 @1
- 测试步骤3：空数组输入测试 @1
- 测试步骤4：不存在部门ID测试 @1
- 测试步骤5：验证排序结果正确性 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

global $tester;
// 直接准备测试数据，避免zendata问题
$tester->dao->delete()->from(TABLE_DEPT)->exec();
for($i = 1; $i <= 10; $i++)
{
    $dept = new stdClass();
    $dept->id = $i;
    $dept->name = "部门{$i}";
    $dept->parent = $i <= 5 ? 0 : 1;
    $dept->path = $i <= 5 ? ",{$i}," : ",1,{$i},";
    $dept->grade = $i <= 5 ? 1 : 2;
    $dept->order = $i;
    $dept->manager = '';
    $tester->dao->insert(TABLE_DEPT)->data($dept)->exec();
}

$deptTest = new deptModelTest();

r($deptTest->updateOrderTest(array('3', '1', '5', '2', '4'))) && p() && e('1'); // 测试步骤1：正常多部门排序更新
r($deptTest->updateOrderTest(array('6'))) && p() && e('1'); // 测试步骤2：单个部门排序更新
r($deptTest->updateOrderTest(array())) && p() && e('1'); // 测试步骤3：空数组输入测试
r($deptTest->updateOrderTest(array('999', '888'))) && p() && e('1'); // 测试步骤4：不存在部门ID测试
r($deptTest->updateOrderSimpleTest(array('7', '8', '9'))) && p() && e('1'); // 测试步骤5：验证排序结果正确性
