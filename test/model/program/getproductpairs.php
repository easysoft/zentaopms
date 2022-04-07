#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 programModel::getProductPairs();
cid=1
pid=1

根据项目或项目集ID获取关联产品详情 >> 已关闭的正常产品24
获取不存在的项目或项目集 >> Not Found
根据项目或项目集指派情况获取关联产品详情 >> 正常产品1
根据项目或项目集状态获取关联产品详情 >> 多平台产品90
查看ID=1，有指派，所有状态的关联产品数量 >> 9
查看ID=1，有指派，未关闭的关联产品数量 >> 5

*/

global $tester;
$tester->loadModel('program');

$products1 = $tester->program->getProductPairs(1, 'assign', 'all');
$products2 = $tester->program->getProductPairs(1, 'assign', 'noclosed');

r(count($products1)) && p()     && e('6'); //获取项目集1下的所有产品数量
r($products1)        && p('24') && e('已关闭的正常产品24'); //根据项目集ID获取关联产品名字

r(count($products2)) && p()     && e('4'); //获取项目集1下的未关闭的产品数量
r($products2)        && p('24') && e('已关闭的正常产品24'); //根据项目或项目集ID获取关联产品详情
