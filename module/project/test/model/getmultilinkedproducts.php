#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
zenData('project')->gen(20);
zenData('product')->gen(20);

$relation = zenData('projectproduct');
$relation->project->range('11-14');
$relation->product->range('1-3');
$relation->branch->range('1-20');
$relation->gen(20);
su('admin');

/**

title=测试 projectModel::getInfoList;
timeout=0
cid=17834

*/

global $tester;
$tester->loadModel('project');

$products2 = $tester->project->getMultiLinkedProducts(11);

$products1 = $tester->project->getMultiLinkedProducts(1);
$products2 = $tester->project->getMultiLinkedProducts(11);

r(count($products1)) && p()          && e('0'); //查询项目1的关联产品数量
r(count($products2)) && p()          && e('3'); //查询项目11的关联产品数量
r($products2)        && p('1,2,3') && e('正常产品1,正常产品2,正常产品3'); //查询项目11关联的产品名称
