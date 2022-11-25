#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/product.class.php';

/**

title=productModel->getStatByID();
cid=1
pid=1

测试获取产品1的stat信息 >> 正常产品1,0,5,2,7,2,14,9,1,0
测试获取产品2的stat信息 >> 正常产品2,0,2,2,7,2,14,4,1,0
测试获取产品3的stat信息 >> 正常产品3,0,0,2,7,2,14,4,1,0
测试获取产品4的stat信息 >> 正常产品4,0,0,2,7,2,14,4,1,0
测试获取产品5的stat信息 >> 正常产品5,0,0,2,7,2,14,4,1,0
测试获取不存在产品的stat信息 >> 0

*/

$productIDList = array('1', '2', '3', '4', '5', '1000001');

$product = new productTest('admin');

r($product->getStatByIDTest($productIDList[0])) && p('name,plans,releases,builds,cases,projects,executions,bugs,docs,progress') && e('正常产品1,0,5,2,7,2,14,9,1,0');   // 测试获取产品1的stat信息
r($product->getStatByIDTest($productIDList[1])) && p('name,plans,releases,builds,cases,projects,executions,bugs,docs,progress') && e('正常产品2,0,2,2,7,2,14,4,1,0');   // 测试获取产品2的stat信息
r($product->getStatByIDTest($productIDList[2])) && p('name,plans,releases,builds,cases,projects,executions,bugs,docs,progress') && e('正常产品3,0,0,2,7,2,14,4,1,0');   // 测试获取产品3的stat信息
r($product->getStatByIDTest($productIDList[3])) && p('name,plans,releases,builds,cases,projects,executions,bugs,docs,progress') && e('正常产品4,0,0,2,7,2,14,4,1,0');   // 测试获取产品4的stat信息
r($product->getStatByIDTest($productIDList[4])) && p('name,plans,releases,builds,cases,projects,executions,bugs,docs,progress') && e('正常产品5,0,0,2,7,2,14,4,1,0');   // 测试获取产品5的stat信息
r($product->getStatByIDTest($productIDList[5])) && p('name,plans,releases,builds,cases,projects,executions,bugs,docs,progress') && e('0');                              // 测试获取不存在产品的stat信息
