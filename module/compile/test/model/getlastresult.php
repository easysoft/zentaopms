#!/usr/bin/env php
<?php

/**

title=测试 compileModel::getLastResult();
timeout=0
cid=1

- 测试步骤1：正常jobID查询最新编译结果 >> 返回最新的有状态编译记录
- 测试步骤2：查询有多条记录的jobID >> 按创建时间倒序返回第一条记录
- 测试步骤3：查询不存在的jobID >> 返回false
- 测试步骤4：查询无状态记录的jobID >> 返回false（只返回有状态的记录）
- 测试步骤5：边界值测试（jobID为0） >> 返回false
- 测试步骤6：负数jobID测试 >> 返回false
- 测试步骤7：验证返回记录包含所有关键字段 >> 包含name、createdBy等属性

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/compile.unittest.class.php';

$compile = zenData('compile');
$compile->id->range('1-10');
$compile->name->range('Build1,Build2,Build3,Compile4,Deploy5,Test6,Release7,Fix8,Update9,Package10');
$compile->job->range('1{3},2{2},3{2},4{1},999{2}');
$compile->queue->range('100-999');
$compile->status->range('success{3},failure{2},running{2},{3}');
$compile->createdBy->range('admin{5},user1{3},user2{2}');
$compile->createdDate->range('2024-01-01 00:00:00-2024-12-31 23:59:59');
$compile->deleted->range('0');
$compile->gen(10);

zenData('job')->gen(5);
su('admin');

$compileTest = new compileTest();

r($compileTest->getLastResultTest(1)) && p('job,status') && e('1,running');                    // 测试步骤1：正常jobID查询最新编译结果
r($compileTest->getLastResultTest(2)) && p('job') && e('2');                                   // 测试步骤2：查询有多条记录的jobID
r($compileTest->getLastResultTest(999)) && p() && e(false);                                   // 测试步骤3：查询不存在的jobID
r($compileTest->getLastResultTest(4)) && p() && e(false);                                     // 测试步骤4：查询无状态记录的jobID
r($compileTest->getLastResultTest(0)) && p() && e(false);                                     // 测试步骤5：边界值测试（jobID为0）
r($compileTest->getLastResultTest(-1)) && p() && e(false);                                    // 测试步骤6：负数jobID测试
r($compileTest->getLastResultTest(1)) && p('name,createdBy') && e('Build3,user2');           // 测试步骤7：验证返回记录包含所有关键字段