#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 projectModel->getBranchesByProject();
cid=1
pid=1

获取id为11的项目的关联产品的分支数组数量，按产品分组 >> 3
获取id为11的项目的关联产品的分支数组，获取第一个数组的键 >> 1
获取id为12的项目的关联产品的分支数组数量，按产品分组 >> 3

*/

global $tester;
$tester->loadModel('project');

$projectIdList = array(11, 12, 13);

r(count($tester->project->getBranchesByProject(11))) && p('') && e('3'); // 获取id为11的项目的关联产品的分支数组数量，按产品分组
r(key($tester->project->getBranchesByProject(11)))   && p('') && e('1'); // 获取id为11的项目的关联产品的分支数组，获取第一个数组的键
r(count($tester->project->getBranchesByProject(12))) && p('') && e('3'); // 获取id为12的项目的关联产品的分支数组数量，按产品分组