#!/usr/bin/env php
<?php

/**

title=测试 compileModel::getSuccessJobs();
timeout=0
cid=15751

- 测试步骤1：传入空数组 >> 期望返回空数组
- 测试步骤2：传入包含成功job的ID列表 >> 期望返回对应的success job映射
- 测试步骤3：传入包含失败job的ID列表 >> 期望返回空数组
- 测试步骤4：传入混合状态job的ID列表 >> 期望只返回success状态的job
- 测试步骤5：传入不存在的job ID >> 期望返回空数组

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$table = zenData('compile');
$table->id->range('1-10');
$table->name->range('构建1,构建2,构建3,构建4,构建5,构建6,构建7,构建8,构建9,构建10');
$table->job->range('1,2,3,4,5,6,7,8,9,10');
$table->queue->range('100-110');
$table->status->range('success{3},failed{3},running{2},success{2}');
$table->deleted->range('0');
$table->gen(10);

su('admin');

$compileTest = new compileModelTest();

r($compileTest->getSuccessJobsTest(array())) && p() && e('0');
r($compileTest->getSuccessJobsTest(array(1, 2, 3))) && p('1,2,3') && e('1,2,3');
r($compileTest->getSuccessJobsTest(array(4, 5, 6))) && p() && e('0');
r($compileTest->getSuccessJobsTest(array(1, 4, 9, 10))) && p('1,9,10') && e('1,9,10');
r($compileTest->getSuccessJobsTest(array(999, 1000))) && p() && e('0');