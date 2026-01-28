#!/usr/bin/env php
<?php

/**

title=测试 treeModel->getTaskTreeMenu();
timeout=0
cid=19384

- 测试获取项目1的task模块 @product-1|6|1|11
- 测试获取项目10的task模块 @product-10
- 测试获取项目20的task模块 @product-20
- 测试获取项目41的task模块 @18
- 测试获取项目100的task模块 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

zenData('product')->gen(20);
zenData('module')->loadYaml('module')->gen(20);

$projectproduct = zenData('projectproduct');
$projectproduct->project->range('1-100');
$projectproduct->product->range('1-100');
$projectproduct->gen(100);

$tree = new treeModelTest();

r($tree->getTaskTreeMenuTest(1))   && p() && e('product-1|6|1|11'); // 测试获取项目1的task模块
r($tree->getTaskTreeMenuTest(10))  && p() && e('product-10');       // 测试获取项目10的task模块
r($tree->getTaskTreeMenuTest(20))  && p() && e('product-20');       // 测试获取项目20的task模块
r($tree->getTaskTreeMenuTest(41))  && p() && e('18');               // 测试获取项目41的task模块
r($tree->getTaskTreeMenuTest(100)) && p() && e('0');                // 测试获取项目100的task模块
