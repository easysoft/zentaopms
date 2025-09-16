#!/usr/bin/env php
<?php

/**

title=测试 projectZen::updateLinkedProducts();
timeout=0
cid=0

- 执行projectTest模块的updateLinkedProductsTest方法，参数是1,   @1
- 执行projectTest模块的updateLinkedProductsTest方法，参数是5,   @1
- 执行projectTest模块的updateLinkedProductsTest方法，参数是9,   @1
- 执行projectTest模块的updateLinkedProductsTest方法，参数是4,   @1
- 执行projectTest模块的updateLinkedProductsTest方法，参数是2,   @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';

$table = zenData('project');
$table->id->range('1-10');
$table->name->range('项目1,项目2,项目3,项目4,项目5,项目6,项目7,项目8,项目9,项目10');
$table->code->range('project1,project2,project3,project4,project5,project6,project7,project8,project9,project10');
$table->model->range('scrum{3},waterfall{3},waterfallplus{2},kanban{2}');
$table->multiple->range('1{6},0{4}');
$table->stageBy->range('product{5},project{5}');
$table->type->range('project{10}');
$table->status->range('wait{3},doing{4},suspended{2},closed{1}');
$table->hasProduct->range('1{8},0{2}');
$table->gen(10);

$productTable = zenData('product');
$productTable->id->range('1-8');
$productTable->name->range('产品1,产品2,产品3,产品4,产品5,产品6,产品7,产品8');
$productTable->code->range('prod1,prod2,prod3,prod4,prod5,prod6,prod7,prod8');
$productTable->status->range('normal{6},closed{2}');
$productTable->type->range('normal{8}');
$productTable->gen(8);

$projectProductTable = zenData('projectproduct');
$projectProductTable->project->range('1{3},2{2},3{2},4{1}');
$projectProductTable->product->range('1,2,3,4,5,6,7,8');
$projectProductTable->gen(8);

$executionTable = zenData('project');
$executionTable->id->range('101-110');
$executionTable->name->range('执行1,执行2,执行3,执行4,执行5,执行6,执行7,执行8,执行9,执行10');
$executionTable->type->range('execution{10}');
$executionTable->parent->range('1{3},2{3},3{2},4{2}');
$executionTable->multiple->range('1{10}');
$executionTable->gen(10);

su('admin');

$projectTest = new projectTest();

r($projectTest->updateLinkedProductsTest(1, (object)array('id' => 1, 'multiple' => 1, 'model' => 'scrum', 'stageBy' => 'product'), array(101, 102, 103))) && p() && e('1');
r($projectTest->updateLinkedProductsTest(5, (object)array('id' => 5, 'multiple' => 1, 'model' => 'scrum', 'stageBy' => 'product'), array())) && p() && e('1');
r($projectTest->updateLinkedProductsTest(9, (object)array('id' => 9, 'multiple' => 0, 'model' => 'scrum', 'stageBy' => 'product'), array(109))) && p() && e('1');
r($projectTest->updateLinkedProductsTest(4, (object)array('id' => 4, 'multiple' => 1, 'model' => 'waterfall', 'stageBy' => 'project'), array(107, 108))) && p() && e('1');
r($projectTest->updateLinkedProductsTest(2, (object)array('id' => 2, 'multiple' => 1, 'model' => 'kanban', 'stageBy' => 'product'), array(104, 105, 106))) && p() && e('1');