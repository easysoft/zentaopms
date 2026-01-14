#!/usr/bin/env php
<?php

/**

title=productModel->getShadowProductByProject();
timeout=0
cid=17512

- 不传入项目编写。 @0
- 传入正常的项目编号。
 - 属性name @正常产品1
 - 属性shadow @1
- 传入正常的项目编号。
 - 属性name @正常产品3
 - 属性shadow @1
- 传入正常的项目编号。
 - 属性name @正常产品5
 - 属性shadow @1
- 传入正常的项目编号。
 - 属性name @正常产品7
 - 属性shadow @1
- 传入正常的项目编号。
 - 属性name @正常产品9
 - 属性shadow @1
- 传入不存在关联关系的项目编号。 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$product = zenData('product');
$product->shadow->range('1');
$product->gen(10);
$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1-10');
$projectProduct->product->range('1-10');
$projectProduct->gen(10);

global $tester;
$product = $tester->loadModel('product');

r($product->getShadowProductByProject(0))  && p()              && e('0');           //不传入项目编写。
r($product->getShadowProductByProject(1))  && p('name,shadow') && e('正常产品1,1'); //传入正常的项目编号。
r($product->getShadowProductByProject(3))  && p('name,shadow') && e('正常产品3,1'); //传入正常的项目编号。
r($product->getShadowProductByProject(5))  && p('name,shadow') && e('正常产品5,1'); //传入正常的项目编号。
r($product->getShadowProductByProject(7))  && p('name,shadow') && e('正常产品7,1'); //传入正常的项目编号。
r($product->getShadowProductByProject(9))  && p('name,shadow') && e('正常产品9,1'); //传入正常的项目编号。
r($product->getShadowProductByProject(11)) && p()              && e('0');           //传入不存在关联关系的项目编号。
