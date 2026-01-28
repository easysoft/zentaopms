#!/usr/bin/env php
<?php

/**

title=测试 bugZen::setCreateMenu();
timeout=0
cid=15478

- 执行bugTest模块的setCreateMenuTest方法，参数是1, 'all', array  @1
- 执行bugTest模块的setCreateMenuTest方法，参数是1, '', array  @1
- 执行bugTest模块的setCreateMenuTest方法，参数是2, 'main', array  @1
- 执行bugTest模块的setCreateMenuTest方法，参数是3, 'all', array  @1
- 执行bugTest模块的setCreateMenuTest方法，参数是4, 'all', array  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$table = zenData('product');
$table->id->range('1-5');
$table->name->range('产品1,产品2,产品3,产品4,产品5');
$table->type->range('normal{3},branch{2}');
$table->status->range('normal{5}');
$table->deleted->range('0{5}');
$table->gen(5);

su('admin');

$bugTest = new bugZenTest();

r($bugTest->setCreateMenuTest(1, 'all', array())) && p() && e('1');
r($bugTest->setCreateMenuTest(1, '', array())) && p() && e('1');
r($bugTest->setCreateMenuTest(2, 'main', array())) && p() && e('1');
r($bugTest->setCreateMenuTest(3, 'all', array('executionID' => 101))) && p() && e('1');
r($bugTest->setCreateMenuTest(4, 'all', array('projectID' => 11))) && p() && e('1');