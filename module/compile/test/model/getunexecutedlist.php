#!/usr/bin/env php
<?php

/**

title=测试 compileModel::getUnexecutedList();
timeout=0
cid=15752

- 步骤1：检查返回结果中未执行构建记录的数量 @3
- 步骤2：验证第一条未执行记录的name属性第0条的name属性 @构建1
- 步骤3：验证第二条未执行记录的name属性第1条的name属性 @构建4
- 步骤4：验证第三条未执行记录的name属性第2条的name属性 @构建8
- 步骤5：验证所有返回记录的status都为空字符串第0条的status属性 @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/compile.unittest.class.php';

$compile = zenData('compile');
$compile->id->range('1-10');
$compile->name->range('构建1,构建2,构建3,构建4,构建5,构建6,构建7,构建8,构建9,构建10');
$compile->job->range('1-10');
$compile->status->range('``,success,failure,``,created,success,failure,``,created,success');
$compile->deleted->range('0{8},1{2}');
$compile->createdBy->range('admin{10}');
$compile->createdDate->range('`2024-01-01 10:00:00`,`2024-01-02 11:00:00`,`2024-01-03 12:00:00`,`2024-01-04 13:00:00`,`2024-01-05 14:00:00`,`2024-01-06 15:00:00`,`2024-01-07 16:00:00`,`2024-01-08 17:00:00`,`2024-01-09 18:00:00`,`2024-01-10 19:00:00`');
$compile->gen(10);

su('admin');

$compileTest = new compileTest();

r(count($compileTest->getUnexecutedListTest())) && p() && e('3'); // 步骤1：检查返回结果中未执行构建记录的数量
r($compileTest->getUnexecutedListTest()) && p('0:name') && e('构建1'); // 步骤2：验证第一条未执行记录的name属性
r($compileTest->getUnexecutedListTest()) && p('1:name') && e('构建4'); // 步骤3：验证第二条未执行记录的name属性
r($compileTest->getUnexecutedListTest()) && p('2:name') && e('构建8'); // 步骤4：验证第三条未执行记录的name属性
r($compileTest->getUnexecutedListTest()) && p('0:status') && e('~~'); // 步骤5：验证所有返回记录的status都为空字符串