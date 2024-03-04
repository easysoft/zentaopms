#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->saveCommit();
timeout=0
cid=1

- 保存版gitlab版本库commit @5
- 保存版gitlab版本库commit第5条的commit属性 @10
- 保存版svn版本库commit @3
- 查看svn版本库files @1

*/

zdTable('pipeline')->gen(5);
zdTable('repo')->config('repo')->gen(4);

$repo = new repoTest();

$gitlabID = 1;
$svnID    = 4;
$version  = 1;
$version2 = 6;

r($repo->saveCommitTest($gitlabID, $version))  && p()           && e('5'); //保存版gitlab版本库commit
r($repo->saveCommitTest($gitlabID, $version2)) && p('5:commit') && e('10'); //保存版gitlab版本库commit

$result = $repo->saveCommitTest($svnID, $version);
r($result['count'])            && p() && e('3'); //保存版svn版本库commit
r(count($result['files']) > 0) && p() && e('1'); //查看svn版本库files