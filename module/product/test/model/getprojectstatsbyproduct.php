#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('product')->gen(50);
zdTable('user')->gen(50);

$project = zdTable('project');
$project->PM->range('user2,user3');
$project->gen(50);

$task = zdTable('task');
$task->project->range('1-20');
$task->gen(100);

$team = zdTable('team');
$team->root->range('1-50');
$team->type->range('project');
$team->account->range('admin,admin,user1,user1');
$team->days->range('5');
$team->hours->range('6,7');
$team->gen(50);

$stakeholder = zdTable('stakeholder');
$stakeholder->objectType->range('project');
$stakeholder->objectID->range('1-50');
$stakeholder->user->range('user4,user5');
$stakeholder->type->range('inside');
$stakeholder->from->range('');
$stakeholder->gen(50);

$projectproduct = zdTable('projectproduct');
$projectproduct->product->range('1-2');
$projectproduct->project->range('1-50');
$projectproduct->gen(50);

/**

title=productModel->getProjectStatsByProductTest();
cid=1
pid=1

*/

$product = new productTest('admin');

r($product->getProjectStatsByProductTest($productID = 0, 'all', '', false, 'order_desc')) && p() && e('0');
r($product->getProjectStatsByProductTest($productID = 1, 'all', '', false, 'order_desc')) && p('11:totalConsumed,totalEstimate,totalLeft,progress,teamCount') && e('15,30,10,60,1');
r($product->getProjectStatsByProductTest($productID = 1, 'all', '', true,  'order_desc')) && p('17:totalConsumed,totalEstimate,totalLeft,progress,teamCount') && e('45,27,11,80,1');
