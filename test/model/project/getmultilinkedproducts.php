#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 projectModel::getInfoList;
cid=1
pid=1

查询项目1的关联产品数量 >> 0
查询项目11的关联产品数量 >> 3
查询项目11关联的产品名称 >> 正常产品1,多平台产品91,多平台产品81

*/

global $tester;
$tester->loadModel('project');

$products1 = $tester->project->getMultiLinkedProducts(1);
$products2 = $tester->project->getMultiLinkedProducts(11);

r(count($products1)) && p()          && e('0'); //查询项目1的关联产品数量
r(count($products2)) && p()          && e('3'); //查询项目11的关联产品数量
r($products2)        && p('1,91,81') && e('正常产品1,多平台产品91,多平台产品81'); //查询项目11关联的产品名称