#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
zenData('user')->gen(5);
su('admin');

zenData('project')->loadYaml('execution')->gen(30);
$projectproduct = zenData('projectproduct')->loadYaml('projectproduct');
$projectproduct->product->range('1-10');
$projectproduct->gen(30);

/**

title=测试executionModel->getPairsByProduct();
timeout=0
cid=16390

- 测试空数据 @0
- 获取关联产品1-5的执行属性101 @迭代5
- 获取关联产品1-5的执行属性107 @阶段11
- 获取关联产品1-5的执行属性108 @阶段12
- 获取关联产品1-5的执行的数量 @11

*/

$productIdList = range(1, 5);

global $tester;
$tester->loadModel('execution');
$emptyResult  = $tester->execution->getPairsByProduct(array());
$normalResult = $tester->execution->getPairsByProduct($productIdList);
r($emptyResult)         && p()      && e('0');      // 测试空数据
r($normalResult)        && p('101') && e('迭代5');  // 获取关联产品1-5的执行
r($normalResult)        && p('107') && e('阶段11'); // 获取关联产品1-5的执行
r($normalResult)        && p('108') && e('阶段12'); // 获取关联产品1-5的执行
r(count($normalResult)) && p()      && e('11');     // 获取关联产品1-5的执行的数量