#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
// @phpstan-ignore-next-line
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

zenData('effort')->gen(0);

$effort1 = new stdclass();
$effort1->id         = 1;
$effort1->objectType = 'custom';
$effort1->objectID   = 0;
$effort1->product    = '0';
$effort1->project    = 0;
$effort1->execution  = 0;
$effort1->account    = 'admin';
$effort1->work       = "提交: #23670f09ee\nFix Task #1";
$effort1->date       = '2024-01-01';
$effort1->left       = 0;
$effort1->consumed   = 1;
$effort1->begin      = '1000';
$effort1->end        = '1100';
$effort1->extra      = 'extra';
$effort1->order      = 0;
$effort1->vision     = 'rnd';
$effort1->deleted    = 0;
$tester->dao->insert(TABLE_EFFORT)->data($effort1)->exec();

$effort2 = new stdclass();
$effort2->id         = 2;
$effort2->objectType = 'custom';
$effort2->objectID   = 0;
$effort2->product    = '0';
$effort2->project    = 0;
$effort2->execution  = 0;
$effort2->account    = 'admin';
$effort2->work       = "提交: #8c69a6c51d\nFix Task #2";
$effort2->date       = '2024-01-01';
$effort2->left       = 0;
$effort2->consumed   = 1;
$effort2->begin      = '1000';
$effort2->end        = '1100';
$effort2->extra      = 'extra';
$effort2->order      = 0;
$effort2->vision     = 'rnd';
$effort2->deleted    = 0;
$tester->dao->insert(TABLE_EFFORT)->data($effort2)->exec();

$repoTest = new repoModelTest();

$commit1 = (object) array('id' => '23670f09ee17bde83ca5e8e50b294dad6d35a5cb', 'message' => "Fix Task #1");
$commit2 = (object) array('sha' => '8c69a6c51d04ad80cd18b4845dc147b32bcf20c7', 'message' => 'Fix Task #2');
$commit3 = (object) array('id' => 'aaaaaaaaaabbbbbbbbbbccccccccccdddddddddd', 'message' => 'Fix Task #3');
$commit4 = (object) array('message' => 'Fix Task #1');
$commit5 = (object) array('id' => '23670f09ee17bde83ca5e8e50b294dad6d35a5cb', 'message' => '');

// @phpstan-ignore-next-line
r($repoTest->isRecordedWebhookCommitTest($commit1)) && p() && e('1'); // 通过 commit->id 命中已有工时记录
// @phpstan-ignore-next-line
r($repoTest->isRecordedWebhookCommitTest($commit2)) && p() && e('1'); // 通过 commit->sha 命中已有工时记录
// @phpstan-ignore-next-line
r($repoTest->isRecordedWebhookCommitTest($commit3)) && p() && e('0'); // 不存在对应工时记录
// @phpstan-ignore-next-line
r($repoTest->isRecordedWebhookCommitTest($commit4)) && p() && e('0'); // 缺少版本号
// @phpstan-ignore-next-line
r($repoTest->isRecordedWebhookCommitTest($commit5)) && p() && e('0'); // 缺少提交说明
