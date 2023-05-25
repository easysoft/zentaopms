#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/bug.class.php';

zdTable('bug')->config('bug_afterbatchedit')->gen(10);
zdTable('user')->gen(2);
zdTable('action')->gen(1);
zdTable('score')->gen(1);

su('admin');

/**

title=bugModel->afterBatchEdit();
cid=1
pid=1

*/

$normal = new stdclass();
$normal->id       = 1;
$normal->severity = 1;
$normal->title    = '修改后的名称1';

$resolved1 = new stdclass();
$resolved1->id         = 2;
$resolved1->severity   = 1;
$resolved1->title      = '修改后的名称2';
$resolved1->status     = 'resolved';
$resolved1->resolvedBy = 'admin';

$resolved3 = new stdclass();
$resolved3->id         = 3;
$resolved3->severity   = 3;
$resolved3->title      = '修改后的名称3';
$resolved3->status     = 'resolved';
$resolved3->resolvedBy = 'admin';

$hasResolved1 = new stdclass();
$hasResolved1->id         = 4;
$hasResolved1->severity   = 1;
$hasResolved1->title      = '修改后的名称4';
$hasResolved1->status     = 'resolved';
$hasResolved1->resolvedBy = 'admin';

$hasResolved3 = new stdclass();
$hasResolved3->id         = 5;
$hasResolved3->severity   = 3;
$hasResolved3->title      = '修改后的名称5';
$hasResolved3->status     = 'resolved';
$hasResolved3->resolvedBy = 'admin';

$bug = new bugTest();

r($bug->afterBatchEditTest($normal))       && p() && e('scoreDifference:0;lastAction:bug-edited-1'); // 测试 普通的 bug 批量更新后的操作
r($bug->afterBatchEditTest($resolved1))    && p() && e('scoreDifference:4;lastAction:bug-edited-2'); // 测试 编辑时解决的优先级1 bug 批量更新后的操作
r($bug->afterBatchEditTest($resolved3))    && p() && e('scoreDifference:2;lastAction:bug-edited-3'); // 测试 编辑时解决的优先级3 bug 批量更新后的操作
r($bug->afterBatchEditTest($hasResolved1)) && p() && e('scoreDifference:0;lastAction:bug-edited-4'); // 测试 之前就解决的优先级1 bug 批量更新后的操作
r($bug->afterBatchEditTest($hasResolved3)) && p() && e('scoreDifference:0;lastAction:bug-edited-5'); // 测试 之前就解决的优先级3 bug 批量更新后的操作
