#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printWaterfallProgressBlock();
timeout=0
cid=0

- 执行blockTest模块的printWaterfallProgressBlockTest方法，参数是1 
 - 属性hasCharts @1
 - 属性pvCount @5
 - 属性evCount @5
 - 属性acCount @5
- 执行blockTest模块的printWaterfallProgressBlockTest方法，参数是999 
 - 属性hasCharts @1
 - 属性pvCount @0
 - 属性evCount @0
 - 属性acCount @0
- 执行blockTest模块的printWaterfallProgressBlockTest方法 
 - 属性hasCharts @1
 - 属性pvCount @0
 - 属性evCount @0
 - 属性acCount @0
- 执行blockTest模块的printWaterfallProgressBlockTest方法，参数是99 
 - 属性hasCharts @1
 - 属性pvCount @0
 - 属性evCount @0
 - 属性acCount @0
- 执行blockTest模块的printWaterfallProgressBlockTest方法，参数是null 
 - 属性hasCharts @1
 - 属性pvCount @0
 - 属性evCount @0
 - 属性acCount @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

su('admin');

$blockTest = new blockTest();

r($blockTest->printWaterfallProgressBlockTest(1)) && p('hasCharts,pvCount,evCount,acCount') && e('1,5,5,5');
r($blockTest->printWaterfallProgressBlockTest(999)) && p('hasCharts,pvCount,evCount,acCount') && e('1,0,0,0');
r($blockTest->printWaterfallProgressBlockTest(0)) && p('hasCharts,pvCount,evCount,acCount') && e('1,0,0,0');
r($blockTest->printWaterfallProgressBlockTest(99)) && p('hasCharts,pvCount,evCount,acCount') && e('1,0,0,0');
r($blockTest->printWaterfallProgressBlockTest(null)) && p('hasCharts,pvCount,evCount,acCount') && e('1,0,0,0');