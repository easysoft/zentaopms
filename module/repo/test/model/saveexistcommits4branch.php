#!/usr/bin/env php
<?php

/**

title=测试 repoModel::saveExistCommits4Branch();
timeout=0
cid=18096

- 测试步骤1：正常情况-保存gitea版本库master分支的历史提交 @1
- 测试步骤2：边界值测试-不存在的仓库ID @0
- 测试步骤3：异常输入-空分支名称 @0
- 测试步骤4：业务逻辑验证-验证保存操作成功 @1
- 测试步骤5：边界条件-仓库没有历史提交记录的分支 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$history = zenData('ops_repohistory');
$history->id->range('1-3');
$history->repo->range('3{2},1');
$history->revision->range('r1,r2,r3');
$history->commit->range('1,2,1');
$history->comment->range('older commit,master commit,repo1 commit');
$history->committer->range('admin{3}');
$history->time->range('1-3')->prefix('2024-01-0')->postfix(' 10:00:00');
$history->gen(3);

$branch = zenData('ops_repobranch');
$branch->repo->range('3,1');
$branch->revision->range('2,3');
$branch->branch->range('master{2}');
$branch->gen(2);

su('admin');

$repo = new repoModelTest();

r($repo->saveExistCommits4BranchTest(3, 'master')) && p() && e('1'); // 测试步骤1：正常情况-保存gitea版本库master分支的历史提交
r($repo->saveExistCommits4BranchTest(999, 'master')) && p() && e('0'); // 测试步骤2：边界值测试-不存在的仓库ID
r($repo->saveExistCommits4BranchTest(3, '')) && p() && e('0'); // 测试步骤3：异常输入-空分支名称
r($repo->saveExistCommits4BranchTest(3, 'master')) && p() && e('1'); // 测试步骤4：业务逻辑验证-验证保存操作成功
r($repo->saveExistCommits4BranchTest(1, 'develop')) && p() && e('0'); // 测试步骤5：边界条件-仓库没有历史提交记录的分支
