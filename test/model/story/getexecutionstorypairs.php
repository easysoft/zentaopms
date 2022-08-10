#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/story.class.php';
su('admin');

/**

title=测试 storyModel->getExecutionStoryPairs();
cid=1
pid=1

获取执行11下的需求数量 >> 5
获取执行11下的需求详情 >> 364:软件需求364 (优先级:4,预计工时:7)
获取执行11、产品91下的需求数量 >> 3
获取执行11、产品91下的需求详情 >> 362:软件需求362 (优先级:2,预计工时:3)
获取执行11、产品1下的需求数量 >> 3
获取执行11、产品1下的需求详情 >> 2:软件需求2 (优先级:2,预计工时:19)

*/

global $tester;
$tester->loadModel('story');
$stories1 = $tester->story->getExecutionStoryPairs(11);
$stories2 = $tester->story->getExecutionStoryPairs(11, 91);
$stories3 = $tester->story->getExecutionStoryPairs(11, 1);

r(count($stories1)) && p()      && e('5');                                     // 获取执行11下的需求数量
r($stories1)        && p('364') && e('364:软件需求364 (优先级:4,预计工时:7)'); // 获取执行11下的需求详情
r(count($stories2)) && p()      && e('3');                                     // 获取执行11、产品91下的需求数量
r($stories2)        && p('362') && e('362:软件需求362 (优先级:2,预计工时:3)'); // 获取执行11、产品91下的需求详情
r(count($stories3)) && p()      && e('3');                                     // 获取执行11、产品1下的需求数量
r($stories3)        && p('2')   && e('2:软件需求2 (优先级:2,预计工时:19)');    // 获取执行11、产品1下的需求详情