#!/usr/bin/env php
<?php

/**

title=测试 gitModel::updateCommit();
timeout=0
cid=16557

- 测试步骤1：未同步的代码库更新提交属性result @1
- 测试步骤2：已同步的空代码库更新提交属性result @1
- 测试步骤3：已同步的代码库正常更新提交属性result @1
- 测试步骤4：包含注释组的代码库更新提交属性result @1
- 测试步骤5：不存在的代码库ID更新提交属性error @repo_not_found

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/git.unittest.class.php';

$repo = zenData('repo');
$repo->id->range('101-105');
$repo->product->range('1');
$repo->name->range('testRepo1,testRepo2,emptyRepo,syncedRepo,errorRepo');
$repo->path->range('/tmp/testrepo1,/tmp/testrepo2,/tmp/emptyrepo,/tmp/syncedrepo,/invalid/path');
$repo->SCM->range('Git');
$repo->synced->range('0,1,1,1,0');
$repo->commits->range('0,5,10,20,0');
$repo->deleted->range('0');
$repo->gen(5);

su('admin');

$git = new gitTest();

r($git->updateCommitTest(101)) && p('result') && e('1'); // 测试步骤1：未同步的代码库更新提交
r($git->updateCommitTest(102)) && p('result') && e('1'); // 测试步骤2：已同步的空代码库更新提交
r($git->updateCommitTest(103)) && p('result') && e('1'); // 测试步骤3：已同步的代码库正常更新提交
r($git->updateCommitTest(104, array((object)array('id' => 1, 'comment' => 'test task')))) && p('result') && e('1'); // 测试步骤4：包含注释组的代码库更新提交
r($git->updateCommitTest(999)) && p('error') && e('repo_not_found'); // 测试步骤5：不存在的代码库ID更新提交