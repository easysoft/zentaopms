#!/usr/bin/env php
<?php

/**

title=测试 bugZen::getProjectsForCreate();
timeout=0
cid=0

- 执行bugTest模块的getProjectsForCreateTest方法，参数是$bug1 属性projectID @11
- 执行bugTest模块的getProjectsForCreateTest方法，参数是$bug1 属性executionID @101
- 执行bugTest模块的getProjectsForCreateTest方法，参数是$bug2 属性projectID @11
- 执行bugTest模块的getProjectsForCreateTest方法，参数是$bug3 属性projectID @11
- 执行bugTest模块的getProjectsForCreateTest方法，参数是$bug4 属性projectID @11

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品1,产品2,产品3,产品4,产品5');
$product->type->range('normal');
$product->shadow->range('0');
$product->gen(5);

su('admin');

$bugTest = new bugTest();

$bug1 = new stdclass();
$bug1->productID = 1;
$bug1->projectID = 11;
$bug1->branch = '0';
$bug1->executionID = 101;

$bug2 = new stdclass();
$bug2->productID = 1;
$bug2->projectID = 999;
$bug2->branch = '0';
$bug2->executionID = 0;

$bug3 = new stdclass();
$bug3->productID = 1;
$bug3->projectID = 0;
$bug3->branch = '0';
$bug3->executionID = 101;

$bug4 = new stdclass();
$bug4->productID = 5;
$bug4->projectID = 0;
$bug4->branch = '0';
$bug4->executionID = 0;

$bug5 = new stdclass();
$bug5->productID = 1;
$bug5->projectID = 13;
$bug5->branch = '0';
$bug5->executionID = 0;

r($bugTest->getProjectsForCreateTest($bug1)) && p('projectID') && e('11');
r($bugTest->getProjectsForCreateTest($bug1)) && p('executionID') && e('101');
r($bugTest->getProjectsForCreateTest($bug2)) && p('projectID') && e('11');
r($bugTest->getProjectsForCreateTest($bug3)) && p('projectID') && e('11');
r($bugTest->getProjectsForCreateTest($bug4)) && p('projectID') && e('11');