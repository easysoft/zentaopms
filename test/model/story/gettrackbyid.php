#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/story.class.php';
su('admin');

/**

title=测试 storyModel->getTrackByID();
cid=1
pid=1

获取用户需求1下面的所有任务数量 >> 6
获取用户需求1下面的任务601的名字 >> 开发任务511
获取用户需求1下面的任务501的名字 >> 开发任务411
获取用户需求的信息 >> 软件需求版本一551,requirement,active

*/

global $tester;
$story1Tracks = $tester->loadModel('story')->getByID(1);

r(count($story1Tracks->tasks)) && p()                    && e('6');                                    //获取用户需求1下面的所有任务数量
r($story1Tracks->tasks[601])   && p('0:name')            && e('开发任务511');                          //获取用户需求1下面的任务601的名字
r($story1Tracks->tasks[501])   && p('0:name')            && e('开发任务411');                          //获取用户需求1下面的任务501的名字
r($story1Tracks)               && p('title,type,status') && e('软件需求版本一551,requirement,active'); //获取用户需求的信息