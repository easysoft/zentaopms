#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('product')->gen(10);
zenData('project')->loadYaml('execution')->gen(102);

/**

title=测试 commonModel::canModify();
timeout=0
cid=15650

- 查看产品1是否可以修改 @1
- 查看产品2是否可以修改 @1
- 查看执行1是否可以修改 @1
- 查看执行2是否可以修改 @1
- 查看项目11是否可以修改 @1

*/

global $tester;
$tester->loadModel('product');
$tester->loadModel('execution');
$tester->loadModel('project');

$product1 = $tester->product->fetchById(1);
$product2 = $tester->product->fetchById(2);

$execution1 = $tester->execution->fetchById(101);
$execution2 = $tester->execution->fetchById(102);

$project1 = $tester->project->fetchById(11);

r(commonModel::canModify('product',   $product1))   && p() && e('1'); // 查看产品1是否可以修改
r(commonModel::canModify('product',   $product2))   && p() && e('1'); // 查看产品2是否可以修改
r(commonModel::canModify('execution', $execution1)) && p() && e('1'); // 查看执行1是否可以修改
r(commonModel::canModify('execution', $execution2)) && p() && e('1'); // 查看执行2是否可以修改
r(commonModel::canModify('project',   $project1))   && p() && e('1'); // 查看项目11是否可以修改
