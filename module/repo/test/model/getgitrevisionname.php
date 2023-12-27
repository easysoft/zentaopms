#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->getGitRevisionName();
timeout=0
cid=1

- commit参数为空获取提交记录名称 @d30919bdb9
- 获取提交记录名称 @d30919bdb9<span title="第2次提交"> (2) </span>

*/

zdTable('repo')->config('repo')->gen(4);
zdTable('repohistory')->config('repohistory')->gen(3);

$repo = $tester->loadModel('repo');

$revision = 'd30919bdb9b4cf8e2698f4a6a30e41910427c01c';
$commit   = 2;

r($repo->getGitRevisionName($revision, 0))       && p() && e('d30919bdb9'); //commit参数为空获取提交记录名称
r($repo->getGitRevisionName($revision, $commit)) && p() && e('d30919bdb9<span title="第2次提交"> (2) </span>'); //获取提交记录名称