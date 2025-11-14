#!/usr/bin/env php
<?php

/**

title=测试 repoModel::getHistory();
timeout=0
cid=18064

- 步骤1：正常查询有效revision列表 @2e0dd521b4a29930d5670a2c142a4400d7cffc1a
- 步骤2：查询空revision数组 @empty
- 步骤3：查询不存在的revision @empty
- 步骤4：测试无效repoID参数 @empty
- 步骤5：测试单个revision查询 @2e0dd521b4a29930d5670a2c142a4400d7cffc1a

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';

zenData('repo')->loadYaml('repo')->gen(4);
zenData('repohistory')->gen(0);
zenData('repobranch')->gen(0);

su('admin');

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

r($repoTest->getHistoryTest(1, $revisions))                               && p('0') && e('2e0dd521b4a29930d5670a2c142a4400d7cffc1a'); // 步骤1：正常查询有效revision列表
r($repoTest->getHistoryTest(1, array()))                                 && p()    && e('empty');                                    // 步骤2：查询空revision数组
r($repoTest->getHistoryTest(1, array('nonexistent123', 'invalid456')))   && p()    && e('empty');                                    // 步骤3：查询不存在的revision
r($repoTest->getHistoryTest(999, $revisions))                            && p()    && e('empty');                                    // 步骤4：测试无效repoID参数
r($repoTest->getHistoryTest(1, array_slice($revisions, 0, 1)))           && p('0') && e('2e0dd521b4a29930d5670a2c142a4400d7cffc1a'); // 步骤5：测试单个revision查询