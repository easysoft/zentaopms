#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printAnnualWorkloadBlock();
timeout=0
cid=0

- 执行blockTest模块的printAnnualWorkloadBlockTest方法 属性success @1
- 执行blockTest模块的printAnnualWorkloadBlockTest方法 属性maxStoryEstimate @80
- 执行blockTest模块的printAnnualWorkloadBlockTest方法 属性maxStoryCount @40
- 执行blockTest模块的printAnnualWorkloadBlockTest方法 属性maxBugCount @15
- 执行blockTest模块的printAnnualWorkloadBlockTest方法 第products条的1属性 @正常产品1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

zenData('product')->loadYaml('product_printannualworkloadblock', false, 2)->gen(5);

su('admin');

$blockTest = new blockTest();

r($blockTest->printAnnualWorkloadBlockTest()) && p('success') && e('1');
r($blockTest->printAnnualWorkloadBlockTest()) && p('maxStoryEstimate') && e('80');
r($blockTest->printAnnualWorkloadBlockTest()) && p('maxStoryCount') && e('40');
r($blockTest->printAnnualWorkloadBlockTest()) && p('maxBugCount') && e('15');
r($blockTest->printAnnualWorkloadBlockTest()) && p('products:1') && e('正常产品1');