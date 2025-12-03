#!/usr/bin/env php
<?php

/**

title=测试 convertTao::createTask();
timeout=0
cid=15846

- 执行convertTest模块的createTaskTest方法，参数是1, 1, $data1, $relations1  @0
- 执行convertTest模块的createTaskTest方法，参数是1, 1, $data2, $relations1  @0
- 执行convertTest模块的createTaskTest方法，参数是2, 2, $data3, $relations3  @0
- 执行convertTest模块的createTaskTest方法，参数是1, 1, $data4, $relations4  @0
- 执行convertTest模块的createTaskTest方法，参数是3, 3, $data5, $relations1  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

$project = zenData('project');
$project->id->range('1-10');
$project->name->range('项目1,项目2,项目3');
$project->code->range('project1,project2,project3');
$project->status->range('wait{10}');
$project->type->range('project{3},sprint{7}');
$project->gen(10);

$user = zenData('user');
$user->id->range('1-10');
$user->account->range('admin,user1,user2,testuser');
$user->realname->range('管理员,用户1,用户2,测试用户');
$user->gen(4);

su('admin');

$convertTest = new convertTest();

$data1 = new stdclass();
$data1->id = 100;
$data1->summary = '正常任务标题';
$data1->priority = 2;
$data1->issuestatus = 'open';
$data1->issuetype = 'task';
$data1->description = '正常任务描述';
$data1->creator = 'admin';
$data1->created = '2023-01-01 10:00:00';
$data1->assignee = 'admin';
$data1->duedate = '2023-12-31';
$data1->resolution = '';
$data1->timeoriginalestimate = 28800;
$data1->timeestimate = 14400;
$data1->timespent = 7200;

$relations1 = array();

r($convertTest->createTaskTest(1, 1, $data1, $relations1)) && p() && e('0');

$data2 = new stdclass();
$data2->id = 101;
$data2->summary = '最小字段任务';
$data2->priority = 0;
$data2->issuestatus = '';
$data2->issuetype = 'task';
$data2->description = '';
$data2->creator = '';
$data2->created = '';
$data2->assignee = '';
$data2->duedate = '';
$data2->resolution = '';

r($convertTest->createTaskTest(1, 1, $data2, $relations1)) && p() && e('0');

$data3 = new stdclass();
$data3->id = 102;
$data3->summary = '工时任务';
$data3->priority = 3;
$data3->issuestatus = 'doing';
$data3->issuetype = 'task';
$data3->description = '测试工时转换';
$data3->creator = 'testuser';
$data3->created = '2023-06-01 15:30:00';
$data3->assignee = 'user1';
$data3->duedate = '';
$data3->resolution = '';
$data3->timeoriginalestimate = 36000;
$data3->timeestimate = 18000;
$data3->timespent = 10800;

$relations3 = array();

r($convertTest->createTaskTest(2, 2, $data3, $relations3)) && p() && e('0');

$data4 = new stdclass();
$data4->id = 103;
$data4->summary = '带解决方案的任务';
$data4->priority = 1;
$data4->issuestatus = 'closed';
$data4->issuetype = 'task';
$data4->description = '已关闭的任务';
$data4->creator = 'user2';
$data4->created = '2023-03-15 09:20:00';
$data4->assignee = 'admin';
$data4->duedate = '2023-04-15';
$data4->resolution = 'done';

$relations4 = array('zentaoReasontask' => array('done' => 'done', 'cancel' => 'cancel'));

r($convertTest->createTaskTest(1, 1, $data4, $relations4)) && p() && e('0');

$data5 = new stdclass();
$data5->id = 104;
$data5->summary = '指派任务';
$data5->priority = 4;
$data5->issuestatus = 'wait';
$data5->issuetype = 'task';
$data5->description = '待处理任务';
$data5->creator = 'admin';
$data5->created = '2023-02-20 14:45:00';
$data5->assignee = 'user2';
$data5->duedate = '2023-05-30';
$data5->resolution = '';

r($convertTest->createTaskTest(3, 3, $data5, $relations1)) && p() && e('0');