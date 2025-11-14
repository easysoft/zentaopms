#!/usr/bin/env php
<?php

/**

title=测试 convertTao::createTask();
timeout=0
cid=15846

- 执行convertTest模块的createTaskTest方法，参数是1, 1, $normalData, $relations  @0
- 执行convertTest模块的createTaskTest方法，参数是1, 1, null, $relations  @0
- 执行convertTest模块的createTaskTest方法，参数是0, 1, $invalidProjectData, $relations  @0
- 执行convertTest模块的createTaskTest方法，参数是1, 0, $invalidExecutionData, $relations  @0
- 执行convertTest模块的createTaskTest方法，参数是1, 1, $missingFieldData, $relations  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

su('admin');

$convertTest = new convertTest();

$normalData = new stdclass();
$normalData->id = 'test001';
$normalData->summary = 'Test Task';
$normalData->timeoriginalestimate = 28800;
$normalData->timeestimate = 14400;
$normalData->timespent = 7200;
$normalData->priority = 2;
$normalData->issuestatus = 'Open';
$normalData->issuetype = 'Task';
$normalData->description = 'Test task description';
$normalData->creator = 'admin';
$normalData->created = '2023-01-01T10:00:00.000+0800';
$normalData->assignee = 'user1';
$normalData->duedate = '2023-12-31';
$normalData->resolution = '';

$relations = array(
    'zentaoStatusTask' => array(
        'Open' => 'wait',
        'In Progress' => 'doing',
        'Resolved' => 'done',
        'Closed' => 'closed'
    ),
    'zentaoReasonTask' => array(
        'Fixed' => 'done',
        'Won\'t Fix' => 'cancel',
        'Duplicate' => 'cancel'
    )
);

$invalidProjectData = new stdclass();
$invalidProjectData->id = 'test002';
$invalidProjectData->summary = 'Invalid Project Task';
$invalidProjectData->issuestatus = 'Open';
$invalidProjectData->issuetype = 'Task';

$invalidExecutionData = new stdclass();
$invalidExecutionData->id = 'test003';
$invalidExecutionData->summary = 'Invalid Execution Task';
$invalidExecutionData->issuestatus = 'Open';
$invalidExecutionData->issuetype = 'Task';

$missingFieldData = new stdclass();
$missingFieldData->id = 'test004';
$missingFieldData->issuestatus = 'Open';
$missingFieldData->issuetype = 'Task';

r($convertTest->createTaskTest(1, 1, $normalData, $relations)) && p() && e('0');
r($convertTest->createTaskTest(1, 1, null, $relations)) && p() && e('0');
r($convertTest->createTaskTest(0, 1, $invalidProjectData, $relations)) && p() && e('0');
r($convertTest->createTaskTest(1, 0, $invalidExecutionData, $relations)) && p() && e('0');
r($convertTest->createTaskTest(1, 1, $missingFieldData, $relations)) && p() && e('0');