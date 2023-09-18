#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('user')->gen(5);
zdTable('product')->gen(10);

/**

title=productModel->getById();
timeout=0
cid=1

*/

$productIdList = array(1, 2, 3, 4, 5, 1000001);

$product = new productTest('admin');

r($product->getByIdTest($productIdList[0])) && p('name,code,type,PO,QD,RD') && e('正常产品1,code1,normal,po1,test1,dev1');   // 测试获取产品1的信息
r($product->getByIdTest($productIdList[1])) && p('name,code,type,PO,QD,RD') && e('正常产品2,code2,normal,po2,test2,dev2');   // 测试获取产品2的信息
r($product->getByIdTest($productIdList[2])) && p('name,code,type,PO,QD,RD') && e('正常产品3,code3,normal,po3,test3,dev3');   // 测试获取产品3的信息
r($product->getByIdTest($productIdList[3])) && p('name,code,type,PO,QD,RD') && e('正常产品4,code4,normal,po4,test4,dev4');   // 测试获取产品4的信息
r($product->getByIdTest($productIdList[4])) && p('name,code,type,PO,QD,RD') && e('正常产品5,code5,normal,po5,test5,dev5');   // 测试获取产品5的信息
r($product->getByIdTest($productIdList[5])) && p() && e('0');                                                                // 测试获取不存在产品的信息
