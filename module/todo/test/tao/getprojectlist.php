#!/usr/bin/env php
<?php

/**

title=测试 todoTao::getProjectList();
timeout=0
cid=19277

- 步骤1：正常task表查询
 - 属性1 @1
 - 属性2 @1
 - 属性3 @1
- 步骤2：空ID列表 @0
- 步骤3：bug表查询
 - 属性1 @2
 - 属性3 @2
 - 属性5 @3
- 步骤4：testtask表查询
 - 属性1 @1
 - 属性2 @1
 - 属性4 @2
- 步骤5：验证返回数组长度 @3

- 验证task获得的键值对的个数 @1
- 验证taskID为10的project为20属性10 @20
- 验证project获得的键值对的个数 @1
- 获取不到的情况属性10 @0
- 验证bugID为10的project为24属性10 @14

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todo.unittest.class.php';

// 准备测试数据
$taskTable = zenData('task');
$taskTable->id->range('1-10');
$taskTable->project->range('1{5},2{3},3{2}');
$taskTable->name->range('Task{1-10}');
$taskTable->status->range('wait,doing,done');
$taskTable->gen(10);

$bugTable = zenData('bug');
$bugTable->id->range('1-8');
$bugTable->project->range('2{4},3{3},4{1}');
$bugTable->title->range('Bug{1-8}');
$bugTable->status->range('active,resolved');
$bugTable->gen(8);

$testtaskTable = zenData('testtask');
$testtaskTable->id->range('1-5');
$testtaskTable->project->range('1{2},2{2},3{1}');
$testtaskTable->name->range('TestTask{1-5}');
$testtaskTable->status->range('wait,doing,done');
$testtaskTable->gen(5);

su('admin');

$todoTest = new todoTest();

r($todoTest->getProjectListTest('zt_task', array(1 => 1, 2 => 2, 3 => 3))) && p('1,2,3') && e('1,1,1'); // 步骤1：正常task表查询
r($todoTest->getProjectListTest('zt_task', array())) && p() && e(0); // 步骤2：空ID列表
r($todoTest->getProjectListTest('zt_bug', array(1 => 1, 3 => 3, 5 => 5))) && p('1,3,5') && e('2,2,3'); // 步骤3：bug表查询
r($todoTest->getProjectListTest('zt_testtask', array(1 => 1, 2 => 2, 4 => 4))) && p('1,2,4') && e('1,1,2'); // 步骤4：testtask表查询
r(count($todoTest->getProjectListTest('zt_task', array(6 => 6, 8 => 8, 10 => 10)))) && p() && e('3'); // 步骤5：验证返回数组长度
