#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";

/**

title=测试productModel->computeLocate4DropMenu();
timeout=0
cid=1

*/

global $tester;
$product = $tester->loadModel('product');

$product->app->moduleName = 'product';
$product->app->methodName = 'browse';
r($product->computeLocate4DropMenu()) && p('0,1') && e('product,browse');

$product->app->moduleName = 'story';
$product->app->methodName = 'view';
r($product->computeLocate4DropMenu()) && p('0,1') && e('product,browse');

$product->app->moduleName = 'story';
$product->app->methodName = 'create';
r($product->computeLocate4DropMenu()) && p('0,1') && e('story,create');

$product->app->moduleName = 'story';
$product->app->methodName = 'report';
r($product->computeLocate4DropMenu()) && p('0,1') && e('product,browse');

$product->app->moduleName = 'product';
$product->app->methodName = 'report';
r($product->computeLocate4DropMenu()) && p('0,1') && e('product,browse');

$product->app->moduleName = 'bug';
$product->app->methodName = 'edit';
r($product->computeLocate4DropMenu()) && p('0,1') && e('bug,browse');

$product->app->moduleName = 'bug';
$product->app->methodName = 'view';
r($product->computeLocate4DropMenu()) && p('0,1') && e('bug,view');

$product->app->moduleName = 'bug';
$product->app->methodName = 'report';
r($product->computeLocate4DropMenu()) && p('0,1') && e('bug,browse');

$product->app->moduleName = 'testcase';
$product->app->methodName = 'edit';
r($product->computeLocate4DropMenu()) && p('0,1') && e('testcase,browse');

$product->app->moduleName = 'testcase';
$product->app->methodName = 'view';
r($product->computeLocate4DropMenu()) && p('0,1') && e('testcase,browse');
