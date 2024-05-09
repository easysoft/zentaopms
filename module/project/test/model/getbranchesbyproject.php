#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

$project = zenData('project')->loadYaml('project')->gen(3);
$product = zenData('product')->loadYaml('product')->gen(3);
$projectproduct = zenData('projectproduct')->loadYaml('projectproduct')->gen(5);

/**

title=测试 projectModel->getBranchesByProject();
timeout=0
cid=1

*/

global $tester;
$tester->loadModel('project');

$projectIdList = array(1, 2, 3);

r(count($tester->project->getBranchesByProject(1))) && p('') && e('3'); // 获取id为1的项目的关联产品的分支数组数量，按产品分组
r(key($tester->project->getBranchesByProject(1)))   && p('') && e('1'); // 获取id为1的项目的关联产品的分支数组，获取第一个数组的键
r(count($tester->project->getBranchesByProject(2))) && p('') && e('2'); // 获取id为2的项目的关联产品的分支数组数量，按产品分组
