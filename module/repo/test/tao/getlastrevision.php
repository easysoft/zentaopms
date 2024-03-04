#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->getLastRevision();
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

r($repo->getLastRevision($gitlabID)) && p() && e('2023-12-13 19:00:25'); //获取版本库1提交时间
r($repo->getLastRevision($giteaID))  && p() && e('2023-12-18 19:00:25'); //获取版本库3提交时间