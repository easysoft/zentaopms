#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 repoModel::isClickable();
timeout=0
cid=1

- 计算exec为disabled是否能进行执行任务操作 @0
- 计算exec为空是否能进行执行任务操作 @1
- 计算report为disabled是否能进行执行任务操作 @0
- 计算report为空是否能进行执行任务操作 @1

*/

zdTable('repo')->config('repo')->gen(4);
zdTable('repohistory')->config('repohistory')->gen(3);

$repoModel = $tester->loadModel('repo');

$repo1 = new stdclass();
$repo1->exec = 'disabled';

$repo2 = new stdclass();
$repo2->exec = '';

$repo3 = new stdclass();
$repo3->report = 'disabled';

$repo4 = new stdclass();
$repo4->report = '';

r($repoModel->isClickable($repo1, 'execJob'))    && p() && e('0'); //计算exec为disabled是否能进行执行任务操作
r($repoModel->isClickable($repo2, 'execJob'))    && p() && e('1'); //计算exec为空是否能进行执行任务操作
r($repoModel->isClickable($repo3, 'reportView')) && p() && e('0'); //计算report为disabled是否能进行执行任务操作
r($repoModel->isClickable($repo4, 'reportView')) && p() && e('1'); //计算report为空是否能进行执行任务操作
