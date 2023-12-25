#!/usr/bin/env php
<?php

/**

title=测试productModel->getProjectPairsByProduct();
cid=0

- 不传入产品 ID。 @0
- 传入不存在产品 ID。 @0
- 用超级管理员，传入产品 ID，确认获取条目数。 @10
- 用超级管理员，传入产品 ID，确认获取非关闭项目的条目数。 @10
- 用超级管理员，传入产品 ID 和追加查询的项目，确认获取非关闭项目的条目数。 @10
- 用超级管理员，传入产品 ID，确认获取启用执行非关闭项目的条目数。 @7
- 用超级管理员，传入产品 ID 和所有分支，确认获取条目数。 @10
- 用超级管理员，传入产品 ID 和存在分支，确认获取条目数。 @10
- 用超级管理员，传入产品 ID 和不存在分支，确认获取条目数。 @0
- 用超级管理员，传入产品 ID，确认获取条目数。 @3
- 用超级管理员，传入产品 ID，确认获取非关闭项目的条目数。 @3
- 用超级管理员，传入产品 ID 和追加查询的项目，确认获取非关闭项目的条目数。 @4
- 用超级管理员，传入产品 ID，确认获取启用执行非关闭项目的条目数。 @2

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('user')->gen(5);
su('admin');

$product = zdTable('product');
$product->type->range('normal{2},branch,normal{2}');
$product->gen(5);

$project = zdTable('project');
$project->status->range('wait{2},doing{4},suspended,closed');
$project->multiple->range('0{25},1{25}');
$project->deleted->range('0');
$project->gen(50);

$projectproduct = zdTable('projectproduct');
$projectproduct->product->range('1-4');
$projectproduct->project->range('11-70');
$projectproduct->branch->range('0{2},1,0');
$projectproduct->gen(50);

global $tester;
$product = $tester->loadModel('product');
$product->app->user->admin = true;

r($product->getProjectPairsByProduct(0))  && p() && e('0'); //不传入产品 ID。
r($product->getProjectPairsByProduct(10)) && p() && e('0'); //传入不存在产品 ID。

r(count($product->getProjectPairsByProduct(4)))                                 && p() && e('10'); // 用超级管理员，传入产品 ID，确认获取条目数。
r(count($product->getProjectPairsByProduct(4, '', '', 'noclosed')))             && p() && e('10'); // 用超级管理员，传入产品 ID，确认获取非关闭项目的条目数。
r(count($product->getProjectPairsByProduct(4, '', '18', 'noclosed')))           && p() && e('10'); // 用超级管理员，传入产品 ID 和追加查询的项目，确认获取非关闭项目的条目数。
r(count($product->getProjectPairsByProduct(4, '', '', 'noclosed', 'multiple'))) && p() && e('7');  // 用超级管理员，传入产品 ID，确认获取启用执行非关闭项目的条目数。
r(count($product->getProjectPairsByProduct(3, 'all')))                          && p() && e('10'); // 用超级管理员，传入产品 ID 和所有分支，确认获取条目数。
r(count($product->getProjectPairsByProduct(3, '1')))                            && p() && e('10'); // 用超级管理员，传入产品 ID 和存在分支，确认获取条目数。
r(count($product->getProjectPairsByProduct(3, '2')))                            && p() && e('0');  // 用超级管理员，传入产品 ID 和不存在分支，确认获取条目数。

$product->app->user->admin = false;
$product->app->user->view->projects = '14,38,46,58';
r(count($product->getProjectPairsByProduct(4)))                                 && p() && e('3'); //用超级管理员，传入产品 ID，确认获取条目数。
r(count($product->getProjectPairsByProduct(4, '', '', 'noclosed')))             && p() && e('3'); //用超级管理员，传入产品 ID，确认获取非关闭项目的条目数。
r(count($product->getProjectPairsByProduct(4, '', '18', 'noclosed')))           && p() && e('4'); //用超级管理员，传入产品 ID 和追加查询的项目，确认获取非关闭项目的条目数。
r(count($product->getProjectPairsByProduct(4, '', '', 'noclosed', 'multiple'))) && p() && e('2'); //用超级管理员，传入产品 ID，确认获取启用执行非关闭项目的条目数。
