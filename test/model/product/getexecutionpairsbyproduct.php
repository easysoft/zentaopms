#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/product.class.php';

/**

title=productModel->getExecutionPairsByProduct();
cid=1
pid=1

测试获取产品1的信息 >> 项目1/迭代1;项目11/迭代11
测试获取产品1 项目11的信息 >> 迭代1;
测试获取产品1 项目21的信息 >> ;迭代11
测试获取产品2的信息 >> 项目2/迭代2;项目12/迭代12
测试获取产品2 项目12的信息 >> 迭代2;
测试获取产品2 项目22的信息 >> ;迭代12
测试获取产品3的信息 >> 项目3/迭代3;项目13/迭代13
测试获取产品3 项目13的信息 >> 迭代3;
测试获取产品3 项目23的信息 >> ;迭代13
测试获取产品4的信息 >> 项目4/迭代4;项目14/迭代14
测试获取产品4 项目14的信息 >> 迭代4;
测试获取产品4 项目24的信息 >> ;迭代14
测试获取产品5的信息 >> 项目5/迭代5;项目15/迭代15
测试获取产品5 项目15的信息 >> 迭代5;
测试获取产品5 项目25的信息 >> ;迭代15

*/

$productIDList = array('1', '2', '3', '4', '5', '1000001');
$projectIDList = array('11', '21', '12', '22', '13', '23', '14', '24', '15', '25');

$product = new productTest('admin');

r($product->getExecutionPairsByProductTest($productIDList[0]))                     && p('101;111') && e('项目1/迭代1;项目11/迭代11');   // 测试获取产品1的信息
r($product->getExecutionPairsByProductTest($productIDList[0], $projectIDList[0]))  && p('101;111') && e('迭代1;');                      // 测试获取产品1 项目11的信息
r($product->getExecutionPairsByProductTest($productIDList[0], $projectIDList[1]))  && p('101;111') && e(';迭代11');                     // 测试获取产品1 项目21的信息
r($product->getExecutionPairsByProductTest($productIDList[1]))                     && p('102;112') && e('项目2/迭代2;项目12/迭代12');   // 测试获取产品2的信息
r($product->getExecutionPairsByProductTest($productIDList[1], $projectIDList[2]))  && p('102;112') && e('迭代2;');                      // 测试获取产品2 项目12的信息
r($product->getExecutionPairsByProductTest($productIDList[1], $projectIDList[3]))  && p('102;112') && e(';迭代12');                     // 测试获取产品2 项目22的信息
r($product->getExecutionPairsByProductTest($productIDList[2]))                     && p('103;113') && e('项目3/迭代3;项目13/迭代13');   // 测试获取产品3的信息
r($product->getExecutionPairsByProductTest($productIDList[2], $projectIDList[4]))  && p('103;113') && e('迭代3;');                      // 测试获取产品3 项目13的信息
r($product->getExecutionPairsByProductTest($productIDList[2], $projectIDList[5]))  && p('103;113') && e(';迭代13');                     // 测试获取产品3 项目23的信息
r($product->getExecutionPairsByProductTest($productIDList[3]))                     && p('104;114') && e('项目4/迭代4;项目14/迭代14');   // 测试获取产品4的信息
r($product->getExecutionPairsByProductTest($productIDList[3], $projectIDList[6]))  && p('104;114') && e('迭代4;');                      // 测试获取产品4 项目14的信息
r($product->getExecutionPairsByProductTest($productIDList[3], $projectIDList[7]))  && p('104;114') && e(';迭代14');                     // 测试获取产品4 项目24的信息
r($product->getExecutionPairsByProducttest($productIDList[4]))                     && p('105;115') && e('项目5/迭代5;项目15/迭代15');   // 测试获取产品5的信息
r($product->getExecutionPairsByProducttest($productIDList[4], $projectIDList[8]))  && p('105;115') && e('迭代5;');                      // 测试获取产品5 项目15的信息
r($product->getExecutionPairsByProducttest($productIDList[4], $projectIDList[9]))  && p('105;115') && e(';迭代15');                     // 测试获取产品5 项目25的信息