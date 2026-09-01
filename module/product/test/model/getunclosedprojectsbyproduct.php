#!/usr/bin/env php
<?php

/**

title=测试productModel->getUnclosedProjectsByProduct();
timeout=0
cid=0

- 查看productID=1的项目数 @3
- 查看projectID=1的项目
 - 属性1 @项目A
 - 属性2 @项目B
 - 属性4 @项目D
- 查看productID=10的项目数 @0
- 查看productID=0的项目数 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('product')->gen(2);

$project = zenData('project');
$project->id->range('1-4');
$project->name->range('项目A,项目B,项目C,项目D');
$project->type->range('project');
$project->status->range('wait,doing,closed,suspended');
$project->deleted->range('0');
$project->gen(4);

$projectProduct = zenData('projectproduct');
$projectProduct->product->range('1{4}');
$projectProduct->project->range('1-4');
$projectProduct->branch->range('0');
$projectProduct->gen(4);

global $tester;
$product = $tester->loadModel('product');

$unclosedProjects = $product->getUnclosedProjectsByProduct(1);
r(count($unclosedProjects)) && p() && e('3');                        // 查看productID=1的项目数
r($unclosedProjects)        && p('1,2,4') && e('项目A,项目B,项目D'); // 查看projectID=1的项目

$unclosedProjects = $product->getUnclosedProjectsByProduct(10);
r(count($unclosedProjects)) && p() && e('0'); // 查看productID=10的项目数

$unclosedProjects = $product->getUnclosedProjectsByProduct(0);
r(count($unclosedProjects)) && p() && e('0'); // 查看productID=0的项目数
