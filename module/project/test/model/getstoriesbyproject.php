#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
zenData('project')->gen(20);
zenData('product')->gen(20);
zenData('story')->gen(20);
zenData('projectstory')->gen(20);
su('admin');

/**

title=测试 projectModel->getStoriesByProject();
timeout=0
cid=17851

- 获取项目1关联的所有产品数量 @1
- 获取项目1关联的产品ID为1的详情第0条的product属性 @1
- 获取项目1关联的产品ID为1的需求ID第0条的storyIDList属性 @2,4
- 获取项目0关联的所有产品数量 @6
- 获取项目100关联的所有产品数量 @0

*/

global $tester;
$tester->loadModel('project');

$result = $tester->project->getStoriesByProject(11);
r(count($result)) && p()                && e('1');   // 获取项目1关联的所有产品数量
r($result[1])     && p('0:product')     && e('1');   // 获取项目1关联的产品ID为1的详情
r($result[1])     && p('0:storyIDList', '|') && e('2,4'); // 获取项目1关联的产品ID为1的需求ID

$result = $tester->project->getStoriesByProject(0);
r(count($result)) && p() && e('6'); // 获取项目0关联的所有产品数量

$result = $tester->project->getStoriesByProject(100);
r(count($result)) && p() && e('0'); // 获取项目100关联的所有产品数量