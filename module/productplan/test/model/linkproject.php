#!/usr/bin/env php
<?php
/**

title=prodeutplanModel->linkProject();
timeout=0
cid=1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/productplan.class.php';

zdTable('user')->gen(5);
zdTable('productplan')->config('productplan')->gen(5);
zdTable('projectproduct')->config('projectproduct')->gen(30);
zdTable('product')->config('product')->gen(5);
zdTable('project')->config('execution')->gen(30);
zdTable('story')->config('story')->gen(0);
zdTable('projectstory')->gen(0);
zdTable('storystage')->gen(0);
zdTable('module')->gen(0);
zdTable('kanbancolumn')->gen(0);
zdTable('kanbancell')->gen(0);

$projectIdList = array(11, 60, 100);
$plans         = array(array(2), array(3));

$planTester = new productPlan('admin');
r($planTester->linkProjectTest($projectIdList[0], $plans[0])) && p() && e('0'); // 测试敏捷项目关联子计划
r($planTester->linkProjectTest($projectIdList[1], $plans[0])) && p() && e('0'); // 测试瀑布项目关联子计划
r($planTester->linkProjectTest($projectIdList[2], $plans[0])) && p() && e('0'); // 测试看板项目关联子计划
r($planTester->linkProjectTest($projectIdList[0], $plans[1])) && p() && e('0'); // 测试敏捷项目关联普通计划
r($planTester->linkProjectTest($projectIdList[1], $plans[1])) && p() && e('0'); // 测试瀑布项目关联普通计划
r($planTester->linkProjectTest($projectIdList[2], $plans[1])) && p() && e('0'); // 测试看板项目关联普通计划
