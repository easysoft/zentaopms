#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/story.class.php';
su('admin');

zdTable('task')->gen(0);
zdTable('taskspec')->gen(0);
zdTable('product')->gen(20);

$project = zdTable('project');
$project->type->range('sprint');
$project->gen(20);

/**

title=测试 storyModel->batchToTask();
cid=1
pid=1

*/

$story = new storyTest();
$tasks = $story->batchToTaskTest();

r(count($tasks))       && p()                                      && e('2');                           //2个需求批量转任务，查看转化后的数量
r(array_shift($tasks)) && p('project,execution,name,status,type')  && e('11,12,软件需求1,wait,devel'); //查看从需求转化过来的任务的名称、状态、类型等字段
r(array_shift($tasks)) && p('project,execution,name,status,type')  && e('11,12,软件需求2,wait,devel'); //查看从需求转化过来的任务的名称、状态、类型等字段
