#!/usr/bin/env php
<?php

/**

title=productTao->checkLocateCreate();
cid=0

- 没有任何产品 @1
- 已经存在产品 @0
- 访问的方法是product-create @0
- 访问的方法是product-index @0
- 访问的方法是product-showerrornone @0
- 访问的方法是product-ajaxgetdropmenu @0
- 访问的方法是product-kanban @0
- 访问的方法是product-kanban @0
- 访问的方法是product-manageline @0
- 访问的方法是product-export @0
- 访问的方法是product-ajaxgetplans @0
- 在移动端访问的product-browse页面 @0
- 通过接口访问的product-browse页面 @0
- 在PC中访问的product-browse页面 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

global $tester;
$productTao = $tester->loadModel('product');

r($productTao->checkLocateCreate(array())) && p() && e('1');                 //没有任何产品
r($productTao->checkLocateCreate(array(1 => '产品1'))) && p() && e('0');     //已经存在产品

$productTao->app->methodName = 'create';
r($productTao->checkLocateCreate(array())) && p() && e('0');        //访问的方法是product-create
$productTao->app->methodName = 'index';
r($productTao->checkLocateCreate(array())) && p() && e('0');        //访问的方法是product-index
$productTao->app->methodName = 'showerrornone';
r($productTao->checkLocateCreate(array())) && p() && e('0');        //访问的方法是product-showerrornone
$productTao->app->methodName = 'ajaxgetdropmenu';
r($productTao->checkLocateCreate(array())) && p() && e('0');        //访问的方法是product-ajaxgetdropmenu
$productTao->app->methodName = 'kanban';
r($productTao->checkLocateCreate(array())) && p() && e('0');        //访问的方法是product-kanban
$productTao->app->methodName = 'all';
r($productTao->checkLocateCreate(array())) && p() && e('0');        //访问的方法是product-kanban
$productTao->app->methodName = 'manageline';
r($productTao->checkLocateCreate(array())) && p() && e('0');        //访问的方法是product-manageline
$productTao->app->methodName = 'export';
r($productTao->checkLocateCreate(array())) && p() && e('0');        //访问的方法是product-export
$productTao->app->methodName = 'ajaxgetplans';
r($productTao->checkLocateCreate(array())) && p() && e('0');        //访问的方法是product-ajaxgetplans

$productTao->app->methodName = 'browse';
$productTao->app->viewType   = 'mhtml';
r($productTao->checkLocateCreate(array())) && p() && e('0');       //在移动端访问的product-browse页面

$productTao->app->viewType   = 'json';
r($productTao->checkLocateCreate(array())) && p() && e('0');       //通过接口访问的product-browse页面

$productTao->app->viewType   = 'html';
r($productTao->checkLocateCreate(array())) && p() && e('1');       //在PC中访问的product-browse页面
