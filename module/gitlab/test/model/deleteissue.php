#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/gitlab.class.php';
su('admin');

/**

title=测试gitlabModel->deleteIssue();
timeout=0
cid=1

- 获取任务18对应的issueID。 @1
- 获取bug5对应的issueID。 @1
- 获取需求8对应的issueID。 @1

*/

zdTable('relation')->config('relation')->gen(4);

$gitlab  = new gitlabTest();
$issueID = 5;

r($gitlab->deleteIssueTest('task', 18, $issueID)) && p() && e('1'); // 获取任务18对应的issueID。
r($gitlab->deleteIssueTest('bug', 5, $issueID))   && p() && e('1'); // 获取bug5对应的issueID。
r($gitlab->deleteIssueTest('story', 8, $issueID)) && p() && e('1'); // 获取需求8对应的issueID。