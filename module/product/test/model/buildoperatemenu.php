#!/usr/bin/env php
<?php

/**

title=productModel->buildOperateMenu();
cid=0

- 检查是否有关闭链接。第0条的text属性 @关闭
- 检查是否有编辑和删除链接。 @2

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('product')->gen(10);

global $tester;
$productModel = $tester->loadModel('product');
$productModel->app->user->admin = true;
$productModel->app->moduleName  = 'product';
$productModel->app->methodName  = 'view';

$product = $productModel->getByID(1);
$menu    = $productModel->buildOperateMenu($product);
r($menu['main'])          && p('0:text') && e('关闭'); // 检查是否有关闭链接。
r(count($menu['suffix'])) && p()         && e('2');    // 检查是否有编辑和删除链接。
