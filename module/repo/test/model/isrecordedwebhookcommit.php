#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 repoModel::isRecordedWebhookCommit();
timeout=0
cid=18103

- 通过 commit->id 命中已有工时记录 @1
- 通过 commit->sha 命中已有工时记录 @1
- 不存在对应工时记录 @0
- 缺少版本号 @0
- 缺少提交说明 @0

*/

$effort = zenData('effort');
$effort->id->range('1-2');
$effort->objectType->range('custom');
$effort->objectID->range('0');
$effort->product->range('0');
$effort->project->range('0');
$effort->account->range('admin');
$effort->work->range("提交: #23670f09ee\nFix Task #1\n提交: #8c69a6c51d\nFix &lt;Task&gt; #2");
$effort->date->range('2024-01-01 00:00:00');
$effort->left->range('0');
$effort->consumed->range('1');
$effort->begin->range('1000');
$effort->end->range('1100');
$effort->gen(2);

$repoTest = new repoModelTest();

$commit1 = (object) array('id' => '23670f09ee17bde83ca5e8e50b294dad6d35a5cb', 'message' => "Fix Task #1");
$commit2 = (object) array('sha' => '8c69a6c51d04ad80cd18b4845dc147b32bcf20c7', 'Message' => 'Fix <Task> #2');
$commit3 = (object) array('id' => 'aaaaaaaaaabbbbbbbbbbccccccccccdddddddddd', 'message' => 'Fix Task #3');
$commit4 = (object) array('message' => 'Fix Task #1');
$commit5 = (object) array('id' => '23670f09ee17bde83ca5e8e50b294dad6d35a5cb', 'message' => '');

r($repoTest->isRecordedWebhookCommitTest($commit1)) && p() && e('1'); // 通过 commit->id 命中已有工时记录
r($repoTest->isRecordedWebhookCommitTest($commit2)) && p() && e('1'); // 通过 commit->sha 命中已有工时记录
r($repoTest->isRecordedWebhookCommitTest($commit3)) && p() && e('0'); // 不存在对应工时记录
r($repoTest->isRecordedWebhookCommitTest($commit4)) && p() && e('0'); // 缺少版本号
r($repoTest->isRecordedWebhookCommitTest($commit5)) && p() && e('0'); // 缺少提交说明
