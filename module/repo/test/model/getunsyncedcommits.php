#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**
title=测试 repoModel->getUnsyncedCommits();
timeout=0
cid=18082

- 获取 gitlab 版本库未同步 commit 属性revision @2e0dd521b4a29930d5670a2c142a4400d7cffc1a
- 获取 gitlab 版本库未同步 commit file 数量 @1
- 获取 gitlab 版本库剩余未同步 commit 数量 @1
- 获取 svn 版本库未同步 commit 属性comment @+ Add file.
- 获取 svn 版本库未同步 commit file 数量 @1
- 获取 svn 版本库剩余未同步 commit 数量 @1

*/

$repo = new repoModelTest();

r($repo->getUnsyncedCommitsTest(1))            && p('0:revision') && e('2e0dd521b4a29930d5670a2c142a4400d7cffc1a');
r($repo->getUnsyncedCommitFileCountTest(1))    && p()             && e('1');
r($repo->getUnsyncedRemainingCountTest(1))     && p()             && e('1');
r($repo->getUnsyncedCommitsTest(4))            && p('0:comment')  && e('+ Add file.');
r($repo->getUnsyncedCommitFileCountTest(4))    && p()             && e('1');
r($repo->getUnsyncedRemainingCountTest(4))     && p()             && e('1');
