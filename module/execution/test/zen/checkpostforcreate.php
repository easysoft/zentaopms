#!/usr/bin/env php
<?php

/**

title=测试 executionZen::checkPostForCreate();
timeout=0
cid=0

- 执行executionzenTest模块的checkPostForCreateTest方法  @1
- 执行executionzenTest模块的checkPostForCreateTest方法 属性project @所属项目不能为空。
- 执行executionzenTest模块的checkPostForCreateTest方法 属性days @可用工作日不能超过『10』天
- 执行executionzenTest模块的checkPostForCreateTest方法 属性products[0] @最少关联一个产品
- 执行executionzenTest模块的checkPostForCreateTest方法  @1
- 执行executionzenTest模块的checkPostForCreateTest方法 属性days @可用工作日不能超过『-180』天
- 执行executionzenTest模块的checkPostForCreateTest方法  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

$project = zenData('project');
$project->id->range('1-10');
$project->name->range('敏捷项目1,敏捷项目2,瀑布项目1,瀑布项目2,看板项目1');
$project->model->range('scrum{2},waterfall{3},waterfallplus{2},kanban{3}');
$project->type->range('project{10}');
$project->status->range('doing{10}');
$project->begin->range('`2020-01-01`');
$project->end->range('`2030-12-31`');
$project->deleted->range('0{10}');
$project->gen(10);

$product = zenData('product');
$product->id->range('1-10');
$product->name->range('产品A,产品B,产品C,产品D,产品E');
$product->type->range('normal{7},branch{3}');
$product->status->range('normal{10}');
$product->deleted->range('0{10}');
$product->gen(10);

zenData('user')->gen(5);

su('admin');

$executionzenTest = new executionZenTest();

$_POST['project'] = 1;
$_POST['name'] = '测试执行1';
$_POST['begin'] = '2024-01-01';
$_POST['end'] = '2024-06-30';
$_POST['days'] = 100;
r($executionzenTest->checkPostForCreateTest()) && p() && e('1');

unset($_POST);
r($executionzenTest->checkPostForCreateTest()) && p('project') && e('所属项目不能为空。');

$_POST['project'] = 1;
$_POST['name'] = '测试执行2';
$_POST['begin'] = '2024-01-01';
$_POST['end'] = '2024-01-10';
$_POST['days'] = 50;
r($executionzenTest->checkPostForCreateTest()) && p('days') && e('可用工作日不能超过『10』天');

$_POST['project'] = 3;
$_POST['name'] = '测试执行3';
$_POST['begin'] = '2024-02-01';
$_POST['end'] = '2024-06-30';
unset($_POST['days']);
r($executionzenTest->checkPostForCreateTest()) && p('products[0]') && e('最少关联一个产品');

$_POST['project'] = 4;
$_POST['name'] = '测试执行4';
$_POST['begin'] = '2024-02-01';
$_POST['end'] = '2024-06-30';
$_POST['products'] = array(1, 2);
r($executionzenTest->checkPostForCreateTest()) && p() && e('1');

$_POST['project'] = 1;
$_POST['name'] = '测试执行5';
$_POST['begin'] = '2024-06-30';
$_POST['end'] = '2024-01-01';
$_POST['days'] = 10;
unset($_POST['products']);
r($executionzenTest->checkPostForCreateTest()) && p('days') && e('可用工作日不能超过『-180』天');

$_POST['project'] = 3;
$_POST['name'] = '测试执行6';
$_POST['begin'] = '2024-02-01';
$_POST['end'] = '2024-06-30';
$_POST['products'] = array(8);
$_POST['branch'] = array(array());
r($executionzenTest->checkPostForCreateTest()) && p() && e('1');