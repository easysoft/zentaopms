#!/usr/bin/env php
<?php

/**

title=测试 bugZen::getProjectsForCreate();
timeout=0
cid=0

- 步骤1:测试获取项目列表，传入存在的productID和projectID属性projectID @1
- 步骤2:测试返回对象包含projects属性 @1
- 步骤3:测试返回对象包含project属性 @1
- 步骤4:测试返回对象包含executionID属性 @1
- 步骤5:测试项目模型为waterfall时设置projectModel属性projectModel @waterfall
- 步骤6:测试传入不存在的projectID时projectID被设置为空字符串 @0
- 步骤7:测试返回bug对象包含productID属性 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';
su('admin');

$product = zenData('product');
$product->id->range('1-10');
$product->name->range('产品1,产品2,产品3,产品4,产品5,产品6,产品7,产品8,产品9,产品10');
$product->code->range('product1,product2,product3,product4,product5,product6,product7,product8,product9,product10');
$product->type->range('normal{8},normal{1},normal{1}');
$product->shadow->range('0{8},1,0');
$product->status->range('normal');
$product->deleted->range('0');
$product->gen(10);

$project = zenData('project');
$project->id->range('1-10');
$project->name->range('项目1,项目2,项目3,项目4,项目5,项目6,项目7,项目8,项目9,项目10');
$project->type->range('project');
$project->model->range('scrum{3},waterfall{2},kanban{5}');
$project->multiple->range('1{8},0,1');
$project->status->range('doing');
$project->deleted->range('0');
$project->gen(10);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1-10');
$projectProduct->product->range('1,2,3,4,5,6,7,8,9,10');
$projectProduct->gen(10);

zenData('user')->gen(5);

$bugTest = new bugZenTest();

$bug1 = new stdClass();
$bug1->productID = 1;
$bug1->projectID = 1;
$bug1->branch = '0';
$bug1->executionID = 0;

$bug2 = new stdClass();
$bug2->productID = 1;
$bug2->projectID = 1;
$bug2->branch = '0';
$bug2->executionID = 0;

$bug3 = new stdClass();
$bug3->productID = 1;
$bug3->projectID = 1;
$bug3->branch = '0';
$bug3->executionID = 0;

$bug4 = new stdClass();
$bug4->productID = 1;
$bug4->projectID = 1;
$bug4->branch = '0';
$bug4->executionID = 0;

$bug5 = new stdClass();
$bug5->productID = 4;
$bug5->projectID = 4;
$bug5->branch = '0';
$bug5->executionID = 0;

$bug6 = new stdClass();
$bug6->productID = 1;
$bug6->projectID = 999;
$bug6->branch = '0';
$bug6->executionID = 0;

$bug7 = new stdClass();
$bug7->productID = 1;
$bug7->projectID = 1;
$bug7->branch = '0';
$bug7->executionID = 0;

r($bugTest->getProjectsForCreateTest($bug1)) && p('projectID') && e('1'); // 步骤1:测试获取项目列表，传入存在的productID和projectID
r(property_exists($bugTest->getProjectsForCreateTest($bug2), 'projects')) && p() && e('1'); // 步骤2:测试返回对象包含projects属性
r(property_exists($bugTest->getProjectsForCreateTest($bug3), 'project')) && p() && e('1'); // 步骤3:测试返回对象包含project属性
r(property_exists($bugTest->getProjectsForCreateTest($bug4), 'executionID')) && p() && e('1'); // 步骤4:测试返回对象包含executionID属性
r($bugTest->getProjectsForCreateTest($bug5)) && p('projectModel') && e('waterfall'); // 步骤5:测试项目模型为waterfall时设置projectModel
r(strlen($bugTest->getProjectsForCreateTest($bug6)->projectID)) && p() && e('0'); // 步骤6:测试传入不存在的projectID时projectID被设置为空字符串
r(property_exists($bugTest->getProjectsForCreateTest($bug7), 'productID')) && p() && e('1'); // 步骤7:测试返回bug对象包含productID属性