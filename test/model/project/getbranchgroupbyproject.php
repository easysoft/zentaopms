#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 projectModel->getBranchGroupByProject();
cid=1
pid=1

获取id为11的项目的关联产品的分支数组数量，按产品分组 >> 1
获取id为11的项目的关联产品的分支数组，获取第一个数组的键 >> 2
获取id为12的项目的关联产品的分支数组数量，按产品分组 >> 1

*/

global $tester;
$tester->loadModel('project');

$productIdList = array(1, 2, 3);

r(count($tester->project->getBranchGroupByProject(11, $productIdList))) && p('') && e('1'); // 获取id为11的项目的关联产品的分支数组数量，按产品分组
r(key($tester->project->getBranchGroupByProject(12, $productIdList)))   && p('') && e('2'); // 获取id为11的项目的关联产品的分支数组，获取第一个数组的键
r(count($tester->project->getBranchGroupByProject(13, $productIdList))) && p('') && e('1'); // 获取id为12的项目的关联产品的分支数组数量，按产品分组