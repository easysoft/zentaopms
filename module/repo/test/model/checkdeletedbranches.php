#!/usr/bin/env php
<?php

/**

title=测试 repoModel::checkDeletedBranches();
timeout=0
cid=18031

- 步骤1：正常删除已删除的分支数据
 - 属性repoHistoryCount @4
 - 属性repoBranchCount @4
 - 属性repoFilesCount @4
- 步骤2：测试空分支列表输入
 - 属性repoHistoryCount @6
 - 属性repoBranchCount @6
 - 属性repoFilesCount @6
- 步骤3：测试master分支不被删除（master存在但不在最新列表中）
 - 属性repoHistoryCount @4
 - 属性repoBranchCount @4
 - 属性repoFilesCount @4
- 步骤4：测试多个分支删除场景
 - 属性repoHistoryCount @2
 - 属性repoBranchCount @2
 - 属性repoFilesCount @2
- 步骤5：测试不存在代码库ID
 - 属性repoHistoryCount @2
 - 属性repoBranchCount @2
 - 属性repoFilesCount @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');
$repoTest = new repoModelTest();

$history = zenData('ops_repohistory');
$history->id->range('1-6');
$history->repo->range('1{6}');
$history->revision->range('r1,r2,r3,r4,r5,r6');
$history->commit->range('1-6');
$history->comment->range('commit 1,commit 2,commit 3,commit 4,commit 5,commit 6');
$history->committer->range('admin{6}');
$history->gen(6, true, false);

$branch = zenData('ops_repobranch');
$branch->id->range('1-6');
$branch->repo->range('1{6}');
$branch->revision->range('1-6');
$branch->branch->range('master,main,develop,develop,main,master');
$branch->gen(6, true, false);

$file = zenData('ops_repofiles');
$file->id->range('1-6');
$file->repo->range('1{6}');
$file->revision->range('1-6');
$file->path->range('/file1,/file2,/file3,/file4,/file5,/file6');
$file->oldPath->range('{6}');
$file->parent->range('/{6}');
$file->type->range('file{6}');
$file->action->range('A{6}');
$file->gen(6, true, false);
r($repoTest->checkDeletedBranchesTest(1, array('main' => 'main'))) && p('repoHistoryCount,repoBranchCount,repoFilesCount') && e('4,4,4');

$history = zenData('ops_repohistory');
$history->id->range('1-6');
$history->repo->range('1{6}');
$history->revision->range('r1,r2,r3,r4,r5,r6');
$history->commit->range('1-6');
$history->comment->range('commit 1,commit 2,commit 3,commit 4,commit 5,commit 6');
$history->committer->range('admin{6}');
$history->gen(6, true, false);

$branch = zenData('ops_repobranch');
$branch->id->range('1-6');
$branch->repo->range('1{6}');
$branch->revision->range('1-6');
$branch->branch->range('master,main,develop,develop,main,master');
$branch->gen(6, true, false);

$file = zenData('ops_repofiles');
$file->id->range('1-6');
$file->repo->range('1{6}');
$file->revision->range('1-6');
$file->path->range('/file1,/file2,/file3,/file4,/file5,/file6');
$file->oldPath->range('{6}');
$file->parent->range('/{6}');
$file->type->range('file{6}');
$file->action->range('A{6}');
$file->gen(6, true, false);
r($repoTest->checkDeletedBranchesTest(1, array())) && p('repoHistoryCount,repoBranchCount,repoFilesCount') && e('6,6,6');

$history = zenData('ops_repohistory');
$history->id->range('1-5');
$history->repo->range('1{5}');
$history->revision->range('r1,r2,r3,r4,r5');
$history->commit->range('1-5');
$history->comment->range('commit 1,commit 2,commit 3,commit 4,commit 5');
$history->committer->range('admin{5}');
$history->gen(5, true, false);

$branch = zenData('ops_repobranch');
$branch->id->range('1-5');
$branch->repo->range('1{5}');
$branch->revision->range('1-5');
$branch->branch->range('master,main,feature,main,master');
$branch->gen(5, true, false);

$file = zenData('ops_repofiles');
$file->id->range('1-5');
$file->repo->range('1{5}');
$file->revision->range('1-5');
$file->path->range('/file1,/file2,/file3,/file4,/file5');
$file->oldPath->range('{5}');
$file->parent->range('/{5}');
$file->type->range('file{5}');
$file->action->range('A{5}');
$file->gen(5, true, false);
r($repoTest->checkDeletedBranchesTest(1, array('main' => 'main'))) && p('repoHistoryCount,repoBranchCount,repoFilesCount') && e('4,4,4');

$history = zenData('ops_repohistory');
$history->id->range('7-10');
$history->repo->range('2{4}');
$history->revision->range('r7,r8,r9,r10');
$history->commit->range('7-10');
$history->comment->range('commit 7,commit 8,commit 9,commit 10');
$history->committer->range('admin{4}');
$history->gen(4, true, false);

$branch = zenData('ops_repobranch');
$branch->id->range('7-10');
$branch->repo->range('2{4}');
$branch->revision->range('7-10');
$branch->branch->range('master,main,develop,develop');
$branch->gen(4, true, false);

$file = zenData('ops_repofiles');
$file->id->range('7-10');
$file->repo->range('2{4}');
$file->revision->range('7-10');
$file->path->range('/file7,/file8,/file9,/file10');
$file->oldPath->range('{4}');
$file->parent->range('/{4}');
$file->type->range('file{4}');
$file->action->range('A{4}');
$file->gen(4, true, false);
r($repoTest->checkDeletedBranchesTest(2, array('main' => 'main'))) && p('repoHistoryCount,repoBranchCount,repoFilesCount') && e('2,2,2');
r($repoTest->checkDeletedBranchesTest(999, array('master' => 'master'))) && p('repoHistoryCount,repoBranchCount,repoFilesCount') && e('2,2,2');
