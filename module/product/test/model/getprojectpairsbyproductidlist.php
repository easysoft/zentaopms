#!/usr/bin/env php
<?php

/**

title=测试productTao->getProjectPairsByProductIdList();
cid=0

- 不传入产品 ID。 @0
- 用超级管理员账号，传入单个产品数组，确认获取条目数。 @10
- 用超级管理员账号，传入多个产品数组，确认获取条目数。 @20
- 用超级管理员账号，传入产品数组中含有不存在的产品，确认获取条目数。 @10
- 不用超级管理员账号，传入单个产品数组，确认获取条目数。 @3
- 不用超级管理员账号，传入多个产品数组，确认获取条目数。 @3
- 不用超级管理员账号，传入产品数组中含有不存在的产品，确认获取条目数。 @3

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

$product = zdTable('product');
$product->type->range('normal{2},branch,normal{2}');
$product->gen(5);

$project = zdTable('project');
$project->status->range('wait{2},doing{4},suspended,closed');
$project->multiple->range('0{25},1{25}');
$project->gen(50);

$projectproduct = zdTable('projectproduct');
$projectproduct->product->range('1-4');
$projectproduct->project->range('11-70');
$projectproduct->branch->range('0{2},1,0');
$projectproduct->gen(50);

global $tester;
$product = $tester->loadModel('product');
$product->app->user->admin = true;

r($product->getProjectPairsByProductIdList(array()))             && p() && e('0'); //不传入产品 ID。
r(count($product->getProjectPairsByProductIdList(array(4))))     && p() && e('10'); //用超级管理员账号，传入单个产品数组，确认获取条目数。
r(count($product->getProjectPairsByProductIdList(array(3, 4))))  && p() && e('20'); //用超级管理员账号，传入多个产品数组，确认获取条目数。
r(count($product->getProjectPairsByProductIdList(array(4, 10)))) && p() && e('10'); //用超级管理员账号，传入产品数组中含有不存在的产品，确认获取条目数。

$product->app->user->admin = false;
$product->app->user->view->projects = '14,38,46,58';
r(count($product->getProjectPairsByProductIdList(array(4))))     && p() && e('3'); //不用超级管理员账号，传入单个产品数组，确认获取条目数。
r(count($product->getProjectPairsByProductIdList(array(3, 4))))  && p() && e('3'); //不用超级管理员账号，传入多个产品数组，确认获取条目数。
r(count($product->getProjectPairsByProductIdList(array(4, 10)))) && p() && e('3'); //不用超级管理员账号，传入产品数组中含有不存在的产品，确认获取条目数。
