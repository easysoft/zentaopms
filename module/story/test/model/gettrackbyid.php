#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getTrackByID();
cid=0

- 获取用户需求1下面的所有任务数量 @8
- 获取用户需求1下面的所有用例数量 @5
- 获取用户需求1下面的所有Bug数量 @5
- 获取用户需求1下面的任务8的名字属性name @开发任务18
- 获取用户需求的信息属性title @用户需求11

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('product')->gen(2);
zdTable('storyspec')->gen(60);
$story = zdTable('story');
$story->product->range(1);
$story->parent->range('0{18},`-1`,19');
$story->type->range('requirement{10},story{10}');
$story->gen(20);

$projectstory = zdTable('projectstory');
$projectstory->project->range(1);
$projectstory->product->range(1);
$projectstory->story->range('1-18');
$projectstory->gen(18);

$case = zdTable('case');
$case->story->range(11);
$case->gen(5);

$bug = zdTable('bug');
$bug->story->range(11);
$bug->gen(5);

$task = zdTable('task');
$task->story->range('11{8},19{2}');
$task->parent->range('0{8},`-1`,9');
$task->gen(10);

$design = zdTable('design');
$design->story->range(11);
$design->gen(10);

$relation = zdTable('relation');
$relation->product->range(1);
$relation->AID->range('1,11,1,2,12,2,3,13,3,4,14,4,5,15,5,6,16,6,7,17,7,8,18,8');
$relation->BID->range('11,1,1,12,2,2,13,3,3,14,4,4,15,5,5,16,6,6,17,7,7,18,8,8');
$relation->AType->range('requirement,story,design');
$relation->BType->range('story,requirement,commit');
$relation->relation->range('subdivideinto,subdividedfrom,completedin');
$relation->gen(24);

zdTable('repohistory')->gen(10);

global $tester;
$story1Tracks = $tester->loadModel('story')->getTrackByID(1);

r(count($story1Tracks[11]->tasks)) && p()        && e('8');          //获取用户需求1下面的所有任务数量
r(count($story1Tracks[11]->cases)) && p()        && e('5');          //获取用户需求1下面的所有用例数量
r(count($story1Tracks[11]->bugs))  && p()        && e('5');          //获取用户需求1下面的所有Bug数量
r($story1Tracks[11]->tasks[8])     && p('name')  && e('开发任务18'); //获取用户需求1下面的任务8的名字
r($story1Tracks[11])               && p('title') && e('用户需求11'); //获取用户需求的信息
