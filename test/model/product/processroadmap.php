#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/product.class.php';

/**

title=productModel->processRoadmap();
cid=1
pid=1

测试获取产品1下的发布roadmap >> 产品1正常的发布1;产品1正常的发布3;产品1正常的里程碑发布5;项目1停止维护的里程碑发布6
测试获取产品2下的发布roadmap >> 项目2发布!@#$$%^&*()测试发布的名称到底可以有多长asdlfkjla8
测试获取产品3下的发布roadmap >> 0
测试获取产品4下的发布roadmap >> 0
测试获取产品5下的发布roadmap >> 0
测试获取不存在产品下的发布roadmap >> 0

*/

$productIDList = array('1', '2', '3', '4', '5', '1000001');

$product = new productTest('admin');
r($product->processRoadmapTest($productIDList[0])) && p('0:name;1:name;2:name;3:name') && e('产品1正常的发布1;产品1正常的发布3;产品1正常的里程碑发布5;项目1停止维护的里程碑发布6');   // 测试获取产品1下的发布roadmap
r($product->processRoadmapTest($productIDList[1])) && p('0:name')                      && e('项目2发布!@#$$%^&*()测试发布的名称到底可以有多长asdlfkjla8');                            // 测试获取产品2下的发布roadmap
r($product->processRoadmapTest($productIDList[2])) && p()                              && e('0');                                                                                     // 测试获取产品3下的发布roadmap
r($product->processRoadmapTest($productIDList[3])) && p()                              && e('0');                                                                                     // 测试获取产品4下的发布roadmap
r($product->processRoadmapTest($productIDList[4])) && p()                              && e('0');                                                                                     // 测试获取产品5下的发布roadmap
r($product->processRoadmapTest($productIDList[5])) && p()                              && e('0');                                                                                     // 测试获取不存在产品下的发布roadmap
