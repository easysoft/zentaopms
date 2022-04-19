#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/product.class.php';

/**

title=productModel->getByIdList();
cid=1
pid=1

测试获取product 1 2 3的信息 >> 正常产品1,po1,test1,dev1;正常产品2,po2,test2,dev2;正常产品3,po3,test3,dev3
测试获取product 4 5 6的信息 >> 正常产品4,po4,test4,dev4;正常产品5,po5,test5,dev5;正常产品6,po6,test6,dev6
测试获取product 7 8 9的信息 >> 正常产品7,po7,test7,dev7;正常产品8,po8,test8,dev8;正常产品9,po9,test9,dev9

*/

$product = new productTest('admin');

$productIDList1 = array('1', '2', '3');
$productIDList2 = array('4', '5', '6');
$productIDList3 = array('7', '8', '9');

r($product->getByIdListTest($productIDList1)) && p('1:name,PO,QD,RD;2:name,PO,QD,RD;3:name,PO,QD,RD') && e('正常产品1,po1,test1,dev1;正常产品2,po2,test2,dev2;正常产品3,po3,test3,dev3');   // 测试获取product 1 2 3的信息
r($product->getByIdListTest($productIDList2)) && p('4:name,PO,QD,RD;5:name,PO,QD,RD;6:name,PO,QD,RD') && e('正常产品4,po4,test4,dev4;正常产品5,po5,test5,dev5;正常产品6,po6,test6,dev6');   // 测试获取product 4 5 6的信息
r($product->getByIdListTest($productIDList3)) && p('7:name,PO,QD,RD;8:name,PO,QD,RD;9:name,PO,QD,RD') && e('正常产品7,po7,test7,dev7;正常产品8,po8,test8,dev8;正常产品9,po9,test9,dev9');   // 测试获取product 7 8 9的信息