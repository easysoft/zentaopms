#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printScrumRoadMapBlock();
timeout=0
cid=15292

- 执行blockTest模块的printScrumRoadMapBlockTest方法，参数是0, 0
 - 属性productsCount @10
 - 属性sync @1
- 执行blockTest模块的printScrumRoadMapBlockTest方法，参数是1, 0
 - 属性productID @1
 - 属性sync @1
- 执行blockTest模块的printScrumRoadMapBlockTest方法，参数是2, 0
 - 属性productID @2
 - 属性sync @1
- 执行blockTest模块的printScrumRoadMapBlockTest方法，参数是3, 0
 - 属性productID @3
 - 属性sync @1
- 执行blockTest模块的printScrumRoadMapBlockTest方法，参数是1, 1
 - 属性productID @1
 - 属性roadMapID @1
 - 属性sync @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('product')->loadYaml('product', false, 2)->gen(10);
zenData('productplan')->loadYaml('productplan', false, 2)->gen(20);
zenData('release')->loadYaml('release', false, 2)->gen(15);
zenData('project')->loadYaml('project', false, 2)->gen(5);
zenData('user')->gen(5);

su('admin');

$blockTest = new blockZenTest();

r($blockTest->printScrumRoadMapBlockTest(0, 0)) && p('productsCount,sync') && e('10,1');
r($blockTest->printScrumRoadMapBlockTest(1, 0)) && p('productID,sync') && e('1,1');
r($blockTest->printScrumRoadMapBlockTest(2, 0)) && p('productID,sync') && e('2,1');
r($blockTest->printScrumRoadMapBlockTest(3, 0)) && p('productID,sync') && e('3,1');
r($blockTest->printScrumRoadMapBlockTest(1, 1)) && p('productID,roadMapID,sync') && e('1,1,1');