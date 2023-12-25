#!/usr/bin/env php
<?php

/**

title=produ0tModel->getProjectStatsByProduct();
cid=0

- 执行product模块的getProjectStatsByProductTest方法，参数是$productID = 0, 'all', '', false, 'order_desc'  @0
- 执行product模块的getProjectStatsByProductTest方法，参数是$productID = 1, 'all', '', false, 'order_desc'
 - 第11条的totalConsumed属性 @36
 - 第11条的totalEstimate属性 @16
 - 第11条的totalLeft属性 @6
 - 第11条的progress属性 @85.70
 - 第11条的teamCount属性 @0
- 执行product模块的getProjectStatsByProductTest方法，参数是$productID = 1, 'all', '', true, 'order_desc'
 - 第61条的totalConsumed属性 @45
 - 第61条的totalEstimate属性 @40
 - 第61条的totalLeft属性 @34
 - 第61条的progress属性 @56.90
 - 第61条的teamCount属性 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('product')->gen(50);
zdTable('user')->gen(50);

$project = zdTable('project')->config('execution');
$project->PM->range('user2,user3');
$project->gen(50);

$task = zdTable('task');
$task->project->range('11,60,61,100');
$task->gen(30);

$team = zdTable('team');
$team->root->range('11,60,61,100');
$team->type->range('project');
$team->account->range('admin,user1,user2');
$team->days->range('5');
$team->hours->range('6,7');
$team->gen(12);

$stakeholder = zdTable('stakeholder');
$stakeholder->objectType->range('project');
$stakeholder->objectID->range('11,60,61,100');
$stakeholder->user->range('user4,user5');
$stakeholder->type->range('inside');
$stakeholder->from->range('');
$stakeholder->gen(20);

$projectproduct = zdTable('projectproduct');
$projectproduct->product->range('1{4},2{4}');
$projectproduct->project->range('11,60,61,100');
$projectproduct->gen(8);

$product = new productTest('admin');

r($product->getProjectStatsByProductTest($productID = 0, 'all', '', false, 'order_desc')) && p() && e('0');
r($product->getProjectStatsByProductTest($productID = 1, 'all', '', false, 'order_desc')) && p('11:totalConsumed,totalEstimate,totalLeft,progress,teamCount') && e('36,16,6,85.70,0');
r($product->getProjectStatsByProductTest($productID = 1, 'all', '', true,  'order_desc')) && p('61:totalConsumed,totalEstimate,totalLeft,progress,teamCount') && e('45,40,34,56.90,0');
