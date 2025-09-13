#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printBugStatisticBlock();
timeout=0
cid=0

- 执行blockTest模块的printBugStatisticBlockTest方法 属性totalBugs @10
- 执行blockTest模块的printBugStatisticBlockTest方法 属性closedBugs @5
- 执行blockTest模块的printBugStatisticBlockTest方法 属性unresovledBugs @3
- 执行blockTest模块的printBugStatisticBlockTest方法 属性resolvedRate @50

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

$table = zenData('product');
$table->id->range('1-10');
$table->name->range('产品1,产品2,产品3,产品4,产品5{2}');
$table->code->range('product1,product2,product3,product4,product5{2}');
$table->status->range('normal{5},closed{3},developing{2}');
$table->deleted->range('0{10}');
$table->gen(10);

su('admin');

$blockTest = new blockTest();

r($blockTest->printBugStatisticBlockTest()) && p('totalBugs') && e('10');
r($blockTest->printBugStatisticBlockTest()) && p('closedBugs') && e('5');
r($blockTest->printBugStatisticBlockTest()) && p('unresovledBugs') && e('3');
r($blockTest->printBugStatisticBlockTest()) && p('resolvedRate') && e('50');
r($blockTest->printBugStatisticBlockTest()) && p('months') && c('6');