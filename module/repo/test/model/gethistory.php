#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->getHistory();
timeout=0
cid=1

- 查询全部提交信息属性revision @2e0dd521b4a29930d5670a2c142a4400d7cffc1a
- 传空数据查询信息 @empty

*/

zdTable('repo')->config('repo')->gen(4);
zdTable('repohistory')->gen(0);

global $tester, $dao;
$repo      = $tester->loadModel('repo')->getByID(1);
$logs      = $tester->repo->getUnsyncedCommits($repo);
$revision  = 1;
$revisions = array();
foreach($logs as $log)
{
    $tester->repo->saveOneCommit($repo->id, $log, $revision);
    $revisions[] = $log->revision;
    $revision ++;
}

$repoTest = new repoTest();

$result = $repoTest->getHistoryTest(1, $revisions);
r(array_shift($result))                     && p('revision') && e('2e0dd521b4a29930d5670a2c142a4400d7cffc1a'); //查询全部提交信息
r($repoTest->getHistoryTest(1, array()))    && p('')         && e('empty');                                    //传空数据查询信息