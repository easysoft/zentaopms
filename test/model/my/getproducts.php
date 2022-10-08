#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/my.class.php';
su('admin');

/**

title=测试 myModel->getProducts();
cid=1
pid=1

undone产品allCount查询 >> 80
undone产品unclosedCount查询 >> 80
undone产品查询统计 >> 5
ownbyme产品allCount查询 >> 0
ownbyme产品unclosedCount查询 >> 0
ownbyme产品查询 >> 0
ownbyme产品查询统计 >> 0
随意输入type产品数量查询 >> 5
不输入type产品数量查询 >> 5

*/

$typeList = array('undone', 'ownbyme', 'all');

$my = new myTest();

$undoneProducts  = $my->getProductsTest($typeList[0]);
$ownbymeProducts = $my->getProductsTest($typeList[1]);
$allProducts     = $my->getProductsTest($typeList[2]);
$noType          = $my->getProductsTest('');

r($undoneProducts->allCount)         && p()              && e('80');                  //undone产品allCount查询
r($undoneProducts->unclosedCount)    && p()              && e('80');                  //undone产品unclosedCount查询
r(count($undoneProducts->products))  && p()              && e('5');                   //undone产品查询统计
r($ownbymeProducts->allCount)        && p()              && e('0');                   //ownbyme产品allCount查询
r($ownbymeProducts->unclosedCount)   && p()              && e('0');                   //ownbyme产品unclosedCount查询
r($ownbymeProducts->products)        && p()              && e('0');                   //ownbyme产品查询
r(count($ownbymeProducts->products)) && p()              && e('0');                   //ownbyme产品查询统计
r(count($allProducts->products))     && p()              && e('5');                   //随意输入type产品数量查询
r(count($noType->products))          && p()              && e('5');                   //不输入type产品数量查询