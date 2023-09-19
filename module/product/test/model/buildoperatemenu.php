#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('product')->gen(10);

/**

title=productModel->buildOperateMenu();
timeout=0
cid=1

*/

global $tester;
$productModel = $tester->loadModel('product');
$productModel->app->user->admin = true;
$productModel->app->moduleName  = 'product';
$productModel->app->methodName  = 'view';

$product = $productModel->getByID(1);
$menu    = $productModel->buildOperateMenu($product);
r($menu['main'])          && p('0:text') && e('关闭'); // 检查是否有关闭链接。
r(count($menu['suffix'])) && p()         && e('2');    // 检查是否有编辑和删除链接。
