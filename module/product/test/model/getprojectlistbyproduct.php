#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('product')->gen(50);
zdTable('user')->gen(50);

$project = zdTable('project');
$project->PM->range('user2,user3');
$project->gen(50);

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

title=productModel->getProjectListByProduct();
cid=1
pid=1

*/

global $tester;
$product = $tester->loadModel('product');
su('admin');
$product->app->user->admin = true;
$product->app->moduleName = 'product';
$product->app->methodName = 'project';

r(count($product->getProjectListByProduct($productID = 0, 'all',    '',    'order_desc')))   && p() && e('0');
r(count($product->getProjectListByProduct($productID = 1, 'all',    '',    'order_desc')))   && p() && e('20');
r(count($product->getProjectListByProduct($productID = 1, 'all',    '0',   'order_desc')))   && p() && e('17');
r(count($product->getProjectListByProduct($productID = 1, 'undone', '',    'order_desc')))   && p() && e('15');
r(count($product->getProjectListByProduct($productID = 1, 'wait',   'all', 'order_desc')))   && p() && e('5');
