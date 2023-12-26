#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/story.class.php';
su('admin');

zdTable('product')->gen(100);
$projectstory = zdTable('projectstory');
$projectstory->project->range('11{50},36{50}');
$projectstory->product->range('1,2');
$projectstory->story->range('1-50');
$projectstory->gen(100);

$story = zdTable('story');
$story->type->range('story');
$story->product->range('1,2');
$story->pri->range('0-4');
$story->estimate->range('1-7');
$story->gen(50);

$project = zdTable('project');
$project->type->range('project{25},sprint{25}');
$project->gen(50);

/**

title=测试 storyModel->getExecutionStoryPairs();
cid=1
pid=1

*/

global $tester;
$tester->loadModel('story');
$stories1 = $tester->story->getExecutionStoryPairs(11);
$stories2 = $tester->story->getExecutionStoryPairs(11, 91);
$stories3 = $tester->story->getExecutionStoryPairs(11, 1);

r(count($stories1)) && p()         && e('50');                                // 获取执行11下的需求数量
r($stories1)        && p('8', '|') && e('8:软件需求8 (优先级:2,预计工时:1)'); // 获取执行11下的需求详情
r(count($stories2)) && p()         && e('0');                                 // 获取执行11、产品91下的需求数量
r(count($stories3)) && p()         && e('25');                                // 获取执行11、产品1下的需求数量
r($stories3)        && p('3', '|') && e('3:用户需求3 (优先级:2,预计工时:3)'); // 获取执行11、产品1下的需求详情
