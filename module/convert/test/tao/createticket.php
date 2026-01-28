#!/usr/bin/env php
<?php

/**

title=测试 convertTao::createTicket();
timeout=0
cid=15848

- 执行convertTest模块的createTicketTest方法，参数是1, $fullData, $relations  @1
- 执行convertTest模块的createTicketTest方法，参数是1, $partialData, $relations  @1
- 执行convertTest模块的createTicketTest方法，参数是1, $minimalData, $relations  @1
- 执行convertTest模块的createTicketTest方法，参数是1, $resolvedData, $relations  @1
- 执行convertTest模块的createTicketTest方法，参数是1, null, array  @0

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. 用户登录(选择合适角色)
su('admin');

// 3. 创建测试实例(变量名与模块名一致)
$convertTest = new convertTaoTest();

// 4. 测试步骤:必须包含至少5个测试步骤

// 步骤1:完整ticket数据创建
$fullData = new stdclass();
$fullData->id = '12345';
$fullData->summary = 'Test Ticket Summary';
$fullData->priority = '2';
$fullData->issuestatus = 'open';
$fullData->issuetype = 'Bug';
$fullData->description = 'This is a test ticket description';
$fullData->creator = 'admin';
$fullData->created = '2024-01-15 10:30:00';
$fullData->assignee = 'admin';
$fullData->resolution = '';
$relations = array();
r($convertTest->createTicketTest(1, $fullData, $relations)) && p() && e('1');

// 步骤2:部分字段缺失数据创建
$partialData = new stdclass();
$partialData->id = '67890';
$partialData->summary = 'Partial Ticket';
$partialData->priority = '';
$partialData->issuestatus = 'open';
$partialData->issuetype = 'Task';
$partialData->creator = '';
$partialData->created = '2024-01-20 14:15:00';
$partialData->resolution = '';
$relations = array();
r($convertTest->createTicketTest(1, $partialData, $relations)) && p() && e('1');

// 步骤3:最小字段数据创建
$minimalData = new stdclass();
$minimalData->id = '999';
$minimalData->summary = 'Minimal Ticket';
$minimalData->priority = '';
$minimalData->issuestatus = 'open';
$minimalData->issuetype = 'Bug';
$minimalData->resolution = '';
$relations = array();
r($convertTest->createTicketTest(1, $minimalData, $relations)) && p() && e('1');

// 步骤4:带有resolution的ticket创建
$resolvedData = new stdclass();
$resolvedData->id = '11111';
$resolvedData->summary = 'Resolved Ticket';
$resolvedData->priority = '1';
$resolvedData->issuestatus = 'closed';
$resolvedData->issuetype = 'Bug';
$resolvedData->description = 'Ticket with resolution';
$resolvedData->creator = 'admin';
$resolvedData->created = '2024-01-10 09:00:00';
$resolvedData->assignee = 'admin';
$resolvedData->resolution = 'Fixed';
$relations = array('zentaoReasonBug' => array('Fixed' => 'bydesign'));
r($convertTest->createTicketTest(1, $resolvedData, $relations)) && p() && e('1');

// 步骤5:空数据对象输入
r($convertTest->createTicketTest(1, null, array())) && p() && e('0');