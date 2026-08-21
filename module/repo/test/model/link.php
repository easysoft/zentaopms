#!/usr/bin/env php
<?php

/**

title=测试 repoModel::link();
timeout=0
cid=18086

- 执行repoTest模块的linkTest方法，参数是1, $validRevision, 'story', 'repo', $validLinks 第0条的relation属性 @commit
- 执行repoTest模块的linkTest方法，参数是1, $validRevision, 'story', 'repo', $validLinks 第0条的BType属性 @story
- 执行repoTest模块的linkTest方法，参数是1, $validRevision, 'story', 'repo', $validLinks 第0条的AType属性 @revision
- 执行repoTest模块的linkTest方法，参数是1, $invalidRevision, 'story', 'repo', $validLinks  @失败
- 执行repoTest模块的linkTest方法，参数是1, $validRevision, 'story', 'repo', $emptyLinks  @0
- 执行repoTest模块的linkTest方法，参数是1, $validRevision, 'story', 'commit', $validLinks 第0条的relation属性 @commit
- 执行repoTest模块的linkTest方法，参数是1, $validRevision, 'task', 'repo', $validLinks 第0条的BType属性 @task

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$repo = zenData('ops_repo');
$repo->id->range('1');
$repo->spaceID->range('1');
$repo->product->range('1');
$repo->name->range('repo1');
$repo->scmType->range('git');
$repo->gitUID->range('link-gituid-1');
$repo->acl->range('private');
$repo->status->range('active');
$repo->deleted->range('0');
$repo->gen(1);

$repoUser = zenData('ops_repouser');
$repoUser->repo->range('1');
$repoUser->account->range('admin');
$repoUser->gen(1);

$history = zenData('ops_repohistory');
$history->id->range('1');
$history->repo->range('1');
$history->revision->range('c808480afe22d3a55d94e91c59a8f3170212ade0');
$history->commit->range('1');
$history->comment->range('Initial commit');
$history->committer->range('admin');
$history->time->range('1')->prefix('2024-01-0')->postfix(' 10:00:00');
$history->gen(1);

zenData('relation')->gen(0);

$story = zenData('story');
$story->id->range('101-102');
$story->product->range('1{2}');
$story->title->range('Story 101,Story 102');
$story->type->range('story{2}');
$story->status->range('active{2}');
$story->stage->range('wait{2}');
$story->version->range('1{2}');
$story->vision->range('rnd{2}');
$story->deleted->range('0{2}');
$story->gen(2);

$task = zenData('task');
$task->id->range('101-102');
$task->project->range('1{2}');
$task->execution->range('1{2}');
$task->name->range('Task 101,Task 102');
$task->type->range('devel{2}');
$task->status->range('wait{2}');
$task->version->range('1{2}');
$task->vision->range('rnd{2}');
$task->deleted->range('0{2}');
$task->gen(2);

$validRevision = 'c808480afe22d3a55d94e91c59a8f3170212ade0';
$invalidRevision = '22222';
$validLinks = array(101, 102);
$emptyLinks = array();

su('admin');

$repoTest = new repoModelTest();

r($repoTest->linkTest(1, $validRevision, 'story', 'repo', $validLinks)) && p('0:relation') && e('commit');
r($repoTest->linkTest(1, $validRevision, 'story', 'repo', $validLinks)) && p('0:BType') && e('story');
r($repoTest->linkTest(1, $validRevision, 'story', 'repo', $validLinks)) && p('0:AType') && e('revision');
r($repoTest->linkTest(1, $invalidRevision, 'story', 'repo', $validLinks)) && p('') && e('失败');
r($repoTest->linkTest(1, $validRevision, 'story', 'repo', $emptyLinks)) && p('') && e('0');
r($repoTest->linkTest(1, $validRevision, 'story', 'commit', $validLinks)) && p('0:relation') && e('commit');
r($repoTest->linkTest(1, $validRevision, 'task', 'repo', $validLinks)) && p('0:BType') && e('task');
