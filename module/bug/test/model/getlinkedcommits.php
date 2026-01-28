#!/usr/bin/env php
<?php

/**

title=测试 bugModel::getLinkedCommits();
timeout=0
cid=15383

- 测试步骤1:正常情况,仓库1的3个有效版本号 @3
- 测试步骤2:正常情况,仓库2的2个版本号 @2
- 测试步骤3:边界值测试,空数组 @0
- 测试步骤4:异常输入,不存在的版本号 @0
- 测试步骤5:异常输入,不存在的仓库ID @0
- 测试步骤6:混合有效和无效版本号 @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 准备仓库历史记录数据
$repohistory = zendata('repohistory');
$repohistory->id->range('1-10');
$repohistory->repo->range('1{5},2{5}');
$repohistory->revision->range('rev001,rev002,rev003,rev004,rev005,rev006,rev007,rev008,rev009,rev010');
$repohistory->commit->range('1-10');
$repohistory->comment->range('Fix bug');
$repohistory->committer->range('admin');
$repohistory->time->range('`2024-01-01 00:00:00`');
$repohistory->gen(10);

// 准备关联关系数据
$relation = zendata('relation');
$relation->id->range('1-10');
$relation->project->range('1');
$relation->product->range('1');
$relation->execution->range('1');
$relation->AType->range('bug');
$relation->AID->range('1-10');
$relation->AVersion->range('1');
$relation->relation->range('completedin');
$relation->BType->range('commit');
$relation->BID->range('1-10');
$relation->BVersion->range('1');
$relation->extra->range('``');
$relation->gen(10);

// 准备Bug数据
$bug = zendata('bug');
$bug->id->range('1-10');
$bug->project->range('1');
$bug->product->range('1');
$bug->injection->range('``');
$bug->identify->range('``');
$bug->branch->range('0');
$bug->module->range('0');
$bug->execution->range('1');
$bug->plan->range('0');
$bug->story->range('0');
$bug->storyVersion->range('1');
$bug->task->range('0');
$bug->toTask->range('0');
$bug->toStory->range('0');
$bug->title->range('Bug 001,Bug 002,Bug 003,Bug 004,Bug 005,Bug 006,Bug 007,Bug 008,Bug 009,Bug 010');
$bug->status->range('active');
$bug->deleted->range('0');
$bug->gen(10);

su('admin');

$bugTest = new bugModelTest();

r(count($bugTest->getLinkedCommitsTest(1, array('rev001', 'rev002', 'rev003')))) && p() && e('3'); // 测试步骤1:正常情况,仓库1的3个有效版本号
r(count($bugTest->getLinkedCommitsTest(2, array('rev006', 'rev007')))) && p() && e('2'); // 测试步骤2:正常情况,仓库2的2个版本号
r(count($bugTest->getLinkedCommitsTest(1, array()))) && p() && e('0'); // 测试步骤3:边界值测试,空数组
r(count($bugTest->getLinkedCommitsTest(1, array('invalid001', 'invalid002')))) && p() && e('0'); // 测试步骤4:异常输入,不存在的版本号
r(count($bugTest->getLinkedCommitsTest(999, array('rev001', 'rev002')))) && p() && e('0'); // 测试步骤5:异常输入,不存在的仓库ID
r(count($bugTest->getLinkedCommitsTest(1, array('rev001', 'invalid001', 'rev002')))) && p() && e('2'); // 测试步骤6:混合有效和无效版本号