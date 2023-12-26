#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('product')->gen(100);
$projectstory = zdTable('projectstory');
$projectstory->project->range('11{50},36{50}');
$projectstory->product->range('1');
$projectstory->story->range('1-50');
$projectstory->gen(100);

$story = zdTable('story');
$story->product->range('1');
$story->gen(50);

$planstory = zdTable('planstory');
$planstory->plan->range('1{20},2{20},3{20}');
$planstory->gen(50);

$project = zdTable('project');
$project->type->range('project{25},sprint{25}');
$project->gen(50);

/**

title=测试 storyModel->getPlanStories();
cid=1
pid=1

*/

global $tester, $app;
$app->methodName = 'getPlanStories';
$tester->loadModel('story');
$app->loadClass('pager', $static = true);
$pager = new pager(0, 10, 1);

$plan1Stories = $tester->story->getPlanStories(1, 'all', 'id_desc', $pager);
$plan2Stories = $tester->story->getPlanStories(1, 'all', 'id_desc');
$plan3Stories = $tester->story->getPlanStories(1, 'all', 'module,id_desc');

r(count($plan1Stories)) && p() && e('10'); //获取计划1下的需求数量，每页10条
r(count($plan2Stories)) && p() && e('20'); //获取计划1下的需求数量，不分页
r(count($plan3Stories)) && p() && e('20'); //获取计划1下，按照模块排序的需求数量，不分页
