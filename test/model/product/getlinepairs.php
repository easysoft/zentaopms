#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/product.class.php';

/**

title=productModel->getLinePairs();
cid=1
pid=1

测试获取程序集1的信息 >> 产品线1,产品线11
测试获取程序集2的信息 >> 产品线2,产品线12
测试获取程序集3的信息 >> 产品线3,产品线13
测试获取程序集4的信息 >> 产品线4,产品线14
测试获取程序集5的信息 >> 产品线5,产品线15
测试获取不存在程序集的信息 >> 0

*/

$programIDList = array('1', '2', '3', '4', '5', '1000001');

$product = new productTest('admin');

r($product->getLinePairsTest($programIDList[0])) && p('1,11') && e('产品线1,产品线11');   // 测试获取程序集1的信息
r($product->getLinePairsTest($programIDList[1])) && p('2,12') && e('产品线2,产品线12');   // 测试获取程序集2的信息
r($product->getLinePairsTest($programIDList[2])) && p('3,13') && e('产品线3,产品线13');   // 测试获取程序集3的信息
r($product->getLinePairsTest($programIDList[3])) && p('4,14') && e('产品线4,产品线14');   // 测试获取程序集4的信息
r($product->getLinePairsTest($programIDList[4])) && p('5,15') && e('产品线5,产品线15');   // 测试获取程序集5的信息
r($product->getLinePairsTest($programIDList[5])) && p('1,11') && e('0');                      // 测试获取不存在程序集的信息