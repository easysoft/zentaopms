#!/usr/bin/env php
<?php

/**

title=测试 pivotZen::productSummary();
timeout=0
cid=0

- 执行pivotTest模块的productSummaryTest方法，参数是'', 0, 'normal', 'normal' 属性title @产品汇总表
- 执行pivotTest模块的productSummaryTest方法，参数是'status=normal', 1, 'normal', 'normal' 属性conditions @status=normal
- 执行pivotTest模块的productSummaryTest方法，参数是'', 2, 'normal', 'normal' 第filters条的productID属性 @2
- 执行pivotTest模块的productSummaryTest方法，参数是'', 0, 'closed', 'normal' 第filters条的productStatus属性 @closed
- 执行pivotTest模块的productSummaryTest方法，参数是'', 0, 'normal', 'branch' 第filters条的productType属性 @branch

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

$table = zenData('product');
$table->id->range('1-5');
$table->name->range('正常产品1,正常产品2,已删除产品3,暂停产品4,新产品5');
$table->status->range('normal{3},closed{1},normal{1}');
$table->type->range('normal{4},branch{1}');
$table->deleted->range('0{4},1{1}');
$table->gen(5);

$table = zenData('user');
$table->id->range('1-3');
$table->account->range('admin,user1,user2');
$table->realname->range('管理员,用户1,用户2');
$table->deleted->range('0');
$table->gen(3);

su('admin');

$pivotTest = new pivotTest();

r($pivotTest->productSummaryTest('', 0, 'normal', 'normal')) && p('title') && e('产品汇总表');
r($pivotTest->productSummaryTest('status=normal', 1, 'normal', 'normal')) && p('conditions') && e('status=normal');
r($pivotTest->productSummaryTest('', 2, 'normal', 'normal')) && p('filters:productID') && e('2');
r($pivotTest->productSummaryTest('', 0, 'closed', 'normal')) && p('filters:productStatus') && e('closed');
r($pivotTest->productSummaryTest('', 0, 'normal', 'branch')) && p('filters:productType') && e('branch');