#!/usr/bin/env php
<?php

/**

title=productModel->getShadowProductByProject();
cid=0

- 不传入项目编写。 @0
- 传入正常的项目编号。
 - 属性name @正常产品1
 - 属性shadow @1
- 传入不存在关联关系的项目编号。 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

$product = zdTable('product');
$product->shadow->range('1');
$product->gen(1);
$projectProduct = zdTable('projectproduct');
$projectProduct->project->range('1');
$projectProduct->product->range('1');
$projectProduct->gen(1);

global $tester;
$product = $tester->loadModel('product');

r($product->getShadowProductByProject(0)) && p()              && e('0');           //不传入项目编写。
r($product->getShadowProductByProject(1)) && p('name,shadow') && e('正常产品1,1'); //传入正常的项目编号。
r($product->getShadowProductByProject(2)) && p()              && e('0');           //传入不存在关联关系的项目编号。
