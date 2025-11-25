#!/usr/bin/env php
<?php

/**

title=测试 bugZen::getExecutionsForCreate();
timeout=0
cid=15452

- 执行bugTest模块的getExecutionsForCreateTest方法，参数是$bug1
 - 属性productID @1
 - 属性projectID @1
- 执行bugTest模块的getExecutionsForCreateTest方法，参数是$bug2
 - 属性productID @1
 - 属性projectID @1
- 执行bugTest模块的getExecutionsForCreateTest方法，参数是$bug3
 - 属性productID @1
 - 属性projectID @0
- 执行bugTest模块的getExecutionsForCreateTest方法，参数是$bug4
 - 属性productID @1
 - 属性projectID @1
- 执行bugTest模块的getExecutionsForCreateTest方法，参数是$bug5
 - 属性productID @1
 - 属性projectID @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('product')->loadYaml('getexecutionsforcreate/product', false, 2)->gen(10);
zenData('project')->loadYaml('getexecutionsforcreate/project', false, 2)->gen(10);
zenData('projectproduct')->loadYaml('getexecutionsforcreate/projectproduct', false, 2)->gen(10);

su('admin');

$bugTest = new bugZenTest();

$bug1 = new stdclass();
$bug1->productID = 1;
$bug1->branch = '1';
$bug1->projectID = 1;
$bug1->executionID = 6;

$bug2 = new stdclass();
$bug2->productID = 1;
$bug2->branch = '';
$bug2->projectID = 1;
$bug2->executionID = 6;

$bug3 = new stdclass();
$bug3->productID = 1;
$bug3->branch = '0';
$bug3->projectID = 0;
$bug3->executionID = 6;

$bug4 = new stdclass();
$bug4->productID = 1;
$bug4->branch = '0';
$bug4->projectID = 1;
$bug4->executionID = 999;

$bug5 = new stdclass();
$bug5->productID = 1;
$bug5->branch = '0';
$bug5->projectID = 1;
$bug5->executionID = 8;

r($bugTest->getExecutionsForCreateTest($bug1)) && p('productID,projectID') && e('1,1');
r($bugTest->getExecutionsForCreateTest($bug2)) && p('productID,projectID') && e('1,1');
r($bugTest->getExecutionsForCreateTest($bug3)) && p('productID,projectID') && e('1,0');
r($bugTest->getExecutionsForCreateTest($bug4)) && p('productID,projectID') && e('1,1');
r($bugTest->getExecutionsForCreateTest($bug5)) && p('productID,projectID') && e('1,1');