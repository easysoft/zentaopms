#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';
su('admin');

/**

title=测试 repoModel->updateCommit();
timeout=0
cid=8

- 更新gitlab代码库 @1
- 更新svn代码库
 - 第5条的id属性 @5
 - 第5条的comment属性 @+ Add secondary file.

*/

zenData('pipeline')->gen(4);
zenData('repo')->loadYaml('repo')->gen(5);
zenData('repohistory')->loadYaml('repohistory')->gen(4);

$repo = new repoTest();

$gitlabID = 1;
$svnID    = 4;

r($repo->updateCommitTest($gitlabID)) && p() && e('1'); //更新gitlab代码库
r($repo->updateCommitTest($svnID))    && p('5:id,comment') && e('5,+ Add secondary file.'); //更新svn代码库
