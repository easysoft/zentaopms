#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 repoModel->getFileCommits();
timeout=0
cid=18056

- 执行repo模块的getFileCommitsTest方法，参数是1, 'branch3' 
 - 第0条的revision属性 @c808480afe22d3a55d94e91c59a8f3170212ade0
 - 第0条的date属性 @2023-12-13 19:00:25
- 执行repo模块的getFileCommitsTest方法，参数是3, ''  @0
- 执行repo模块的getFileCommitsTest方法，参数是4, '', $parent 
 - 第0条的revision属性 @1
 - 第0条的comment属性 @+ Add file.

*/

$repo = zenData('ops_repo');
$repo->id->range('1,3,4');
$repo->spaceID->range('1{3}');
$repo->product->range('1{3}');
$repo->name->range('gitlabRepo,giteaRepo,svnRepo');
$repo->scmType->range('git,git,svn');
$repo->gitUID->range('filecommits-gituid-1,filecommits-gituid-3,filecommits-gituid-4');
$repo->acl->range('private{3}');
$repo->status->range('active{3}');
$repo->deleted->range('0{3}');
$repo->gen(3);

$history = zenData('ops_repohistory');
$history->id->range('1-3');
$history->repo->range('1,3,4');
$history->revision->range('c808480afe22d3a55d94e91c59a8f3170212ade0,deleted-commit,1');
$history->commit->range('1-3');
$history->comment->range('Add license,Delete file,+ Add file.');
$history->committer->range('admin{3}');
$history->time->range('13{3}')->prefix('2023-12-')->postfix(' 19:00:25');
$history->gen(3);

$branch = zenData('ops_repobranch');
$branch->repo->range('1');
$branch->revision->range('1');
$branch->branch->range('branch3');
$branch->gen(1);

$file = zenData('ops_repofiles');
$file->repo->range('1,3,4');
$file->revision->range('1-3');
$file->path->range('/LICENSE,/deleted,/README');
$file->oldPath->range('[]{3}');
$file->parent->range('/{3}');
$file->type->range('file{3}');
$file->action->range('A,D,A');
$file->gen(3);

$repo = new repoModelTest();
$parent = '';

r($repo->getFileCommitsTest(1, 'branch3')) && p('0:revision,date')    && e('c808480afe22d3a55d94e91c59a8f3170212ade0,2023-12-13 19:00:25');
r($repo->getFileCommitsTest(3, ''))        && p()                     && e('0');
r($repo->getFileCommitsTest(4, '', $parent)) && p('0:revision,comment') && e('1,+ Add file.');