#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

/**
title=测试获取产品的执行信息 productModel->getAllExecutionPairsByProduct();
cid=1
pid=1
*/

zdTable('projectproduct')->gen(50);

$project = zdTable('project');
$project->id->range('11-100'); // 第一个项目ID为11。
$project->project->range('0{5},11{5},13{5},15{5}'); // 第一个产品ID为6，第一个执行的ID为16。
$project->model->range('scrum{2},waterfall{2},kanban{1},``{100}'); // 设置项目的类型，Scrum、瀑布和看板，其他的为非项目不设置项目类型。
$project->type->range('project{5},sprint{5},stage{5},kanban{5}'); // 前5个是项目，后面的是执行。
$project->grade->range('0{5},1{5},2{5}');
$project->gen(20);

$product = new productTest('admin');

r($product->getAllExecutionPairsByProductTest(6, 11)) && p('16') && e('项目1/项目6'); // 获取产品ID为6、项目ID为11的执行信息
r($product->getAllExecutionPairsByProductTest(7, 11)) && p('17') && e('项目1/项目7'); // 获取产品ID为7、项目ID为11的执行信息
