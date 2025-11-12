#!/usr/bin/env php
<?php

/**

title=测试 pivotZen::productSummary();
timeout=0
cid=0

- 执行pivotTest模块的productSummaryTest方法，参数是'', 0, 'normal', 'normal' 属性title @产品汇总表
- 执行pivotTest模块的productSummaryTest方法，参数是'', 1, 'normal', 'normal' 第filters条的productID属性 @1
- 执行pivotTest模块的productSummaryTest方法，参数是'', 0, 'closed', 'normal' 第filters条的productStatus属性 @closed
- 执行pivotTest模块的productSummaryTest方法，参数是'', 0, 'normal', 'branch' 第filters条的productType属性 @branch
- 执行pivotTest模块的productSummaryTest方法，参数是'all', 5, 'normal', 'normal'
 - 第filters条的productID属性 @5
 - 第filters条的productStatus属性 @normal
 - 第filters条的productType属性 @normal

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivotzen.unittest.class.php';

global $tester;
$tester->app->loadLang('pivot');

zenData('product')->loadYaml('product', false, 2)->gen(20);
zenData('productplan')->loadYaml('productplan', false, 2)->gen(30);
zenData('story')->loadYaml('story', false, 2)->gen(50);
zenData('user')->gen(10);

su('admin');

$pivotTest = new pivotZenTest();

r($pivotTest->productSummaryTest('', 0, 'normal', 'normal')) && p('title') && e('产品汇总表');
r($pivotTest->productSummaryTest('', 1, 'normal', 'normal')) && p('filters:productID') && e('1');
r($pivotTest->productSummaryTest('', 0, 'closed', 'normal')) && p('filters:productStatus') && e('closed');
r($pivotTest->productSummaryTest('', 0, 'normal', 'branch')) && p('filters:productType') && e('branch');
r($pivotTest->productSummaryTest('all', 5, 'normal', 'normal')) && p('filters:productID,productStatus,productType') && e('5,normal,normal');