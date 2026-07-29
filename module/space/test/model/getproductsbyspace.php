#!/usr/bin/env php
<?php

/**

title=测试 spaceModel::getProductsBySpace();
timeout=0
cid=0

- 查询空间ID=0的产品为空 @0
- 查询有效空间ID=1的产品并验证结果类型 @1
- 查询有效空间ID=2的产品并验证结果类型 @1
- 查询无效空间ID=9999的产品为空 @0
- 查询空间ID=1并用hasPairs参数查询并验证结果类型 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(5);

$product = zenData('product');
$product->id->range('1-3');
$product->name->range('product-one,product-two,product-three');
$product->deleted->range('0{3}');
$product->gen(3);

$repo = zenData('ops_repo');
$repo->id->range('1-3');
$repo->spaceID->range('1,1,2');
$repo->product->range('1,2,3');
$repo->name->range('repo-one,repo-two,repo-three');
$repo->gitUID->range('product-repo-gituid-1,product-repo-gituid-2,product-repo-gituid-3');
$repo->status->range('active{3}');
$repo->deleted->range('0{3}');
$repo->gen(3);

su('admin');

$spaceTester = new spaceModelTest();

r($spaceTester->getProductsBySpaceCountTest(0))          && p() && e('0');            // 查询空间ID=0的产品为空
r($spaceTester->getProductsBySpaceCountTest(1))          && p() && e('2');            // 查询空间1下的产品数量
r($spaceTester->getProductsBySpaceCountTest(2))          && p() && e('1');            // 查询空间2下的产品数量
r($spaceTester->getProductsBySpaceCountTest(9999))       && p() && e('0');            // 查询无效空间的产品为空
r($spaceTester->getProductsBySpaceItemTest(1, true, 1)) && p() && e('product-one');  // 查询空间1下产品键值对
