#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printSingleMonthlyProgressBlock();
timeout=0
cid=0

- 执行blockTest模块的printSingleMonthlyProgressBlockTest方法，参数是1
 - 属性success @1
 - 属性monthCount @6
- 执行blockTest模块的printSingleMonthlyProgressBlockTest方法，参数是0
 - 属性success @1
 - 属性productID @0
- 执行blockTest模块的printSingleMonthlyProgressBlockTest方法，参数是999
 - 属性success @1
 - 属性productID @999
- 执行blockTest模块的printSingleMonthlyProgressBlockTest方法，参数是1，验证数据结构
 - 属性dataCount @6
 - 属性hasMetricData @1
- 执行blockTest模块的printSingleMonthlyProgressBlockTest方法，参数是2，验证度量数据
 - 属性hasMetricData @1
 - 属性success @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

su('admin');

$blockTest = new blockTest();

r($blockTest->printSingleMonthlyProgressBlockTest(1)) && p('success,monthCount') && e('1,6');
r($blockTest->printSingleMonthlyProgressBlockTest(0)) && p('success,productID') && e('1,0');
r($blockTest->printSingleMonthlyProgressBlockTest(999)) && p('success,productID') && e('1,999');
r($blockTest->printSingleMonthlyProgressBlockTest(1)) && p('dataCount,hasMetricData') && e('6,1');
r($blockTest->printSingleMonthlyProgressBlockTest(2)) && p('hasMetricData,success') && e('1,1');