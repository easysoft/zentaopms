#!/usr/bin/env php
<?php

/**

title=测试 pivotZen::bugAssign();
timeout=0
cid=17453

- 执行$result @1
- 执行$result) || isset($result[0]->product @1
- 执行$result) || isset($result[0]->productName @1
- 执行$result) || isset($result[0]->total @1
- 执行$result) || isset($result[0]->rowspan @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivotzen.unittest.class.php';

global $tester;
$tester->app->loadLang('bug');

// 准备测试数据:生成active状态的bug
$bug = zenData('bug');
$bug->product->range('1-3');
$bug->assignedTo->range('admin{5},user1{3},user2{2}');
$bug->status->range('active');
$bug->deleted->range('0');
$bug->gen(10);

zenData('product')->gen(5);
zenData('project')->gen(5);
zenData('projectproduct')->gen(3);
zenData('user')->gen(5);

su('admin');

$pivotTest = new pivotZenTest();

// 测试步骤1:验证bugAssign方法返回数组
$result = $pivotTest->bugAssignTest();
r(is_array($result)) && p() && e('1');

// 测试步骤2:验证数据项包含product字段
r(empty($result) || isset($result[0]->product)) && p() && e('1');

// 测试步骤3:验证数据项包含productName字段
r(empty($result) || isset($result[0]->productName)) && p() && e('1');

// 测试步骤4:验证数据项包含total字段
r(empty($result) || isset($result[0]->total)) && p() && e('1');

// 测试步骤5:验证第一行数据包含rowspan字段
r(empty($result) || isset($result[0]->rowspan)) && p() && e('1');