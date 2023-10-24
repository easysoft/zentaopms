#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 storyModel->linkStory();
cid=1
pid=1

获取关联需求后的执行下的需求数量 >> 5
获取关联需求后的执行下的需求数量 >> 5
获取关联需求后的执行下的需求数量 >> 5
获取关联需求后的执行下的需求详情 >> 软件需求300,story,closed
获取关联需求后的执行下的需求详情 >> 软件需求302,story,planned
获取关联需求后的执行下的需求详情 >> 软件需求304,story,developing

*/

global $tester;
$tester->loadModel('story');
$tester->story->linkStory(11, 1, 300);
$tester->story->linkStory(12, 1, 302);
$tester->story->linkStory(13, 1, 304);

$stories1 = $tester->story->getExecutionStories(11);
$stories2 = $tester->story->getExecutionStories(12);
$stories3 = $tester->story->getExecutionStories(13);

r(count($stories1)) && p()                       && e('5');                            // 获取关联需求后的执行下的需求数量
r(count($stories2)) && p()                       && e('5');                            // 获取关联需求后的执行下的需求数量
r(count($stories3)) && p()                       && e('5');                            // 获取关联需求后的执行下的需求数量
r($stories1)        && p('300:title,type,stage') && e('软件需求300,story,closed');     // 获取关联需求后的执行下的需求详情
r($stories2)        && p('302:title,type,stage') && e('软件需求302,story,planned');    // 获取关联需求后的执行下的需求详情
r($stories3)        && p('304:title,type,stage') && e('软件需求304,story,developing'); // 获取关联需求后的执行下的需求详情
