#!/usr/bin/env php
<?php

/**

title=测试productModel->formatDataForList();
timeout=0
cid=17484

- id1的产品信息
 - 属性id @1
 - 属性name @正常产品1
 - 属性code @code1
 - 属性type @product
 - 属性status @normal
 - 属性PO @po1
 - 属性QD @test1
 - 属性RD @dev1
 - 属性order @5
 - 属性storyCompleteRate @50
 - 属性bugFixedRate @33.3
- id2的产品信息
 - 属性id @2
 - 属性name @正常产品2
 - 属性code @code2
 - 属性type @product
 - 属性status @normal
 - 属性PO @po2
 - 属性QD @test2
 - 属性RD @dev2
 - 属性order @10
 - 属性storyCompleteRate @50
 - 属性bugFixedRate @33.3
- id3的产品信息
 - 属性id @3
 - 属性name @正常产品3
 - 属性code @code3
 - 属性type @product
 - 属性status @normal
 - 属性PO @po3
 - 属性QD @test3
 - 属性RD @dev3
 - 属性order @15
 - 属性storyCompleteRate @50
 - 属性bugFixedRate @33.3
- id5的产品信息
 - 属性id @5
 - 属性name @正常产品5
 - 属性code @code5
 - 属性type @product
 - 属性status @normal
 - 属性PO @po5
 - 属性QD @test5
 - 属性RD @dev5
 - 属性order @25
 - 属性storyCompleteRate @50
 - 属性bugFixedRate @33.3
- id8的产品信息
 - 属性id @8
 - 属性name @正常产品8
 - 属性code @code8
 - 属性type @product
 - 属性status @normal
 - 属性PO @po8
 - 属性QD @test8
 - 属性RD @dev8
 - 属性order @40
 - 属性storyCompleteRate @50
 - 属性bugFixedRate @33.3
- 不存在的产品 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('story')->gen(0);
zenData('task')->gen(0);
zenData('bug')->gen(0);
zenData('product')->gen(10);

$product = new productModelTest();

r($product->formatDataForListTest(1))  && p('id,name,code,type,status,PO,QD,RD,order,storyCompleteRate,bugFixedRate') && e('1,正常产品1,code1,product,normal,po1,test1,dev1,5,50,33.3');  // id1的产品信息
r($product->formatDataForListTest(2))  && p('id,name,code,type,status,PO,QD,RD,order,storyCompleteRate,bugFixedRate') && e('2,正常产品2,code2,product,normal,po2,test2,dev2,10,50,33.3'); // id2的产品信息
r($product->formatDataForListTest(3))  && p('id,name,code,type,status,PO,QD,RD,order,storyCompleteRate,bugFixedRate') && e('3,正常产品3,code3,product,normal,po3,test3,dev3,15,50,33.3'); // id3的产品信息
r($product->formatDataForListTest(5))  && p('id,name,code,type,status,PO,QD,RD,order,storyCompleteRate,bugFixedRate') && e('5,正常产品5,code5,product,normal,po5,test5,dev5,25,50,33.3'); // id5的产品信息
r($product->formatDataForListTest(8))  && p('id,name,code,type,status,PO,QD,RD,order,storyCompleteRate,bugFixedRate') && e('8,正常产品8,code8,product,normal,po8,test8,dev8,40,50,33.3'); // id8的产品信息
r($product->formatDataForListTest(11)) && p() && e('0'); // 不存在的产品
