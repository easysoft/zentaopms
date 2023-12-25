#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->getHistory();
timeout=0
cid=1

- 查询全部提交信息属性1 @d5789b703dc09c44be1e89a991c3506de1f678e3
- 传空数据查询信息 @empty

*/

global $tester, $dao;
$repo      = $tester->loadModel('repo')->getRepoByID(1);
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

r($repoTest->getHistoryTest(1, $revisions)) && p('1') && e('d5789b703dc09c44be1e89a991c3506de1f678e3'); //查询全部提交信息
r($repoTest->getHistoryTest(1, array()))    && p('')  && e('empty');                                    //传空数据查询信息

$dao->exec('truncate table zt_repohistory');
$dao->exec('truncate table zt_repobranch');