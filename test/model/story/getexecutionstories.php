#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/story.class.php';
su('admin');

/**

title=测试 storyModel->getExecutionStories();
cid=1
pid=1

获取执行11下的需求数量 >> 4
获取执行11下的需求详情 >> 软件需求364,story,draft,developing
获取执行11、产品91下的需求数量 >> 2
获取执行11、产品91下的需求详情 >> 软件需求362,story,draft,planned
获取执行11、产品1下的需求数量 >> 2
获取执行11、产品1下的需求详情 >> 软件需求2,story,active,wait

*/

global $tester;
$tester->loadModel('story');
$stories1 = $tester->story->getExecutionStories(11);
$stories2 = $tester->story->getExecutionStories(11, 91);
$stories3 = $tester->story->getExecutionStories(11, 1);

r(count($stories1)) && p()                              && e('4');                                  // 获取执行11下的需求数量
r($stories1)        && p('364:title,type,status,stage') && e('软件需求364,story,draft,developing'); // 获取执行11下的需求详情
r(count($stories2)) && p()                              && e('2');                                  // 获取执行11、产品91下的需求数量
r($stories2)        && p('362:title,type,status,stage') && e('软件需求362,story,draft,planned');    // 获取执行11、产品91下的需求详情
r(count($stories3)) && p()                              && e('2');                                  // 获取执行11、产品1下的需求数量
r($stories3)        && p('2:title,type,status,stage')   && e('软件需求2,story,active,wait');        // 获取执行11、产品1下的需求详情