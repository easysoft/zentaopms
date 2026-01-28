#!/usr/bin/env php
<?php

/**

title=测试 storyModel->batchToTask();
cid=18476

- 2个需求批量转任务，查看转化后的数量 @2
- 查看从需求转化过来的任务的名称、状态、类型等字段
 - 属性project @11
 - 属性execution @12
 - 属性name @软件需求1
 - 属性status @wait
 - 属性type @devel
- 查看从需求转化过来的任务的名称、状态、类型等字段
 - 属性project @11
 - 属性execution @12
 - 属性name @软件需求2
 - 属性status @wait
 - 属性type @devel

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('task')->gen(0);
zenData('taskspec')->gen(0);
zenData('product')->gen(20);

$project = zenData('project');
$project->type->range('sprint');
$project->gen(20);

$story = new storyModelTest();
$tasks = $story->batchToTaskTest();

r(count($tasks))       && p()                                      && e('2');                           //2个需求批量转任务，查看转化后的数量
r(array_shift($tasks)) && p('project,execution,name,status,type')  && e('11,12,软件需求1,wait,devel'); //查看从需求转化过来的任务的名称、状态、类型等字段
r(array_shift($tasks)) && p('project,execution,name,status,type')  && e('11,12,软件需求2,wait,devel'); //查看从需求转化过来的任务的名称、状态、类型等字段
