#!/usr/bin/env php
<?php

/**

title=测试 bugZen::buildCreateForm();
timeout=0
cid=0

- 执行bugTest模块的buildCreateFormTest方法，参数是$bug1, $param1, 'global' 属性viewSet @1
- 执行bugTest模块的buildCreateFormTest方法，参数是$bug2, $param2, 'global' 属性viewSet @1
- 执行bugTest模块的buildCreateFormTest方法，参数是$bug3, $param3, 'qa' 属性viewSet @1
- 执行bugTest模块的buildCreateFormTest方法，参数是$bug1, $param4, 'project' 属性viewSet @1
- 执行bugTest模块的buildCreateFormTest方法，参数是$bug2, $param5, 'execution' 属性viewSet @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

zenData('product')->gen(3);
zenData('project')->gen(5);
zenData('user')->gen(10);
zenData('module')->gen(20);
zenData('build')->gen(10);
zenData('story')->gen(15);
zenData('task')->gen(10);
zenData('team')->gen(30);

su('admin');

$bugTest = new bugTest();

$bug1 = new stdclass();
$bug1->productID = 1;
$bug1->projectID = 11;
$bug1->executionID = 101;
$bug1->assignedTo = 'admin';
$bug1->branch = 'all';
$bug1->moduleID = 1;

$bug2 = new stdclass();
$bug2->productID = 2;
$bug2->projectID = 12;
$bug2->executionID = 102;
$bug2->assignedTo = 'user1';
$bug2->branch = '1';
$bug2->moduleID = 2;

$bug3 = new stdclass();
$bug3->productID = 1;
$bug3->projectID = 0;
$bug3->executionID = 0;
$bug3->assignedTo = 'admin';
$bug3->branch = 'all';
$bug3->moduleID = 0;

$param1 = array();
$param2 = array('resultID' => 1, 'stepIdList' => '1_2_3');
$param3 = array('executionID' => 103);
$param4 = array();
$param5 = array();

r($bugTest->buildCreateFormTest($bug1, $param1, 'global')) && p('viewSet') && e(1);
r($bugTest->buildCreateFormTest($bug2, $param2, 'global')) && p('viewSet') && e(1);
r($bugTest->buildCreateFormTest($bug3, $param3, 'qa')) && p('viewSet') && e(1);
r($bugTest->buildCreateFormTest($bug1, $param4, 'project')) && p('viewSet') && e(1);
r($bugTest->buildCreateFormTest($bug2, $param5, 'execution')) && p('viewSet') && e(1);