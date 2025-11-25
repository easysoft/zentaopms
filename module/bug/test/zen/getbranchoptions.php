#!/usr/bin/env php
<?php

/**

title=测试 bugZen::getBranchOptions();
timeout=0
cid=15449

- 执行$result @array
- 执行$result @6
- 执行$result @4
- 执行$result @0
- 执行$result @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

global $tester, $app;
$app->rawModule = 'bug';
$app->rawMethod = 'browse';

// zendata数据准备
$table = zenData('branch');
$table->id->range('1-10');
$table->product->range('1{5}, 2{3}, 3{2}');
$table->name->range('master{2}, develop{2}, feature-v1.0{2}, feature-v2.0{2}, hotfix-bug{2}');
$table->status->range('active{8}, closed{2}');
$table->deleted->range('0{10}');
$table->gen(10);

$zen = initReference('bug');
$func = $zen->getMethod('getBranchOptions');

// 测试步骤1：正常产品ID获取分支选项
$result = $func->invokeArgs($zen->newInstance(), [1]);
r(gettype($result)) && p() && e('array');

// 测试步骤2：获取产品1的分支选项并检查数量
$result = $func->invokeArgs($zen->newInstance(), [1]);
r(count($result)) && p() && e('6');

// 测试步骤3：获取产品2的分支选项并检查数量
$result = $func->invokeArgs($zen->newInstance(), [2]);
r(count($result)) && p() && e('4');

// 测试步骤4：获取不存在产品的分支选项
$result = $func->invokeArgs($zen->newInstance(), [999]);
r(count($result)) && p() && e('0');

// 测试步骤5：测试无效产品ID参数
$result = $func->invokeArgs($zen->newInstance(), [0]);
r(count($result)) && p() && e('0');