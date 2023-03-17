#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 projectModel->updateProducts();
cid=1
pid=1

查看更新项目关联的产品之前的产品数量 >> 3
查看更新项目关联的产品之后的产品数量 >> 4
查看更新项目关联的产品之前的产品名称 >> 多平台产品81
查看更新项目关联的产品之后的产品名称 >> 正常产品4

*/

global $tester;
$tester->loadModel('project');
$tester->loadModel('product');

$beforeProducts = $tester->product->getProductPairsByProject(11);

$productIdList = array(1, 2, 3, 4);
$tester->project->updateProducts(11, $productIdList);

$afterProducts = $tester->product->getProductPairsByProject(11);

r(count($beforeProducts)) && p()     && e('3');            // 查看更新项目关联的产品之前的产品数量
r(count($afterProducts))  && p()     && e('4');            // 查看更新项目关联的产品之后的产品数量
r($beforeProducts)        && p('81') && e('多平台产品81'); // 查看更新项目关联的产品之前的产品名称
r($afterProducts)         && p('4')  && e('正常产品4');    // 查看更新项目关联的产品之后的产品名称
