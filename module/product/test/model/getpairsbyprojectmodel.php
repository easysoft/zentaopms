#!/usr/bin/env php
<?php

/**

title=productModel->getPairsByProjectModel();
cid=0

- 用超级管理员账号，测试获取model为空产品数量属性name @0
- 用超级管理员账号，测试获取model为all产品数量属性name @50
- 用超级管理员账号，测试获取model为scrum产品数量属性name @10
- 用超级管理员账号，测试获取model为waterfall产品数量属性name @10
- 用超级管理员账号，测试获取model为kanban产品数量属性name @10
- 用普通账号，测试获取model为all产品数量属性name @30
- 用普通账号，测试获取model为scrum产品数量属性name @6
- 用普通账号，测试获取model为waterfall产品数量属性name @6
- 用普通账号，测试获取model为kanban产品数量属性name @6

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('product')->gen(50);
zdTable('project')->gen(90);
$projectProduct = zdTable('projectproduct');
$projectProduct->project->range('11-100');
$projectProduct->product->range('1-50');
$projectProduct->gen(90);

$modelList = array('all', 'scrum', 'waterfall', 'kanban');

$product = new productTest('admin');
$product->objectModel->app->user->admin = true;

r($product->getPairsByProjectModelTest(''))            && p('name') && e('0');  //用超级管理员账号，测试获取model为空产品数量
r($product->getPairsByProjectModelTest($modelList[0])) && p('name') && e('50'); //用超级管理员账号，测试获取model为all产品数量
r($product->getPairsByProjectModelTest($modelList[1])) && p('name') && e('10'); //用超级管理员账号，测试获取model为scrum产品数量
r($product->getPairsByProjectModelTest($modelList[2])) && p('name') && e('10'); //用超级管理员账号，测试获取model为waterfall产品数量
r($product->getPairsByProjectModelTest($modelList[3])) && p('name') && e('10'); //用超级管理员账号，测试获取model为kanban产品数量

$product->objectModel->app->user->admin = false;
$product->objectModel->app->user->view->products = '1,2,3,4,5,6,7,8,9,10,21,22,23,24,25,26,27,28,29,30,41,42,43,44,45,46,47,48,49,50';

r($product->getPairsByProjectModelTest($modelList[0])) && p('name') && e('30'); //用普通账号，测试获取model为all产品数量
r($product->getPairsByProjectModelTest($modelList[1])) && p('name') && e('6'); //用普通账号，测试获取model为scrum产品数量
r($product->getPairsByProjectModelTest($modelList[2])) && p('name') && e('6'); //用普通账号，测试获取model为waterfall产品数量
r($product->getPairsByProjectModelTest($modelList[3])) && p('name') && e('6'); //用普通账号，测试获取model为kanban产品数量
