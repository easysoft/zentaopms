#!/usr/bin/env php
<?php
/**

title=测试 designModel->getPairs();
cid=1

- 获取关联所有产品的所有类型的设计属性1 @1:设计1
- 获取关联所有产品的概要设计属性1 @1:设计1
- 获取关联所有产品的详细设计属性2 @2:设计2
- 获取关联所有产品的数据库设计属性11 @11:设计11
- 获取关联所有产品的接口设计属性12 @12:设计12
- 获取关联所有产品的不存在类型的设计 @0
- 获取关联产品1的所有类型的设计属性3 @3:设计3
- 获取关联产品1的概要设计属性5 @5:设计5
- 获取关联产品1的详细设计属性6 @6:设计6
- 获取关联产品1的数据库设计属性3 @3:设计3
- 获取关联产品1的接口设计属性4 @4:设计4
- 获取关联产品1的不存在类型的设计 @0
- 获取关联产品不存在的所有类型的设计 @0
- 获取关联产品不存在的概要设计 @0
- 获取关联产品不存在的详细设计 @0
- 获取关联产品不存在的数据库设计 @0
- 获取关联产品不存在的接口设计 @0
- 获取关联产品不存在的不存在类型的设计 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/design.class.php';

zdTable('design')->config('design')->gen(20);
zdTable('user')->gen(5);

$products = array(0, 1, 11);
$types    = array('all', 'HLDS', 'DDS', 'DBDS', 'ADS', 'test');

$designTester = new designTest();
r($designTester->getPairsTest($products[0], $types[0])) && p('1')  && e('1:设计1');   // 获取关联所有产品的所有类型的设计
r($designTester->getPairsTest($products[0], $types[1])) && p('1')  && e('1:设计1');   // 获取关联所有产品的概要设计
r($designTester->getPairsTest($products[0], $types[2])) && p('2')  && e('2:设计2');   // 获取关联所有产品的详细设计
r($designTester->getPairsTest($products[0], $types[3])) && p('11') && e('11:设计11'); // 获取关联所有产品的数据库设计
r($designTester->getPairsTest($products[0], $types[4])) && p('12') && e('12:设计12'); // 获取关联所有产品的接口设计
r($designTester->getPairsTest($products[0], $types[5])) && p()     && e('0');         // 获取关联所有产品的不存在类型的设计
r($designTester->getPairsTest($products[1], $types[0])) && p('3')  && e('3:设计3');   // 获取关联产品1的所有类型的设计
r($designTester->getPairsTest($products[1], $types[1])) && p('5')  && e('5:设计5');   // 获取关联产品1的概要设计
r($designTester->getPairsTest($products[1], $types[2])) && p('6')  && e('6:设计6');   // 获取关联产品1的详细设计
r($designTester->getPairsTest($products[1], $types[3])) && p('3')  && e('3:设计3');   // 获取关联产品1的数据库设计
r($designTester->getPairsTest($products[1], $types[4])) && p('4')  && e('4:设计4');   // 获取关联产品1的接口设计
r($designTester->getPairsTest($products[1], $types[5])) && p()     && e('0');         // 获取关联产品1的不存在类型的设计
r($designTester->getPairsTest($products[2], $types[0])) && p()     && e('0');         // 获取关联产品不存在的所有类型的设计
r($designTester->getPairsTest($products[2], $types[1])) && p()     && e('0');         // 获取关联产品不存在的概要设计
r($designTester->getPairsTest($products[2], $types[2])) && p()     && e('0');         // 获取关联产品不存在的详细设计
r($designTester->getPairsTest($products[2], $types[3])) && p()     && e('0');         // 获取关联产品不存在的数据库设计
r($designTester->getPairsTest($products[2], $types[4])) && p()     && e('0');         // 获取关联产品不存在的接口设计
r($designTester->getPairsTest($products[2], $types[5])) && p()     && e('0');         // 获取关联产品不存在的不存在类型的设计
