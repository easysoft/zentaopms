#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/story.class.php';
su('admin');

/**

title=测试 storyModel->batchToTask();
cid=1
pid=1

6个需求批量转任务，查看转化后的数量 >> 5
查看从需求转化过来的任务的名称、状态、优先级等字段 >> 11,101,软件需求66,wait,0,test,
查看从需求转化过来的任务的名称、状态、优先级等字段 >> 11,101,软件需求68,wait,0,test,
查看从需求转化过来的任务的名称、状态、优先级等字段 >> 11,101,软件需求70,wait,0,test,
查看从需求转化过来的任务的名称、状态、优先级等字段 >> 11,101,软件需求102,wait,0,test,
查看从需求转化过来的任务的名称、状态、优先级等字段 >> 11,101,软件需求220,wait,0,test,

*/

$story = new storyTest();

$params['storyIdList'] = array(66, 68, 70, 102, 220, 320);
$params['type']        = 'test';
$params['fields']      = array('assignedTo');

$tasks = $story->batchToTaskTest(101, 11, $params);

r(count($tasks)) && p()                                       && e('5');                        //6个需求批量转任务，查看转化后的数量
r($tasks)        && p('911:project,execution,name,status,pri,type,assignedTo')  && e('11,101,软件需求66,wait,0,test,');  //查看从需求转化过来的任务的名称、状态、优先级等字段
r($tasks)        && p('912:project,execution,name,status,pri,type,assignedTo')  && e('11,101,软件需求68,wait,0,test,');  //查看从需求转化过来的任务的名称、状态、优先级等字段
r($tasks)        && p('913:project,execution,name,status,pri,type,assignedTo')  && e('11,101,软件需求70,wait,0,test,');  //查看从需求转化过来的任务的名称、状态、优先级等字段
r($tasks)        && p('914:project,execution,name,status,pri,type,assignedTo')  && e('11,101,软件需求102,wait,0,test,'); //查看从需求转化过来的任务的名称、状态、优先级等字段
r($tasks)        && p('915:project,execution,name,status,pri,type,assignedTo')  && e('11,101,软件需求220,wait,0,test,'); //查看从需求转化过来的任务的名称、状态、优先级等字段
