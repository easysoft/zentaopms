#!/usr/bin/env php
<?php
/**

title=prodeutplanModel->linkProject();
timeout=0
cid=17643

- 测试敏捷项目关联子计划 @0
- 测试瀑布项目关联子计划 @0
- 测试看板项目关联子计划 @0
- 测试敏捷项目关联普通计划 @0
- 测试瀑布项目关联普通计划 @0
- 测试看板项目关联普通计划 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(5);
zenData('productplan')->loadYaml('productplan')->gen(5);
zenData('projectproduct')->loadYaml('projectproduct')->gen(30);
zenData('product')->loadYaml('product')->gen(5);
zenData('project')->loadYaml('execution')->gen(30);
zenData('story')->loadYaml('story')->gen(0);
zenData('projectstory')->gen(0);
zenData('storystage')->gen(0);
zenData('module')->gen(0);
zenData('kanbancolumn')->gen(0);
zenData('kanbancell')->gen(0);

$projectIdList = array(11, 60, 100);
$plans         = array(array(2), array(3));

$planTester = new productPlan('admin');
r($planTester->linkProjectTest($projectIdList[0], $plans[0])) && p() && e('0'); // 测试敏捷项目关联子计划
r($planTester->linkProjectTest($projectIdList[1], $plans[0])) && p() && e('0'); // 测试瀑布项目关联子计划
r($planTester->linkProjectTest($projectIdList[2], $plans[0])) && p() && e('0'); // 测试看板项目关联子计划
r($planTester->linkProjectTest($projectIdList[0], $plans[1])) && p() && e('0'); // 测试敏捷项目关联普通计划
r($planTester->linkProjectTest($projectIdList[1], $plans[1])) && p() && e('0'); // 测试瀑布项目关联普通计划
r($planTester->linkProjectTest($projectIdList[2], $plans[1])) && p() && e('0'); // 测试看板项目关联普通计划
