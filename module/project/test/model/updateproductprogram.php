#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';

$project = zenData('project');
$project->id->range('1-5');
$project->name->range('项目1,项目2,迭代1,迭代2,迭代3');
$project->type->range('project{2},sprint,stage,kanban');
$project->status->range('doing');
$project->parent->range('0,0,1,1,2');
$project->project->range('0,0,1,1,2');
$project->grade->range('2{2},1{3}');
$project->multiple->range('1,0,1{3}');
$project->path->range('1,2,`1,3`,`1,4`,`2,5`')->prefix(',')->postfix(',');
$project->begin->range('20230102 000000:0')->type('timestamp')->format('YYYY-MM-DD');
$project->end->range('20230212 000000:0')->type('timestamp')->format('YYYY-MM-DD');
$project->gen(5);

$product = zenData('product');
$product->type->range('normal,branch,platform');
$product->gen(20);

su('admin');

/**

title=测试 projectModel->updateProductProgram();
timeout=0
cid=17877

- 查看被更新了项目集的产品数量 @4
- 查看被更新了项目集的产品详情
 - 第1条的program属性 @1
 - 第1条的name属性 @正常产品1
- 查看被更新了项目集的产品详情
 - 第2条的program属性 @1
 - 第2条的name属性 @正常产品2
- 查看被更新了项目集的产品详情
 - 第3条的program属性 @1
 - 第3条的name属性 @正常产品3
- 查看被更新了项目集的产品详情
 - 第4条的program属性 @1
 - 第4条的name属性 @正常产品4

*/

global $tester;
$tester->loadModel('project');

$productIdList = array(1, 2, 3, 4);
$tester->project->updateProductProgram(7, 1, $productIdList);

$products = $tester->loadModel('product')->getByIdList($productIdList);

r(count($products)) && p('')               && e('4');           // 查看被更新了项目集的产品数量
r($products)        && p('1:program,name') && e('1,正常产品1'); // 查看被更新了项目集的产品详情
r($products)        && p('2:program,name') && e('1,正常产品2'); // 查看被更新了项目集的产品详情
r($products)        && p('3:program,name') && e('1,正常产品3'); // 查看被更新了项目集的产品详情
r($products)        && p('4:program,name') && e('1,正常产品4'); // 查看被更新了项目集的产品详情