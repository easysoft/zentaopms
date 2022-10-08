#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/product.class.php';

/**

title=productModel->getById();
cid=1
pid=1

测试获取产品1的信息 >> 正常产品1,code1,normal,po1,test1,dev1
测试获取产品2的信息 >> 正常产品2,code2,normal,po2,test2,dev2
测试获取产品3的信息 >> 正常产品3,code3,normal,po3,test3,dev3
测试获取产品4的信息 >> 正常产品4,code4,normal,po4,test4,dev4
测试获取产品5的信息 >> 正常产品5,code5,normal,po5,test5,dev5
测试获取不存在产品的信息 >> 0

*/

$productIDList = array('1', '2', '3', '4', '5', '1000001');

$product = new productTest('admin');

r($product->getByIdTest($productIDList[0])) && p('name,code,type,PO,QD,RD') && e('正常产品1,code1,normal,po1,test1,dev1');   // 测试获取产品1的信息
r($product->getByIdTest($productIDList[1])) && p('name,code,type,PO,QD,RD') && e('正常产品2,code2,normal,po2,test2,dev2');   // 测试获取产品2的信息
r($product->getByIdTest($productIDList[2])) && p('name,code,type,PO,QD,RD') && e('正常产品3,code3,normal,po3,test3,dev3');   // 测试获取产品3的信息
r($product->getByIdTest($productIDList[3])) && p('name,code,type,PO,QD,RD') && e('正常产品4,code4,normal,po4,test4,dev4');   // 测试获取产品4的信息
r($product->getByIdTest($productIDList[4])) && p('name,code,type,PO,QD,RD') && e('正常产品5,code5,normal,po5,test5,dev5');   // 测试获取产品5的信息
r($product->getByIdTest($productIDList[5])) && p('name,code,type,PO,QD,RD') && e('0');                                       // 测试获取不存在产品的信息