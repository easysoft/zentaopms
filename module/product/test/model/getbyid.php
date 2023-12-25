#!/usr/bin/env php
<?php

/**

title=productModel->getById();
cid=0

- 测试获取产品1的信息
 - 属性name @正常产品1
 - 属性code @code1
 - 属性type @normal
 - 属性PO @po1
 - 属性QD @test1
 - 属性RD @dev1
- 测试获取产品2的信息
 - 属性name @正常产品2
 - 属性code @code2
 - 属性type @normal
 - 属性PO @po2
 - 属性QD @test2
 - 属性RD @dev2
- 测试获取产品3的信息
 - 属性name @正常产品3
 - 属性code @code3
 - 属性type @normal
 - 属性PO @po3
 - 属性QD @test3
 - 属性RD @dev3
- 测试获取产品4的信息
 - 属性name @正常产品4
 - 属性code @code4
 - 属性type @normal
 - 属性PO @po4
 - 属性QD @test4
 - 属性RD @dev4
- 测试获取产品5的信息
 - 属性name @正常产品5
 - 属性code @code5
 - 属性type @normal
 - 属性PO @po5
 - 属性QD @test5
 - 属性RD @dev5
- 测试获取不存在产品的信息 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('user')->gen(5);
zdTable('product')->gen(10);

$productIdList = array(1, 2, 3, 4, 5, 1000001);

$product = new productTest('admin');

r($product->getByIdTest($productIdList[0])) && p('name,code,type,PO,QD,RD') && e('正常产品1,code1,normal,po1,test1,dev1');   // 测试获取产品1的信息
r($product->getByIdTest($productIdList[1])) && p('name,code,type,PO,QD,RD') && e('正常产品2,code2,normal,po2,test2,dev2');   // 测试获取产品2的信息
r($product->getByIdTest($productIdList[2])) && p('name,code,type,PO,QD,RD') && e('正常产品3,code3,normal,po3,test3,dev3');   // 测试获取产品3的信息
r($product->getByIdTest($productIdList[3])) && p('name,code,type,PO,QD,RD') && e('正常产品4,code4,normal,po4,test4,dev4');   // 测试获取产品4的信息
r($product->getByIdTest($productIdList[4])) && p('name,code,type,PO,QD,RD') && e('正常产品5,code5,normal,po5,test5,dev5');   // 测试获取产品5的信息
r($product->getByIdTest($productIdList[5])) && p() && e('0');                                                                // 测试获取不存在产品的信息
