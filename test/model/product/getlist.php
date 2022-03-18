#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/product.class.php';

/**

title=productModel->getList();
cid=1
pid=1

返回项目集1下的产品数量 >> 9
测试传入programID=0的情况 >> 100
传入不存在的项目集 >> 0
返回项目集1下的未关闭的产品数量 >> 5
获取所有的未关闭的产品数量 >> 60
返回项目集1下的关闭了的产品数量 >> 4
返回所有的未关闭的产品数量 >> 40
返回项目集1下的与当前用户有关系的产品数量 >> 0
返回项目集1下产品线1的产品数量 >> 9
返回项目集1下产品线2的产品数量 >> 0

*/

$adminTester = new Product('admin');

$t_numproject = array('0','1', '2', '11');

r($adminTester->getAllProductsCount($t_numproject[1]))      && p() && e('9');   // 返回项目集1下的产品数量
r($adminTester->getAllProductsCount($t_numproject[0]))      && p() && e('100'); // 测试传入programID=0的情况
r($adminTester->getAllProductsCount($t_numproject[3]))      && p() && e('0');   // 传入不存在的项目集
r($adminTester->getNoclosedProductsCount($t_numproject[1])) && p() && e('5');   // 返回项目集1下的未关闭的产品数量
r($adminTester->getNoclosedProductsCount($t_numproject[0])) && p() && e('60');  // 获取所有的未关闭的产品数量
r($adminTester->getClosedProductsCount($t_numproject[1]))   && p() && e('4');   // 返回项目集1下的关闭了的产品数量
r($adminTester->getClosedProductsCount($t_numproject[0]))   && p() && e('40');  // 返回所有的未关闭的产品数量
r($adminTester->getInvolvedProductsCount($t_numproject[1])) && p() && e('0');   // 返回项目集1下的与当前用户有关系的产品数量
r($adminTester->countProductsByLine($t_numproject[1], $t_numproject[1]))   && p() && e('9');   // 返回项目集1下产品线1的产品数量
r($adminTester->countProductsByLine($t_numproject[1], $t_numproject[2]))    && p() && e('0');   // 返回项目集1下产品线2的产品数量