#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
zdTable('project')->gen(20);
zdTable('product')->gen(20);
zdTable('story')->gen(20);
zdTable('projectstory')->gen(20);
su('admin');

/**

title=测试 projectModel->getStoriesByProject();
timeout=0
cid=1

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
