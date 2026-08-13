#!/usr/bin/env php
<?php

/**

title=测试 repoModel::getLatestCommit();
timeout=0
cid=18067

- 执行repo模块的getLatestCommitTest方法，参数是1
 - 属性id @1
 - 属性revision @c808480afe22d3a55d94e91c59a8f3170212ade0
- 执行repo模块的getLatestCommitTest方法，参数是3
 - 属性id @2
 - 属性commit @1
- 执行repo模块的getLatestCommitTest方法，参数是2  @0
- 执行repo模块的getLatestCommitTest方法，参数是4
 - 属性id @6
 - 属性revision @3
- 执行repo模块的getLatestCommitTestWithoutCount方法，参数是1
 - 属性id @1
 - 属性revision @c808480afe22d3a55d94e91c59a8f3170212ade0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$repo = zenData('ops_repo');
$repo->id->range('1-4');
$repo->spaceID->range('1{4}');
$repo->product->range('1{4}');
$repo->name->range('repo1,repo2,repo3,repo4');
$repo->gitUID->range('latest-commit-uid-1,latest-commit-uid-2,latest-commit-uid-3,latest-commit-uid-4');
$repo->scmType->range('git,git,git,svn');
$repo->status->range('active{4}');
$repo->deleted->range('0{4}');
$repo->gen(4);

$history = zenData('ops_repohistory');
$history->id->range('1,2,3,6');
$history->repo->range('1,3,3,4');
$history->revision->range('c808480afe22d3a55d94e91c59a8f3170212ade0,d30919bdb9b4cf8e2698f4a6a30e41910427c01c,0dbb150d4904c9a9d5a804b6cdddea3cb3d856bb,3');
$history->commit->range('1{4}');
$history->comment->range('repo1 commit,repo3 latest,repo3 older,svn latest');
$history->committer->range('admin{4}');
$history->time->range('1,3,2,4')->prefix('2024-01-0')->postfix(' 10:00:00');
$history->gen(4);

$branch = zenData('ops_repobranch');
$branch->repo->range('1,3,3');
$branch->revision->range('1-3');
$branch->branch->range('master,develop,feature');
$branch->gen(3);

su('admin');

$repo = new repoModelTest();

r($repo->getLatestCommitTest(1)) && p('id,revision') && e('1,c808480afe22d3a55d94e91c59a8f3170212ade0');
r($repo->getLatestCommitTest(3)) && p('id,commit') && e('2,1');
r($repo->getLatestCommitTest(2)) && p() && e('0');
r($repo->getLatestCommitTest(4)) && p('id,revision') && e('6,3');
r($repo->getLatestCommitTestWithoutCount(1)) && p('id,revision') && e('1,c808480afe22d3a55d94e91c59a8f3170212ade0');
