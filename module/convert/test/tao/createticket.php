#!/usr/bin/env php
<?php

/**

title=测试 convertTao::createTicket();
timeout=0
cid=0

- 步骤1：正常情况 @0
- 步骤2：边界值 @0
- 步骤3：最小数据 @0
- 步骤4：空关系数组 @0
- 步骤5：复杂结构 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

$table = zenData('ticket');
$table->id->range('1-10');
$table->product->range('1-3');
$table->title->range('工单标题1, 工单标题2, 工单标题3, 工单标题4, 工单标题5');
$table->type->range('code{5}');
$table->desc->range('工单描述1, 工单描述2, 工单描述3, 工单描述4, 工单描述5');
$table->pri->range('1-4');
$table->status->range('wait, doing, done, closed');
$table->openedBy->range('admin, user1, user2');
$table->openedDate->range('`2024-01-01 10:00:00`, `2024-01-02 11:00:00`, `2024-01-03 12:00:00`, `2024-01-04 13:00:00`, `2024-01-05 14:00:00`');
$table->assignedTo->range('admin, user1, user2, closed');
$table->openedBuild->range('trunk{10}');
$table->gen(5);

$action = zenData('action');
$action->id->range('1-20');
$action->objectType->range('ticket{10}');
$action->objectID->range('1-10');
$action->product->range(',1,, ,2,, ,3,');
$action->actor->range('admin, user1, user2');
$action->action->range('opened{10}');
$action->date->range('`2024-01-01 10:00:00`, `2024-01-02 11:00:00`, `2024-01-03 12:00:00`, `2024-01-04 13:00:00`, `2024-01-05 14:00:00`, `2024-01-06 15:00:00`, `2024-01-07 16:00:00`, `2024-01-08 17:00:00`, `2024-01-09 18:00:00`, `2024-01-10 19:00:00`');
$action->gen(10);

su('admin');

// 设置必要的session数据以支持createTicket方法
global $app;
$app->session->set('jiraMethod', 'database');
$app->session->set('jiraUser', array('mode' => 'account'));

$convertTest = new convertTest();

// 测试数据1：正常创建工单
$data1 = new stdclass();
$data1->id = 1001;
$data1->summary = '测试工单标题';
$data1->priority = 2;
$data1->issuestatus = 'Open';
$data1->issuetype = 'Bug';
$data1->description = '这是一个测试工单的描述';
$data1->creator = 'admin';
$data1->created = '2024-01-01 10:00:00';
$data1->assignee = 'user1';
$data1->resolution = 'Fixed';

$relations1 = array(
    'zentaoReason' . $data1->issuetype => array('Fixed' => 'fixed', 'Won\'t Fix' => 'bydesign'),
    'statusMapping' => array('Open' => 'wait', 'In Progress' => 'doing', 'Closed' => 'closed')
);

// 测试数据2：边界值测试（产品ID为0）
$data2 = new stdclass();
$data2->id = 1002;
$data2->summary = '边界测试工单';
$data2->priority = null;
$data2->issuestatus = 'Closed';
$data2->issuetype = 'Task';
$data2->description = '';
$data2->creator = '';
$data2->created = '2024-01-02 11:00:00';
$data2->assignee = '';
$data2->resolution = '';

$relations2 = array();

// 测试数据3：最小有效数据
$data3 = new stdclass();
$data3->id = 1003;
$data3->summary = '最小数据工单';
$data3->priority = 3;
$data3->issuestatus = 'Open';
$data3->issuetype = 'Story';
$data3->description = '';
$data3->creator = '';
$data3->created = '';
$data3->assignee = '';
$data3->resolution = '';

$relations3 = array(
    'zentaoReason' . $data3->issuetype => array()
);

// 测试数据4：关系数组为空的情况
$data4 = new stdclass();
$data4->id = 1004;
$data4->summary = '空关系数组工单';
$data4->priority = 4;
$data4->issuestatus = 'In Progress';
$data4->issuetype = 'Improvement';
$data4->description = '测试空关系数组的情况';
$data4->creator = 'user2';
$data4->created = '2024-01-04 14:00:00';
$data4->assignee = 'admin';
$data4->resolution = '';

$relations4 = array();

// 测试数据5：复杂数据结构
$data5 = new stdclass();
$data5->id = 1005;
$data5->summary = '复杂结构工单测试';
$data5->priority = 1;
$data5->issuestatus = 'Resolved';
$data5->issuetype = 'Epic';
$data5->description = '这是一个包含复杂数据结构的测试工单，用于验证系统的处理能力';
$data5->creator = 'projectmanager';
$data5->created = '2024-01-05 16:30:00';
$data5->assignee = 'developer1';
$data5->resolution = 'Done';

$relations5 = array(
    'zentaoReason' . $data5->issuetype => array('Done' => 'done', 'Cancelled' => 'cancel'),
    'statusMapping' => array('Resolved' => 'resolved', 'Closed' => 'closed'),
    'userMapping' => array('projectmanager' => 'pm1', 'developer1' => 'dev1')
);

r($convertTest->createTicketTest(1, $data1, $relations1)) && p() && e('0'); // 步骤1：正常情况
r($convertTest->createTicketTest(0, $data2, $relations2)) && p() && e('0'); // 步骤2：边界值
r($convertTest->createTicketTest(2, $data3, $relations3)) && p() && e('0'); // 步骤3：最小数据
r($convertTest->createTicketTest(3, $data4, $relations4)) && p() && e('0'); // 步骤4：空关系数组
r($convertTest->createTicketTest(1, $data5, $relations5)) && p() && e('0'); // 步骤5：复杂结构