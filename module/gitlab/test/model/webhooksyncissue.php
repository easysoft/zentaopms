#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/gitlab.class.php';
su('admin');

/**

title=测试 gitlabModel::webhookSyncIssue();
timeout=0
cid=1

- 对象类型为空 @0
- 对象类型错误 @0
- 对象ID为空 @0
- 对象ID错误 @0
- 对象ID正确属性name @开发任务11
- 对象ID正确,更新名称属性name @任务1

*/

zdTable('task')->gen(5);

$gitlab = new gitlabTest();

$issue = new stdclass();
$issue->object     = new stdclass();
$issue->objectID   = 0;
$issue->objectType = '';

r($gitlab->webhookSyncIssueTest($issue)) && p() && e('0'); // 对象类型为空

$issue->objectType = 'project';
r($gitlab->webhookSyncIssueTest($issue)) && p() && e('0'); // 对象类型错误

$issue->objectType = 'task';
r($gitlab->webhookSyncIssueTest($issue)) && p() && e('0'); // 对象ID为空

$issue->objectID = 10;
r($gitlab->webhookSyncIssueTest($issue)) && p() && e('0'); // 对象ID错误

$issue->objectID = 1;
r($gitlab->webhookSyncIssueTest($issue)) && p('name') && e('开发任务11'); // 对象ID正确

$issue->object->name = '任务1';
r($gitlab->webhookSyncIssueTest($issue)) && p('name') && e('任务1'); // 对象ID正确,更新名称