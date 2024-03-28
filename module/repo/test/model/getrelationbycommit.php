#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=- 获取任务关联信息第8条的title属性 @
timeout=0
cid=8

- 获取任务关联信息第0条的type属性 @task
- 获取任务关联信息第0条的id属性 @4
- 获取任务关联信息第0条的type属性 @story

*/

zdTable('task')->gen(10);
zdTable('bug')->gen(10);
zdTable('story')->gen(10);
zdTable('relation')->config('relation')->gen(3);
zdTable('repo')->config('config')->gen(4);
zdTable('repohistory')->config('repohistory')->gen(1);

global $tester, $app;
$repo = $tester->loadModel('repo');
include($app->getModuleRoot() . '/repo/control.php');
$app->control = new repo();

$repoID = 1;
$commit = 'c808480afe22d3a55d94e91c59a8f3170212ade0';

r($repo->getRelationByCommit($repoID, $commit, 'task'))  && p('0:type')  && e('task'); //获取任务关联信息
r($repo->getRelationByCommit($repoID, $commit, 'bug'))   && p('0:id')    && e('4'); //获取任务关联信息
r($repo->getRelationByCommit($repoID, $commit, 'story')) && p('0:type')  && e('story'); //获取任务关联信息
