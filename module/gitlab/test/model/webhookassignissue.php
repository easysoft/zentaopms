#!/usr/bin/env php
<?php

/**

title=测试 gitlabModel::webhookAssignIssue();
timeout=0
cid=1

- 对象类型为空 @0
- 对象类型错误 @0
- 对象ID为空 @0
- 对象ID错误 @0
- 对象ID正确属性assignedTo @~~
- 对象ID正确,更新指派给属性assignedTo @user1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/gitlab.class.php';

zdTable('story')->gen(5);
su('admin');

$gitlab = new gitlabTest();

$issue = new stdclass();
$issue->object     = new stdclass();
$issue->objectID   = 0;
$issue->objectType = '';

r($gitlab->webhookAssignIssueTest($issue)) && p() && e('0'); // 对象类型为空

$issue->objectType = 'project';
r($gitlab->webhookAssignIssueTest($issue)) && p() && e('0'); // 对象类型错误

$issue->objectType = 'story';
r($gitlab->webhookAssignIssueTest($issue)) && p() && e('0'); // 对象ID为空

r($gitlab->webhookAssignIssueTest($issue)) && p() && e('0'); // 对象ID错误

$issue->objectID = 1;
r($gitlab->webhookAssignIssueTest($issue)) && p('assignedTo') && e('~~'); // 对象ID正确

$issue->object->assignedTo = 'user1';
r($gitlab->webhookAssignIssueTest($issue)) && p('assignedTo') && e('user1'); // 对象ID正确,更新指派给