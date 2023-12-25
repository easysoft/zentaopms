#!/usr/bin/env php
<?php

/**

title=productModel->getStatByID();
cid=0

- 测试获取产品1的stat信息
 - 属性name @正常产品1
 - 属性plans @0
 - 属性releases @25
 - 属性builds @5
 - 属性cases @4
 - 属性projects @1
 - 属性executions @0
 - 属性bugs @3
 - 属性docs @1
 - 属性progress @25
- 测试获取产品2的stat信息
 - 属性name @正常产品2
 - 属性plans @0
 - 属性releases @10
 - 属性builds @5
 - 属性cases @4
 - 属性projects @1
 - 属性executions @0
 - 属性bugs @3
 - 属性docs @1
 - 属性progress @25
- 测试获取产品3的stat信息
 - 属性name @正常产品3
 - 属性plans @0
 - 属性releases @0
 - 属性builds @5
 - 属性cases @4
 - 属性projects @1
 - 属性executions @0
 - 属性bugs @3
 - 属性docs @1
 - 属性progress @25
- 测试获取不存在产品的stat信息属性name @0
属性plans @0
属性releases @0
属性builds @0
属性cases @0
属性projects @0
属性executions @0
属性bugs @0
属性docs @0
属性progress @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('product')->gen(50);
zdTable('story')->gen(50);
zdTable('productplan')->gen(50);
zdTable('release')->gen(50);
zdTable('build')->gen(50);
zdTable('case')->gen(50);
zdTable('project')->gen(50);
zdTable('projectproduct')->gen(50);
zdTable('bug')->gen(50);
zdTable('doc')->gen(50);

$productIDList = array(1, 2, 3, 1000001);

$product = new productTest('admin');

r($product->getStatByIDTest($productIDList[0])) && p('name,plans,releases,builds,cases,projects,executions,bugs,docs,progress') && e('正常产品1,0,25,5,4,1,0,3,1,25'); // 测试获取产品1的stat信息
r($product->getStatByIDTest($productIDList[1])) && p('name,plans,releases,builds,cases,projects,executions,bugs,docs,progress') && e('正常产品2,0,10,5,4,1,0,3,1,25'); // 测试获取产品2的stat信息
r($product->getStatByIDTest($productIDList[2])) && p('name,plans,releases,builds,cases,projects,executions,bugs,docs,progress') && e('正常产品3,0,0,5,4,1,0,3,1,25');  // 测试获取产品3的stat信息
r($product->getStatByIDTest($productIDList[3])) && p('name,plans,releases,builds,cases,projects,executions,bugs,docs,progress') && e('0');                             // 测试获取不存在产品的stat信息
