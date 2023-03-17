#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/story.class.php';
su('admin');

/**

title=测试 storyModel->sortStoriesOfPlan();
cid=1
pid=1

获取更新排序之前的需求在计划下的排序 >> 5
获取更新排序之前的需求在计划下的排序 >> 10
获取更新排序之前的需求在计划下的排序 >> 15
获取更新排序之前的需求在计划下的排序 >> 20
获取更新排序之后的需求在计划下的排序 >> 2
获取更新排序之后的需求在计划下的排序 >> 4
获取更新排序之后的需求在计划下的排序 >> 1
获取更新排序之后的需求在计划下的排序 >> 3

*/

global $tester;
$tester->loadModel('story');
$beforeStories = $tester->story->getPlanStories(1);
$tester->story->sortStoriesOfPlan(1, array(3, 1, 4, 2));
$afterStories  = $tester->story->getPlanStories(1);

r($beforeStories) && p('1:order') && e('5');  //获取更新排序之前的需求在计划下的排序
r($beforeStories) && p('2:order') && e('10'); //获取更新排序之前的需求在计划下的排序
r($beforeStories) && p('3:order') && e('15'); //获取更新排序之前的需求在计划下的排序
r($beforeStories) && p('4:order') && e('20'); //获取更新排序之前的需求在计划下的排序
r($afterStories)  && p('1:order') && e('2');  //获取更新排序之后的需求在计划下的排序
r($afterStories)  && p('2:order') && e('4');  //获取更新排序之后的需求在计划下的排序
r($afterStories)  && p('3:order') && e('1');  //获取更新排序之后的需求在计划下的排序
r($afterStories)  && p('4:order') && e('3');  //获取更新排序之后的需求在计划下的排序
