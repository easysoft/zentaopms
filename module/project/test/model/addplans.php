#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('project')->loadYaml('project')->gen(100);
zenData('product')->gen(100);
zenData('productplan')->gen(100);
$storyTable = zenData('story');
$storyTable->status->range('active');
$storyTable->version->range('1');
$storyTable->gen(100);
zenData('storyspec')->gen(100);
zenData('planstory')->gen(100);
zenData('projectstory')->gen(0);

su('admin');

/**

title=测试 projectModel::addPlans();
timeout=0
cid=17797

- 将计划1，4，7下的需求关联到项目13，查看关联后的需求数 @16
- 将计划1，4，7下的需求关联到项目13，查看关联后的需求ID/产品ID
 - 第3条的story属性 @4
 - 第3条的product属性 @1
- 将计划2，5，10，13下的需求关联到项目11，查看关联后的需求数 @8
- 将计划2，5，10，13下的需求关联到项目11，查看关联后的需求ID/产品ID
 - 第0条的story属性 @13
 - 第0条的product属性 @4

*/

$project = new projectModelTest();

$plan = array();
$plan[3] = array(1, 4, 7);
r(count($project->addPlansTest(13, $plan))) && p('') && e('16'); // 将计划1，4，7下的需求关联到项目13，查看关联后的需求数
r($project->addPlansTest(13, $plan)) && p('3:story,product') && e('4,1'); // 将计划1，4，7下的需求关联到项目13，查看关联后的需求ID/产品ID

$plan = array();
$plan[1] = array(2, 5, 10, 13);
r(count($project->addPlansTest(11, $plan))) && p('') && e('8'); // 将计划2，5，10，13下的需求关联到项目11，查看关联后的需求数
r($project->addPlansTest(11, $plan)) && p('0:story,product') && e('13,4'); // 将计划2，5，10，13下的需求关联到项目11，查看关联后的需求ID/产品ID
