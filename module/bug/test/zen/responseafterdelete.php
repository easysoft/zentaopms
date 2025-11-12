#!/usr/bin/env php
<?php

/**

title=测试 bugZen::responseAfterDelete();
timeout=0
cid=0

- 执行bugTest模块的responseAfterDeleteTest方法，参数是$bug1, '', '' 属性result @1
- 执行bugTest模块的responseAfterDeleteTest方法，参数是$bug2, '', 'Custom success message' 属性result @1
- 执行bugTest模块的responseAfterDeleteTest方法，参数是$bug3, '', '' 属性result @1
- 执行bugTest模块的responseAfterDeleteTest方法，参数是$bug4, 'taskkanban', '' 属性result @1
- 执行bugTest模块的responseAfterDeleteTest方法，参数是$bug5, '', '' 属性result @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zendata('bug')->gen(10);
zendata('task')->gen(5);
zendata('product')->gen(5);

su('admin');

$bugTest = new bugZenTest();

$bug1 = new stdClass();
$bug1->id = 1;
$bug1->product = 1;
$bug1->toTask = 0;

$bug2 = new stdClass();
$bug2->id = 2;
$bug2->product = 1;
$bug2->toTask = 0;

$bug3 = new stdClass();
$bug3->id = 3;
$bug3->product = 1;
$bug3->toTask = 1;

$bug4 = new stdClass();
$bug4->id = 4;
$bug4->product = 1;
$bug4->toTask = 0;

$bug5 = new stdClass();
$bug5->id = 5;
$bug5->product = 1;
$bug5->toTask = 0;

r($bugTest->responseAfterDeleteTest($bug1, '', '')) && p('result') && e('1');
r($bugTest->responseAfterDeleteTest($bug2, '', 'Custom success message')) && p('result') && e('1');
r($bugTest->responseAfterDeleteTest($bug3, '', '')) && p('result') && e('1');
r($bugTest->responseAfterDeleteTest($bug4, 'taskkanban', '')) && p('result') && e('1');
r($bugTest->responseAfterDeleteTest($bug5, '', '')) && p('result') && e('1');