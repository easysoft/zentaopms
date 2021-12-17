#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试获取产品列表;
cid=1
pid=1

调用成功，返回200 >> 200
测试所有产品的数量 >> 191
查看所有产品中第一个产品的ID和code >> 1,code1
调用成功，返回200 >> 200
测试已关闭的产品的数量 >> 90
查看已关闭的第一个产品的ID和code >> 21,code21
调用成功，返回200 >> 200
测试正常状态的产品的数量 >> 101
查看正常状态的第一个产品的ID和code >> 1,code1
调用成功,返回200 >> 200
测试使用错误的状态参数查询时结果为空 >> 0

*/
global $token;
$header = array('Token' => $token->token);

$allProducts = $rest->get('/products', $header);
$productList = $allProducts->body->products;
r($allProducts->status_code) && p()            && e('200');     //调用成功，返回200
r(count($productList))       && p()            && e('191');     //测试所有产品的数量
r($productList)              && p('0:id,code') && e('1,code1'); //查看所有产品中第一个产品的ID和code

$closedProducts = $rest->get('/products?status=closed', $header);
$productList    = $closedProducts->body->products;
r($closedProducts->status_code) && p()            && e('200');       //调用成功，返回200
r(count($productList))          && p()            && e('90');        //测试已关闭的产品的数量
r($productList)                 && p('0:id,code') && e('21,code21'); //查看已关闭的第一个产品的ID和code

$normalProducts = $rest->get('/products?status=normal', $header);
$productList    = $normalProducts->body->products;
r($normalProducts->status_code) && p()            && e('200');     //调用成功，返回200
r(count($productList))          && p()            && e('101');     //测试正常状态的产品的数量
r($productList)                 && p('0:id,code') && e('1,code1'); //查看正常状态的第一个产品的ID和code

$statusError = $rest->get('/products?status=doing', $header);
r($statusError->status_code)           && p() && e('200'); //调用成功,返回200
r(count($statusError->body->products)) && p() && e('0');   //测试使用错误的状态参数查询时结果为空