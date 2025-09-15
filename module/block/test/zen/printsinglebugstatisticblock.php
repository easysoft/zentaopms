#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printSingleBugStatisticBlock();
timeout=0
cid=0

- 执行blockTest模块的printSingleBugStatisticBlockTest方法，参数是$block1 
 - 属性productID @1
 - 属性totalBugs @100
 - 属性closedBugs @85
- 执行blockTest模块的printSingleBugStatisticBlockTest方法，参数是null 
 - 属性productID @1
 - 属性totalBugs @100
- 执行blockTest模块的printSingleBugStatisticBlockTest方法，参数是$block3 属性error @block params not found
- 执行blockTest模块的printSingleBugStatisticBlockTest方法，参数是$block4 
 - 属性status @normal
 - 属性totalBugs @100
- 执行blockTest模块的printSingleBugStatisticBlockTest方法，参数是$block5 
 - 属性count @5
 - 属性resolvedRate @85

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

zenData('product')->loadYaml('product_printsinglebugstatisticblock', false, 2)->gen(10);
zenData('metriclib')->loadYaml('metriclib_printsinglebugstatisticblock', false, 2)->gen(100);

su('admin');

$blockTest = new blockTest();

// 测试步骤1：正常block对象测试
$block1 = new stdClass();
$block1->params = new stdClass();
$block1->params->type = '';
$block1->params->count = '';
r($blockTest->printSingleBugStatisticBlockTest($block1)) && p('productID,totalBugs,closedBugs') && e('1,100,85');

// 测试步骤2：空block参数测试
r($blockTest->printSingleBugStatisticBlockTest(null)) && p('productID,totalBugs') && e('1,100');

// 测试步骤3：无效block.params测试
$block3 = new stdClass();
r($blockTest->printSingleBugStatisticBlockTest($block3)) && p('error') && e('block params not found');

// 测试步骤4：指定type参数测试
$block4 = new stdClass();
$block4->params = new stdClass();
$block4->params->type = 'normal';
$block4->params->count = '';
r($blockTest->printSingleBugStatisticBlockTest($block4)) && p('status,totalBugs') && e('normal,100');

// 测试步骤5：指定count参数测试
$block5 = new stdClass();
$block5->params = new stdClass();
$block5->params->type = '';
$block5->params->count = '5';
r($blockTest->printSingleBugStatisticBlockTest($block5)) && p('count,resolvedRate') && e('5,85');