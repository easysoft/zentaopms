#!/usr/bin/env php
<?php

/**

title=测试 executionZen::getLinkedObjects();
timeout=0
cid=16431

- 执行executionTest模块的getLinkedObjectsTest方法，参数是1 属性currentPlan @0
- 执行executionTest模块的getLinkedObjectsTest方法，参数是2 属性currentPlan @0
- 执行executionTest模块的getLinkedObjectsTest方法，参数是3 属性currentPlan @0
- 执行executionTest模块的getLinkedObjectsTest方法，参数是4 属性currentPlan @0
- 执行executionTest模块的getLinkedObjectsTest方法，参数是5 属性currentPlan @0
- 执行executionTest模块的getLinkedObjectsTest方法，参数是6 属性currentPlan @0
- 执行executionTest模块的getLinkedObjectsTest方法，参数是7 属性currentPlan @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

zenData('project')->gen(50);
zenData('product')->gen(10);
zenData('projectproduct')->gen(30);
zenData('productplan')->gen(20);
zenData('projectstory')->gen(30);
zenData('branch')->gen(10);

su('admin');

$executionTest = new executionZenTest();

r($executionTest->getLinkedObjectsTest(1)) && p('currentPlan') && e('0');
r($executionTest->getLinkedObjectsTest(2)) && p('currentPlan') && e('0');
r($executionTest->getLinkedObjectsTest(3)) && p('currentPlan') && e('0');
r($executionTest->getLinkedObjectsTest(4)) && p('currentPlan') && e('0');
r($executionTest->getLinkedObjectsTest(5)) && p('currentPlan') && e('0');
r($executionTest->getLinkedObjectsTest(6)) && p('currentPlan') && e('0');
r($executionTest->getLinkedObjectsTest(7)) && p('currentPlan') && e('0');