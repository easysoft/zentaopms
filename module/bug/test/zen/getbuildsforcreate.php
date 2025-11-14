#!/usr/bin/env php
<?php

/**

title=测试 bugZen::getBuildsForCreate();
timeout=0
cid=15451

- 步骤1:测试allBuilds为true时builds是数组 @1
- 步骤2:测试有executionID时builds是数组 @1
- 步骤3:测试有projectID但无executionID时builds是数组 @1
- 步骤4:测试无executionID和projectID时builds是数组 @1
- 步骤5:测试返回对象包含builds属性 @1
- 步骤6:测试不同分支获取builds是数组 @1
- 步骤7:测试返回对象包含原有属性 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';
su('admin');

$build = zenData('build');
$build->id->range('1-20');
$build->product->range('1{10},2{5},3{5}');
$build->branch->range('0,1,2');
$build->project->range('1{5},2{5},3{5},0{5}');
$build->execution->range('0,101{5},102{5},0{10}');
$build->name->prefix('Build')->range('1-20');
$build->deleted->range('0');
$build->gen(20);

zenData('product')->gen(5);
zenData('project')->gen(5);
zenData('user')->gen(5);

$bugTest = new bugZenTest();

$bug1 = new stdClass();
$bug1->productID = 1;
$bug1->branch = '0';
$bug1->projectID = 0;
$bug1->executionID = 0;
$bug1->allBuilds = true;

$bug2 = new stdClass();
$bug2->productID = 1;
$bug2->branch = '0';
$bug2->projectID = 1;
$bug2->executionID = 101;
$bug2->allBuilds = false;

$bug3 = new stdClass();
$bug3->productID = 1;
$bug3->branch = '0';
$bug3->projectID = 1;
$bug3->executionID = 0;
$bug3->allBuilds = false;

$bug4 = new stdClass();
$bug4->productID = 1;
$bug4->branch = '0';
$bug4->projectID = 0;
$bug4->executionID = 0;
$bug4->allBuilds = false;

$bug5 = new stdClass();
$bug5->productID = 2;
$bug5->branch = '1';
$bug5->projectID = 0;
$bug5->executionID = 0;
$bug5->allBuilds = false;

r(is_array($bugTest->getBuildsForCreateTest($bug1)->builds)) && p() && e('1'); // 步骤1:测试allBuilds为true时builds是数组
r(is_array($bugTest->getBuildsForCreateTest($bug2)->builds)) && p() && e('1'); // 步骤2:测试有executionID时builds是数组
r(is_array($bugTest->getBuildsForCreateTest($bug3)->builds)) && p() && e('1'); // 步骤3:测试有projectID但无executionID时builds是数组
r(is_array($bugTest->getBuildsForCreateTest($bug4)->builds)) && p() && e('1'); // 步骤4:测试无executionID和projectID时builds是数组
r(property_exists($bugTest->getBuildsForCreateTest($bug1), 'builds')) && p() && e('1'); // 步骤5:测试返回对象包含builds属性
r(is_array($bugTest->getBuildsForCreateTest($bug5)->builds)) && p() && e('1'); // 步骤6:测试不同分支获取builds是数组
r(property_exists($bugTest->getBuildsForCreateTest($bug1), 'productID')) && p() && e('1'); // 步骤7:测试返回对象包含原有属性