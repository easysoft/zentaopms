#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('project')->loadYaml('project')->gen(3);
zenData('product')->loadYaml('product')->gen(3);
zenData('projectproduct')->loadYaml('projectproduct')->gen(5);

/**

title=测试 projectModel->getBranchesByProject();
timeout=0
cid=17814

- 获取id为1的项目的关联产品的分支数组数量，按产品分组 @3
- 获取id为1的项目的关联产品的分支数组，获取第一个数组的键 @1
- 获取id为2的项目的关联产品的分支数组数量，按产品分组 @2
- 获取id为2的项目的关联产品的分支数组，获取第一个数组的键 @1
- 获取id为3的项目的关联产品的分支数组数量，按产品分组 @0
- 获取id为3的项目的关联产品的分支数组，获取第一个数组的键 @0

*/

global $tester;
$tester->loadModel('project');

r(count($tester->project->getBranchesByProject(1))) && p('') && e('3'); // 获取id为1的项目的关联产品的分支数组数量，按产品分组
r(key($tester->project->getBranchesByProject(1)))   && p('') && e('1'); // 获取id为1的项目的关联产品的分支数组，获取第一个数组的键
r(count($tester->project->getBranchesByProject(2))) && p('') && e('2'); // 获取id为2的项目的关联产品的分支数组数量，按产品分组
r(key($tester->project->getBranchesByProject(2)))   && p('') && e('1'); // 获取id为2的项目的关联产品的分支数组，获取第一个数组的键
r(count($tester->project->getBranchesByProject(3))) && p('') && e('0'); // 获取id为3的项目的关联产品的分支数组数量，按产品分组
r(key($tester->project->getBranchesByProject(3)))   && p('') && e('0'); // 获取id为3的项目的关联产品的分支数组，获取第一个数组的键
