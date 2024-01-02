#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->getUnsyncedCommits();
timeout=0
cid=1

- 获取gitlab版本库未同步commit属性revision @2e0dd521b4a29930d5670a2c142a4400d7cffc1a
- 获取gitlab版本库未同步commit file数量 @1
- 获取gitlab版本库未同步commit数量 @1
- 获取svn版本库未同步commit属性comment @+ Add file.
- 获取svn版本库未同步commit file数量 @1
- 获取svn版本库未同步commit数量 @1

*/

zdTable('pipeline')->gen(5);
zdTable('repo')->config('repo')->gen(4);

$repo = new repoTest();

$gitlabID = 1;
$svnID    = 4;

$result    = $repo->getUnsyncedCommitsTest($gitlabID);
$oneCommit = array_shift($result);
r($oneCommit)                        && p('revision') && e('2e0dd521b4a29930d5670a2c142a4400d7cffc1a'); //获取gitlab版本库未同步commit
r(count($oneCommit->files['A']) > 2) && p()           && e('1'); //获取gitlab版本库未同步commit file数量
r(count($result) > 2)                && p()           && e('1'); //获取gitlab版本库未同步commit数量

$result    = $repo->getUnsyncedCommitsTest($svnID);
$oneCommit = array_shift($result);
r($oneCommit)                        && p('comment') && e('+ Add file.'); //获取svn版本库未同步commit
r(count($oneCommit->files['A']) > 0) && p()          && e('1');           //获取svn版本库未同步commit file数量
r(count($result) > 1)                && p()          && e('1');           //获取svn版本库未同步commit数量