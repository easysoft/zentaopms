#!/usr/bin/env php
<?php

/**

title=productTao->getModulesForSearchForm();
cid=0

- 产品视图下，不传入数据。 @/
- 产品视图下，传入正常产品编号。 @5
- 产品视图下，传入分支产品编号，并查询所有分支。 @5
- 产品视图下，传入分支产品编号，并查询主干分支。 @2
- 产品视图下，传入分支产品编号，并查询1分支。 @3
- 项目视图下，不传入数据。 @/
- 项目视图下，传入正常产品编号。 @0
- 项目视图下，传入正常产品编号，传入项目编号。 @5
- 项目视图下，传入分支产品编号，传入项目编号，并查询所有分支。 @/主干/这是一个模块13
- 项目视图下，传入分支产品编号，传入项目编号，并查询主干分支。 @/主干/这是一个模块13
- 项目视图下，传入分支产品编号，传入项目编号，并查询1分支。 @/主干/这是一个模块13
- 项目视图下，不传入产品编号，传入项目编号，并查询所有分支。 @产品7/分支11/这是一个模块25
- 项目视图下，不传入产品编号，传入项目编号，并查询主干分支。 @产品7/分支11/这是一个模块25
- 项目视图下，不传入产品编号，传入项目编号，并查询10分支。 @产品7/分支11/这是一个模块25

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

$product = zdTable('product')->config('product');
$product->type->range('normal{3},branch{3},platform{3}');
$product->gen(9);

$branch = zdTable('branch');
$branch->product->range('4-9{3}');
$branch->gen(18);

$module = zdTable('module');
$module->root->range('1-9{4}');
$module->type->range('story');
$module->branch->range('0{12},0-3,0,4-6,0,7-9,0,10-12,0,13-15,0,16-18');
$module->gen(36);

$projectProduct = zdTable('projectproduct');
$projectProduct->project->range('1-3,4-30{2}');
$projectProduct->product->range('1-3,4-9{2}');
$projectProduct->branch->range('0{3},0,1,0,4,0,7,10,11,13,15,16,18');
$projectProduct->gen(15);

global $tester;
$tester->config->global->syncProduct = '';
$product = $tester->loadModel('product');
$product->app->tab = 'product';

$products = $product->getPairs();

r($product->getModulesForSearchForm(0, array())[0])              && p() && e('/'); //产品视图下，不传入数据。
r(count($product->getModulesForSearchForm(1, $products)))        && p() && e('5'); //产品视图下，传入正常产品编号。
r(count($product->getModulesForSearchForm(4, $products, 'all'))) && p() && e('5'); //产品视图下，传入分支产品编号，并查询所有分支。
r(count($product->getModulesForSearchForm(4, $products, '0')))   && p() && e('2'); //产品视图下，传入分支产品编号，并查询主干分支。
r(count($product->getModulesForSearchForm(4, $products, '1')))   && p() && e('3'); //产品视图下，传入分支产品编号，并查询1分支。

$product->app->tab = 'project';

r($product->getModulesForSearchForm(0, array())[0]) && p() && e('/'); // 项目视图下，不传入数据。
r($product->getModulesForSearchForm(1, $products))  && p() && e('0'); // 项目视图下，传入正常产品编号。

r(count($product->getModulesForSearchForm(1, $products, '0', 1))) && p() && e('5'); // 项目视图下，传入正常产品编号，传入项目编号。

r($product->getModulesForSearchForm(4, $products, 'all', 4)[13]) && p() && e('/主干/这是一个模块13'); // 项目视图下，传入分支产品编号，传入项目编号，并查询所有分支。
r($product->getModulesForSearchForm(4, $products, '0',   4)[13]) && p() && e('/主干/这是一个模块13'); // 项目视图下，传入分支产品编号，传入项目编号，并查询主干分支。
r($product->getModulesForSearchForm(4, $products, '1',   4)[13]) && p() && e('/主干/这是一个模块13'); // 项目视图下，传入分支产品编号，传入项目编号，并查询1分支。

r($product->getModulesForSearchForm(0, array(7 => $products[7]), 'all', 7)[25]) && p() && e('产品7/分支11/这是一个模块25'); // 项目视图下，不传入产品编号，传入项目编号，并查询所有分支。
r($product->getModulesForSearchForm(0, array(7 => $products[7]), '0',   7)[25]) && p() && e('产品7/分支11/这是一个模块25'); // 项目视图下，不传入产品编号，传入项目编号，并查询主干分支。
r($product->getModulesForSearchForm(0, array(7 => $products[7]), '10',  7)[25]) && p() && e('产品7/分支11/这是一个模块25'); // 项目视图下，不传入产品编号，传入项目编号，并查询10分支。
