#!/usr/bin/env php
<?php

/**

title=测试 gitlabModel::webhookCloseIssue();
timeout=0
cid=1

- 对象类型为空 @0
- 对象类型错误 @0
- 对象ID为空 @0
- 对象ID错误 @0
- 对象ID正确属性lastEditedBy @~~
- 对象ID正确,更新最后编辑人属性lastEditedBy @user1

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

r($gitlab->webhookCloseIssueTest($issue)) && p() && e('0'); // 对象类型为空

$issue->objectType = 'project';
r($gitlab->webhookCloseIssueTest($issue)) && p() && e('0'); // 对象类型错误

$issue->objectType = 'story';
r($gitlab->webhookCloseIssueTest($issue)) && p() && e('0'); // 对象ID为空

r($gitlab->webhookCloseIssueTest($issue)) && p() && e('0'); // 对象ID错误

$issue->objectID = 1;
r($gitlab->webhookCloseIssueTest($issue)) && p('lastEditedBy') && e('~~'); // 对象ID正确

$issue->object->lastEditedBy = 'user1';
r($gitlab->webhookCloseIssueTest($issue)) && p('lastEditedBy') && e('user1'); // 对象ID正确,更新最后编辑人