#!/usr/bin/env php
<?php

/**

title=测试 projectreleaseZen::commonAction();
timeout=0
cid=0

- 执行projectreleaseTest模块的commonActionTest方法，参数是1, 1, ''  @2,1,0,,1,0

- 执行projectreleaseTest模块的commonActionTest方法，参数是2, 0, ''  @3,3,0,,2,0

- 执行projectreleaseTest模块的commonActionTest方法，参数是3, 6, '1'  @2,6,3,1,3,0

- 执行projectreleaseTest模块的commonActionTest方法，参数是1, 2, ''  @2,2,0,,1,0

- 执行projectreleaseTest模块的commonActionTest方法，参数是999, 0, ''  @0,0,0,,0,0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/projectrelease.unittest.class.php';

zenData('user')->gen(5);
su('admin');

$project = zenData('project');
$project->id->range('1-10');
$project->name->range('项目{1-10}');
$project->type->range('project{10}');
$project->status->range('wait{3},doing{5},done{2}');
$project->gen(10);

$product = zenData('product');
$product->id->range('1-10');
$product->name->range('产品{1-10}');
$product->type->range('normal{5},branch{5}');
$product->status->range('normal{8},closed{2}');
$product->gen(10);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1{2},2{3},3{2}');
$projectProduct->product->range('1-7');
$projectProduct->branch->range('0{5},1-2{2}');
$projectProduct->gen(7);

$branch = zenData('branch');
$branch->id->range('1-5');
$branch->product->range('6-10');
$branch->name->range('分支{1-5}');
$branch->status->range('active{4},closed{1}');
$branch->gen(5);

$projectreleaseTest = new projectreleaseTest();

r($projectreleaseTest->commonActionTest(1, 1, '')) && p() && e('2,1,0,,1,0');
r($projectreleaseTest->commonActionTest(2, 0, '')) && p() && e('3,3,0,,2,0');
r($projectreleaseTest->commonActionTest(3, 6, '1')) && p() && e('2,6,3,1,3,0');
r($projectreleaseTest->commonActionTest(1, 2, '')) && p() && e('2,2,0,,1,0');
r($projectreleaseTest->commonActionTest(999, 0, '')) && p() && e('0,0,0,,0,0');