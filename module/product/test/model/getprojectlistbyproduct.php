#!/usr/bin/env php
<?php

/**

title=productModel->getProjectListByProduct();
cid=0

- 执行product模块的getProjectListByProduct方法，参数是$productID = 0, 'all', '', false, 'order_desc'  @0
- 执行product模块的getProjectListByProduct方法，参数是$productID = 1, 'all', '', false, 'order_desc'  @17
- 执行product模块的getProjectListByProduct方法，参数是$productID = 1, 'all', '0', false, 'order_desc'  @17
- 执行product模块的getProjectListByProduct方法，参数是$productID = 1, 'undone', '', false, 'order_desc'  @12
- 执行product模块的getProjectListByProduct方法，参数是$productID = 1, 'wait', 'all', false, 'order_desc'  @5
- 执行product模块的getProjectListByProduct方法，参数是$productID = 1, 'all', '', false, 'order_desc', $pager  @17
- 执行product模块的getProjectListByProduct方法，参数是$productID = 1, 'all', '', false, 'order_desc', $pager  @5
- 执行product模块的getProjectListByProduct方法，参数是$productID = 1, 'all', '', true, 'order_desc'  @4
- 执行product模块的getProjectListByProduct方法，参数是$productID = 1, 'all', '0', true, 'order_desc'  @4
- 执行product模块的getProjectListByProduct方法，参数是$productID = 1, 'undone', '', true, 'order_desc'  @4
- 执行product模块的getProjectListByProduct方法，参数是$productID = 1, 'wait', 'all', true, 'order_desc'  @2
- 执行product模块的getProjectListByProduct方法，参数是$productID = 1, 'all', '', true, 'order_desc', $pager  @4
- 执行product模块的getProjectListByProduct方法，参数是$productID = 1, 'all', '', true, 'order_desc', $pager  @4

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
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

global $tester;
$product = $tester->loadModel('product');
su('admin');
$product->app->user->admin = true;
$product->app->moduleName = 'product';
$product->app->methodName = 'project';

r(count($product->getProjectListByProduct($productID = 0, 'all',    '',    false, 'order_desc')))   && p() && e('0');
r(count($product->getProjectListByProduct($productID = 1, 'all',    '',    false, 'order_desc')))   && p() && e('17');
r(count($product->getProjectListByProduct($productID = 1, 'all',    '0',   false, 'order_desc')))   && p() && e('17');
r(count($product->getProjectListByProduct($productID = 1, 'undone', '',    false, 'order_desc')))   && p() && e('12');
r(count($product->getProjectListByProduct($productID = 1, 'wait',   'all', false, 'order_desc')))   && p() && e('5');

$product->app->loadClass('pager', $static = true);
$pager = new pager(0, 50, 1);
r(count($product->getProjectListByProduct($productID = 1, 'all', '', false, 'order_desc', $pager))) && p() && e('17');

$pager = new pager(0, 5, 1);
r(count($product->getProjectListByProduct($productID = 1, 'all', '', false, 'order_desc', $pager))) && p() && e('5');

$product->app->user->admin = false;
$product->app->user->view->projects = '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,48,49,50';
r(count($product->getProjectListByProduct($productID = 1, 'all',    '',    true, 'order_desc')))   && p() && e('4');
r(count($product->getProjectListByProduct($productID = 1, 'all',    '0',   true, 'order_desc')))   && p() && e('4');
r(count($product->getProjectListByProduct($productID = 1, 'undone', '',    true, 'order_desc')))   && p() && e('4');
r(count($product->getProjectListByProduct($productID = 1, 'wait',   'all', true, 'order_desc')))   && p() && e('2');

$product->app->loadClass('pager', $static = true);
$pager = new pager(0, 50, 1);
r(count($product->getProjectListByProduct($productID = 1, 'all', '', true, 'order_desc', $pager))) && p() && e('4');

$pager = new pager(0, 5, 1);
r(count($product->getProjectListByProduct($productID = 1, 'all', '', true, 'order_desc', $pager))) && p() && e('4');
