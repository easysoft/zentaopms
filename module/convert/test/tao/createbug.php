#!/usr/bin/env php
<?php

/**

title=测试 convertTao::createBug();
timeout=0
cid=15831

- 执行convertTest模块的createBugTest方法，参数是1, 1, 1, $data1, $relations1  @0
- 执行convertTest模块的createBugTest方法，参数是1, 1, 1, $data2, $relations1  @0
- 执行convertTest模块的createBugTest方法，参数是999, 1, 1, $data3, $relations1  @0
- 执行convertTest模块的createBugTest方法，参数是2, 2, 2, $data4, $relations4  @0
- 执行convertTest模块的createBugTest方法，参数是3, 3, 0, $data5, $relations5  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

$product = zenData('product');
$product->id->range('1-10');
$product->name->range('产品1,产品2,产品3');
$product->code->range('product1,product2,product3');
$product->status->range('normal{10}');
$product->gen(3);

$project = zenData('project');
$project->id->range('1-10');
$project->name->range('项目1,项目2,项目3');
$project->code->range('project1,project2,project3');
$project->status->range('wait{10}');
$project->type->range('project{10}');
$project->gen(3);

$user = zenData('user');
$user->id->range('1-10');
$user->account->range('admin,user1,user2,testuser');
$user->realname->range('管理员,用户1,用户2,测试用户');
$user->gen(4);

su('admin');

$convertTest = new convertTest();

$data1 = new stdclass();
$data1->id = 100;
$data1->summary = '正常Bug标题';
$data1->priority = 2;
$data1->issuestatus = 'open';
$data1->issuetype = 'bug';
$data1->description = '正常Bug描述';
$data1->creator = 'admin';
$data1->created = '2023-01-01 10:00:00';
$data1->assignee = 'admin';
$data1->duedate = '2023-12-31';
$data1->resolution = '';

$relations1 = array();

r($convertTest->createBugTest(1, 1, 1, $data1, $relations1)) && p() && e('0');

$data2 = new stdclass();
$data2->id = 101;
$data2->summary = '';
$data2->priority = 0;
$data2->issuestatus = '';
$data2->issuetype = 'bug';
$data2->description = '';
$data2->creator = '';
$data2->created = '';
$data2->assignee = '';
$data2->duedate = '';
$data2->resolution = '';

r($convertTest->createBugTest(1, 1, 1, $data2, $relations1)) && p() && e('0');

$data3 = new stdclass();
$data3->id = 102;
$data3->summary = '无效产品Bug';
$data3->priority = 3;
$data3->issuestatus = 'open';
$data3->issuetype = 'bug';
$data3->description = '测试无效产品ID';
$data3->creator = 'testuser';
$data3->created = '2023-06-01 15:30:00';
$data3->assignee = 'user1';
$data3->duedate = '';
$data3->resolution = '';

r($convertTest->createBugTest(999, 1, 1, $data3, $relations1)) && p() && e('0');

$data4 = new stdclass();
$data4->id = 103;
$data4->summary = '带解决方案的Bug';
$data4->priority = 1;
$data4->issuestatus = 'resolved';
$data4->issuetype = 'bug';
$data4->description = '已解决的Bug';
$data4->creator = 'user2';
$data4->created = '2023-03-15 09:20:00';
$data4->assignee = 'admin';
$data4->duedate = '2023-04-15';
$data4->resolution = 'fixed';

$relations4 = array('zentaoResolutionbug' => array('fixed' => 'fixed', 'wontfix' => 'postponed'));

r($convertTest->createBugTest(2, 2, 2, $data4, $relations4)) && p() && e('0');

$data5 = new stdclass();
$data5->id = 104;
$data5->summary = '已关闭的Bug';
$data5->priority = 4;
$data5->issuestatus = 'closed';
$data5->issuetype = 'bug';
$data5->description = '关闭状态的Bug';
$data5->creator = 'admin';
$data5->created = '2023-02-20 14:45:00';
$data5->assignee = '';
$data5->duedate = '';
$data5->resolution = 'duplicate';

$relations5 = array('zentaoResolutionbug' => array('duplicate' => 'duplicate'));

r($convertTest->createBugTest(3, 3, 0, $data5, $relations5)) && p() && e('0');