#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('product')->gen(10);

/**

title=productModel->buildOperateMenu();
cid=1
pid=1

*/

global $tester;
$productModel = $tester->loadModel('product');
$productModel->app->user->admin = true;
$productModel->app->moduleName  = 'product';
$productModel->app->methodName  = 'view';

$product = $productModel->getByID(1);
$menu    = $productModel->buildOperateMenu($product, 'view');
r(str_contains($menu, '关闭'))     && p() && e('1'); //检查是否有关闭链接。
r(str_contains($menu, '编辑产品')) && p() && e('1'); //检查是否有编辑链接。
r(str_contains($menu, '删除产品')) && p() && e('1'); //检查是否有删除链接。

$menu = $productModel->buildOperateMenu($product, 'browse');
r(str_contains($menu, '编辑产品')) && p() && e('1'); //检查是否有编辑链接。
r(str_contains($menu, '删除产品')) && p() && e('0'); //检查是否有删除链接。
