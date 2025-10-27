#!/usr/bin/env php
<?php

/**

title=测试 bugZen::responseAfterCreate();
timeout=0
cid=0

- 执行bugTest模块的responseAfterCreateTest方法，参数是$bug1, array 
 - 属性result @success
 - 属性load @bug-browse-productID-1-branch-0
- 执行bugTest模块的responseAfterCreateTest方法，参数是$bug2, array 
 - 属性result @success
 - 属性load @execution-bug-executionID-10
- 执行bugTest模块的responseAfterCreateTest方法，参数是$bug3, array 
 - 属性result @success
 - 属性load @project-bug-projectID-5
- 执行bugTest模块的responseAfterCreateTest方法，参数是$bug1, array 
 - 属性result @success
 - 属性message @创建成功
 - 属性id @1
- 执行bugTest模块的responseAfterCreateTest方法，参数是$bug2, array 
 - 属性status @success
 - 属性data @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

// 准备测试数据 - 创建mock bug对象
$bug1 = new stdClass();
$bug1->id = 1;
$bug1->product = 1;
$bug1->branch = 0;
$bug1->module = 1;
$bug1->execution = 0;

$bug2 = new stdClass();
$bug2->id = 2;
$bug2->product = 2;
$bug2->branch = 1;
$bug2->module = 2;
$bug2->execution = 10;

$bug3 = new stdClass();
$bug3->id = 3;
$bug3->product = 3;
$bug3->branch = 0;
$bug3->module = 3;
$bug3->execution = 0;

su('admin');

$bugTest = new bugTest();

r($bugTest->responseAfterCreateTest($bug1, array('tab' => 'product'), '')) && p('result,load') && e('success,bug-browse-productID-1-branch-0');
r($bugTest->responseAfterCreateTest($bug2, array('tab' => 'execution', 'executionID' => 10), '')) && p('result,load') && e('success,execution-bug-executionID-10');
r($bugTest->responseAfterCreateTest($bug3, array('tab' => 'project', 'projectID' => 5), '')) && p('result,load') && e('success,project-bug-projectID-5');
r($bugTest->responseAfterCreateTest($bug1, array('viewType' => 'json'), '创建成功')) && p('result,message,id') && e('success,创建成功,1');
r($bugTest->responseAfterCreateTest($bug2, array('runMode' => 'api'), '')) && p('status,data') && e('success,2');