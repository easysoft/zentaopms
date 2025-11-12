#!/usr/bin/env php
<?php

/**

title=测试 storyZen::setHiddenFieldsForView();
timeout=0
cid=0

- 执行storyTest模块的setHiddenFieldsForViewTest方法，参数是$product1  @0
- 执行storyTest模块的setHiddenFieldsForViewTest方法，参数是$product2  @0
- 执行storyTest模块的setHiddenFieldsForViewTest方法，参数是$product3  @1
- 执行storyTest模块的setHiddenFieldsForViewTest方法，参数是$product4  @1
- 执行storyTest模块的setHiddenFieldsForViewTest方法，参数是$product5  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/storyzen.unittest.class.php';

$product = zenData('product');
$product->id->range('1-10');
$product->shadow->range('0{2},1{8}');
$product->name->range('product1,product2,product3,product4,product5,product6,product7,product8,product9,product10');
$product->gen(10);

$project = zenData('project');
$project->id->range('1-10');
$project->model->range('scrum,kanban,waterfall,scrum,scrum,scrum,scrum,scrum,scrum,scrum');
$project->type->range('project');
$project->multiple->range('1,1,1,0,1,1,1,1,1,1');
$project->name->range('project1,project2,project3,project4,project5,project6,project7,project8,project9,project10');
$project->gen(10);

$projectproduct = zenData('projectproduct');
$projectproduct->project->range('1,2,3,4');
$projectproduct->product->range('3,4,5,6');
$projectproduct->branch->range('0');
$projectproduct->gen(4);

zenData('user')->gen(5);

su('admin');

$storyTest = new storyZenTest();

$product1 = new stdclass();
$product1->id = 1;
$product1->shadow = 0;

$product2 = new stdclass();
$product2->id = 3;
$product2->shadow = 1;

$product3 = new stdclass();
$product3->id = 5;
$product3->shadow = 1;

$product4 = new stdclass();
$product4->id = 4;
$product4->shadow = 1;

$product5 = new stdclass();
$product5->id = 6;
$product5->shadow = 1;

r($storyTest->setHiddenFieldsForViewTest($product1)) && p() && e('0');
r($storyTest->setHiddenFieldsForViewTest($product2)) && p() && e('0');
r($storyTest->setHiddenFieldsForViewTest($product3)) && p() && e('1');
r($storyTest->setHiddenFieldsForViewTest($product4)) && p() && e('1');
r($storyTest->setHiddenFieldsForViewTest($product5)) && p() && e('1');