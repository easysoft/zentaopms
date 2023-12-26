#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/story.class.php';

$project = zdTable('project');
$project->model->range('scrum{10},{10}');
$project->project->range('0{10},11{10}');
$project->type->range('project{10},sprint{10}');
$project->gen(20);

$projectstory = zdTable('projectstory');
$projectstory->product->range('1-2');
$projectstory->story->range('1-50');
$projectstory->project->range('11-15');
$projectstory->branch->range('0{30},1{10},2{10}');
$projectstory->gen(50);

su('admin');

/**

title=测试 storyModel->getIdListOfExecutionsByProjectID();
cid=1
pid=1

*/

global $tester;
$storyModel = $tester->loadModel('story');

r(count($storyModel->getIdListOfExecutionsByProjectID('unclosed', 0)))           && p() && e('0');  //请求类型是 unclosed，不传入项目。
r(count($storyModel->getIdListOfExecutionsByProjectID('unclosed', 11)))          && p() && e('0');  //请求类型是 unclosed，项目下有执行。
r(count($storyModel->getIdListOfExecutionsByProjectID('linkedexecution', 0)))    && p() && e('50'); //请求类型是 linkedexecution，不传入项目。
r(count($storyModel->getIdListOfExecutionsByProjectID('linkedexecution', 11)))   && p() && e('50'); //请求类型是 linkedexecution，项目下有执行。
r(count($storyModel->getIdListOfExecutionsByProjectID('linkedexecution', 12)))   && p() && e('0');  //请求类型是 linkedexecution，项目下无执行。
r(count($storyModel->getIdListOfExecutionsByProjectID('unlinkedexecution', 0)))  && p() && e('50'); //请求类型是 unlinkedexecution，不传入项目。
r(count($storyModel->getIdListOfExecutionsByProjectID('unlinkedexecution', 11))) && p() && e('50'); //请求类型是 unlinkedexecution，项目下有执行。
r(count($storyModel->getIdListOfExecutionsByProjectID('unlinkedexecution', 12))) && p() && e('0');  //请求类型是 unlinkedexecution，项目下无执行。
