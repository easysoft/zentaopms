#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/project.class.php';
su('admin');

/**

title=测试 projectModel->updateProductProgram();
cid=1
pid=1

查看被更新了项目集的产品数量 >> 4
查看被更新了项目集的产品详情 >> 1,正常产品1
查看被更新了项目集的产品详情 >> 1,正常产品2
查看被更新了项目集的产品详情 >> 1,正常产品3
查看被更新了项目集的产品详情 >> 1,正常产品4

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
