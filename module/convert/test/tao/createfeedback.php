#!/usr/bin/env php
<?php

/**

title=测试 convertTao::createFeedback();
timeout=0
cid=15839

- 执行convertTest模块的createFeedbackTest方法，参数是1, $normalData, $relations  @0
- 执行convertTest模块的createFeedbackTest方法，参数是1, $priorityData, $relations  @0
- 执行convertTest模块的createFeedbackTest方法，参数是2, $resolvedData, $relations  @0
- 执行convertTest模块的createFeedbackTest方法，参数是1, $noCreatorData, $relations  @0
- 执行convertTest模块的createFeedbackTest方法，参数是3, $noDescData, $relations  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zenData('feedback')->gen(0);
zenData('action')->gen(0);
zenData('product')->gen(5);
zenData('user')->gen(10);

su('admin');

$convertTest = new convertTaoTest();

$normalData = new stdclass();
$normalData->id = 1;
$normalData->summary = 'Test feedback title';
$normalData->description = 'Test feedback description';
$normalData->priority = 2;
$normalData->issuestatus = 'open';
$normalData->issuetype = 'bug';
$normalData->creator = 'testuser';
$normalData->created = '2023-01-01 10:00:00';
$normalData->assignee = 'admin';
$normalData->resolution = '';

$priorityData = new stdclass();
$priorityData->id = 2;
$priorityData->summary = 'High priority feedback';
$priorityData->description = 'High priority feedback description';
$priorityData->priority = 1;
$priorityData->issuestatus = 'open';
$priorityData->issuetype = 'improvement';
$priorityData->creator = 'testuser2';
$priorityData->created = '2023-01-02 11:00:00';
$priorityData->assignee = 'admin';
$priorityData->resolution = '';

$resolvedData = new stdclass();
$resolvedData->id = 3;
$resolvedData->summary = 'Resolved feedback';
$resolvedData->description = 'Resolved feedback description';
$resolvedData->priority = 3;
$resolvedData->issuestatus = 'resolved';
$resolvedData->issuetype = 'bug';
$resolvedData->creator = 'testuser3';
$resolvedData->created = '2023-01-03 12:00:00';
$resolvedData->assignee = 'admin';
$resolvedData->resolution = 'fixed';

$noCreatorData = new stdclass();
$noCreatorData->id = 4;
$noCreatorData->summary = 'No creator feedback';
$noCreatorData->description = 'No creator feedback description';
$noCreatorData->priority = '';
$noCreatorData->issuestatus = 'open';
$noCreatorData->issuetype = 'task';
$noCreatorData->creator = '';
$noCreatorData->created = '2023-01-04 13:00:00';
$noCreatorData->assignee = '';
$noCreatorData->resolution = '';

$noDescData = new stdclass();
$noDescData->id = 5;
$noDescData->summary = 'No description feedback';
$noDescData->priority = 4;
$noDescData->issuestatus = 'open';
$noDescData->issuetype = 'story';
$noDescData->creator = 'testuser5';
$noDescData->created = '2023-01-05 14:00:00';
$noDescData->assignee = 'admin';
$noDescData->resolution = '';

$relations = array(
    'zentaoReasonbug' => array('fixed' => 'fixed', 'wontfix' => 'refuse'),
    'zentaoReasonstory' => array('done' => 'done'),
    'zentaoReasontask' => array('done' => 'done')
);

r($convertTest->createFeedbackTest(1, $normalData, $relations)) && p() && e('0');
r($convertTest->createFeedbackTest(1, $priorityData, $relations)) && p() && e('0');
r($convertTest->createFeedbackTest(2, $resolvedData, $relations)) && p() && e('0');
r($convertTest->createFeedbackTest(1, $noCreatorData, $relations)) && p() && e('0');
r($convertTest->createFeedbackTest(3, $noDescData, $relations)) && p() && e('0');