#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/gitlab.class.php';
su('admin');

/**

title=测试gitlabModel->getIssueListByObjects();
timeout=0
cid=1

- 获取任务18对应的issueID。
 - 第18条的issueID属性 @4
 - 第19条的issueID属性 @1
- 获取bug5对应的issueID。第5条的issueID属性 @3
- 获取需求8对应的issueID。第8条的issueID属性 @2

*/

zdTable('relation')->config('relation')->gen(4);

$gitlab = new gitlabTest();

$taskIds  = array(18, 19);
$bugIds   = array(5);
$storyIds = array(8);

r($gitlab->getIssueListByObjectsTest('task', $taskIds))   && p('18:issueID;19:issueID') && e('4,1'); // 获取任务18对应的issueID。
r($gitlab->getIssueListByObjectsTest('bug', $bugIds))     && p('5:issueID')  && e('3'); // 获取bug5对应的issueID。
r($gitlab->getIssueListByObjectsTest('story', $storyIds)) && p('8:issueID')  && e('2'); // 获取需求8对应的issueID。