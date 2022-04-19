#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/product.class.php';

/**

title=productModel->getPairsByProjectModel();
cid=1
pid=1

测试获取model为all产品数量 >> 120
测试获取model为scrum产品数量 >> 70
测试获取model为waterfall产品数量 >> 40
测试获取model为kanban产品数量 >> 40

*/

$modelList = array('all', 'scrum', 'waterfall', 'kanban');

$product = new productTest('admin');

r($product->getPairsByProjectModelTest($modelList[0])) && p('name') && e('120');  // 测试获取model为all产品数量
r($product->getPairsByProjectModelTest($modelList[1])) && p('name') && e('70');   // 测试获取model为scrum产品数量
r($product->getPairsByProjectModelTest($modelList[2])) && p('name') && e('40');   // 测试获取model为waterfall产品数量
r($product->getPairsByProjectModelTest($modelList[3])) && p('name') && e('40');   // 测试获取model为kanban产品数量