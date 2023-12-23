#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/project.class.php';

zdTable('project')->config('project')->gen(100);
zdTable('product')->gen(100);
zdTable('productplan')->gen(100);
zdTable('story')->gen(100);
zdTable('planstory')->gen(100);
zdTable('projectstory')->gen(0);

su('admin');

/**

title=测试 projectModel::addPlans();
timeout=0
cid=1

*/

$project = new Project();

$plan = array();
$plan[3] = array(1, 4, 7);
r(count($project->addPlansTest(13, $plan))) && p('') && e('4'); // 将计划1，4，7下的需求关联到项目13，查看关联后的需求数
r($project->addPlansTest(13, $plan)) && p('3:story,product') && e('10,3'); // 将计划1，4，7下的需求关联到项目13，查看关联后的需求ID/产品ID

$plan = array();
$plan[1] = array(2, 5, 10, 13);
r(count($project->addPlansTest(11, $plan))) && p('') && e('2'); // 将计划2，5，10，13下的需求关联到项目11，查看关联后的需求数
r($project->addPlansTest(11, $plan)) && p('0:story,product') && e('14,4'); // 将计划2，5，10，13下的需求关联到项目11，查看关联后的需求ID/产品ID
