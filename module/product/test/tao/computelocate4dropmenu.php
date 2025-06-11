#!/usr/bin/env php
<?php

/**

title=测试productModel->computeLocate4DropMenu();
cid=0

- 执行product模块的computeLocate4DropMenu方法
 -  @product
 - 属性1 @browse
- 执行product模块的computeLocate4DropMenu方法
 -  @product
 - 属性1 @browse
- 执行product模块的computeLocate4DropMenu方法
 -  @story
 - 属性1 @create
- 执行product模块的computeLocate4DropMenu方法
 -  @product
 - 属性1 @browse
- 执行product模块的computeLocate4DropMenu方法
 -  @product
 - 属性1 @browse
- 执行product模块的computeLocate4DropMenu方法
 -  @bug
 - 属性1 @browse
- 执行product模块的computeLocate4DropMenu方法
 -  @bug
 - 属性1 @view
- 执行product模块的computeLocate4DropMenu方法
 -  @bug
 - 属性1 @browse
- 执行product模块的computeLocate4DropMenu方法
 -  @testcase
 - 属性1 @browse
- 执行product模块的computeLocate4DropMenu方法
 -  @testcase
 - 属性1 @browse

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

global $tester;
$product = $tester->loadModel('product');

$product->app->rawModule  = 'product';
$product->app->rawMethod  = 'browse';
r($product->computeLocate4DropMenu()) && p('0,1') && e('product,browse');

$product->app->rawModule = 'story';
$product->app->rawMethod = 'view';
r($product->computeLocate4DropMenu()) && p('0,1') && e('product,browse');

$product->app->rawModule = 'story';
$product->app->rawMethod = 'create';
r($product->computeLocate4DropMenu()) && p('0,1') && e('story,create');

$product->app->rawModule = 'story';
$product->app->rawMethod = 'report';
r($product->computeLocate4DropMenu()) && p('0,1') && e('product,browse');

$product->app->rawModule = 'product';
$product->app->rawMethod = 'report';
r($product->computeLocate4DropMenu()) && p('0,1') && e('product,browse');

$product->app->rawModule = 'bug';
$product->app->rawMethod = 'edit';
r($product->computeLocate4DropMenu()) && p('0,1') && e('bug,browse');

$product->app->rawModule = 'bug';
$product->app->rawMethod = 'view';
r($product->computeLocate4DropMenu()) && p('0,1') && e('bug,view');

$product->app->rawModule = 'bug';
$product->app->rawMethod = 'report';
r($product->computeLocate4DropMenu()) && p('0,1') && e('bug,browse');

$product->app->rawModule = 'testcase';
$product->app->rawMethod = 'edit';
r($product->computeLocate4DropMenu()) && p('0,1') && e('testcase,browse');

$product->app->rawModule = 'testcase';
$product->app->rawMethod = 'view';
r($product->computeLocate4DropMenu()) && p('0,1') && e('testcase,browse');
