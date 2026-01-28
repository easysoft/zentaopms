#!/usr/bin/env php
<?php

/**

title=测试 compileModel::getLastResult();
timeout=0
cid=15747

- 测试步骤1：正常jobID查询最新编译结果属性job @1
- 测试步骤2：查询有多条记录的jobID属性job @2
- 测试步骤3：查询不存在的jobID @0
- 测试步骤4：查询无状态记录的jobID @0
- 测试步骤5：边界值测试（jobID为0） @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$compile = zenData('compile');
$compile->id->range('1-10');
$compile->name->range('Build1,Build2,Build3,Compile4,Deploy5,Test6,Release7,Fix8,Update9,Package10');
$compile->job->range('1{3},2{2},3{1},4{1},5{3}');
$compile->queue->range('1-10');
$compile->status->range('success,failure,running,failure,done,success,``,running,done,pending');
$compile->createdBy->range('admin,user1,user2,admin,user1,user2,admin,user1,user2,admin');
$compile->createdDate->range('`2024-01-01 10:00:00`,`2024-01-01 11:00:00`,`2024-01-01 12:00:00`,`2024-01-01 13:00:00`,`2024-01-01 14:00:00`,`2024-01-01 15:00:00`,`2024-01-01 16:00:00`,`2024-01-01 17:00:00`,`2024-01-01 18:00:00`,`2024-01-01 19:00:00`');
$compile->deleted->range('0');
$compile->gen(10);

zenData('job')->gen(5);
su('admin');

$compileTest = new compileModelTest();

r($compileTest->getLastResultTest(1))   && p('job') && e('1'); // 测试步骤1：正常jobID查询最新编译结果
r($compileTest->getLastResultTest(2))   && p('job') && e('2'); // 测试步骤2：查询有多条记录的jobID
r($compileTest->getLastResultTest(999)) && p()      && e('0'); // 测试步骤3：查询不存在的jobID
r($compileTest->getLastResultTest(4))   && p()      && e('0'); // 测试步骤4：查询无状态记录的jobID
r($compileTest->getLastResultTest(0))   && p()      && e('0'); // 测试步骤5：边界值测试（jobID为0）
