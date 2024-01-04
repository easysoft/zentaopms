#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->getCommitsByRevisions();
timeout=0
cid=8

- 获取版本库1提交信息属性1 @1
- 获取版本库3提交信息属性3 @3

*/

zdTable('repo')->config('repo')->gen(4);
zdTable('repohistory')->config('repohistory')->gen(3);

$repo = $tester->loadModel('repo');

$revisions1 = array('c808480afe22d3a55d94e91c59a8f3170212ade0');
$revisions2 = array('d30919bdb9b4cf8e2698f4a6a30e41910427c01c', '0dbb150d4904c9a9d5a804b6cdddea3cb3d856bb');

r($repo->getCommitsByRevisions($revisions1)) && p('1') && e('1'); //获取版本库1提交信息
r($repo->getCommitsByRevisions($revisions2)) && p('3') && e('3'); //获取版本库3提交信息
