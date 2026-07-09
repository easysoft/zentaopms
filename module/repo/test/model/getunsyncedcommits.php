#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 repoModel->getUnsyncedCommits();
timeout=0
cid=18082

- 获取gitlab版本库未同步commit属性revision @2e0dd521b4a29930d5670a2c142a4400d7cffc1a
- 获取gitlab版本库未同步commit file数量 @1
- 获取gitlab版本库未同步commit数量 @1
- 获取svn版本库未同步commit属性comment @+ Add file.
- 获取svn版本库未同步commit file数量 @1
- 获取svn版本库未同步commit数量 @1

*/

$repo = new repoModelTest();

$gitlabID = 1;
$svnID    = 4;

$result    = $repo->getUnsyncedCommitsTest($gitlabID);
$oneCommit = array_shift($result);
r($oneCommit)                        && p('revision') && e('2e0dd521b4a29930d5670a2c142a4400d7cffc1a'); //获取gitlab版本库未同步commit
r(count($oneCommit->files['A']))     && p()           && e('1'); //获取gitlab版本库未同步commit file数量
r(count($result))                    && p()           && e('1'); //获取gitlab版本库未同步commit数量

$result    = $repo->getUnsyncedCommitsTest($svnID);
$oneCommit = array_shift($result);
r($oneCommit)                        && p('comment') && e('+ Add file.'); //获取svn版本库未同步commit
r(count($oneCommit->files['A']))     && p()          && e('1');           //获取svn版本库未同步commit file数量
r(count($result))                    && p()          && e('1');           //获取svn版本库未同步commit数量
