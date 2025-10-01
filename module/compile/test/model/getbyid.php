#!/usr/bin/env php
<?php

/**

title=测试 compileModel::getByID();
cid=0

- 测试步骤1：正常查询存在的构建ID >> 期望返回构建对象
- 测试步骤2：查询不存在的构建ID >> 期望返回false
- 测试步骤3：查询ID为0的构建 >> 期望返回false
- 测试步骤4：查询负数ID的构建 >> 期望返回false
- 测试步骤5：查询非数字ID的构建 >> 期望返回false

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/compile.unittest.class.php';

$table = zenData('compile');
$table->id->range('1-5');
$table->name->range('构建1,构建2,构建3,构建4,构建5');
$table->job->range('1-5');
$table->status->range('success,failure,running,pending,created');
$table->queue->range('101-105');
$table->createdBy->range('admin');
$table->gen(5);

su('admin');

$compileTest = new compileTest();

r($compileTest->getByIDTest(1)) && p('id,name,status') && e('1,构建1,success');
r($compileTest->getByIDTest(999)) && p() && e(false);
r($compileTest->getByIDTest(0)) && p() && e(false);
r($compileTest->getByIDTest(-1)) && p() && e(false);
r($compileTest->getByIDTest('abc')) && p() && e(false);