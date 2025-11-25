#!/usr/bin/env php
<?php

/**

title=测试 projectreleaseZen::commonAction();
timeout=0
cid=17976

- 执行projectreleaseTest模块的commonActionTest方法，参数是1, 0, ''  @0
- 执行projectreleaseTest模块的commonActionTest方法，参数是1, 1, ''  @0
- 执行projectreleaseTest模块的commonActionTest方法，参数是2, 0, '' 第project条的id属性 @0
- 执行projectreleaseTest模块的commonActionTest方法，参数是3, 0, ''  @0
- 执行projectreleaseTest模块的commonActionTest方法，参数是1, 0, 'test' 属性branch @0
- 执行projectreleaseTest模块的commonActionTest方法，参数是10, 0, ''  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$product = zendata('product');
$product->id->range('1-10');
$product->name->range('Product 1,Product 2,Product 3,Product 4,Product 5,Product 6{5}');
$product->type->range('normal{5},branch{5}');
$product->status->range('normal');
$product->vision->range(',rnd,');
$product->deleted->range('0');
$product->shadow->range('0');
$product->gen(10);

$project = zendata('project');
$project->id->range('1-10');
$project->name->range('Project 1,Project 2,Project 3,Project 4,Project 5,Project 6{5}');
$project->type->range('project');
$project->status->range('doing');
$project->deleted->range('0');
$project->gen(10);

$projectproduct = zendata('projectproduct');
$projectproduct->project->range('1{3},2{2},3{2}');
$projectproduct->product->range('1-7');
$projectproduct->branch->range('0{5},1,2');
$projectproduct->gen(7);

$branch = zendata('branch');
$branch->id->range('1-5');
$branch->product->range('6,7,8,9,10');
$branch->name->range('Branch 1,Branch 2,Branch 3,Branch 4,Branch 5');
$branch->status->range('active');
$branch->deleted->range('0');
$branch->gen(5);

$system = zendata('system');
$system->id->range('1-5');
$system->product->range('1-5');
$system->name->range('System 1,System 2,System 3,System 4,System 5');
$system->deleted->range('0');
$system->gen(5);

zendata('user')->gen(5);

$userview = zendata('userview');
$userview->account->range('admin');
$userview->products->range(',1,2,3,4,5,6,7,8,9,10,');
$userview->projects->range(',1,2,3,4,5,6,7,8,9,10,');
$userview->gen(1);

su('admin');

$projectreleaseTest = new projectreleaseZenTest();

r($projectreleaseTest->commonActionTest(1, 0, '')) && p() && e('0');
r($projectreleaseTest->commonActionTest(1, 1, '')) && p() && e('0');
r($projectreleaseTest->commonActionTest(2, 0, '')) && p('project:id') && e('0');
r($projectreleaseTest->commonActionTest(3, 0, '')) && p() && e('0');
r($projectreleaseTest->commonActionTest(1, 0, 'test')) && p('branch') && e('0');
r($projectreleaseTest->commonActionTest(10, 0, '')) && p() && e('0');