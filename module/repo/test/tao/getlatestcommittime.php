#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->getLatestCommitTime();
timeout=0
cid=8

- 获取版本库1提交时间 @2023-12-13 19:00:25
- 获取版本库3提交时间 @2023-12-18 19:00:25

*/

zdTable('repo')->config('repo')->gen(4);
zdTable('repohistory')->config('repohistory')->gen(3);

$repo = $tester->loadModel('repo');

$gitlabID = 1;
$giteaID  = 3;
$revision = 'HEAD';

r($repo->getLatestCommitTime($gitlabID, $revision, '')) && p() && e('2023-12-13 19:00:25'); //获取版本库1提交时间
r($repo->getLatestCommitTime($giteaID, $revision, ''))  && p() && e('2023-12-18 19:00:25'); //获取版本库3提交时间
$revision = '0dbb150d4904c9a9d5a804b6cdddea3cb3d856bb';
r($repo->getLatestCommitTime($giteaID, $revision, ''))  && p() && e('2023-12-13 13:04:27'); //指定revision获取版本库3提交时间
