#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';

zenData('project')->loadYaml('project')->gen(100);
zenData('product')->gen(100);
zenData('productplan')->gen(100);
$story = zenData('story');
$story->version->range('1');
$story->gen(100);
zenData('planstory')->gen(100);
zenData('storyspec')->gen(100);
zenData('projectstory')->gen(0);
zenData('user')->gen(1);

su('admin');

/**

title=测试 projectModel::updatePlans();
timeout=0
cid=17876

- 将计划1，4，7下的需求关联到项目13，查看关联后的需求数 @4

- 将计划1，4，7下的需求关联到项目13，查看关联后的需求ID/产品ID
 - 第3条的story属性 @98
 - 第3条的product属性 @25

- 将计划2，5，10，13下的需求关联到项目11，查看关联后的需求数 @2

- 将计划2，5，10，13下的需求关联到项目11，查看关联后的需求ID/产品ID
 - 第0条的story属性 @14
 - 第0条的product属性 @4

*/

global $tester, $app;
$project = new projectTest();
$app->rawModule  = 'project';
$app->moduleName = 'project';

$plan = array();
$plan[3] = array(1, 4, 7);
r(count($project->updatePlansTest(13, $plan))) && p('') && e('4'); // 将计划1，4，7下的需求关联到项目13，查看关联后的需求数
r($project->updatePlansTest(13, $plan)) && p('3:story,product') && e('98,25'); // 将计划1，4，7下的需求关联到项目13，查看关联后的需求ID/产品ID

$plan = array();
$plan[1] = array(2, 5, 10, 13);
r(count($project->updatePlansTest(11, $plan))) && p('') && e('2'); // 将计划2，5，10，13下的需求关联到项目11，查看关联后的需求数
r($project->updatePlansTest(11, $plan)) && p('0:story,product') && e('14,4'); // 将计划2，5，10，13下的需求关联到项目11，查看关联后的需求ID/产品ID
