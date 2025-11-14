#!/usr/bin/env php
<?php

/**

title=测试 repoModel::updateCommit();
timeout=0
cid=18110

- 步骤1：测试Gitlab类型代码库更新 @1
- 步骤2：测试Git类型代码库更新，期望返回2条历史记录 @2
- 步骤3：测试SVN类型代码库更新，期望返回2条历史记录 @2
- 步骤4：测试无效代码库ID @0
- 步骤5：测试不存在的代码库ID @0
- 步骤6：测试带分支参数的Git更新属性result @1
- 步骤7：测试带objectID参数的更新属性result @1

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';

// 2. zendata数据准备
$pipeline = zenData('pipeline');
$pipeline->gen(5);

$repo = zenData('repo');
$repo->id->range('1-5');
$repo->product->range('1-3');
$repo->name->range('GitlabRepo,GitRepo,SVNRepo,TestRepo,EmptyRepo');
$repo->path->range('/gitlab,/git,/svn,/test,/empty');
$repo->SCM->range('Gitlab,Git,Subversion,Git,Subversion');
$repo->client->range('1-5');
$repo->serviceHost->range('localhost{5}');
$repo->serviceProject->range('project{5}');
$repo->deleted->range('0{4},1');
$repo->gen(5);

$repohistory = zenData('repohistory');
$repohistory->id->range('1-10');
$repohistory->repo->range('1-5');
$repohistory->revision->range('abc123,def456,ghi789,jkl012,mno345,pqr678,stu901,vwx234,yz567,abc890');
$repohistory->commit->range('1-10');
$repohistory->comment->range('Initial commit,Add feature,Fix bug,Update docs,Refactor code,Add test,Remove file,Merge branch,Update config,Final commit');
$repohistory->committer->range('admin,user1,user2,dev1,dev2');
$repohistory->time->range('`2024-01-01 10:00:00`,`2024-01-02 10:00:00`,`2024-01-03 10:00:00`,`2024-01-04 10:00:00`,`2024-01-05 10:00:00`,`2024-01-06 10:00:00`,`2024-01-07 10:00:00`,`2024-01-08 10:00:00`,`2024-01-09 10:00:00`,`2024-01-10 10:00:00`');
$repohistory->gen(10);

$job = zenData('job');
$job->id->range('1-5');
$job->name->range('CommitJob{5}');
$job->repo->range('1-5');
$job->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$repoTest = new repoTest();

// 5. 执行测试步骤
r($repoTest->updateCommitTest(1)) && p() && e('1'); // 步骤1：测试Gitlab类型代码库更新
r($repoTest->updateCommitTest(2)) && p() && e('2'); // 步骤2：测试Git类型代码库更新，期望返回2条历史记录
r($repoTest->updateCommitTest(3)) && p() && e('2'); // 步骤3：测试SVN类型代码库更新，期望返回2条历史记录
r($repoTest->updateCommitTest(999)) && p() && e('0'); // 步骤4：测试无效代码库ID
r($repoTest->updateCommitTest(0)) && p() && e('0'); // 步骤5：测试不存在的代码库ID
r($repoTest->updateCommitTest(2, 0, 'main')) && p('result') && e('1'); // 步骤6：测试带分支参数的Git更新
r($repoTest->updateCommitTest(2, 123)) && p('result') && e('1'); // 步骤7：测试带objectID参数的更新