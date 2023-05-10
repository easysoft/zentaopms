#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('project')->config('project')->gen(3);
zdTable('branch')->config('branch')->gen(5);
zdTable('product')->config('product')->gen(3);
zdTable('projectproduct')->config('projectproduct')->gen(6);

/**

title=测试 projectModel->getBranchGroupByProject();
timeout=0
cid=1
pid=1

*/

global $tester;
$tester->loadModel('project');

$productIdList = array(1, 2, 3);
$project1      = $tester->project->getBranchGroupByProject(1, $productIdList);
$project2      = $tester->project->getBranchGroupByProject(2, $productIdList);
$project3      = $tester->project->getBranchGroupByProject(3, $productIdList);

r(key($project1[1]))   && p('') && e('1'); // 获取id为1的项目的关联产品的分支数组，获取第一个数组的键
r(count($project1[1])) && p('') && e('3'); // 获取id为1的项目的关联产品的分支数组数量，按产品分组
r(key($project2[2]))   && p('') && e('4'); // 获取id为2的项目的关联产品的分支数组，获取第一个数组的键
r(count($project2[2])) && p('') && e('1'); // 获取id为2的项目的关联产品的分支数组数量，按产品分组
r(key($project3[3]))   && p('') && e('5'); // 获取id为3的项目的关联产品的分支数组，获取第一个数组的键
r(count($project3[3])) && p('') && e('1'); // 获取id为3的项目的关联产品的分支数组数量，按产品分组
